<?php

namespace ClawRock\TwigEngine\Twig;

use Magento\Framework\App\Filesystem\DirectoryList;

class LoaderFactory
{
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->directoryList = $directoryList;
    }

    /**
     * @return \Twig\Loader\LoaderInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function create(): \Twig\Loader\LoaderInterface
    {
        return new \Twig\Loader\FilesystemLoader($this->directoryList->getPath(DirectoryList::ROOT));
    }
}
