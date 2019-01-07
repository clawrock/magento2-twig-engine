<?php

namespace ClawRock\TwigEngine\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;

class Config
{
    const DEBUG_MODE = 'twig/environment/debug';
    const CHARSET = 'twig/environment/charset';
    const BASE_TEMPLATE_CLASS = 'twig/environment/base_template_class';
    const CACHE = 'twig/environment/cache';
    const AUTO_RELOAD = 'twig/environment/auto_reload';
    const STRICT_VARIABLES = 'twig/environment/strict_variables';
    const AUTOESCAPE = 'twig/environment/autoescape';
    const OPTIMIZATIONS = 'twig/environment/optimizations';
    const AUTO_RESOLVE = 'twig/template/auto_resolve';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * @var \ClawRock\TwigEngine\Model\Config\Data
     */
    private $twigConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\State $appState,
        \ClawRock\TwigEngine\Model\Config\Data $twigConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->appState = $appState;
        $this->twigConfig = $twigConfig;
    }

    public function isDebugMode(): bool
    {
        if ($this->appState->getMode() === State::MODE_PRODUCTION) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(self::DEBUG_MODE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function getCharset(): string
    {
        return $this->scopeConfig->getValue(self::CHARSET, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function getBaseTemplateClass(): string
    {
        return $this->scopeConfig->getValue(self::BASE_TEMPLATE_CLASS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function isCacheEnabled(): bool
    {
        if ($this->appState->getMode() === State::MODE_PRODUCTION) {
            return true;
        }

        return (bool) $this->scopeConfig->getValue(self::CACHE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function isAutoReloadEnabled(): bool
    {
        if ($this->appState->getMode() === State::MODE_PRODUCTION) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(self::AUTO_RELOAD, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function isStrictVariables(): bool
    {
        if ($this->appState->getMode() === State::MODE_PRODUCTION) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(self::STRICT_VARIABLES, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function getAutoescape(): string
    {
        return $this->scopeConfig->getValue(self::AUTOESCAPE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function getOptimizations(): int
    {
        if ($this->appState->getMode() === State::MODE_PRODUCTION) {
            return \Twig_NodeVisitor_Optimizer::OPTIMIZE_ALL;
        }

        return (int) $this->scopeConfig->getValue(self::OPTIMIZATIONS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function isAutoResolveEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(self::AUTO_RESOLVE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function getExtensions(): array
    {
        return $this->twigConfig->get('extensions');
    }
}
