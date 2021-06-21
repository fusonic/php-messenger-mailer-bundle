<?php

namespace Fusonic\MessengerMailerBundle\Tests\EmailAttachmentHandler;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\EmailAttachmentHandler\FilesystemAttachmentHandler;
use PHPUnit\Framework\TestCase;

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
        $email->attachPersisted($content, $name);

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
