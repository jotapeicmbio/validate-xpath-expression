<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class FloorFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        return floor($this->value);
    }
}
