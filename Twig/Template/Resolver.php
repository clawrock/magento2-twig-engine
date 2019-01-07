<?php

namespace ClawRock\TwigEngine\Twig\Template;

use Magento\Framework\View\Design\Fallback\RulePool;

class Resolver
{
    const TEMPLATE_EXTENSIONS = ['.html.twig', '.twig', '.phtml'];

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    private $readFactory;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $ioFile;

    /**
     * @var \Magento\Framework\View\Design\Fallback\RulePool
     */
    private $rulePool;

    /**
     * @var \ClawRock\TwigEngine\Twig\Template\PathValidator
     */
    private $pathValidator;

    public function __construct(
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \Magento\Framework\View\Design\Fallback\RulePool $rulePool,
        \ClawRock\TwigEngine\Twig\Template\PathValidator $pathValidator
    ) {
        $this->readFactory = $readFactory;
        $this->ioFile = $ioFile;
        $this->rulePool = $rulePool;
        $this->pathValidator = $pathValidator;
    }

    /**
     * @param array $params
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function resolve(array $params): string
    {
        $params = array_filter($params, function ($value) {
            return $value !== null;
        });

        ['dirname' => $dirname, 'filename' => $filename] = $this->ioFile->getPathInfo($params['file']);
        $file = $dirname === '.' ? $filename : $dirname . DIRECTORY_SEPARATOR . $filename;

        foreach ($this->rulePool->getRule(RulePool::TYPE_TEMPLATE_FILE)->getPatternDirs($params) as $dir) {
            foreach (self::TEMPLATE_EXTENSIONS as $ext) {
                $path = $dir . DIRECTORY_SEPARATOR . $file . $ext;
                $dirRead = $this->readFactory->create($dir);
                if ($dirRead->isExist($file . $ext) && $this->pathValidator->isValid($file . $ext, $path)) {
                    return $path;
                }
            }
        }

        return '';
    }
}
