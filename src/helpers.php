<?php

namespace Icmbio\ValidateXpathExpression;

/**
 * @param array<string, mixed> $context
 */
function validate(string $expression, mixed $value, array $context = [], bool $returnsBool = true): mixed
{
    return Xpath::validate(
        expression: $expression,
        value: $value,
        context: $context,
        returnsBool: $returnsBool
    );
}

function escape_expression(string $expression): string
{
    return Xpath::escapeExpression($expression);
}
