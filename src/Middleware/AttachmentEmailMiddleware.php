<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Middleware;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmailInterface;
use Fusonic\MessengerMailerBundle\Component\Mime\PersistedAttachment;
use Fusonic\MessengerMailerBundle\Contracts\EmailAttachmentHandlerInterface;
use Fusonic\MessengerMailerBundle\Helper\RandomHelper;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

class AttachmentEmailMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly EmailAttachmentHandlerInterface $handler,
    ) {
    }

    private function onSend(AttachmentEmailInterface $email): void
    {
        $persistedAttachments = [];

        foreach ($email->getCollectedDataParts() as $dataPart) {
            $name = $dataPart->getFilename() ?? RandomHelper::randomHex();
            $body = $dataPart->getBody();
            $path = $this->handler->writeAttachment($email, $name, $body);

            $persistedAttachments[] = new PersistedAttachment($name, $path, $dataPart->getContentType());
        }

        $email->setPersistedAttachments($persistedAttachments);
    }

    private function onReceive(AttachmentEmailInterface&Email $email): void
    {
        foreach ($email->getPersistedAttachments() as $persistedAttachment) {
            $body = $this->handler->readAttachment($persistedAttachment->path);

            $email->addPart(new DataPart($body, $persistedAttachment->name, $persistedAttachment->contentType));
        }

        $email->setPersistedAttachments([]);

        $this->handler->removeAttachments($email);
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if (!$message instanceof SendEmailMessage) {
            return $stack->next()->handle($envelope, $stack);
        }

        $email = $message->getMessage();

        if (!$email instanceof AttachmentEmailInterface || !$email instanceof Email) {
            return $stack->next()->handle($envelope, $stack);
        }

        if (null !== $envelope->last(ReceivedStamp::class)) {
            $this->onReceive($email);
        } else {
            $this->onSend($email);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
