<?php

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Tests\EmailAttachmentHandler;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\EmailAttachmentHandler\NullAttachmentHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Part\DataPart;

class NullAttachmentHandlerTest extends TestCase
{
    public function testWriteAndRemove(): void
    {
        $handler = new NullAttachmentHandler();
        $email = new AttachmentEmail();

        $content = 'inline file content';
        $name = 'inline-file.txt';
        $email->addPersistedPart(new DataPart($content, $name));

        $filename = $handler->writeAttachment($email, $name, $content);

        self::assertSame($name, $filename);

        $handler->removeAttachments($email);
    }
}
