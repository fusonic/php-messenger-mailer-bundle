<?php

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\EmailAttachmentHandler;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmailInterface;
use Fusonic\MessengerMailerBundle\Contracts\EmailAttachmentHandlerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * A simple EmailAttachmentHandler implementation using the Symfony Filesystem component.
 */
class FilesystemAttachmentHandler implements EmailAttachmentHandlerInterface
{
    private Filesystem $fs;
    private string $attachmentsDirectory;

    public function __construct(string $attachmentsDirectory)
    {
        $this->fs = new Filesystem();
        $this->attachmentsDirectory = $attachmentsDirectory;
    }

    public function writeAttachment(AttachmentEmailInterface $email, string $filename, string $body): string
    {
        $path = sprintf(
            '%s/%s/%s',
            $this->attachmentsDirectory,
            $email->getId(),
            $filename
        );

        $this->fs->dumpFile($path, $body);

        return $path;
    }

    public function removeAttachments(AttachmentEmailInterface $email): void
    {
        $this->fs->remove(
            sprintf('%s/%s', $this->attachmentsDirectory, $email->getId())
        );
    }
}
