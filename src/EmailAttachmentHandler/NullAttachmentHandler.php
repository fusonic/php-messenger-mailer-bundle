<?php

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\EmailAttachmentHandler;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmailInterface;
use Fusonic\MessengerMailerBundle\Contracts\EmailAttachmentHandlerInterface;

/**
 * A simple EmailAttachmentHandler that doesn't do anything. Useful for testing.
 */
class NullAttachmentHandler implements EmailAttachmentHandlerInterface
{
    public function writeAttachment(AttachmentEmailInterface $email, string $filename, string $body): string
    {
        return $filename;
    }

    public function removeAttachments(AttachmentEmailInterface $email): void
    {
    }
}
