<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Component\Mime;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

/**
 * Extends the Symfony Email object.
 *
 *   - It adds an auto generated `id` property for adding the possibility to 'trace'
 *   - The `Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail::attachPersisted`
 *     and `Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail::attachPersistedFromPath` methods can be used
 *     to add email attachment that will be persisted when handled on an async messenger transport.
 */
class AttachmentEmail extends Email
{
    private string $id;

    /**
     * @var AttachmentData[]
     */
    private array $persistedAttachments = [];

    public function __construct(Headers $headers = null, AbstractPart $body = null)
    {
        $this->id = self::generateRandomId();

        parent::__construct($headers, $body);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function attachPersistedFromPath(string $path, string $name = null, string $contentType = null): self
    {
        $this->persistedAttachments[] = AttachmentData::fromPath($path, $name, $contentType);

        return $this;
    }

    public function attachPersisted(string $body, string $name, string $contentType = null): self
    {
        $this->persistedAttachments[] = AttachmentData::fromBody($name, $body, $contentType);

        return $this;
    }

    /**
     * @return AttachmentData[]
     */
    public function getPersistedAttachments(): array
    {
        return $this->persistedAttachments;
    }

    public function __serialize(): array
    {
        return [$this->id, parent::__serialize()];
    }

    public function __unserialize(array $data): void
    {
        [$this->id, $parentData] = $data;

        parent::__unserialize($parentData);
    }

    private static function generateRandomId(): string
    {
        return bin2hex(random_bytes(16));
    }
}
