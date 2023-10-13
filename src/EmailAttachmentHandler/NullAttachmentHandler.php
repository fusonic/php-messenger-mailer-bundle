<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\EmailAttachmentHandler;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmailInterface;
use Fusonic\MessengerMailerBundle\Contracts\EmailAttachmentHandlerInterface;

/**
 * A simple EmailAttachmentHandler that doesn't do anything. Useful for testing.
 */
class NullAttachmentHandler implements EmailAttachmentHandlerInterface
{
    public function writeAttachment(AttachmentEmailInterface $email, string $path, string $body): string
    {
        return $path;
    }

    public function readAttachment(string $path): string
    {
        return '';
    }

    public function removeAttachments(AttachmentEmailInterface $email): void
    {
    }
}
