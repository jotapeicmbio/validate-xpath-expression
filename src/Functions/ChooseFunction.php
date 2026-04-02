<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class ChooseFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $condition,
        protected mixed $valueWhenTrue,
        protected mixed $valueWhenFalse
    ) {
    }

    public function handle(): mixed
    {
        return $this->condition ? $this->valueWhenTrue : $this->valueWhenFalse;
    }
}
