<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EventDayCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(EventDayRegistry::class)) {
            return;
        }

        $registry = $container->findDefinition(EventDayRegistry::class);

        $taggedDays = $container->findTaggedServiceIds('app.event_day');

        foreach ($taggedDays as $id => $tags) {
            $registry->addMethodCall('addDay', [new Reference($id)]);
        }
    }
}
