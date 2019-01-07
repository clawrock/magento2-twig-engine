# Magento 2 - Twig Template Engine
Module implementing Twig as Magento 2 template engine. It works with default .phtml templates as fallback.

## Installation
1. Install module via composer `composer require clawrock/magento2-twig-engine`
2. Register module `php bin/magento setup:upgrade`

## Compatibility
* Magento 2.2 - 2.3
* PHP 7.0 - 7.2

## Configuration
There is **no** `system.xml` file, because [Twig configuration](https://twig.symfony.com/doc/2.x/api.html#environment-options) should be only edited by developers.
### Auto resolve option
This module can automatically look for twig files. For example if you want to use twig template in `Magento\Theme\Block\Html\Title` block just add file in `app/design/frontend/Vendor/theme/Magento_Theme/templates/html/title.html.twig`. If you disable auto resolve you will have to update each block template manually:
```xml
<referenceBlock name="page.main.title" template="Magento_Theme::html/title.html.twig"/>
```
Auto resolve option works the same as Magento 2 template resolver. For example if your theme is Luma and template is `Magento_Theme::html/title.phtml` it will check possible paths in following order until first existing file:
```text
vendor/magento/theme-frontend-luma/Magento_Theme/templates/html/title.html.twig
vendor/magento/theme-frontend-luma/Magento_Theme/templates/html/title.twig
vendor/magento/theme-frontend-luma/Magento_Theme/templates/html/title.phtml

vendor/magento/theme-frontend-blank/Magento_Theme/templates/html/title.html.twig
vendor/magento/theme-frontend-blank/Magento_Theme/templates/html/title.twig
vendor/magento/theme-frontend-blank/Magento_Theme/templates/html/title.phtml

vendor/magento/module-theme/view/frontend/templates/html/title.html.twig
vendor/magento/module-theme/view/frontend/templates/html/title.twig
vendor/magento/module-theme/view/frontend/templates/html/title.phtml
```

## Extending
### Environment options override
#### XML
Create module dependent on ClawRock_TwigEngine and override values in config.xml
#### Programmatically
Create before plugin for EnvironmentFactory and modify $options argument.

### Create new Twig Extension
Create Extension class extending AbstractExtension or implementing ExtensionInterface and register it with `etc/twig.xml`.

Example: `ClawRock\TwigEngine\Twig\Extension\MagentoExtension`

[See Twig documentation](https://twig.symfony.com/doc/2.x/advanced.html#creating-an-extension). 

**Important!**
You can use Magento DI in Twig Extensions. 

## Resources
- [Twig](https://twig.symfony.com/)
- [Extensions](https://github.com/twigphp/Twig-extensions)
- [Online converter](https://phptotwig.com/)

