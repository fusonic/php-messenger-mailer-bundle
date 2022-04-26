<?php

namespace Fusonic\MessengerMailerBundle\Tests;

use Fusonic\MessengerMailerBundle\Component\Mime\AttachmentEmail;
use Fusonic\MessengerMailerBundle\EventSubscriber\AttachmentEmailEventSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use TestKernel;

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
        $container = self::$container;

        $eventSubscriber = $container->get('test.event_subscriber_attachment_email_event_subscriber');
        self::assertInstanceOf(AttachmentEmailEventSubscriber::class, $eventSubscriber);
    }

    public function testEmail(): void
    {
        $attachmentDirectory = $this->getAttachmentDirectory();

        /** @var MailerInterface $mailer */
        $mailer = self::$container->get('test.mailer');

        $email = (new AttachmentEmail())
            ->subject('test')
            ->from('test@example.com')
            ->to('test@example.com')
            ->text('test');

        $email->attachPersisted('inline file content', 'inline-file.txt');
        file_put_contents('/tmp/path-file.txt', 'file from path content');
        $email->attachPersistedFromPath('/tmp/path-file.txt');

        $mailer->send($email);

        /** @var InMemoryTransport $transport */
        /** @var InMemoryTransport $transport */
        $transport = self::$container->get('test.async_transport');
        /** @var Envelope[] $messages */
        $messages = $transport->get();

        self::assertCount(1, $transport->getSent());
        self::assertCount(1, $messages);
        self::assertInstanceOf(SendEmailMessage::class, $messages[0]->getMessage());

        self::assertDirectoryExists($attachmentDirectory.'/'.$email->getId());
        self::assertFileExists($attachmentDirectory.'/'.$email->getId().'/path-file.txt');
        self::assertFileExists($attachmentDirectory.'/'.$email->getId().'/inline-file.txt');
    }
}
