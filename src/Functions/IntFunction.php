<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class IntFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        return intval($this->value);
    }
}
