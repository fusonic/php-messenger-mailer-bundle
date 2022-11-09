<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

namespace Fusonic\MessengerMailerBundle\Tests\Component\Mime;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmailInterface;
use Fusonic\MessengerMailerBundle\Component\Mime\TemplatedAttachmentEmail;
use PHPUnit\Framework\TestCase;

class AttachmentEmailTest extends TestCase
{
    /**
     * @dataProvider getTestClasses
     *
     * @param class-string<AttachmentEmailInterface> $testClass
     */
    public function testWithAttachments(string $testClass): void
    {
        $email = new $testClass();

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

    /**
     * @dataProvider getTestClasses
     *
     * @param class-string<AttachmentEmailInterface> $testClass
     */
    public function testSerialization(string $testClass): void
    {
        $email = new $testClass();
        $id = $email->getId();

        $serialized = serialize($email);
        $unserialized = unserialize($serialized);

        self::assertInstanceOf($testClass, $unserialized);
        self::assertSame($id, $unserialized->getId());
    }

    /**
     * @return array<array<class-string<AttachmentEmailInterface>>>
     */
    public function getTestClasses(): array
    {
        return [
            [AttachmentEmail::class],
            [TemplatedAttachmentEmail::class],
        ];
    }
}
