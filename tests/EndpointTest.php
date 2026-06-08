<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

use Contentstack\Utils\Endpoint;
use Contentstack\Utils\Utils;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    protected function setUp(): void
    {
        Endpoint::resetCache();
    }

    // -------------------------------------------------------------------------
    // Default region (us / na)
    // -------------------------------------------------------------------------

    public function testDefaultRegionReturnsAllEndpoints(): void
    {
        $endpoints = Endpoint::getContentstackEndpoint();
        $this->assertIsArray($endpoints);
        $this->assertArrayHasKey('contentDelivery', $endpoints);
        $this->assertArrayHasKey('contentManagement', $endpoints);
    }

    public function testDefaultRegionContentDelivery(): void
    {
        $url = Endpoint::getContentstackEndpoint('us', 'contentDelivery');
        $this->assertSame('https://cdn.contentstack.io', $url);
    }

    public function testDefaultRegionContentManagement(): void
    {
        $url = Endpoint::getContentstackEndpoint('us', 'contentManagement');
        $this->assertSame('https://api.contentstack.io', $url);
    }

    // -------------------------------------------------------------------------
    // Region aliases resolve to the same region
    // -------------------------------------------------------------------------

    /**
     * @dataProvider naAliasProvider
     */
    public function testNaRegionAliasesResolveToSameEndpoint(string $alias): void
    {
        $url = Endpoint::getContentstackEndpoint($alias, 'contentDelivery');
        $this->assertSame('https://cdn.contentstack.io', $url);
    }

    public function naAliasProvider(): array
    {
        return [
            'id na'     => ['na'],
            'alias us'  => ['us'],
            'alias aws-na' => ['aws-na'],
            'alias aws_na' => ['aws_na'],
            'upper NA'  => ['NA'],
            'upper US'  => ['US'],
        ];
    }

    // -------------------------------------------------------------------------
    // All seven regions – contentDelivery spot-checks
    // -------------------------------------------------------------------------

    /**
     * @dataProvider regionContentDeliveryProvider
     */
    public function testContentDeliveryUrlByRegion(string $region, string $expected): void
    {
        $url = Endpoint::getContentstackEndpoint($region, 'contentDelivery');
        $this->assertSame($expected, $url);
    }

    public function regionContentDeliveryProvider(): array
    {
        return [
            'na'       => ['na',       'https://cdn.contentstack.io'],
            'eu'       => ['eu',       'https://eu-cdn.contentstack.com'],
            'au'       => ['au',       'https://au-cdn.contentstack.com'],
            'azure-na' => ['azure-na', 'https://azure-na-cdn.contentstack.com'],
            'azure-eu' => ['azure-eu', 'https://azure-eu-cdn.contentstack.com'],
            'gcp-na'   => ['gcp-na',   'https://gcp-na-cdn.contentstack.com'],
            'gcp-eu'   => ['gcp-eu',   'https://gcp-eu-cdn.contentstack.com'],
        ];
    }

    /**
     * @dataProvider regionContentManagementProvider
     */
    public function testContentManagementUrlByRegion(string $region, string $expected): void
    {
        $url = Endpoint::getContentstackEndpoint($region, 'contentManagement');
        $this->assertSame($expected, $url);
    }

    public function regionContentManagementProvider(): array
    {
        return [
            'na'       => ['na',       'https://api.contentstack.io'],
            'eu'       => ['eu',       'https://eu-api.contentstack.com'],
            'au'       => ['au',       'https://au-api.contentstack.com'],
            'azure-na' => ['azure-na', 'https://azure-na-api.contentstack.com'],
            'azure-eu' => ['azure-eu', 'https://azure-eu-api.contentstack.com'],
            'gcp-na'   => ['gcp-na',   'https://gcp-na-api.contentstack.com'],
            'gcp-eu'   => ['gcp-eu',   'https://gcp-eu-api.contentstack.com'],
        ];
    }

    // -------------------------------------------------------------------------
    // All service keys present for a region
    // -------------------------------------------------------------------------

    public function testAllServiceKeysPresent(): void
    {
        $expected = [
            'application', 'contentDelivery', 'contentManagement', 'auth',
            'graphqlDelivery', 'preview', 'graphqlPreview', 'images', 'assets',
            'automate', 'launch', 'developerHub', 'brandKit', 'genAI',
            'personalizeManagement', 'personalizeEdge', 'composableStudio',
        ];
        $endpoints = Endpoint::getContentstackEndpoint('eu');
        foreach ($expected as $key) {
            $this->assertArrayHasKey($key, $endpoints, "Missing service key: {$key}");
        }
    }

    // -------------------------------------------------------------------------
    // omitHttps flag
    // -------------------------------------------------------------------------

    public function testOmitHttpsStripsSchemeFromSingleService(): void
    {
        $url = Endpoint::getContentstackEndpoint('eu', 'contentDelivery', true);
        $this->assertSame('eu-cdn.contentstack.com', $url);
    }

    public function testOmitHttpsStripsSchemeFromAllServices(): void
    {
        $endpoints = Endpoint::getContentstackEndpoint('na', '', true);
        $this->assertIsArray($endpoints);
        foreach ($endpoints as $key => $url) {
            $this->assertStringNotContainsString('https://', $url, "Service {$key} still has https://");
            $this->assertStringNotContainsString('http://', $url, "Service {$key} still has http://");
        }
    }

    public function testOmitHttpsFalseRetainsScheme(): void
    {
        $url = Endpoint::getContentstackEndpoint('na', 'contentManagement', false);
        $this->assertStringStartsWith('https://', $url);
    }

    // -------------------------------------------------------------------------
    // Case-insensitive alias matching
    // -------------------------------------------------------------------------

    public function testUppercaseAliasResolves(): void
    {
        $url = Endpoint::getContentstackEndpoint('AWS-NA', 'contentDelivery');
        $this->assertSame('https://cdn.contentstack.io', $url);
    }

    public function testUnderscoreAliasResolves(): void
    {
        $url = Endpoint::getContentstackEndpoint('azure_na', 'contentDelivery');
        $this->assertSame('https://azure-na-cdn.contentstack.com', $url);
    }

    public function testGcpUnderscoreAliasResolves(): void
    {
        $url = Endpoint::getContentstackEndpoint('gcp_eu', 'contentManagement');
        $this->assertSame('https://gcp-eu-api.contentstack.com', $url);
    }

    // -------------------------------------------------------------------------
    // Return-all-endpoints (no service)
    // -------------------------------------------------------------------------

    public function testNoServiceReturnsArray(): void
    {
        $result = Endpoint::getContentstackEndpoint('au');
        $this->assertIsArray($result);
        $this->assertGreaterThan(1, count($result));
    }

    public function testNoServiceContainsCorrectUrls(): void
    {
        $endpoints = Endpoint::getContentstackEndpoint('au');
        $this->assertSame('https://au-cdn.contentstack.com', $endpoints['contentDelivery']);
        $this->assertSame('https://au-api.contentstack.com', $endpoints['contentManagement']);
    }

    // -------------------------------------------------------------------------
    // Error cases
    // -------------------------------------------------------------------------

    public function testEmptyRegionThrowsInvalidArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Empty region provided');
        Endpoint::getContentstackEndpoint('');
    }

    public function testUnknownRegionThrowsInvalidArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid region: invalid-region');
        Endpoint::getContentstackEndpoint('invalid-region');
    }

    public function testUnknownServiceThrowsInvalidArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Service "unknownService" not found');
        Endpoint::getContentstackEndpoint('na', 'unknownService');
    }

    // -------------------------------------------------------------------------
    // Utils::getContentstackEndpoint() proxy
    // -------------------------------------------------------------------------

    public function testUtilsProxyReturnsSameResultAsEndpointClass(): void
    {
        $viaEndpoint = Endpoint::getContentstackEndpoint('eu', 'contentDelivery');
        $viaUtils    = Utils::getContentstackEndpoint('eu', 'contentDelivery');
        $this->assertSame($viaEndpoint, $viaUtils);
    }

    public function testUtilsProxyDefaultRegion(): void
    {
        $url = Utils::getContentstackEndpoint('us', 'contentManagement');
        $this->assertSame('https://api.contentstack.io', $url);
    }

    public function testUtilsProxyOmitHttps(): void
    {
        $url = Utils::getContentstackEndpoint('gcp-na', 'contentDelivery', true);
        $this->assertSame('gcp-na-cdn.contentstack.com', $url);
    }

    public function testUtilsProxyAllEndpoints(): void
    {
        $endpoints = Utils::getContentstackEndpoint('azure-eu');
        $this->assertIsArray($endpoints);
        $this->assertArrayHasKey('contentDelivery', $endpoints);
    }
}
