<?php

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Component\Mime;

use Symfony\Component\Mime\Part\DataPart;

/**
 * Trait with methods for the extends the Symfony Email/TemplatedEmail objects.
 *
 *   - It adds an auto generated `id` property for adding the possibility to 'trace'
 *   - The `Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail::addPersistedPart` method can be used
 *     to add email attachment that will be persisted when handled on an async messenger transport.
 */
trait AttachmentEmailTrait
{
    private string $id;

    /**
     * @var PersistedAttachment[]
     */
    private array $persistedAttachments = [];

    /**
     * @internal this array is only used to 'collect' the data parts so that they can be
     *   converted into real attachment when the message gets handled
     *
     * @var DataPart[]
     */
    private array $collectedDataParts = [];

    public function getId(): string
    {
        return $this->id;
    }

    public function addPersistedPart(DataPart $part): self
    {
        $this->collectedDataParts[] = $part;

        return $this;
    }

    /**
     * @internal
     *
     * @return DataPart[]
     */
    public function getCollectedDataParts(): array
    {
        return $this->collectedDataParts;
    }

    /**
     * @internal
     *
     * @return PersistedAttachment[]
     */
    public function getPersistedAttachments(): array
    {
        return $this->persistedAttachments;
    }

    /**
     * @internal
     *
     * @param PersistedAttachment[] $persistedAttachments
     */
    public function setPersistedAttachments(array $persistedAttachments): void
    {
        $this->persistedAttachments = $persistedAttachments;
        $this->collectedDataParts = [];
    }
}
