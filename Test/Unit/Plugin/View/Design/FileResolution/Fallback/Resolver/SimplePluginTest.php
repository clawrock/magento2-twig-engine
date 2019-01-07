<?php

namespace ClawRock\TwigEngine\Test\Unit\Plugin\View\Design\FileResolution\Fallback\Resolver;

use ClawRock\TwigEngine\Model\Config;
use ClawRock\TwigEngine\Plugin\View\Design\FileResolution\Fallback\Resolver\SimplePlugin;
use ClawRock\TwigEngine\Twig\Template\Resolver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Design\Fallback\RulePool;
use Magento\Framework\View\Design\FileResolution\Fallback\Resolver\Simple;
use PHPUnit\Framework\TestCase;

class SimplePluginTest extends TestCase
{
    /**
     * @var \ClawRock\TwigEngine\Model\Config\|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * @var \ClawRock\TwigEngine\Twig\Template\Resolver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $twigResolverMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $subjectMock;

    /**
     * @var callable
     */
    private $proceed;

    /**
     * @var \ClawRock\TwigEngine\Plugin\View\Design\FileResolution\Fallback\Resolver\SimplePlugin
     */
    private $plugin;

    protected function setUp()
    {
        parent::setUp();

        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigResolverMock = $this->getMockBuilder(Resolver::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subjectMock = $this->getMockBuilder(Simple::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->proceed = function () {
            return 'resolved_path';
        };

        $this->plugin = (new ObjectManager($this))->getObject(SimplePlugin::class, [
            'twigResolver' => $this->twigResolverMock,
            'config' => $this->configMock,
        ]);
    }

    public function testPluginNoResolve()
    {
        $this->configMock->expects($this->once())->method('isAutoResolveEnabled')->willReturn(false);
        $this->assertEquals('resolved_path', $this->plugin->aroundResolve(
            $this->subjectMock,
            $this->proceed,
            RulePool::TYPE_TEMPLATE_FILE,
            'file'
        ));
    }

    public function testPluginNotFound()
    {
        $this->configMock->expects($this->once())->method('isAutoResolveEnabled')->willReturn(true);
        $this->twigResolverMock->expects($this->once())->method('resolve')->willReturn('');
        $this->assertFalse($this->plugin->aroundResolve(
            $this->subjectMock,
            $this->proceed,
            RulePool::TYPE_TEMPLATE_FILE,
            'file'
        ));
    }

    public function testPlugin()
    {
        $this->configMock->expects($this->once())->method('isAutoResolveEnabled')->willReturn(true);
        $this->twigResolverMock->expects($this->once())->method('resolve')->willReturn('file_path');
        $this->assertEquals('file_path', $this->plugin->aroundResolve(
            $this->subjectMock,
            $this->proceed,
            RulePool::TYPE_TEMPLATE_FILE,
            'file'
        ));
    }
}
