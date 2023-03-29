# Upgrade 1.x to 2.0

## Middleware

The `AttachmentEmailEventSubscriber` has been replaced by a messenger middleware. To register the middleware, update
your `messenger.yaml` like this:

```yaml
# Your messenger configuration
framework:
    # ...
    messenger:
        # ...
        buses:
            default: # or your own bus that handled the SendEmailMessage event
                - Fusonic\MessengerMailerBundle\Middleware\AttachmentEmailMiddleware

```

## AttachmentEmailInterface

The `Fusonic\MessengerMailerBundle\Contracts\AttachmentEmailInterface` signature has changed. A `readAttachment` method
has been added and the incorrect
`Fusonic\MessengerMailerBundle\Mime\AttachmentEmail` type in the signatures has been changed
to `Fusonic\MessengerMailerBundle\Contracts\AttachmentEmailInterface`.

