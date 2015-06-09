<?php

namespace Ponticlaro\Bebop\Common\Patterns\Exceptions;

class NotManufacturableException extends \RuntimeException
{

    private $type;

    function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

}
