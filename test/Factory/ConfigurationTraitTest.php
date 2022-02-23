<?php

declare(strict_types=1);

namespace LaminasTest\I18n\PhoneNumber\Factory;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ConfigurationTraitTest extends TestCase
{
    private TestFactory $factory;
    /** @var MockObject&ContainerInterface */
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory   = new TestFactory();
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /** @return array<array-key, array{0: array}> */
    public function nullConfigProvider(): array
    {
        return [
            [[]],
            [['laminas-i18n-phone-number' => []]],
            [['laminas-i18n-phone-number' => ['default-country-code' => null]]],
        ];
    }

    /** @dataProvider nullConfigProvider */
    public function testGetCountryCodeForConfigurationSetupsThatResultInNull(array $config): void
    {
        $this->container->expects(self::once())
            ->method('has')
            ->with('config')
            ->willReturn(true);

        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        self::assertNull($this->factory->getDefaultCountry($this->container));
    }

    public function testThatCountryCodeIsNullWhenThereIsNoConfig(): void
    {
        $this->container->expects(self::once())
            ->method('has')
            ->with('config')
            ->willReturn(false);

        $this->container->expects(self::never())
            ->method('get');

        self::assertNull($this->factory->getDefaultCountry($this->container));
    }

    public function testExpectedCountryCodeIsReturned(): void
    {
        $this->container->expects(self::once())
            ->method('has')
            ->with('config')
            ->willReturn(true);

        $this->container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn([
                'laminas-i18n-phone-number' => [
                    'default-country-code' => 'ZA',
                ],
            ]);

        self::assertEquals('ZA', $this->factory->getDefaultCountry($this->container));
    }
}
