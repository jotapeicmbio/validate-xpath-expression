<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class NotFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        return !$this->value;
    }
}
