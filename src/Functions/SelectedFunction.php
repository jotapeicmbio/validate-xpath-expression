<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class SelectedFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $set,
        protected mixed $value
    ) {
    }

    public function handle(): mixed
    {
        if ($this->set === null || $this->value === null) {
            return false;
        }

        $tokens = preg_split('/\s+/', trim((string) $this->set));

        return in_array((string) $this->value, $tokens, true);
    }
}
