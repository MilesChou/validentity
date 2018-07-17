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
     * @param string $id
     * @return string
     */
    private function transferCharToNumericString($id)
    {
        return $this->isLocalIdentity($id)
            ? static::$charMapping[$id[0]] . mb_substr($id, 1, 8)
            : static::$charMapping[$id[0]] . static::$charMapping[$id[1]][1] . mb_substr($id, 2, 7);
    }

    /**
     * @param string $id
     * @return string
     */
    private function calculateSum($id)
    {
        return $this->isLocalIdentity($id)
            ? $this->localChecker->calculateSum($id)
            : $this->foreignChecker->calculateSum($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    private function isLocalIdentity($id)
    {
        return in_array($id[1], ['1', '2'], true);
    }

    /**
     * @return array
     */
    public function buildGenderChars()
    {
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
