<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class SubstringAfterFunction implements XPathFunctionInterface
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

        return substr($this->value, $position + strlen($this->separator));
    }
}
