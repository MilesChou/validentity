<?php

namespace MilesChou\IdentityCard;

class Taiwan
{
    /**
     * @var array
     */
    private static $locationMapping = [
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

        // Identity pattern
        if (!preg_match('/(^[A-Z]\d{9})$/', $id)) {
            return false;
        }

        /** @var string $idWithLocationNumber */
        $idWithLocationNumber = self::$locationMapping[$id[0]] . substr($id, 1);

        $checksum = (string)array_sum(array_map(function ($split, $weight) {
            return $split * $weight;
        }, str_split($idWithLocationNumber), self::$weights));

        return '0' === $checksum[strlen($checksum) - 1];
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
}
