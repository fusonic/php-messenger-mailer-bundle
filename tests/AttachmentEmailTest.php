<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Tests;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\Component\Mime\TemplatedAttachmentEmail;
use Fusonic\MessengerMailerBundle\EmailAttachmentHandler\FilesystemAttachmentHandler;
use Fusonic\MessengerMailerBundle\Middleware\AttachmentEmailMiddleware;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Test\Middleware\MiddlewareTestCase;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Mime\Part\DataPart;

final class AttachmentEmailTest extends MiddlewareTestCase
{
    use TestSetupTrait;

    /**
     * @dataProvider provideEmailInstances
     */
    public function testSendAndHandleMessage(TemplatedAttachmentEmail|AttachmentEmail $email): void
    {
        $sender = $this->createMock(SenderInterface::class);
        $attachmentDirectory = $this->getAttachmentDirectory();

        $email->addPersistedPart(new DataPart('inline file content', 'inline-file.txt'));
        file_put_contents('/tmp/path-file.txt', 'file from path content');
        $email->addPersistedPart(DataPart::fromPath('/tmp/path-file.txt', 'path-file.txt', 'text/plain'));

        $stack = $this->getStackMock();
        // Test sending
        $attachmentHandler = new FilesystemAttachmentHandler($attachmentDirectory);

        $middleware = new AttachmentEmailMiddleware($attachmentHandler);

        $envelope = (new Envelope(new SendEmailMessage($email)));

        self::assertCount(2, $email->getCollectedDataParts());

        $middleware->handle($envelope, $stack);
        $envelope = $envelope->with(new SentStamp($sender::class, 'test_sender'));

        self::assertCount(0, $email->getAttachments());
        // The collectedDataParts have been reset
        self::assertCount(0, $email->getCollectedDataParts());

        self::assertFileExists($attachmentDirectory.'/'.$email->getId().'/path-file.txt');
        self::assertFileExists($attachmentDirectory.'/'.$email->getId().'/inline-file.txt');

        $envelope = $envelope->with(new ReceivedStamp('test_transport'));
        $middleware->handle($envelope, $stack);

        self::assertFileDoesNotExist($attachmentDirectory.'/'.$email->getId().'/path-file.txt');
        self::assertFileDoesNotExist($attachmentDirectory.'/'.$email->getId().'/inline-file.txt');
    }

    /**
     * @return array<array<TemplatedAttachmentEmail|AttachmentEmail>>
     */
    public static function provideEmailInstances(): array
    {
        return [
            [new AttachmentEmail()],
            [new TemplatedAttachmentEmail()],
        ];
    }
}
