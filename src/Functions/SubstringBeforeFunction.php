<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class SubstringBeforeFunction implements XPathFunctionInterface
{
    public function __construct(
        protected string $value,
        protected string $separator
    ) {
    }

    public function handle(): mixed
    {
        $position = strpos($this->value, $this->separator);

        if ($position === false) {
            return '';
        }

        return substr($this->value, 0, $position);
    }
}
