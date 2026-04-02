<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class CeilingFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        return ceil($this->value);
    }
}
