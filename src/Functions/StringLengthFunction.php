<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class StringLengthFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        return mb_strlen((string) $this->value);
    }
}
