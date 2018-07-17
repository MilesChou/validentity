<?php

namespace Validentity;

interface ValidentityInterface
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
     * @return string
     */
    public function generate();

    /**
     * @param mixed $id
     * @return string
     */
    public function normalize($id);
}
