# Basilicom Thumbnail bundle for Pimcore

Enables asynchronous, worker based thumbnail creation.

## License

GPLv3 - see: gpl-3.0.txt


## Requirements

* Pimcore >= 5.4.0
* RabbitMQ (for ampq on PHP 7.3 see: https://github.com/pdezwart/php-amqp/issues/337)


## Installation

1) Install the bundle using composer `composer require basilicom/thumbnail-bundle`.
2) Execute `bin/console pimcore:bundle:enable BasilicomThumbnailBundle`.


## Configuration

0) As long as there is no patch preventing Pimcore from
  generating system thumbnails, change `vendor/pimcore/pimcore/models/Asset/Image.php`,
  in method `update` the line `$path = $this->getThumbnail(Image\Thumbnail\Config::getPreviewConfig())->getFileSystemPath();` to
  `$path = $this->getThumbnail(Image\Thumbnail\Config::getPreviewConfig())->getFileSystemPath(true);`
1) Disable low quality preview generation:
```yaml
    # app/config/config.yml - 'pimcore' section:
    assets:
        image:
            low_quality_image_preview:
                enabled: false
```
2) Configure the Symfony Messenger (see https://symfony.com/doc/current/messenger.html).
```yaml
    # app/config/local/messenger.yaml
    framework:
        messenger:
            transports:
                #sample format:
                #async: "%env(MESSENGER_TRANSPORT_DSN)%"
                #redis (seems not to work, problems with timeouts?):
                #async: "redis://redis:6379/messages"
                #doctrine does not work on pimcore, as only dbal is loaded, not doctrine!
                #async: "doctrine://default"
                #rabbitmq - works!:
                async: "amqp://rabbitmq:rabbitmq@rabbitmq:5672/%2f/messages"
            routing:
                # async is whatever name you gave your transport above
                'Basilicom\ThumbnailBundle\Message\ThumbnailJob':  async
            # The bus that is going to be injected when injecting MessageBusInterface
            default_bus: command.bus
            buses:
                command.bus:
                    middleware:
                        - validation
```
3) Process async messenges (jobs) via console (or supervisord, see: https://symfony.com/doc/current/messenger.html)
```
    bin/console messenger:consume
```

### Configure additional thumbnail formats

If there is a text property `thumbnailConfig` on the asset 
(possibly inherited from a parent folder) containing a
comma separated list of asset format names, thumbnails
for these formats are going to be generated, too.

### Configure thumbnail placeholders

@todo not implemented, yet

