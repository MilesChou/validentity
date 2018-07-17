<?php

namespace Validentity\Concerns;

trait StringNormalizer
{
    public function normalize($id)
    {
        if (is_object($id) && method_exists($id, '__toString')) {
            $id = $id->__toString();
        }

        if (!is_string($id)) {
            $type = gettype($id);
            throw new \InvalidArgumentException("Excepted string type, given is $type");
        }

        return strtoupper(trim($id));
    }
}
