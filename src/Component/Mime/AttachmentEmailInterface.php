<?php

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Component\Mime;

use Symfony\Component\Mime\Part\DataPart;

interface AttachmentEmailInterface
{
    public function getId(): string;

    public function addPersistedPart(DataPart $part): self;

    /**
     * @return DataPart[]
     */
    public function getCollectedDataParts(): array;

    /**
     * @return PersistedAttachment[]
     */
    public function getPersistedAttachments(): array;

    /**
     * @param PersistedAttachment[] $persistedAttachments
     */
    public function setPersistedAttachments(array $persistedAttachments): void;
}
