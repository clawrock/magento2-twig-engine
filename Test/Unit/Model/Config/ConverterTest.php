<?php

namespace ClawRock\TwigEngine\Test\Unit\Model\Config;

use ClawRock\TwigEngine\Model\Config\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    /**
     * @var \ClawRock\TwigEngine\Model\Config\Converter
     */
    private $converter;

    protected function setUp()
    {
        $this->converter = new Converter();
    }

    public function testConvert()
    {
        $extensions = [
            'MagentoExtension',
            'MagentoExtension2',
            'MagentoExtension3',
        ];

        $xml = new \DOMDocument('1.0', 'UTF-8');
        $twigNode = $xml->createElement('twig');
        $extensionsNode = $xml->createElement('extensions');

        foreach ($extensions as $extension) {
            $extensionsNode->appendChild($xml->createElement('extension', $extension));
        }

        $xml->appendChild($twigNode);
        $twigNode->appendChild($extensionsNode);

        $this->assertEquals([
            'extensions' => $extensions
        ], $this->converter->convert($xml));
    }
}
