<?php

namespace Validentity;

class Taiwan implements GeneratorInterface, ValidatorInterface
{
    use GeneratorConcerns\TaiwanGenerator;

    const VALIDATE_LOCAL = 0b0001;

    const VALIDATE_FOREIGN = 0b0010;

    const VALIDATE_ALL = 0b0011;

    /**
     * @var int
     */
    private $validateFor;

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
     * @param int $type
     */
    public function __construct($type = self::VALIDATE_ALL)
    {
        $this->validateFor = $type;
    }

    public function check($id)
    {
        if (!$this->checkPattern($id)) {
            return false;
        }

        return $this->checkIdentity($id);
    }

    public function normalize($id)
    {
        if (is_object($id) && method_exists($id, '__toString')) {
            $id = $id->__toString();
        }

        if (!is_string($id)) {
            $type = gettype($id);
            throw new \InvalidArgumentException("Excepted string type, given is $type");
        }

        return strtoupper(trim($id));
    }

    /**
     * @param string $id
     * @return bool
     */
    private function checkPattern($id)
    {
        return preg_match($this->buildPattern(), $id);
    }

    /**
     * @return string
     */
    private function buildPattern()
    {
        switch ($this->validateFor) {
            case self::VALIDATE_LOCAL:
                return '/(^[A-Z][1-2]\d{8})$/';
            case self::VALIDATE_FOREIGN:
                return '/(^[A-Z][A-D]\d{8})$/';
            case self::VALIDATE_ALL:
            default:
                return '/(^[A-Z][A-D1-2]\d{8})$/';
        }
    }

    /**
     * @param string $id
     * @return bool
     */
    private function checkIdentity($id)
    {
        $checksum = $this->getChecksum($id);

        $idNumber = $this->transferIdentityToNumericString($id);

        $sum = $this->calculateSum($idNumber);

        return $this->checksum($sum, $checksum);
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
            ? '0'
            : (string)(10 - $sub);
    }

    /**
     * @param string $id
     * @return int
     */
    private function calculateSum($id)
    {
        $algorithm = $this->isLocal($id)
            ? $this->createLocalAlgorithm()
            : $this->createForeignAlgorithm();

        $splitId = str_split($id);

        return array_sum(
            array_map($algorithm, $splitId, array_keys($splitId))
        );
    }

    /**
     * @return \Closure
     */
    private function createForeignAlgorithm()
    {
        return function ($split, $index) {
            return ($split * static::$weights[$index]) % 10;
        };
    }

    /**
     * @return \Closure
     */
    private function createLocalAlgorithm()
    {
        return function ($split, $index) {
            return $split * static::$weights[$index];
        };
    }

    /**
     * @param string $id
     * @return string
     */
    private function getChecksum($id)
    {
        return $id[mb_strlen($id) - 1];
    }

    /**
     * @param string $id
     * @return bool
     */
    private function isLocal($id)
    {
        return in_array($id[1], ['1', '2'], true);
    }

    /**
     * @param string $id
     * @return string
     */
    private function transferIdentityToNumericString($id)
    {
        return $this->isLocal($id)
            ? static::$charMapping[$id[0]] . mb_substr($id, 1, 8)
            : static::$charMapping[$id[0]] . static::$charMapping[$id[1]][1] . mb_substr($id, 2, 7);
    }

    /**
     * @param int $type Should be const VALIDATE_LOCAL, VALIDATE_FOREIGN, VALIDATE_ALL
     * @return static
     */
    public function setValidateType($type)
    {
        if (!in_array($type, [
            static::VALIDATE_LOCAL,
            static::VALIDATE_FOREIGN,
            static::VALIDATE_ALL,
        ], true)) {
            $message = 'Excepted const VALIDATE_LOCAL, VALIDATE_FOREIGN, VALIDATE_ALL';
            throw new \InvalidArgumentException($message);
        }

        $this->validateFor = $type;

        return $this;
    }
}
