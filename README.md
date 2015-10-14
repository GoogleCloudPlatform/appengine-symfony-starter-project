## [Symfony](http://symfony.com/) for [Google App Engine](https://cloud.google.com/appengine/)
This repository contains a Symfony app for Google App Engine, based off the Symfony Standard Edition.

## Installation

Follow the [Symfony Hello World](https://cloud.google.com/appengine/docs/php/symfony-hello-world) tutorial
for instructions on how to run this app.

## Troubleshooting

1. If Composer fails to download the dependencies, make sure that your local PHP installation satisfies Composer's [system requirements](https://getcomposer.org/doc/00-intro.md#system-requirements). Specifically, [cURL](http://php.net/manual/en/book.curl.php) support is required.

1. If you see errors about missing the default Cloud Storage bucket, follow the [cloud integration instructions](https://cloud.google.com/appengine/docs/php/googlestorage/setup) to create a default bucket for your project.

## Contributing
Have a patch that will benefit this project? Awesome! Follow these steps to have it accepted.

1. Please sign our [Contributor License Agreement](CONTRIB.md).
1. Fork this Git repository and make your changes.
1. Create a Pull Request
1. Incorporate review feedback to your changes.
1. Accepted!

## License
All files in this repository are under the [MIT License](LICENSE) unless noted otherwise.
