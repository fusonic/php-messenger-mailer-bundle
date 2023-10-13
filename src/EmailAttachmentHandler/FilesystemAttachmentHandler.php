<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\EmailAttachmentHandler;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmailInterface;
use Fusonic\MessengerMailerBundle\Contracts\EmailAttachmentHandlerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
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

    public function writeAttachment(AttachmentEmailInterface $email, string $path, string $body): string
    {
        $path = sprintf(
            '%s/%s/%s',
            $this->attachmentsDirectory,
            $email->getId(),
            $path
        );

        $this->fs->dumpFile($path, $body);

        return $path;
    }

    public function readAttachment(string $path): string
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException(null, 0, null, $path);
        }

        $content = file_get_contents($path);

        return false !== $content ? $content : '';
    }

    public function removeAttachments(AttachmentEmailInterface $email): void
    {
        $this->fs->remove(
            sprintf('%s/%s', $this->attachmentsDirectory, $email->getId())
        );
    }
}
