## [Symfony][symfony] for [Google App Engine][appengine]
This repository contains a Symfony app for Google App Engine, based off the Symfony Standard Edition.

## Installation

Install this via [composer][composer]. Follow the
[installation instructions][composer_install] if you do not already have
composer installed.

Once composer is installed, execute the following command to create this project:

```sh
composer create-project google/appengine-symfony-starter-project
```

## Set Up

To run the starter app, be sure to authorize gcloud for your project.

```
gcloud auth login
```

## Run Locally

```sh
composer run-script server
```

This builds the cache for the "dev" environment and runs the `dev_appserver.py`
script, which will be available if you've installed the
[Google App Engine Launcher][app_engine_launcher]. The command for this is
defined in `scripts/deploy.php`.

## Deployment

Deploy to your App Engine instance by running the following command:

```sh
composer run-script deploy
```

This builds the cache for the "prod" environment and runs `gcloud app deploy`,
which will be available if you've installed the
[Google Cloud SDK][gcloud]. The command for this is defined in
`scripts/deploy.php`.

> See also the [Symfony Hello World][gcp_symfony_hello] tutorial

## Using Twig

It should be noted this example application uses a subclass of `Twig_Environment`,
defined in `src/Twig/Environment.php` and configured in `app/config/services.yml`.
The reason for this subclass is to customize the `getOptionsHash` method. Without
this, the cache cannot be warmed up outside of the PHP version being used in App
Engine.

## Troubleshooting

1. If Composer fails to download the dependencies, make sure that your local PHP installation
satisfies Composer's [system requirements][composer_reqs]. Specifically, [cURL][curl] support is
required.

1. If you see errors about missing the default Cloud Storage bucket, follow the
[cloud integration instructions][gcs_setup] to create a default bucket for your project.

## Contributing
Have a patch that will benefit this project? Awesome! Follow these steps to have it accepted.

1. Please sign our [Contributor License Agreement](CONTRIBUTING.md).
1. Fork this Git repository and make your changes.
1. Create a Pull Request
1. Incorporate review feedback to your changes.
1. Accepted!

## License
All files in this repository are under the [MIT License](LICENSE) unless noted otherwise.

[symfony]: http://symfony.com/
[appengine]: https://cloud.google.com/appengine/
[app_engine_launcher]: https://cloud.google.com/appengine/docs/standard/php/download
[gcloud]: https://cloud.google.com/sdk/docs/
[composer]: https://getcomposer.org
[composer_install]: https://getcomposer.org/doc/00-intro.md
[gcs]: https://cloud.google.com/appengine/docs/php/googlestorage/setup
[gcp_symfony_hello]: https://cloud.google.com/appengine/docs/php/symfony-hello-world
[composer_reqs]: https://getcomposer.org/doc/00-intro.md#system-requirements
[curl]: http://php.net/manual/en/book.curl.php
[gcs_setup]: https://cloud.google.com/appengine/docs/php/googlestorage/setup
