<?php

/**
 * Configuration of bundle
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\ClarcMessageBusBundle\DependencyInjection;

use ArtoxLab\Bundle\ClarcMessageBusBundle\ArtoxLabClarcMessageBusBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $treeBuilder = new TreeBuilder(ArtoxLabClarcMessageBusBundle::CONFIG_BUNDLE_NAMESPACE);

        $this->getRootNode($treeBuilder)
            ->children()
                ->arrayNode('bus')
                    ->children()
                        ->arrayNode('middleware')
                            ->children()
                                ->arrayNode('add_redelivery_stamp_middleware')
                                    ->children()
                                        ->integerNode('retry_count')
                                            ->min(0)
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * Getting root node of configuration with symfony 3.4 compatibility
     *
     * @param TreeBuilder $treeBuilder TreeBuilder
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    protected function getRootNode(TreeBuilder $treeBuilder)
    {
        if (true === method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->getRootNode();
        }

        return $treeBuilder->root(ArtoxLabClarcMessageBusBundle::CONFIG_BUNDLE_NAMESPACE);
    }

}
