<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Tests\EmailAttachmentHandler;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\EmailAttachmentHandler\FilesystemAttachmentHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Part\DataPart;

class FilesystemAttachmentHandlerTest extends TestCase
{
    public function testWriteAndRemove(): void
    {
        $directory = 'var/email-attachments';

        // Test write
        $handler = new FilesystemAttachmentHandler($directory);

        $email = new AttachmentEmail();

        $content = 'inline file content';
        $name = 'inline-file.txt';
        $email->addPersistedPart(new DataPart($content, $name));

        $handler->writeAttachment($email, $name, $content);
        $path = $directory.'/'.$email->getId().'/'.$name;

        self::assertFileExists($path);
        self::assertSame($content, file_get_contents($path));

        // Test remove
        $handler->removeAttachments($email);

        self::assertFileDoesNotExist($directory.'/'.$email->getId().'/'.$name);
        self::assertDirectoryDoesNotExist($directory.'/'.$email->getId());
    }
}
