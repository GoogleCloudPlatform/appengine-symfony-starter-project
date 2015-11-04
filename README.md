## [Symfony](http://symfony.com/) for [Google App Engine](https://cloud.google.com/appengine/)
This repository contains a Symfony app for Google App Engine, based off the Symfony Standard Edition.

## Installation

Install this via [composer](https://getcomposer.org). Follow the
[installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have
composer installed.

Once composer is installed, execute the following command to create this project:

```sh
composer create-project google/appengine-symfony-starter-project
```

## Set Up

To run the starter app, complete the following steps:

 1. create a [cloud storage bucket][0] for your App Engine project.
 2. open `app.yaml` and change `YOUR_GCS_BUCKET_NAME` to the name of the bucket you created.
 3. open `php.ini` and change `YOUR_GCS_BUCKET_NAME` to the name of the bucket you created.

## Deployment

Deploy to your AppEngine instance:

```sh
gcloud preview app deploy app.yaml --promote
```

You can also run this locally using the app-engine

> See also the [Symfony Hello World](https://cloud.google.com/appengine/docs/php/symfony-hello-world) tutorial

## Troubleshooting

1. If Composer fails to download the dependencies, make sure that your local PHP installation satisfies Composer's [system requirements](https://getcomposer.org/doc/00-intro.md#system-requirements). Specifically, [cURL](http://php.net/manual/en/book.curl.php) support is required.

1. If you see errors about missing the default Cloud Storage bucket, follow the [cloud integration instructions](https://cloud.google.com/appengine/docs/php/googlestorage/setup) to create a default bucket for your project.

1. It may be useful to **clear the cache**. You can do this by issuing a **GET** request to `https://YOUR_PROJECT_NAME.appspot.com/clear_cache.php`. You will be promoted for authentication, and then the script will delete symfony's cache from your Cloud Storage Bucket.

## Contributing
Have a patch that will benefit this project? Awesome! Follow these steps to have it accepted.

1. Please sign our [Contributor License Agreement](CONTRIB.md).
1. Fork this Git repository and make your changes.
1. Create a Pull Request
1. Incorporate review feedback to your changes.
1. Accepted!

## License
All files in this repository are under the [MIT License](LICENSE) unless noted otherwise.

[0]: https://cloud.google.com/appengine/docs/php/googlestorage/setup