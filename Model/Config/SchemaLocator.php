<?php

namespace ClawRock\TwigEngine\Model\Config;

use Magento\Framework\Config\SchemaLocatorInterface;

class SchemaLocator implements SchemaLocatorInterface
{
    const XSD_PATH = 'urn:magento:module:ClawRock_TwigEngine:etc/twig.xsd';

    /**
     * @var \Magento\Framework\Config\Dom\UrnResolver
     */
    private $urnResolver;

    public function __construct(
        \Magento\Framework\Config\Dom\UrnResolver $urnResolver
    ) {
        $this->urnResolver = $urnResolver;
    }

    /**
     * @return string|null
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function getSchema()
    {
        return $this->urnResolver->getRealPath(self::XSD_PATH);
    }

    /**
     * @return string|null
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function getPerFileSchema()
    {
        return $this->urnResolver->getRealPath(self::XSD_PATH);
    }
}
