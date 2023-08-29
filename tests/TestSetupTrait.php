<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

declare(strict_types=1);

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
