<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Component\Mime;

use Symfony\Component\Mime\Part\DataPart;

class AttachmentData
{
    private ?string $contentType;
    private string $body;
    private string $name;

    private function __construct(string $name, string $body, string $contentType = null)
    {
        $this->name = $name;
        $this->body = $body;
        $this->contentType = $contentType;
    }

    public static function fromBody(string $name, string $body, string $contentType = null): self
    {
        return new self($name, $body, $contentType);
    }

    public static function fromPath(string $path, string $name = null, string $contentType = null): self
    {
        $data = DataPart::fromPath($path);
        $body = $data->getBody();

        if (!$name) {
            $name = basename($path);
        }

        return new self($name, $body, $contentType);
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
