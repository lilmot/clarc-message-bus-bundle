<?php

/**
 * Extension of an ArtoxLabClarcMessengerExtension
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessengerBundle\DependencyInjection;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ArtoxLabClarcMessengerExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container Container builder
     *
     * @return void
     */
    public function prepend(ContainerBuilder $container)
    {
        $this->prependFrameworkMessenger($container);
    }

    /**
     * Prepend configuration of symfony/messenger
     *
     * @param ContainerBuilder $container Container builder
     *
     * @return void
     */
    private function prependFrameworkMessenger(ContainerBuilder $container): void
    {
        $config = [
            'messenger' => [
                'buses'       => [
                    'message.bus' => [
                        'default_middleware' => 'allow_no_handlers',
                        'middleware'         => [
                            'validation',
                            'artox_lab_clarc_messenger.middleware.add_redelivery_stamp_middleware',
                        ],
                    ],
                ],
            ],
        ];

        $container->prependExtensionConfig('framework', $config);
    }

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   Configs
     * @param ContainerBuilder $container Container Builder
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     * @throws Exception
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $this->loadBus(($config['bus'] ?? []), $container);
    }

    /**
     * Load configuration for bus
     *
     * @param array            $config    Config of API
     * @param ContainerBuilder $container Container Builder
     *
     * @return void
     */
    private function loadBus(array $config, ContainerBuilder $container): void
    {
        if (false === empty($config['middleware']['add_redelivery_stamp_middleware']['retry_count'])) {
            $definition = $container->getDefinition('artox_lab_clarc.bus.middleware.add_redelivery_stamp_middleware');
            $definition->setArgument(
                '$retryCount',
                $config['middleware']['add_redelivery_stamp_middleware']['retry_count']
            );
        }
    }

}
