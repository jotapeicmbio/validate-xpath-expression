<?php

namespace Icmbio\ValidateXpathExpression;

/**
 * @phpstan-consistent-constructor
 */
class Xpath
{
    protected string $expression;
    protected mixed $value;
    /** @var array<string, mixed> */
    protected array $context;
    protected bool $returnsBool;

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $expression, mixed $value, array $context = [], bool $returnsBool = true)
    {
        $this->expression = $expression;
        $this->value = $value;
        $this->context = $context;
        $this->returnsBool = $returnsBool;
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function validate(string $expression, mixed $value, array $context = [], bool $returnsBool = true): mixed
    {
        return (new static(
            expression: $expression,
            value: $value,
            context: $context,
            returnsBool: $returnsBool
        ))->execute();
    }

    public static function escapeExpression(string $expr): string
    {
        return str_replace('${', '\${', $expr);
    }

    public function execute(): mixed
    {
        $preparer = new ExpressionPreparer($this->functionRegistry());
        $expr = $preparer->prepare($this->expression, $this->value, $this->context);
        $result = $this->expressionEvaluator()->evaluate($expr);

        return $this->returnsBool ? (bool) $result : $result;
    }

    protected function functionRegistry(): FunctionRegistry
    {
        return new FunctionRegistry();
    }

    protected function expressionEvaluator(): ExpressionEvaluator
    {
        return new ExpressionEvaluator(
            $this->functionRegistry(),
            new ExpressionTokenizer()
        );
    }
}
