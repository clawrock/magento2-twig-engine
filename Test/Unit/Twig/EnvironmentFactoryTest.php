<?php

namespace ClawRock\TwigEngine\Test\Unit\Twig;

use ClawRock\TwigEngine\Twig\EnvironmentFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class EnvironmentFactoryTest extends TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManagerMock;

    /**
     * @var \ClawRock\TwigEngine\Twig\LoaderFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loaderFactoryMock;

    /**
     * @var \Twig\Loader\LoaderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loaderMock;

    /**
     * @var \ClawRock\TwigEngine\Model\Config|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * @var \Twig\Extension\ExtensionInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $extensionMock;

    /**
     * @var \ClawRock\TwigEngine\Twig\EnvironmentFactory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();

        $this->objectManagerMock = $this->getMockForAbstractClass(\Magento\Framework\ObjectManagerInterface::class);

        $this->loaderFactoryMock = $this->getMockBuilder(\ClawRock\TwigEngine\Twig\LoaderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loaderMock = $this->getMockForAbstractClass(\Twig\Loader\LoaderInterface::class);

        $this->configMock = $this->getMockBuilder(\ClawRock\TwigEngine\Model\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->extensionMock = $this->getMockForAbstractClass(\Twig\Extension\ExtensionInterface::class);

        $this->factory = (new ObjectManager($this))->getObject(EnvironmentFactory::class, [
            'objectManager' => $this->objectManagerMock,
            'loaderFactory' => $this->loaderFactoryMock,
            'config' => $this->configMock,
        ]);
    }

    public function testCreate()
    {
        $this->configMock->expects($this->once())->method('isDebugMode')->willReturn(true);
        $this->configMock->expects($this->once())->method('getCharset')->willReturn(true);
        $this->configMock->expects($this->once())->method('getBaseTemplateClass')->willReturn(true);
        $this->configMock->expects($this->once())->method('isCacheEnabled')->willReturn(false);
        $this->configMock->expects($this->once())->method('isAutoReloadEnabled')->willReturn(true);
        $this->configMock->expects($this->once())->method('isStrictVariables')->willReturn(true);
        $this->configMock->expects($this->once())->method('getAutoescape')->willReturn(true);
        $this->configMock->expects($this->once())->method('getOptimizations')->willReturn(true);

        $this->loaderFactoryMock->expects($this->once())->method('create')->willReturn($this->loaderMock);

        $this->configMock->expects($this->once())->method('getExtensions')
            ->willReturn(['extension']);

        $this->objectManagerMock->expects($this->once())->method('create')
            ->willReturn($this->extensionMock);

        $this->assertInstanceOf(\Twig\Environment::class, $this->factory->create());
    }
}
