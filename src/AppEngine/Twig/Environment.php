<?php

namespace AppEngine\Twig;

use Twig_Environment;

/**
*
*/
class Environment extends Twig_Environment
{
    /**
     * Gets the template class associated with the given string.
     *
     * The generated template class is based on the following parameters:
     *
     *  * The cache key for the given template;
     *  * The currently enabled extensions;
     *  * Whether the Twig C extension is available or not;
     *  * PHP version;
     *  * Twig version;
     *  * Options with what environment was created.
     *
     * @param string   $name  The name for which to calculate the template class name
     * @param int|null $index The index if it is an embedded template
     *
     * @return string The template class name
     */
    public function getTemplateClass($name, $index = null)
    {
        $key = $this->getLoader()->getCacheKey($name).$this->getOptionsHash();

        return $this->templateClassPrefix.hash('sha256', $key).(null === $index ? '' : '_'.$index);
    }

    /**
     * Uses a twig subclass to avoid cache differences between PHP versions.
     * This is required in order to prebuild the cache for App Engine.
     */
    private function getOptionsHash()
    {
        $hashParts = array_merge(
            array_keys($this->extensions),
            array(
                (int) function_exists('twig_template_get_attributes'),
                self::VERSION,
                (int) $this->debug,
                $this->baseTemplateClass,
                (int) $this->strictVariables,
            )
        );
        return implode(':', $hashParts);
    }
}