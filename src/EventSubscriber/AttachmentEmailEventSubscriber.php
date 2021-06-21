<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\EventSubscriber;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\Contracts\EmailAttachmentHandlerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Event\SendMessageToTransportsEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

class AttachmentEmailEventSubscriber implements EventSubscriberInterface
{
    private EmailAttachmentHandlerInterface $handler;

    public function __construct(EmailAttachmentHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SendMessageToTransportsEvent::class => 'onSendMessageToTransportsEvent',
            WorkerMessageHandledEvent::class => 'onWorkerMessageHandledEvent',
        ];
    }

    /**
     * Write AttachmentEmail attachments using the provided EmailAttachmentHandlerInterface.
     */
    public function onSendMessageToTransportsEvent(SendMessageToTransportsEvent $event): void
    {
        $envelope = $event->getEnvelope();
        $message = $envelope->getMessage();

        if (!$message instanceof SendEmailMessage) {
            return;
        }

        $email = $message->getMessage();

        if (!$email instanceof AttachmentEmail) {
            return;
        }

        foreach ($email->getPersistedAttachments() as $attachmentData) {
            $name = $attachmentData->getName();
            $body = $attachmentData->getBody();
            $path = $this->handler->writeAttachment($email, $name, $body);
            $email->attachFromPath($path, $name, $attachmentData->getContentType());
        }
    }

    /**
     * Remove AttachmentEmail attachments using the provided EmailAttachmentHandlerInterface.
     */
    public function onWorkerMessageHandledEvent(WorkerMessageHandledEvent $event): void
    {
        $envelope = $event->getEnvelope();
        $message = $envelope->getMessage();

        if (!$message instanceof SendEmailMessage) {
            return;
        }

        $email = $message->getMessage();

        if (!$email instanceof AttachmentEmail) {
            return;
        }

        $this->handler->removeAttachments($email);
    }
}
