<?php

namespace ClawRock\TwigEngine\Twig;

class EnvironmentFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \ClawRock\TwigEngine\Twig\LoaderFactory
     */
    private $loaderFactory;

    /**
     * @var \ClawRock\TwigEngine\Model\Config
     */
    private $config;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \ClawRock\TwigEngine\Twig\LoaderFactory $loaderFactory,
        \ClawRock\TwigEngine\Model\Config $config
    ) {
        $this->objectManager = $objectManager;
        $this->loaderFactory = $loaderFactory;
        $this->config = $config;
    }

    /**
     * @param array $options
     * @return \Twig\Environment
     */
    public function create($options = []): \Twig\Environment
    {
        $options = array_merge([
            'debug'               => $this->config->isDebugMode(),
            'charset'             => $this->config->getCharset(),
            'base_template_class' => $this->config->getBaseTemplateClass(),
            'cache'               => $this->config->isCacheEnabled(),
            'auto_reload'         => $this->config->isAutoReloadEnabled(),
            'strict_variables'    => $this->config->isStrictVariables(),
            'autoescape'          => $this->config->getAutoescape(),
            'optimizations'       => $this->config->getOptimizations(),
        ], $options);

        $twig = new \Twig\Environment($this->loaderFactory->create(), $options);

        foreach ($this->config->getExtensions() as $extension) {
            $twig->addExtension($this->objectManager->create($extension));
        }

        return $twig;
    }
}
