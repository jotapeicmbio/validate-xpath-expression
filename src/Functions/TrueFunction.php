<?php

namespace Icmbio\ValidateXpathExpression\Functions;

class TrueFunction implements XPathFunctionInterface
{
    public function handle(): mixed
    {
        return true;
    }
}
