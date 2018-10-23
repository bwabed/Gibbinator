<?php

class Builder
{
    private $config;

    public function __construct()
    {
        $this->config = array();
    }

    protected function addProperty($key, $value = null)
    {
        $this->config[$key] = $value;
    }

    protected function build()
    {
        throw new Exception('Build method is not implemented');
    }

    public function __set($key, $value)
    {
        if (!array_key_exists($key, $this->config)) {
            throw new Exception("This builder does not supports the key $key");
        }

        $this->addProperty($key, $value);
    }

    public function __get($key)
    {
        if (!array_key_exists($key, $this->config)) {
            throw new Exception("This builder does not supports the key $key");
        }

        return $this->config[$key];
    }

    public function __call($name, $args)
    {
        $this->$name = $args[0];

        return $this;
    }

    public function __toString()
    {
        return $this->build();
    }
}
