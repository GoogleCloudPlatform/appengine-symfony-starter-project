<?php
// Copyright 2015 Google Inc. All Rights Reserved.
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use AppEngine\Environment;

class AppKernel extends Kernel
{
    private $gcsBucketName;

    public function __construct($environment = null, $debug = null)
    {
        // determine the environment / debug configuration based on whether or not this is running
        // in App Engine's Dev App Server, or in production
        if (is_null($debug)) {
            $debug = Environment::onDevAppServer();
        }

        if (is_null($environment)) {
            $environment = $debug ? 'prod' : 'dev';
        }

        parent::__construct($environment, $debug);

        // Symfony console requires timezone to be set manually.
        if (!ini_get('date.timezone')) {
          date_default_timezone_set('UTC');
        }

        // Enable optimistic caching for GCS.
        $options = ['gs' => ['enable_optimsitic_cache' => true]];
        stream_context_set_default($options);

        $this->gcsBucketName = getenv('GCS_BUCKET_NAME');
    }

    public function getCacheDir()
    {
        if ($this->gcsBucketName) {
            return sprintf('gs://%s/symfony/cache', $this->gcsBucketName);
        }

        return parent::getCacheDir();
    }

    public function getLogDir()
    {
        if ($this->gcsBucketName) {
            return sprintf('gs://%s/symfony/log', $this->gcsBucketName);
        }

        return parent::getLogDir();
    }

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppEngine\HelloWorldBundle\AppEngineHelloWorldBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
