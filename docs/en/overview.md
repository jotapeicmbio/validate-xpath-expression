# Overview

`validate-xpath-expression` is a focused PHP library for validating XPath-like expressions against a value and an optional context.

It is designed for projects that need lightweight validation rules such as:

- numeric ranges
- conditional checks
- string manipulation
- date formatting checks
- multi-value membership checks

The library does not aim to implement full XPath. Instead, it supports a constrained dialect that is easier to reason about, test, and secure.

## Main goals

- keep the public API small
- isolate expression validation from application code
- support common XPath-inspired validation rules
- avoid unsafe PHP code execution
- keep behavior covered by both positive and rejection-oriented tests

## Public API

The main public entry point is `Icmbio\ValidateXpathExpression\Xpath`.

Typical usage:

```php
use Icmbio\ValidateXpathExpression\Xpath;

$result = Xpath::validate('. >= ${min} and . <= ${max}', 10, [
    'min' => 1,
    'max' => 100,
]);
```

## Processing flow

1. `Xpath` receives the expression, current value, context, and output mode.
2. `ExpressionPreparer` replaces `.` and `${name}` placeholders and normalizes operators.
3. `ExpressionTokenizer` converts the normalized expression into tokens.
4. `ExpressionEvaluator` parses the token stream and evaluates the expression.
5. `FunctionRegistry` resolves built-in function names to handler classes.

## Related reading

- [Architecture](./architecture.md)
- [Built-in Functions](./functions.md)
- [Xpath object](./objects/xpath.md)
