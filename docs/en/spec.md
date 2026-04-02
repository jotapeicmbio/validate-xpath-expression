# Expression Dialect Specification

This document defines the supported expression dialect for this repository.

It is intentionally narrower than full XPath.

## Status

This is the normative implementation-facing specification for the current library behavior.

If code and this document diverge, both should be updated together.

## Evaluation model

Expressions are evaluated against:

- a current value, referenced by `.`
- an optional context array, referenced by `${name}`

The public API may return:

- a boolean result
- the raw evaluated value, when explicitly requested

## Supported literals

### Numbers

Supported:

- integers, such as `10`
- decimal numbers, such as `10.7`

Not currently supported:

- scientific notation, such as `1e3`

### Strings

Supported:

- single-quoted strings
- double-quoted strings

### Keywords

Supported:

- `true`
- `false`
- `null`

## Supported placeholders

### Current value

`.` references the current value.

Example:

```text
. >= 10
```

### Context values

`${name}` references a named context entry.

Example:

```text
. >= ${min}
```

## Supported operators

### Arithmetic

- `+`
- `-`
- `*`
- `div`
- `mod`

Internal normalization:

- `div` -> `/`
- `mod` -> `%`

### Comparison

- `=`
- `!=`
- `<`
- `<=`
- `>`
- `>=`

Internal normalization:

- standalone `=` -> `==`

### Boolean

- `and`
- `or`

Internal normalization:

- `and` -> `&&`
- `or` -> `||`

### Unary

- unary minus, such as `-5`

## Grouping

Parentheses are supported.

Example:

```text
(. div 5) = 2
```

## Function calls

Only functions registered in `FunctionRegistry` are supported.

Unknown function names must be rejected.

### Built-in functions

- `selected`
- `string-length`
- `string_length`
- `int`
- `floor`
- `ceiling`
- `number`
- `string`
- `contains`
- `starts-with`
- `normalize-space`
- `choose`
- `not`
- `true`
- `false`
- `uuid`
- `format-date-time`
- `substring-after`
- `substring-before`

### Function argument validation

- too few arguments must be rejected
- too many arguments must be rejected
- validation is based on handler constructor arity

## Parsing and precedence

Expressions are parsed with the following precedence, from lowest to highest:

1. `or`
2. `and`
3. comparison operators
4. additive operators
5. multiplicative operators
6. unary minus
7. literals, grouped expressions, and function calls

## Explicitly unsupported syntax

The following syntax is currently unsupported and should be rejected:

- `..`
- `->`
- `::`
- `$name`
- backticks
- `{` and `}`
- `? :`
- array syntax such as `[1, 2]`
- unsupported punctuation
- multiple expressions in a single input

## Error model

Malformed or unsupported expressions should fail with a `RuntimeException`.

Rejection is part of the contract.

## Security model

The engine must not evaluate arbitrary PHP code.

Only the supported expression dialect may be interpreted.
