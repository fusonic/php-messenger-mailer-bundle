<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\MessengerMailerBundle\Tests;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\Middleware\AttachmentEmailMiddleware;
use Fusonic\MessengerMailerBundle\Tests\app\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;

class FunctionalTest extends KernelTestCase
{
    use TestSetupTrait;

    protected function setUp(): void
    {
        (new Filesystem())->remove($this->getAttachmentDirectory());
        self::bootKernel();
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function testBootKernel(): void
    {
        (new Filesystem())->remove('var/cache/test');
        self::bootKernel();
        self::assertTrue(static::$booted);
    }

    public function testServiceWiring(): void
    {
        $middleware = self::getContainer()->get(AttachmentEmailMiddleware::class);
        self::assertInstanceOf(AttachmentEmailMiddleware::class, $middleware);
    }

    public function testEmail(): void
    {
        $attachmentDirectory = $this->getAttachmentDirectory();

        /** @var MailerInterface $mailer */
        $mailer = self::getContainer()->get('test.mailer');

        $email = (new AttachmentEmail())
            ->subject('test')
            ->from('test@example.com')
            ->to('test@example.com')
            ->text('test');

        $email->addPersistedPart(new DataPart('inline file content', 'inline-file.txt'));
        file_put_contents('/tmp/path-file.txt', 'file from path content');
        $email->addPersistedPart(DataPart::fromPath('/tmp/path-file.txt'));

        $mailer->send($email);

        self::assertQueuedEmailCount(1);
        self::assertDirectoryExists($attachmentDirectory.'/'.$email->getId());
        self::assertFileExists($attachmentDirectory.'/'.$email->getId().'/path-file.txt');
        self::assertFileExists($attachmentDirectory.'/'.$email->getId().'/inline-file.txt');
    }
}
