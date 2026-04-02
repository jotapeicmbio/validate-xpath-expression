# ExpressionTokenizer

Source: [`src/ExpressionTokenizer.php`](../../../src/ExpressionTokenizer.php)

## Purpose

`ExpressionTokenizer` performs lexical analysis.

It reads a normalized expression string and emits tokens such as:

- numbers
- strings
- identifiers
- operators
- symbols

## Responsibilities

- split the input into a token stream
- decode quoted string literals
- reject unsupported lexical patterns

## Important rejection behavior

The tokenizer rejects inputs containing unsupported tokens such as:

- `->`
- `..`
- backticks
- raw `$`
- unterminated strings
- unsupported punctuation

## Supported token categories

### Numbers

Examples:

- `10`
- `10.7`

### Strings

Examples:

- `'abacate'`
- `"abc"`

### Identifiers

Examples:

- `floor`
- `string-length`
- `format-date-time`

### Operators and symbols

Examples:

- `&&`
- `||`
- `==`
- `(`
- `)`
- `,`

## Relationship with the evaluator

The tokenizer does not try to understand grammar structure. It only produces validated tokens. `ExpressionEvaluator` is responsible for parsing those tokens.
