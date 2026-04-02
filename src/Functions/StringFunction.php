<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class StringFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        return strval($this->value);
    }
}
