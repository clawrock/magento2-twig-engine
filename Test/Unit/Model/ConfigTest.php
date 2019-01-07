<?php

namespace ClawRock\TwigEngine\Test\Unit\Model;

use ClawRock\TwigEngine\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfigMock;

    /**
     * @var \Magento\Framework\App\State|\PHPUnit_Framework_MockObject_MockObject
     */
    private $appStateMock;

    /**
     * @var \ClawRock\TwigEngine\Model\Config\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    private $twigConfigMock;

    /**
     * @var \ClawRock\TwigEngine\Model\Config
     */
    private $config;

    protected function setUp()
    {
        parent::setUp();

        $this->scopeConfigMock = $this->getMockForAbstractClass(ScopeConfigInterface::class);

        $this->appStateMock = $this->getMockBuilder(State::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigConfigMock = $this->getMockBuilder(\ClawRock\TwigEngine\Model\Config\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = (new ObjectManager($this))->getObject(Config::class, [
            'scopeConfig' => $this->scopeConfigMock,
            'appState' => $this->appStateMock,
            'twigConfig' => $this->twigConfigMock,
        ]);
    }

    public function testGetAutoescape()
    {
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn('html');
        $this->assertEquals('html', $this->config->getAutoescape());
    }

    public function testIsAutoReloadEnabled()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_DEVELOPER);
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn(true);
        $this->assertTrue($this->config->isAutoReloadEnabled());
    }

    public function testIsAutoReloadEnabledProduction()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_PRODUCTION);
        $this->assertFalse($this->config->isAutoReloadEnabled());
    }

    public function testGetCharset()
    {
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn('UTF-8');
        $this->assertEquals('UTF-8', $this->config->getCharset());
    }

    public function testIsStrictVariables()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_DEVELOPER);
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn(true);
        $this->assertTrue($this->config->isStrictVariables());
    }

    public function testIsStrictVariablesProduction()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_PRODUCTION);
        $this->assertFalse($this->config->isStrictVariables());
    }

    public function testGetExtensions()
    {
        $extensions = ['ext1', 'ext2', 'ext3,'];
        $this->twigConfigMock->expects($this->once())->method('get')->with('extensions')->willReturn($extensions);
        $this->assertEquals($extensions, $this->config->getExtensions());
    }

    public function testIsAutoResolveEnabled()
    {
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn(true);
        $this->assertTrue($this->config->isAutoResolveEnabled());
    }

    public function testIsDebugMode()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_DEVELOPER);
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn(true);
        $this->assertTrue($this->config->isDebugMode());
    }

    public function testIsDebugModeProduction()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_PRODUCTION);
        $this->assertFalse($this->config->isDebugMode());
    }

    public function testIsCacheEnabled()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_DEVELOPER);
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn(false);
        $this->assertFalse($this->config->isCacheEnabled());
    }

    public function testIsCacheEnabledProduction()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_PRODUCTION);
        $this->assertTrue($this->config->isCacheEnabled());
    }

    public function testGetOptimizations()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_DEVELOPER);
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn('-1');
        $this->assertEquals(-1, $this->config->getOptimizations());
    }

    public function testGetOptimizationsProduction()
    {
        $this->appStateMock->expects($this->once())->method('getMode')->willReturn(State::MODE_PRODUCTION);
        $this->assertEquals(\Twig_NodeVisitor_Optimizer::OPTIMIZE_ALL, $this->config->getOptimizations());
    }

    public function testGetBaseTemplateClass()
    {
        $this->scopeConfigMock->expects($this->once())->method('getValue')->willReturn('template_class');
        $this->assertEquals('template_class', $this->config->getBaseTemplateClass());
    }
}
