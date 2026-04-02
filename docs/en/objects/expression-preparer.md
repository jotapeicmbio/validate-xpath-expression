# ExpressionPreparer

Source: [`src/ExpressionPreparer.php`](../../../src/ExpressionPreparer.php)

## Purpose

`ExpressionPreparer` converts the original user-facing syntax into the normalized syntax consumed by the parser.

## What it transforms

### Current value placeholder

`.` becomes the current value.

Example:

```text
. >= 10
```

May become:

```text
10 >= 10
```

### Context placeholders

`${name}` becomes the exported value from the provided context array.

Example:

```text
. >= ${min}
```

May become:

```text
10 >= 1
```

### Operators

It normalizes XPath-like operators into the internal form expected by the parser:

- `and` -> `&&`
- `or` -> `||`
- `div` -> `/`
- `mod` -> `%`
- `=` -> `==`

## What it does not do

- it does not tokenize
- it does not parse
- it does not evaluate
- it does not resolve function handlers

## Safety note

Preparation is intentionally limited. It normalizes a known expression dialect, but the actual safety boundary is enforced later by tokenization and parsing.
