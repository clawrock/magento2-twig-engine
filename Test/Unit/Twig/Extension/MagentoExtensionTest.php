<?php

namespace ClawRock\TwigEngine\Test\Unit\Twig\Extension;

use ClawRock\TwigEngine\Twig\Extension\MagentoExtension;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class MagentoExtensionTest extends TestCase
{
    /**
     * @var \Magento\Checkout\Model\Session|\PHPUnit_Framework_MockObject_MockObject
     */
    private $checkoutSessionMock;

    /**
     * @var \Magento\Customer\Model\Session|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customerSessionMock;

    /**
     * @var \ClawRock\TwigEngine\Twig\Extension\MagentoExtension
     */
    private $extension;

    protected function setUp()
    {
        parent::setUp();

        $this->checkoutSessionMock = $this->getMockBuilder(\Magento\Checkout\Model\Session\Proxy::class)
            ->setMethods(['getQuote'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerSessionMock = $this->getMockBuilder(\Magento\Customer\Model\Session\Proxy::class)
            ->setMethods(['getCustomer'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->extension = (new ObjectManager($this))->getObject(MagentoExtension::class, [
            'checkoutSession' => $this->checkoutSessionMock,
            'customerSession' => $this->customerSessionMock,
        ]);
    }

    public function testGetFilters()
    {
        foreach ($this->extension->getFilters() as $filter) {
            $this->assertInstanceOf(\Twig\TwigFilter::class, $filter);
        }
    }

    public function testGetFunctions()
    {
        foreach ($this->extension->getFunctions() as $function) {
            $this->assertInstanceOf(\Twig\TwigFunction::class, $function);
        }
    }

    public function testGetGlobals()
    {
        $this->assertInternalType('array', $this->extension->getGlobals());
    }
}
