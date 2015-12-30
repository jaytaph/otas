<?php

namespace Otas\Engine;

/* Configuration loader class that returns a read-only configuration */

class Config implements \ArrayAccess {

    const MAIN_YAML_FILE = "main.yml";

    /**
     * @param array $config
     */
    protected function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Loads configuration from given directory
     *
     * @param $dir
     * @return Config
     */
    static function load($dir) {

        $config = Yaml::load($dir."/".self::MAIN_YAML_FILE);

        $config['base_directory'] = $dir;
        return new Config($config);
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException("Immutable configuration");
    }

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException("Immutable configuration");
    }
}

