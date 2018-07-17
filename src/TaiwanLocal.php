<?php

namespace Validentity;

class TaiwanLocal implements ValidatorInterface
{
    use Concerns\StringNormalizer;
    use ValidatorConcerns\TaiwanValidatorCommon;

    public function check($id)
    {
        if (!preg_match('/(^[A-Z][1-2]\d{8})$/', $id)) {
            return false;
        }

        return $this->checkIdentity($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    private function checkIdentity($id)
    {
        // Checksum is the last numeric char
        $checksum = (int)$id[mb_strlen($id) - 1];

        // Transfer to a numeric string
        $numericString = $this->transferCharToNumericString($id);

        // Use the algorithm to calculate the sum
        $sum = $this->calculateSum($numericString);

        // Validate the sum and checksum
        return $this->checksum($sum, $checksum);
    }

    /**
     * @param string $id
     * @return string
     */
    private function transferCharToNumericString($id)
    {
        return static::$charMapping[$id[0]] . mb_substr($id, 1, 8);
    }

    /**
     * @param string $id
     * @return int
     */
    private function calculateSum($id)
    {
        $splitId = str_split($id);

        return array_sum(
            array_map($this->createAlgorithm($id), $splitId, array_keys($splitId))
        );
    }

    /**
     * @param string $id
     * @return \Closure
     */
    private function createAlgorithm($id)
    {
        // The local identity algorithm for calc the sum
        return function ($split, $index) {
            return $split * static::$weights[$index];
        };
    }

    /**
     * @param int $sum
     * @param int $checksum
     * @return bool
     */
    private function checksum($sum, $checksum)
    {
        return $this->generateChecksum($sum) === $checksum;
    }

    /**
     * @param int $sum
     * @return string
     */
    private function generateChecksum($sum)
    {
        $sub = $sum % 10;

        return 0 === $sub
            ? 0
            : (10 - $sub);
    }
}
