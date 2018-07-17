<?php

namespace Validentity;

class Taiwan implements GeneratorInterface, ValidatorInterface
{
    use Concerns\Generators\TaiwanGenerator;
    use Concerns\Validators\TaiwanValidatorCommon;
    use Concerns\StringNormalizer;

    const TYPE_LOCAL = 0b0001;

    const TYPE_FOREIGN = 0b0010;

    const TYPE_ALL = 0b0011;

    /**
     * @var int
     */
    private $validateType;

    /**
     * @param int $type
     */
    public function __construct($type = self::TYPE_ALL)
    {
        $this->setValidateType($type);
    }

    public function check($id)
    {
        if (!$this->checkPattern($id)) {
            return false;
        }

        return $this->checkIdentity($id);
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
        switch ($this->validateType) {
            case static::TYPE_LOCAL:
                return '/(^[A-Z][1-2]\d{8})$/';
            case static::TYPE_FOREIGN:
                return '/(^[A-Z][A-D]\d{8})$/';
            case static::TYPE_ALL:
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
        return $this->isLocalIdentity($id)
            ? static::$charMapping[$id[0]] . mb_substr($id, 1, 8)
            : static::$charMapping[$id[0]] . static::$charMapping[$id[1]][1] . mb_substr($id, 2, 7);
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
        if ($this->isLocalIdentity($id)) {
            // The local identity algorithm for calc the sum
            return function ($split, $index) {
                return $split * static::$weights[$index];
            };
        }

        // The foreign identity algorithm for calc the sum
        return function ($split, $index) {
            return ($split * static::$weights[$index]) % 10;
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

    /**
     * @param int $type Should be const VALIDATE_LOCAL, VALIDATE_FOREIGN, VALIDATE_ALL
     * @return static
     */
    public function setValidateType($type)
    {
        if (!in_array($type, [
            static::TYPE_LOCAL,
            static::TYPE_FOREIGN,
            static::TYPE_ALL,
        ], true)) {
            $message = 'Excepted const VALIDATE_LOCAL, VALIDATE_FOREIGN, VALIDATE_ALL';
            throw new \InvalidArgumentException($message);
        }

        $this->validateType = $type;

        return $this;
    }
}
