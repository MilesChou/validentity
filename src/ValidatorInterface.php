<?php

namespace Validentity;

interface ValidatorInterface
{
    /**
     * @param string $id
     * @return bool
     */
    public function check($id);

    /**
     * @param string $id
     * @return bool
     */
    public function checkWithNormalize($id);

    /**
     * @param mixed $id
     * @return string
     */
    public function normalize($id);
}
