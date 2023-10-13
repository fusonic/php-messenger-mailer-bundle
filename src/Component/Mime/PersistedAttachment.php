<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Component\Mime;

class PersistedAttachment
{
    public function __construct(public readonly string $name, public readonly string $path, public readonly ?string $contentType = null)
    {
    }

    public function __serialize(): array
    {
        return [$this->name, $this->path, $this->contentType];
    }

    public function __unserialize(array $data): void
    {
        [$this->name, $this->path, $this->contentType] = $data;
    }
}
