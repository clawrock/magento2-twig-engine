<?php

namespace ClawRock\TwigEngine\Test\Unit\Twig\Template;

use ClawRock\TwigEngine\Twig\Template\Resolver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Design\Fallback\Rule\RuleInterface;
use Magento\Framework\View\Design\Fallback\RulePool;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $readFactoryMock;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $readMock;

    /**
     * @var \Magento\Framework\Filesystem\Io\File|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ioFileMock;

    /**
     * @var \Magento\Framework\View\Design\Fallback\RulePool|\PHPUnit_Framework_MockObject_MockObject
     */
    private $rulePoolMock;

    /**
     * @var \Magento\Framework\View\Design\Fallback\Rule\RuleInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ruleMock;

    /**
     * @var \ClawRock\TwigEngine\Twig\Template\PathValidator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $pathValidatorMock;

    /**
     * @var \ClawRock\TwigEngine\Twig\Template\Resolver
     */
    private $resolver;

    protected function setUp()
    {
        parent::setUp();

        $this->readFactoryMock = $this->getMockBuilder(\Magento\Framework\Filesystem\Directory\ReadFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->readMock = $this->getMockForAbstractClass(\Magento\Framework\Filesystem\Directory\ReadInterface::class);

        $this->ioFileMock = $this->getMockBuilder(\Magento\Framework\Filesystem\Io\File::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->rulePoolMock = $this->getMockBuilder(RulePool::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->ruleMock = $this->getMockForAbstractClass(RuleInterface::class);

        $this->pathValidatorMock = $this->getMockBuilder(\ClawRock\TwigEngine\Twig\Template\PathValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resolver = (new ObjectManager($this))->getObject(Resolver::class, [
            'readFactory' => $this->readFactoryMock,
            'ioFile' => $this->ioFileMock,
            'rulePool' => $this->rulePoolMock,
            'pathValidator' => $this->pathValidatorMock,
        ]);
    }

    public function testResolve()
    {
        $path = 'html/title.html.twig';
        $params = [
            'file' => $path,
            'locale' => null,
        ];
        $dirs = ['path1', 'path2'];
        $result = $dirs[0] . '/' . $path;

        $this->ioFileMock->expects($this->once())->method('getPathInfo')->with($params['file'])->willReturn([
            'dirname' => 'html',
            'filename' => 'title',
        ]);

        $this->rulePoolMock->expects($this->once())->method('getRule')
            ->with(RulePool::TYPE_TEMPLATE_FILE)
            ->willReturn($this->ruleMock);

        $this->ruleMock->expects($this->once())->method('getPatternDirs')
            ->with(['file' => $params['file']])
            ->willReturn($dirs);

        $this->readFactoryMock->expects($this->once())->method('create')->with('path1')->willReturn($this->readMock);

        $this->readMock->expects($this->once())->method('isExist')->with($path)->willReturn(true);

        $this->pathValidatorMock->expects($this->once())->method('isValid')
            ->with($path, 'path1' . DIRECTORY_SEPARATOR . $path)
            ->willReturn(true);

        $this->assertEquals($result, $this->resolver->resolve($params));
    }

    public function testNoResolve()
    {
        $path = 'html/title.html.twig';
        $params = [
            'file' => $path,
            'locale' => null,
        ];
        $dirs = ['path1', 'path2'];

        $this->ioFileMock->expects($this->once())->method('getPathInfo')->with($params['file'])->willReturn([
            'dirname' => 'html',
            'filename' => 'title',
        ]);

        $this->rulePoolMock->expects($this->once())->method('getRule')
            ->with(RulePool::TYPE_TEMPLATE_FILE)
            ->willReturn($this->ruleMock);

        $this->ruleMock->expects($this->once())->method('getPatternDirs')
            ->with(['file' => $params['file']])
            ->willReturn($dirs);

        $this->readFactoryMock->expects($this->exactly(6))->method('create')
            ->withConsecutive(['path1'], ['path1'], ['path1'], ['path2'], ['path2'], ['path2'])
            ->willReturn($this->readMock);

        $this->readMock->expects($this->exactly(6))->method('isExist')->willReturn(false);

        $this->pathValidatorMock->expects($this->never())->method('isValid');

        $this->assertEquals('', $this->resolver->resolve($params));
    }
}
