<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

namespace Fusonic\MessengerMailerBundle\Tests;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\EmailAttachmentHandler\FilesystemAttachmentHandler;
use Fusonic\MessengerMailerBundle\EventSubscriber\AttachmentEmailEventSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Event\SendMessageToTransportsEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

class AttachableEmailTest extends TestCase
{
    use TestSetupTrait;

    public function testSendAndHandleMessage(): void
    {
        $attachmentDirectory = $this->getAttachmentDirectory();
        $email = new AttachmentEmail();

        $email->attachPersisted('inline file content', 'inline-file.txt');
        file_put_contents('/tmp/path-file.txt', 'file from path content');
        $email->attachPersistedFromPath('/tmp/path-file.txt');

        // Test sending
        $attachmentHandler = new FilesystemAttachmentHandler($attachmentDirectory);
        $eventSubscriber = new AttachmentEmailEventSubscriber($attachmentHandler);

        $event = new SendMessageToTransportsEvent(new Envelope(new SendEmailMessage($email)));
        $eventSubscriber->onSendMessageToTransportsEvent($event);

        $attachments = $email->getAttachments();
        self::assertCount(2, $attachments);
        self::assertFileExists($attachmentDirectory.'/'.$email->getId().'/path-file.txt');
        self::assertFileExists($attachmentDirectory.'/'.$email->getId().'/inline-file.txt');

        $event = new WorkerMessageHandledEvent(new Envelope(new SendEmailMessage($email)), 'bus');
        $eventSubscriber->onWorkerMessageHandledEvent($event);

        self::assertFileDoesNotExist($attachmentDirectory.'/'.$email->getId().'/path-file.txt');
        self::assertFileDoesNotExist($attachmentDirectory.'/'.$email->getId().'/inline-file.txt');
    }
}
