<?php

namespace ClawRock\TwigEngine\Twig\Template;

use Magento\Framework\App\Filesystem\DirectoryList;

class PathValidator
{
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    private $readFactory;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystem;

    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\Filesystem\Driver\File $filesystem
    ) {
        $this->directoryList = $directoryList;
        $this->readFactory = $readFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $fileName
     * @param string $filePath
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function isValid(string $fileName, string $filePath): bool
    {
        // Check if file name not contains any references '/./', '/../'
        if (strpos(str_replace('\\', '/', $fileName), './') === false) {
            return true;
        }

        $directoryWeb = $this->readFactory->create($this->directoryList->getPath(DirectoryList::LIB_WEB));
        $fileRead = $this->readFactory->create($this->filesystem->getRealPath($filePath));

        // Check if file path starts with web lib directory path
        if (strpos($fileRead->getAbsolutePath(), $directoryWeb->getAbsolutePath()) === 0) {
            return true;
        }

        throw new \InvalidArgumentException("File path '{$filePath}' is forbidden for security reasons.");
    }
}
