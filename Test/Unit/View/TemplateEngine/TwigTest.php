<?php

namespace ClawRock\TwigEngine\Test\Unit\View\TemplateEngine;

use ClawRock\TwigEngine\View\TemplateEngine\Twig;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class TwigTest extends TestCase
{
    public function testRender()
    {
        $environmentFactoryMock = $this->getMockBuilder(\ClawRock\TwigEngine\Twig\EnvironmentFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $environmentMock = $this->getMockBuilder(\Twig\Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $environmentFactoryMock->expects($this->once())->method('create')->willReturn($environmentMock);

        $filesystemMock = $this->getMockBuilder(\Magento\Framework\Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $readMock = $this->getMockForAbstractClass(\Magento\Framework\Filesystem\Directory\ReadInterface::class);

        $blockMock = $this->getMockForAbstractClass(\Magento\Framework\View\Element\BlockInterface::class);

        $engine = (new ObjectManager($this))->getObject(Twig::class, [
            'environmentFactory' => $environmentFactoryMock,
            'filesystem' => $filesystemMock,
        ]);

        $filesystemMock->expects($this->once())->method('getDirectoryRead')
            ->with(\Magento\Framework\App\Filesystem\DirectoryList::ROOT)
            ->willReturn($readMock);
        $readMock->expects(($this->once()))->method('getRelativePath')->willReturn('template_file');
        $environmentMock->expects($this->once())->method('load')
            ->with('template_file')
            ->willReturnSelf();
        $environmentMock->expects($this->once())->method('render')
            ->with(['entry' => 1, 'block' => $blockMock])
            ->willReturn('rendered');

        $this->assertEquals('rendered', $engine->render($blockMock, 'template_file', ['entry' => 1]));
    }
}
