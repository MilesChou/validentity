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
     * @return string
     */
    public function generate();

    /**
     * @param string $id
     * @return string
     */
    public function normalize($id);
}
