<?php

namespace Validentity;

class Taiwan implements GeneratorInterface, ValidatorInterface
{
    use Concerns\Generators\TaiwanGenerator;
    use Concerns\Validators\TaiwanValidatorCommon;
    use Concerns\StringNormalizer;

    /**
     * @var TaiwanLocal
     */
    private $localChecker;

    /**
     * @var TaiwanForeign
     */
    private $foreignChecker;

    public function __construct()
    {
        $this->localChecker = new TaiwanLocal();
        $this->foreignChecker = new TaiwanForeign();
    }

    public function check($id)
    {
        return $this->localChecker->check($id) || $this->foreignChecker->check($id);
    }
}
