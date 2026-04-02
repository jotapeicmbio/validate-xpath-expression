<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class NumberFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        return floatval($this->value);
    }
}
