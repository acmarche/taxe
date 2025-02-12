<?php
/**
 * This file is part of sf5 application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 16/11/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AcMarche\Taxe;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class TaxeBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function loadExtension(array $config, ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void
    {
        $containerConfigurator->import('../config/services.php');
    }

    public function prependExtension(ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void
    {
        $containerConfigurator->import('../config/packages/twig.php');
        $containerConfigurator->import('../config/packages/doctrine.php');
        $containerConfigurator->import('../config/packages/vich_uploader.php');
    }
}
