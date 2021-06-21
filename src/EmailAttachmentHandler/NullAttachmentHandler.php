<?php

namespace Fusonic\MessengerMailerBundle\EmailAttachmentHandler;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\Contracts\EmailAttachmentHandlerInterface;

/**
 * A simple EmailAttachmentHandler that doesn't do anything. Useful for testing.
 */
class NullAttachmentHandler implements EmailAttachmentHandlerInterface
{
    public function writeAttachment(AttachmentEmail $email, string $filename, string $body): string
    {
        return $filename;
    }

    public function removeAttachments(AttachmentEmail $email): void
    {
    }
}
