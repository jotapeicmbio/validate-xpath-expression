<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class NormalizeSpaceFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        return preg_replace('/\s+/', ' ', trim((string) $this->value));
    }
}
