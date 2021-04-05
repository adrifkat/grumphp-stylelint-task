<?php

namespace Adrifkat\GrumPHPStylelint;

use GrumPHP\Extension\ExtensionInterface;
use Adrifkat\GrumPHPStylelint\Task\StylelintTask;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Extension implements ExtensionInterface
{
    public function load(ContainerBuilder $container): void
    {
        $container->register('task.stylelint', StylelintTask::class)
            ->addArgument(new Reference('config'))
            ->addArgument(new Reference('process_builder'))
            ->addArgument(new Reference('formatter.raw_process'))
            ->addTag('grumphp.task', ['config' => 'stylelint']);
    }
}