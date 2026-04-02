# Architecture

The library is intentionally organized as a small expression engine with clear object boundaries.

## High-level pipeline

```text
Xpath
  -> ExpressionPreparer
  -> ExpressionTokenizer
  -> ExpressionEvaluator
       -> FunctionRegistry
       -> Functions\*
```

## Responsibilities

### `Xpath`

Owns the user-facing API and orchestrates execution.

### `ExpressionPreparer`

Normalizes the original expression before parsing:

- replaces `.` with the current value
- replaces `${name}` with context values
- converts `and` to `&&`
- converts `or` to `||`
- converts `div` to `/`
- converts `mod` to `%`
- converts standalone `=` to `==`

### `ExpressionTokenizer`

Turns the normalized string into a token stream. This stage is responsible for lexical validation and rejects unsupported token patterns such as `->` and `..`.

### `ExpressionEvaluator`

Consumes tokens, parses the supported grammar, resolves function calls, checks constructor arity, and computes the final result.

### `FunctionRegistry`

Defines which function names are accepted and which handler classes implement them.

### `Functions`

Each built-in function lives in its own class and implements `XPathFunctionInterface`.

## Design notes

- The evaluator is not a general PHP evaluator.
- The registry is explicit, so function calls are limited to known handlers.
- Rejection tests are part of the design, not an afterthought.

## Object-level docs

- [Xpath](./objects/xpath.md)
- [FunctionRegistry](./objects/function-registry.md)
- [ExpressionPreparer](./objects/expression-preparer.md)
- [ExpressionTokenizer](./objects/expression-tokenizer.md)
- [ExpressionEvaluator](./objects/expression-evaluator.md)
