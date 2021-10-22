<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EventDayCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(EventDayRegistry::class)) {
            return;
        }

        $registry = $container->findDefinition(EventDayRegistry::class);

        $taggedDays = $container->findTaggedServiceIds('app.event_day');

        foreach ($taggedDays as $id => $tags) {
            preg_match('/Year(\d+)\\\\Day(\d+)/', $id, $matches);
            $registry->addMethodCall('addDay', [(int) $matches[1], (int) $matches[2], new Reference($id)]);
        }
    }
}
