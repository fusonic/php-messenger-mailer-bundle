<?php

namespace Fusonic\MessengerMailerBundle\Tests;

use Symfony\Component\Filesystem\Filesystem;

trait TestSetupTrait
{
    protected function setUp(): void
    {
        (new Filesystem())->remove($this->getAttachmentDirectory());
    }

    protected function getAttachmentDirectory(): string
    {
        return 'var/email-attachments';
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove($this->getAttachmentDirectory());
    }
}
