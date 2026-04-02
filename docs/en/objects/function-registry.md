# FunctionRegistry

Source: [`src/FunctionRegistry.php`](../../../src/FunctionRegistry.php)

## Purpose

`FunctionRegistry` is the allowlist of supported XPath-like functions.

It is the object that answers:

- which function names are valid
- which handler class implements each function

## Public methods

### `all(): array`

Returns the full function map.

### `resolve(string $name): string`

Returns the handler class for a function name.

If the function is not registered, it throws a `RuntimeException`.

## Why it matters

Without a registry, function resolution tends to become implicit and harder to audit. This object keeps the supported surface area explicit.

## Current role in the pipeline

`ExpressionEvaluator` calls `resolve()` when it finds a function call during parsing.
