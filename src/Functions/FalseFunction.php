<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class FalseFunction implements XPathFunctionInterface
{
    public function handle(): mixed
    {
        return false;
    }
}
