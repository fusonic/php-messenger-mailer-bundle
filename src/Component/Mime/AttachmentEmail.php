<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Component\Mime;

use Fusonic\MessengerMailerBundle\Helper\RandomHelper;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

class AttachmentEmail extends Email implements AttachmentEmailInterface
{
    use AttachmentEmailTrait;

    public function __construct(?Headers $headers = null, ?AbstractPart $body = null)
    {
        $this->id = RandomHelper::randomHex();

        parent::__construct($headers, $body);
    }

    public function __serialize(): array
    {
        return [$this->id, $this->persistedAttachments, parent::__serialize()];
    }

    /**
     * @param array<mixed> $data
     */
    public function __unserialize(array $data): void
    {
        [$this->id, $this->persistedAttachments, $parentData] = $data;

        parent::__unserialize($parentData);
    }
}
