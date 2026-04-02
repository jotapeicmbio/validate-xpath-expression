# Xpath

Source: [`src/Xpath.php`](../../../src/Xpath.php)

## Purpose

`Xpath` is the public entry point of the library. It stores execution input and coordinates the full validation pipeline.

## Public methods

### `__construct(string $expression, mixed $value, array $context = [], bool $returnsBool = true)`

Stores the expression execution state inside the object.

### `validate(string $expression, mixed $value, array $context = [], bool $returnsBool = true)`

Static convenience method. It creates an instance and immediately executes the expression.

### `escapeExpression(string $expr): string`

Escapes `${` into `\${`.

This is useful when you need the literal text `${name}` to remain untouched instead of being interpreted as a context placeholder.

### `execute()`

Runs the full pipeline:

1. creates an `ExpressionPreparer`
2. prepares the expression
3. creates an `ExpressionEvaluator`
4. evaluates the prepared expression
5. returns either a boolean or the raw value

## Notes

- `Xpath` is intentionally small.
- It does not parse expressions directly.
- It does not know how functions work internally.
- It delegates those concerns to dedicated objects.
