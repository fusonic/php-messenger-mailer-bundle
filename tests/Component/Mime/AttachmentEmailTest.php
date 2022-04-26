<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

namespace Fusonic\MessengerMailerBundle\Tests\Component\Mime;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use PHPUnit\Framework\TestCase;

class AttachmentEmailTest extends TestCase
{
    public function test(): void
    {
        $email = new AttachmentEmail();

        $id = $email->getId();

        $serialized = serialize($email);
        $unserialized = unserialize($serialized);

        self::assertInstanceOf(AttachmentEmail::class, $unserialized);
        self::assertSame($id, $unserialized->getId());

        $body1 = 'test content';
        $email->attachPersisted($body1, 'test.txt', 'plain/text');

        $path = '/tmp/test_attachment.txt';
        $body2 = 'this is the content';
        file_put_contents($path, $body2);

        $email->attachPersistedFromPath($path, 'test.txt', 'plain/text');

        $persistedAttachments = $email->getPersistedAttachments();

        self::assertCount(2, $persistedAttachments);

        self::assertSame($body1, $persistedAttachments[0]->getBody());
        self::assertSame($body2, $persistedAttachments[1]->getBody());
    }
}
