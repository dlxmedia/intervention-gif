<?php

namespace Intervention\Gif\Traits;

use Exception;
use Intervention\Gif\Encoder\AbstractEncoder;

trait CanEncode
{
    /**
     * Encode current entity
     *
     * @return string
     */
    public function encode(): string
    {
        return $this->getEncoder()->encode();
    }

    /**
     * Get encoder object for current entity
     *
     * @return AbstractEncoder
     */
    protected function getEncoder(): AbstractEncoder
    {
        $classname = $this->getEncoderClassname();

        if (!class_exists($classname)) {
            throw new Exception("Encoder for '".get_class($this)."' not found.");
        }

        return new $classname($this);
    }

    /**
     * Get encoder classname for current entity
     *
     * @return string
     */
    protected function getEncoderClassname(): string
    {
        return sprintf('Intervention\Gif\Encoder\%sEncoder', $this->getShortClassname());
    }
}