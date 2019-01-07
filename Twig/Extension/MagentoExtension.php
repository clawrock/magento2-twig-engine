<?php

namespace ClawRock\TwigEngine\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class MagentoExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    private $formKey;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $url;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \ClawRock\TwigEngine\Model\TranslationFactory
     */
    private $translationFactory;

    public function __construct(
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\Session\Proxy $checkoutSession,
        \Magento\Customer\Model\Session\Proxy $customerSession,
        \ClawRock\TwigEngine\Model\TranslationFactory $translationFactory
    ) {
        $this->formKey = $formKey;
        $this->url = $url;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->translationFactory = $translationFactory;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new \Twig\TwigFilter('price', [$this->priceCurrency, 'format'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('__', [$this->translationFactory, 'create']),
            new \Twig\TwigFunction('url', [$this->url, 'getUrl']),
            new \Twig\TwigFunction('is_logged_in', [$this->customerSession, 'isLoggedIn']),
        ];
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getGlobals()
    {
        return [
            'store' => $this->storeManager->getStore(),
            'form_key' => $this->formKey->getFormKey(),
            'customer' => $this->customerSession->getCustomer(),
            'quote' => $this->checkoutSession->getQuote(),
        ];
    }
}
