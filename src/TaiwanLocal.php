<?php

namespace Validentity;

class TaiwanLocal implements ValidatorInterface
{
    use Concerns\Validators\TaiwanValidatorCommon;
    use Concerns\StringNormalizer;

    public function check($id)
    {
        // Check pattern
        if (!preg_match('/(^[A-Z][1-2]\d{8})$/', $id)) {
            return false;
        }

        // Checksum is the last numeric char
        $checksum = (int)$id[mb_strlen($id) - 1];

        // Transfer to a numeric string
        $numericString = static::$charMapping[$id[0]] . mb_substr($id, 1, 8);

        // Use the algorithm to calculate the sum
        $sum = $this->calculateSum($numericString);

        // Validate the sum and checksum
        return $this->generateChecksum($sum) === $checksum;
    }

    /**
     * The algorithm for calc the sum
     *
     * @param string $id
     * @return int
     */
    private function calculateSum($id)
    {
        $splitId = str_split($id);

        $calcArray = array_map(function ($split, $index) {
            return $split * static::$weights[$index];
        }, $splitId, array_keys($splitId));

        return array_sum($calcArray);
    }
}
