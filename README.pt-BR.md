# Validate XPath Expression

[English](./README.md)

Uma pequena biblioteca PHP para validar e avaliar um dialeto enxuto de expressões inspiradas em XPath contra um valor e um array opcional de contexto.

Este projeto foi inspirado no estilo de API da biblioteca Python [`znc-sistemas/xpath_validator`](https://github.com/znc-sistemas/xpath_validator). A implementação PHP deste repositório é independente, mas o uso público segue a mesma ideia de validar expressões sobre valores de execução.

## Por que este projeto existe

Este repositório isola um comportamento de validação em estilo XPath que antes estava embutido em outro sistema. A ideia é manter a validação de expressões pequena, testável, reutilizável e segura para evoluir separadamente.

## Recursos

- API estática de conveniência via `Xpath::validate(...)`
- Execução orientada a instância via `new Xpath(...)->execute()`
- Suporte ao placeholder do valor atual com `.`
- Interpolação de contexto com `${nome}`
- Funções nativas em estilo XPath mapeadas para handlers PHP
- Parser e avaliador próprios
- Sem `eval()`
- Cobertura de testes positivos e de rejeição

## Instalação

Se o pacote estiver publicado no Packagist, você pode instalar com:

```bash
composer require icmbio/validate-xpath-expression
```

Se você estiver consumindo o repositório diretamente antes da publicação, adicione-o como repositório VCS no `composer.json` e exija o pacote a partir daí.

## Uso rápido

```php
<?php

use Icmbio\ValidateXpathExpression\Xpath;

$isValid = Xpath::validate('. >= ${min} and . <= ${max}', 10, [
    'min' => 1,
    'max' => 100,
]);

var_dump($isValid); // true
```

Retornando o resultado bruto da expressão em vez de um booleano:

```php
<?php

use Icmbio\ValidateXpathExpression\Xpath;

$length = Xpath::validate('string-length(.)', 'abacate', [], false);

var_dump($length); // 7
```

Se você preferir uma API em estilo função, mais próxima da ergonomia inspirada na versão em Python, o pacote também expõe helpers namespaced:

```php
<?php

use function Icmbio\ValidateXpathExpression\validate;

$isValid = validate('. >= ${min} and . <= ${max}', 10, [
    'min' => 1,
    'max' => 100,
]);
```

## Modelo de expressão suportado

Esta biblioteca suporta intencionalmente um subconjunto focado, e não XPath completo.

Entre os elementos suportados estão:

- Números e strings com aspas
- `true`, `false` e `null`
- Aritmética: `+`, `-`, `*`, `div`, `mod`
- Comparação: `=`, `!=`, `<`, `<=`, `>`, `>=`
- Operadores booleanos: `and`, `or`
- Parênteses
- Chamadas de função registradas em `FunctionRegistry`

## Funções nativas

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

## Visão geral da arquitetura

A biblioteca foi dividida em objetos pequenos e coesos:

- [`Xpath`](./src/Xpath.php): ponto de entrada público e orquestrador
- [`ExpressionPreparer`](./src/ExpressionPreparer.php): normaliza placeholders e operadores
- [`ExpressionTokenizer`](./src/ExpressionTokenizer.php): transforma a expressão preparada em tokens
- [`ExpressionEvaluator`](./src/ExpressionEvaluator.php): faz parsing e avaliação dos tokens
- [`FunctionRegistry`](./src/FunctionRegistry.php): mapeia nomes de funções para classes handler
- [`src/Functions`](./src/Functions): handlers executáveis das funções

## Documentação

O repositório inclui documentação mais profunda em dois idiomas.

English:

- [Overview](./docs/en/overview.md)
- [Architecture](./docs/en/architecture.md)
- [Built-in Functions](./docs/en/functions.md)
- [Xpath object](./docs/en/objects/xpath.md)
- [FunctionRegistry object](./docs/en/objects/function-registry.md)
- [ExpressionPreparer object](./docs/en/objects/expression-preparer.md)
- [ExpressionTokenizer object](./docs/en/objects/expression-tokenizer.md)
- [ExpressionEvaluator object](./docs/en/objects/expression-evaluator.md)

Português do Brasil:

- [Visão geral](./docs/pt-BR/visao-geral.md)
- [Arquitetura](./docs/pt-BR/arquitetura.md)
- [Funções nativas](./docs/pt-BR/funcoes.md)
- [Objeto Xpath](./docs/pt-BR/objetos/xpath.md)
- [Objeto FunctionRegistry](./docs/pt-BR/objetos/function-registry.md)
- [Objeto ExpressionPreparer](./docs/pt-BR/objetos/expression-preparer.md)
- [Objeto ExpressionTokenizer](./docs/pt-BR/objetos/expression-tokenizer.md)
- [Objeto ExpressionEvaluator](./docs/pt-BR/objetos/expression-evaluator.md)

Essas páginas também podem servir como base para a Wiki do GitHub.

## Desenvolvimento

Comandos úteis:

```bash
composer repl
composer test
composer test:coverage
composer stan
composer check
```

`composer test:coverage` exige um driver local de cobertura, como `pcov` ou `xdebug`.

## Guard rails locais

Este repositório inclui alguns guard rails para um desenvolvimento mais seguro:

- `phpstan` para análise estática
- PHPUnit para testes automatizados
- geração de cobertura quando `pcov` ou `xdebug` estiver disponível localmente
- hooks Git versionados em [`.githooks`](./.githooks)
- CI no GitHub Actions em [`.github/workflows/ci.yml`](./.github/workflows/ci.yml)

Para habilitar os hooks Git locais:

```bash
git config core.hooksPath .githooks
```

## REPL

O repositório também traz um REPL simples para testar expressões com o projeto já carregado:

```bash
composer repl
```

Dentro do REPL você pode chamar:

- `validate(...)`
- `escape_expression(...)`
- `Xpath::validate(...)`
- `new Xpath(...)->execute()`

Comandos especiais:

- `:help`
- `:clear`
- `:quit`

Se `readline` estiver disponível, o REPL também oferece histórico de comandos e autocomplete básico.

## Licença

MIT
