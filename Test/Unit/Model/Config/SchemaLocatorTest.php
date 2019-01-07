<?php

namespace ClawRock\TwigEngine\Test\Unit\Model\Config;

use ClawRock\TwigEngine\Model\Config\SchemaLocator;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class SchemaLocatorTest extends TestCase
{
    /**
     * @var \Magento\Framework\Config\Dom\UrnResolver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $urnResolverMock;

    /**
     * @var \ClawRock\TwigEngine\Model\Config\SchemaLocator
     */
    private $schemaLocator;

    protected function setUp()
    {
        parent::setUp();

        $this->urnResolverMock = $this->getMockBuilder(\Magento\Framework\Config\Dom\UrnResolver::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->schemaLocator = (new ObjectManager($this))->getObject(SchemaLocator::class, [
            'urnResolver' => $this->urnResolverMock,
        ]);
    }

    public function testGetPerFileSchema()
    {
        $result = 'xsd realpath';

        $this->urnResolverMock->expects($this->once())->method('getRealPath')
            ->with(SchemaLocator::XSD_PATH)
            ->willReturn($result);

        $this->assertEquals($result, $this->schemaLocator->getPerFileSchema());
    }

    public function testGetSchema()
    {
        $result = 'xsd realpath';

        $this->urnResolverMock->expects($this->once())->method('getRealPath')
            ->with(SchemaLocator::XSD_PATH)
            ->willReturn($result);

        $this->assertEquals($result, $this->schemaLocator->getSchema());
    }
}
