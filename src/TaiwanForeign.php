<?php

namespace Validentity;

class TaiwanForeign implements ValidatorInterface
{
    use Concerns\Validators\TaiwanValidatorCommon;
    use Concerns\StringNormalizer;

    public function check($id)
    {
        // Check pattern
        if (!preg_match('/(^[A-Z][A-D]\d{8})$/', $id)) {
            return false;
        }

        // Checksum is the last numeric char
        $checksum = $this->getChecksum($id);

        // Transfer to a numeric string
        $numericString = static::$charMapping[$id[0]] . static::$charMapping[$id[1]][1] . mb_substr($id, 2, 7);

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
            return ($split * static::$weights[$index]) % 10;
        }, $splitId, array_keys($splitId));

        return array_sum($calcArray);
    }
}
