<?php

namespace ClawRock\TwigEngine\Model\Config;

use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{
    /**
     * @param \DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        $config = [
            'extensions' => [],
        ];

        /** @var \DOMNodeList $extensions */
        $extensions = $source->getElementsByTagName('extension');
        foreach ($extensions as $extension) {
            $config['extensions'][] = $extension->nodeValue;
        }

        return $config;
    }
}
