<?php

declare(strict_types=1);

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

namespace Fusonic\MessengerMailerBundle\DependencyInjection;

use Fusonic\MessengerMailerBundle\EmailAttachmentHandler\FilesystemAttachmentHandler;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('messenger_mailer');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('attachment_handler')->defaultValue(FilesystemAttachmentHandler::class)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
