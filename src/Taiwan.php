<?php

namespace Validentity;

class Taiwan
{
    /**
     * @var array
     */
    private static $charMapping = [
        'A' => '10',
        'B' => '11',
        'C' => '12',
        'D' => '13',
        'E' => '14',
        'F' => '15',
        'G' => '16',
        'H' => '17',
        'I' => '34',
        'J' => '18',
        'K' => '19',
        'L' => '20',
        'M' => '21',
        'N' => '22',
        'O' => '35',
        'P' => '23',
        'Q' => '24',
        'R' => '25',
        'S' => '26',
        'T' => '27',
        'U' => '28',
        'V' => '29',
        'W' => '32',
        'X' => '30',
        'Y' => '31',
        'Z' => '33',
    ];

    /**
     * @var array
     */
    private static $weights = [
        1,
        9,
        8,
        7,
        6,
        5,
        4,
        3,
        2,
        1,
        1,
    ];

    /**
     * @param string $id
     * @return bool
     */
    public function check($id)
    {
        $id = $this->normalize($id);

        // Local identity pattern
        if (preg_match('/(^[A-Z][1-2]\d{8})$/', $id)) {
            return $this->checksumForLocalIdentity($id);
        }

        // Foreign identity pattern
        if (preg_match('/(^[A-Z][A-D]\d{8})$/', $id)) {
            return $this->checksumForForeignIdentity($id);
        }

        return false;
    }

    /**
     * @param string $id
     * @return string
     */
    public function normalize($id)
    {
        if (!is_string($id)) {
            $type = gettype($id);
            throw new \InvalidArgumentException("Excepted string type, given is $type");
        }

        return strtoupper($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    private function checksumForForeignIdentity($id)
    {
        $checkNumber = (int)$id[strlen($id) - 1];

        $id = self::$charMapping[$id[0]] . self::$charMapping[$id[1]][1] . substr($id, 2, -1);

        $splitId = str_split($id);

        $checksum = array_sum(array_map(function ($split, $index) {
            return ($split * self::$weights[$index]) % 10;
        }, $splitId, array_keys($splitId)));

        $sub = $checksum % 10;

        if (0 === $sub) {
            return $sub === $checkNumber;
        }

        return 10 - $sub === $checkNumber;
    }

    /**
     * @param string $id
     * @return bool
     */
    private function checksumForLocalIdentity($id)
    {
        $id = self::$charMapping[$id[0]] . substr($id, 1);

        $checksum = $this->generateChecksum($id);

        return '0' === $checksum[strlen($checksum) - 1];
    }

    private function generateChecksum($id)
    {
        return (string)array_sum(array_map(function ($split, $weight) {
            return $split * $weight;
        }, str_split($id), self::$weights));
    }
}
