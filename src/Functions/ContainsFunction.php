<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class ContainsFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value,
        protected mixed $substring
    ) {
    }

    public function handle(): mixed
    {
        return mb_strpos((string) $this->value, (string) $this->substring) !== false;
    }
}
