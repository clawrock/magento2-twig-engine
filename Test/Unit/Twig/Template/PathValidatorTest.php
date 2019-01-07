<?php

namespace ClawRock\TwigEngine\Test\Unit\Twig\Template;

use ClawRock\TwigEngine\Twig\Template\PathValidator;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class PathValidatorTest extends TestCase
{
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList|\PHPUnit_Framework_MockObject_MockObject
     */
    private $directoryListMock;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $readFactoryMock;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File|\PHPUnit_Framework_MockObject_MockObject
     */
    private $filesystemMock;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $webDirectoryReadMock;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fileReadMock;

    /**
     * @var \ClawRock\TwigEngine\Twig\Template\PathValidator
     */
    private $pathValidator;

    protected function setUp()
    {
        parent::setUp();

        $this->directoryListMock = $this->getMockBuilder(\Magento\Framework\App\Filesystem\DirectoryList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->readFactoryMock = $this->getMockBuilder(\Magento\Framework\Filesystem\Directory\ReadFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filesystemMock = $this->getMockBuilder(\Magento\Framework\Filesystem\Driver\File::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->webDirectoryReadMock = $this->getMockForAbstractClass(ReadInterface::class);

        $this->fileReadMock = $this->getMockForAbstractClass(ReadInterface::class);

        $this->pathValidator = (new ObjectManager($this))->getObject(PathValidator::class, [
            'directoryList' => $this->directoryListMock,
            'readFactory' => $this->readFactoryMock,
            'filesystem' => $this->filesystemMock,
        ]);
    }

    public function testIsValid()
    {
        $this->assertTrue($this->pathValidator->isValid('path/filename', 'path'));
    }

    public function testIsLibWebDirectory()
    {
        $filename = 'path2/path/../lib/web/filename';
        $filePath = 'lib/web/path';
        $realPath = 'realpath';
        $webPath = 'lib_web_path';
        $this->directoryListMock->expects($this->once())->method('getPath')
            ->with(DirectoryList::LIB_WEB)
            ->willReturn($webPath);

        $this->filesystemMock->expects($this->once())->method('getRealPath')->with($filePath)->willReturn($realPath);

        $this->readFactoryMock->expects($this->exactly(2))->method('create')
            ->withConsecutive([$webPath], [$realPath])
            ->willReturnOnConsecutiveCalls($this->webDirectoryReadMock, $this->fileReadMock);

        $this->fileReadMock->expects($this->once())->method('getAbsolutePath')->willReturn($filePath);
        $this->webDirectoryReadMock->expects($this->once())->method('getAbsolutePath')->willReturn('lib/web');

        $this->assertTrue($this->pathValidator->isValid($filename, $filePath));
    }

    public function testForbiddenPath()
    {
        $filename = 'path2/path/../app/code/filename';
        $filePath = 'app/code/path';
        $realPath = 'realpath';
        $webPath = 'lib_web_path';

        $this->expectException(\InvalidArgumentException::class);

        $this->directoryListMock->expects($this->once())->method('getPath')
            ->with(DirectoryList::LIB_WEB)
            ->willReturn($webPath);

        $this->filesystemMock->expects($this->once())->method('getRealPath')->with($filePath)->willReturn($realPath);

        $this->readFactoryMock->expects($this->exactly(2))->method('create')
            ->withConsecutive([$webPath], [$realPath])
            ->willReturnOnConsecutiveCalls($this->webDirectoryReadMock, $this->fileReadMock);

        $this->fileReadMock->expects($this->once())->method('getAbsolutePath')->willReturn($filePath);
        $this->webDirectoryReadMock->expects($this->once())->method('getAbsolutePath')->willReturn('lib/web');

        $this->assertTrue($this->pathValidator->isValid($filename, $filePath));
    }
}
