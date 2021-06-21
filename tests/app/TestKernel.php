<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Fusonic\MessengerMailerBundle\MessengerMailerBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config.yaml');
    }
}
