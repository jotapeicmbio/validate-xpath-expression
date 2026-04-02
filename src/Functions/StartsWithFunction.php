<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class StartsWithFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value,
        protected mixed $prefix
    ) {
    }

    public function handle(): mixed
    {
        return str_starts_with((string) $this->value, (string) $this->prefix);
    }
}
