<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Contracts;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;

interface EmailAttachmentHandlerInterface
{
    /**
     * Handle the writing of AttachmentEmail attachments. For example; creating a directory for the email and
     * writing the attachments in that directory.
     */
    public function writeAttachment(AttachmentEmail $email, string $filename, string $body): string;

    /**
     * Handle the removing of AttachmentEmail attachments. For example; deleting the directory containing the
     * attachments.
     */
    public function removeAttachments(AttachmentEmail $email): void;
}
