<?php

namespace ClawRock\TwigEngine\Plugin\View\Design\FileResolution\Fallback\Resolver;

use Magento\Framework\View\Design\Fallback\RulePool;
use Magento\Framework\View\Design\FileResolution\Fallback\Resolver\Simple;
use Magento\Framework\View\Design\ThemeInterface;

class SimplePlugin
{
    /**
     * @var \ClawRock\TwigEngine\Twig\Template\Resolver
     */
    private $twigResolver;

    /**
     * @var \ClawRock\TwigEngine\Model\Config
     */
    private $config;

    public function __construct(
        \ClawRock\TwigEngine\Twig\Template\Resolver $twigResolver,
        \ClawRock\TwigEngine\Model\Config $config
    ) {
        $this->twigResolver = $twigResolver;
        $this->config = $config;
    }

    public function aroundResolve(
        Simple $subject,
        callable $proceed,
        $type,
        $file,
        $area = null,
        ThemeInterface $theme = null,
        $locale = null,
        $module = null
    ) {
        if ($type !== RulePool::TYPE_TEMPLATE_FILE || !$this->config->isAutoResolveEnabled()) {
            return $proceed($type, $file, $area, $theme, $locale, $module);
        }

        $path = $this->twigResolver->resolve([
            'area' => $area,
            'theme' => $theme,
            'locale' => $locale,
            'module_name' => $module,
            'file' => $file,
        ]);

        return !empty($path) ? $path : false;
    }
}
