<?php

namespace Ponticlaro\Bebop\Common;

use Ponticlaro\Bebop\Common\Collection;

class FeatureManager extends \Ponticlaro\Bebop\Common\Patterns\SingletonAbstract {

    protected $features;

    protected function __construct()
    {
        $this->features = new Collection;
    }

    public function add($id, array $config = array())
    {
        if (!is_string($id))
            throw new \Exception("Feature ID must be a string");
            
        $this->features->set($id, new Feature($id, $config));

        return $this->get($id);
    }

    public function &get($id)
    {
        return $this->features->get($id);
    } 

    public function exists($id)
    {
        return $this->features->hasKey($id);
    }
}