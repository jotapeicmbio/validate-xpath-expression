# ExpressionEvaluator

Source: [`src/ExpressionEvaluator.php`](../../../src/ExpressionEvaluator.php)

## Purpose

`ExpressionEvaluator` parses and evaluates the token stream produced by `ExpressionTokenizer`.

## Grammar shape

The evaluator currently handles:

- boolean `or`
- boolean `and`
- comparisons
- additive expressions
- multiplicative expressions
- unary minus
- grouped expressions
- literals
- function calls

## Parsing strategy

The parser is implemented as a recursive descent evaluator with operator precedence encoded in the method order:

1. `parseOrExpression`
2. `parseAndExpression`
3. `parseComparisonExpression`
4. `parseAdditiveExpression`
5. `parseMultiplicativeExpression`
6. `parseUnaryExpression`
7. `parsePrimaryExpression`

## Function execution

When the evaluator finds a function call:

1. it resolves the handler through `FunctionRegistry`
2. it validates constructor arity through reflection
3. it instantiates the handler
4. it calls `handle()`

## Error model

The evaluator wraps parsing and execution failures in `RuntimeException`, preserving the original expression in the error message.

## Why this replaced `eval()`

The current evaluator only understands the supported expression dialect. It does not execute arbitrary PHP code, which makes the validation surface narrower and easier to reason about.
