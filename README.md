# Validate XPath Expression

[Português do Brasil](./README.pt-BR.md)

A small PHP library for validating and evaluating a focused XPath-like expression dialect against a value and an optional context array.

This project was inspired by the API style of the Python library [`znc-sistemas/xpath_validator`](https://github.com/znc-sistemas/xpath_validator). The PHP implementation in this repository is independent, but the public usage style follows the same idea of validating expressions against runtime values.

## Why this project exists

This repository isolates XPath-style validation behavior that was previously embedded in another project. The goal is to keep expression validation small, testable, reusable, and safe to evolve on its own.

## Features

- Static convenience API via `Xpath::validate(...)`
- Instance-based execution via `new Xpath(...)->execute()`
- Value placeholder support with `.`
- Context interpolation with `${name}`
- Built-in XPath-like functions mapped to PHP handlers
- Custom expression parser and evaluator
- No `eval()`
- Positive and rejection-oriented test coverage

## Installation

If the package is published to Packagist, you can install it with:

```bash
composer require icmbio/validate-xpath-expression
```

If you are consuming the repository directly before publication, add it as a VCS repository in your `composer.json` and require the package from there.

## Quick start

```php
<?php

use Icmbio\ValidateXpathExpression\Xpath;

$isValid = Xpath::validate('. >= ${min} and . <= ${max}', 10, [
    'min' => 1,
    'max' => 100,
]);

var_dump($isValid); // true
```

Returning the raw evaluated result instead of a boolean:

```php
<?php

use Icmbio\ValidateXpathExpression\Xpath;

$length = Xpath::validate('string-length(.)', 'abacate', [], false);

var_dump($length); // 7
```

## Supported expression model

This library intentionally supports a focused subset instead of full XPath.

Supported pieces include:

- Numbers and quoted strings
- `true`, `false`, and `null`
- Arithmetic: `+`, `-`, `*`, `div`, `mod`
- Comparison: `=`, `!=`, `<`, `<=`, `>`, `>=`
- Boolean operators: `and`, `or`
- Parentheses
- Function calls registered in `FunctionRegistry`

## Built-in functions

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

## Architecture overview

The library is intentionally split into small objects:

- [`Xpath`](./src/Xpath.php): public entry point and orchestrator
- [`ExpressionPreparer`](./src/ExpressionPreparer.php): normalizes placeholders and operators
- [`ExpressionTokenizer`](./src/ExpressionTokenizer.php): converts the prepared expression into tokens
- [`ExpressionEvaluator`](./src/ExpressionEvaluator.php): parses and evaluates tokens
- [`FunctionRegistry`](./src/FunctionRegistry.php): maps XPath-like function names to handler classes
- [`src/Functions`](./src/Functions): executable function handlers

## Documentation

The repository includes deeper documentation in two languages.

English:

- [Overview](./docs/en/overview.md)
- [Architecture](./docs/en/architecture.md)
- [Built-in Functions](./docs/en/functions.md)
- [Xpath object](./docs/en/objects/xpath.md)
- [FunctionRegistry object](./docs/en/objects/function-registry.md)
- [ExpressionPreparer object](./docs/en/objects/expression-preparer.md)
- [ExpressionTokenizer object](./docs/en/objects/expression-tokenizer.md)
- [ExpressionEvaluator object](./docs/en/objects/expression-evaluator.md)

Portuguese (Brazil):

- [Visão geral](./docs/pt-BR/visao-geral.md)
- [Arquitetura](./docs/pt-BR/arquitetura.md)
- [Funções nativas](./docs/pt-BR/funcoes.md)
- [Objeto Xpath](./docs/pt-BR/objetos/xpath.md)
- [Objeto FunctionRegistry](./docs/pt-BR/objetos/function-registry.md)
- [Objeto ExpressionPreparer](./docs/pt-BR/objetos/expression-preparer.md)
- [Objeto ExpressionTokenizer](./docs/pt-BR/objetos/expression-tokenizer.md)
- [Objeto ExpressionEvaluator](./docs/pt-BR/objetos/expression-evaluator.md)

These pages are also suitable as a base for a GitHub Wiki.

## Development

Run tests with:

```bash
vendor/bin/phpunit --testdox
```

## License

MIT
