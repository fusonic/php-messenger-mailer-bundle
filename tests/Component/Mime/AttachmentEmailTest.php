<?php

declare(strict_types=1);

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

namespace Fusonic\MessengerMailerBundle\Tests\Component\Mime;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmailInterface;
use Fusonic\MessengerMailerBundle\Component\Mime\TemplatedAttachmentEmail;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Part\DataPart;

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
        $email->addPersistedPart(new DataPart($body1, 'test.txt', 'plain/text'));

        $path = '/tmp/test_attachment.txt';
        $body2 = 'this is the content';
        file_put_contents($path, $body2);

        $email->addPersistedPart(DataPart::fromPath($path, 'test.txt', 'plain/text'));

        $collectedDataParts = $email->getCollectedDataParts();

        self::assertCount(2, $collectedDataParts);

        self::assertSame($body1, $collectedDataParts[0]->getBody());
        self::assertSame($body2, $collectedDataParts[1]->getBody());
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
