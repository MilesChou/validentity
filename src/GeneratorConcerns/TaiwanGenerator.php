<?php

namespace Validentity\GeneratorConcerns;

trait TaiwanGenerator
{
    public function generate()
    {
        $locationChar = array_rand(static::$charMapping);

        $genderChars = $this->buildGenderChars();
        $genderChar = $genderChars[array_rand($genderChars)];

        $fakeId = $locationChar . $genderChar . mt_rand(1000000, 9999999);

        $fakeIdNumber = $this->transferCharToNumericString($fakeId);

        $sum = $this->calculateSum($fakeIdNumber);

        return $fakeId . $this->generateChecksum($sum);
    }

    /**
     * @return array
     */
    public function buildGenderChars()
    {
        switch ($this->validateFor) {
            case self::VALIDATE_LOCAL:
                return [
                    '1',
                    '2',
                ];
            case self::VALIDATE_FOREIGN:
                return [
                    'A',
                    'B',
                    'C',
                    'D',
                ];
            case self::VALIDATE_ALL:
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
