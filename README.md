# messenger-mailer-bundle

[![License](https://img.shields.io/packagist/l/fusonic/messenger-mailer-bundle?color=blue)](https://github.com/fusonic/php-messenger-mailer-bundle/blob/master/LICENSE)
[![Latest Version](https://img.shields.io/github/tag/fusonic/php-messenger-mailer-bundle.svg?color=blue)](https://github.com/fusonic/php-messenger-mailer-bundle/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/fusonic/messenger-mailer-bundle.svg?color=blue)](https://packagist.org/packages/fusonic/messenger-mailer-bundle)
![php 8.2+](https://img.shields.io/badge/php-min%208.1-blue.svg)

* [About](#about)
* [Install](#install)
* [Usage](#usage)
* [Contributing](#contributing)

## About

You might need to bundle if your project fulfills the following criteria:

* You are using **Symfony Mailer** with **Symfony Messenger**.
* Your message queue containing the Mailer messages is running asynchronously.
* Your email contains **attachments**.

This bundle solves the following problems that can occur:

* If you use `Symfony\Component\Mime\Email::attach` the message will contain the entire file. Using blob data inside the message transport is not recommended
  and can lead to problems.
* If you use `Symfony\Component\Mime\Email::attachFromPath`, the path might not exist at the moment of handling the message (depending on your implementation).
An example is when you are generating a temporary file (such as a PDF) and want to attach it to the e-mail. If this is a temporary file
it might get deleted before the message is handled.

## Install

Use composer to install the bundle from packagist.

```bash
composer require fusonic/messenger-mailer-bundle
```

Requirements:

- PHP 8.2+
- Symfony 6.2+

In case Symfony did not add the bundle to the bundle configuration, add the following (by default located in `config/bundles.php`):

```
<?php

return [
    // ...
    Fusonic\MessengerMailerBundle\MessengerMailerBundle::class => ['all' => true],
];
```

## Configuration (optional)
The only thing you are required to configure is the provided middleware on your message bus that
handles the `SendEmailMessage` event.

```yaml
# Your messenger configuration
framework:
    # ...
    messenger:
        # ...
        buses:
            default: # or your own bus that handled the SendEmailMessage event
                - Fusonic\MessengerMailerBundle\Middleware\AttachmentEmailMiddleware


# Bundle default configuration
messenger_mailer:
    # Use the included filesystem implementation or implement your own service
    # by implementing the `Fusonic\MessengerMailerBundle\Contracts\EmailAttachmentHandlerInterface` interface.
    attachment_handler: Fusonic\MessengerMailerBundle\EmailAttachmentHandler\FilesystemAttachmentHandler

services:
    # Configure the services used as the `attachment_handler` above. This service is configured by default.
    Fusonic\MessengerMailerBundle\EmailAttachmentHandler\FilesystemAttachmentHandler:
        arguments:
            $attachmentsDirectory: "%kernel.project_dir%/var/email-attachments"
```

If you want to use a different service for attachment handling, you can create your own and overwrite the default in your service configuration.
You can for example create a handler that would save the attachments with an abstract filesystem (e.g.: `thephpleague/flysystem`).

## Usage

This bundle provides two classes for creating e-mails [AttachmentEmail](src/Component/Mime/AttachmentEmail.php) (extension of the Symfony `Email` class)
and [TemplatedAttachmentEmail](src/Component/Mime/TemplatedAttachmentEmail.php) (extension of the Symfony `TemplatedEmail` class).

Instead of using `attach`, `attachFromPath` and `addPart` you should use `addPersistedPart`.
This will persist the content depending on the `FilesystemAttachmentHandler`. This way the email can be safely handled asynchronously.

```php

$email = (new TemplatedAttachmentEmail())
    ->from('hello@example.com')
    ->addPersistedPart(new DataPart('Email text', 'filename.txt', 'plain/text'))
    // ...
    ->html('...');
```

## Contributing

This is a subtree split of [fusonic/php-extensions](https://github.com/fusonic/php-extensions) repository. Please create your pull requests there.
