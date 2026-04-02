<?php

namespace Icmbio\ValidateXpathExpression;

class ExpressionPreparer
{
    public function __construct(
        protected FunctionRegistry $functionRegistry
    ) {
    }

    public function prepare(string $expression, mixed $value, array $context): string
    {
        $expression = $this->replaceCurrentValue($expression, $value);
        $expression = $this->replaceContextVariables($expression, $context);
        $expression = $this->replaceOperators($expression);

        return $this->replaceEqualsOperator($expression);
    }

    protected function replaceCurrentValue(string $expression, mixed $value): string
    {
        return preg_replace_callback(
            '/(?<![a-zA-Z0-9_\.])\.(?![a-zA-Z0-9_\.])/',
            fn() => var_export($value, true),
            $expression
        );
    }

    protected function replaceContextVariables(string $expression, array $context): string
    {
        return preg_replace_callback(
            '/\$\{([a-zA-Z0-9_]+)\}/',
            fn($matches) => var_export($context[$matches[1]] ?? null, true),
            $expression
        );
    }

    protected function replaceOperators(string $expression): string
    {
        $replacements = [
            'and' => '&&',
            'or' => '||',
            '!=' => '!=',
            'div' => '/',
            'mod' => '%',
        ];

        return preg_replace_callback(
            '/\b(and|or|div|mod)\b|!=/',
            fn($matches) => $replacements[$matches[0]] ?? $matches[0],
            $expression
        );
    }

    protected function replaceEqualsOperator(string $expression): string
    {
        return preg_replace('/(?<![<>!])=(?!=)/', '==', $expression);
    }
}
