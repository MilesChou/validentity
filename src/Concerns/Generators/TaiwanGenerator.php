<?php

namespace Validentity\Concerns\Generators;

trait TaiwanGenerator
{
    public function generate()
    {
        $locationChar = array_rand(static::$charMapping);

        $genderChars = $this->buildGenderChars();
        $genderChar = $genderChars[array_rand($genderChars)];

        $fakeId = $locationChar . $genderChar . mt_rand(1000000, 9999999);

        $fakeNumericString = $this->transferCharToNumericString($fakeId);

        $sum = $this->calculateSum($fakeNumericString);

        return $fakeId . $this->generateChecksum($sum);
    }

    /**
     * @return array
     */
    public function buildGenderChars()
    {
        switch ($this->validateType) {
            case static::TYPE_LOCAL:
                return [
                    '1',
                    '2',
                ];
            case static::TYPE_FOREIGN:
                return [
                    'A',
                    'B',
                    'C',
                    'D',
                ];
            case static::TYPE_ALL:
            default:
                return [
                    '1',
                    '2',
                    'A',
                    'B',
                    'C',
                    'D',
                ];
        }
    }
}
