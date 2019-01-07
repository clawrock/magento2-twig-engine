<?php

namespace ClawRock\TwigEngine\View\TemplateEngine;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\TemplateEngineInterface;

class Twig implements TemplateEngineInterface
{
    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    public function __construct(
        \ClawRock\TwigEngine\Twig\EnvironmentFactory $environmentFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->twig = $environmentFactory->create([]);
        $this->filesystem = $filesystem;
    }

    /**
     * @param \Magento\Framework\View\Element\BlockInterface $block
     * @param string                                         $templateFile
     * @param array                                          $dictionary
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(BlockInterface $block, $templateFile, array $dictionary = [])
    {
        $templateFile = $this->filesystem->getDirectoryRead(DirectoryList::ROOT)->getRelativePath($templateFile);
        $dictionary['block'] = $block;

        return $this->twig->load($templateFile)->render($dictionary);
    }
}
