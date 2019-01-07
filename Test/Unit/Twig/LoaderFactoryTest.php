<?php

namespace ClawRock\TwigEngine\Test\Unit\Twig;

use ClawRock\TwigEngine\Twig\LoaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class LoaderFactoryTest extends TestCase
{
    public function testCreate()
    {
        $directoryListMock = $this->getMockBuilder(DirectoryList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $directoryListMock->expects($this->once())->method('getPath')->with(DirectoryList::ROOT);

        $factory = (new ObjectManager($this))->getObject(LoaderFactory::class, [
            'directoryList' => $directoryListMock,
        ]);

        $this->assertInstanceOf(\Twig\Loader\LoaderInterface::class, $factory->create());
    }
}
