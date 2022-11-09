<?php

namespace Fusonic\MessengerMailerBundle\Component\Mime;

/**
 * Trait with methods for the extends the Symfony Email/TemplatedEmail objects.
 *
 *   - It adds an auto generated `id` property for adding the possibility to 'trace'
 *   - The `Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail::attachPersisted`
 *     and `Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail::attachPersistedFromPath` methods can be used
 *     to add email attachment that will be persisted when handled on an async messenger transport.
 */
trait AttachmentEmailTrait
{
    private string $id;

    /**
     * @var AttachmentData[]
     */
    private array $persistedAttachments = [];

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

    private function generateRandomId(): string
    {
        return bin2hex(random_bytes(16));
    }
}
