<?php

namespace App\DependencyInjection;

use App\Service\FeatureTypeRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FeatureTypePass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(FeatureTypeRegistry::class)) {
            return;
        }

        $definition = $container->findDefinition(FeatureTypeRegistry::class);

        $taggedServices = $container->findTaggedServiceIds('app.feature_type');

        foreach ($taggedServices as $id => $tagged) {
            $definition->addMethodCall('register', [new Reference($id)]);
        }
    }
}