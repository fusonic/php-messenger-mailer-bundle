<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Contracts;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmailInterface;

interface EmailAttachmentHandlerInterface
{
    /**
     * Handle the writing of AttachmentEmailInterface attachments. For example; creating a directory for the email and
     * writing the attachments in that directory.
     */
    public function writeAttachment(AttachmentEmailInterface $email, string $path, string $body): string;

    public function readAttachment(string $path): string;

    /**
     * Handle the removing of AttachmentEmailInterface attachments. For example; deleting the directory containing the
     * attachments.
     */
    public function removeAttachments(AttachmentEmailInterface $email): void;
}
