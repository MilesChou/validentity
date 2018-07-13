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
     * @return string
     */
    public function normalize($id);
}
