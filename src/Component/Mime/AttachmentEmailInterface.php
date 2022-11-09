<?php

namespace Fusonic\MessengerMailerBundle\Component\Mime;

interface AttachmentEmailInterface
{
    public function getId(): string;

    public function attachPersistedFromPath(string $path, string $name = null, string $contentType = null): self;

    public function attachPersisted(string $body, string $name, string $contentType = null): self;

    /**
     * @return AttachmentData[]
     */
    public function getPersistedAttachments(): array;
}
