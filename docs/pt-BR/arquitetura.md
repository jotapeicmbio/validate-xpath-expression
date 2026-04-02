# Arquitetura

A biblioteca foi organizada intencionalmente como um pequeno motor de expressões com limites claros entre objetos.

## Pipeline de alto nível

```text
Xpath
  -> ExpressionPreparer
  -> ExpressionTokenizer
  -> ExpressionEvaluator
       -> FunctionRegistry
       -> Functions\*
```

## Responsabilidades

### `Xpath`

Responsável pela API pública e pela orquestração da execução.

### `ExpressionPreparer`

Normaliza a expressão original antes do parse:

- substitui `.` pelo valor atual
- substitui `${nome}` pelos valores do contexto
- converte `and` para `&&`
- converte `or` para `||`
- converte `div` para `/`
- converte `mod` para `%`
- converte `=` isolado para `==`

### `ExpressionTokenizer`

Transforma a string normalizada em um fluxo de tokens. Essa etapa faz a validação léxica e rejeita padrões não suportados como `->` e `..`.

### `ExpressionEvaluator`

Consome tokens, faz o parse da gramática suportada, resolve chamadas de função, valida aridade do construtor e calcula o resultado final.

### `FunctionRegistry`

Define quais nomes de função são aceitos e quais classes os implementam.

### `Functions`

Cada função nativa vive em sua própria classe e implementa `XPathFunctionInterface`.

## Observações de design

- o avaliador não é um avaliador genérico de PHP
- o registry é explícito, então chamadas ficam limitadas aos handlers conhecidos
- testes de rejeição fazem parte do desenho da solução

## Documentação por objeto

- [Xpath](./objetos/xpath.md)
- [FunctionRegistry](./objetos/function-registry.md)
- [ExpressionPreparer](./objetos/expression-preparer.md)
- [ExpressionTokenizer](./objetos/expression-tokenizer.md)
- [ExpressionEvaluator](./objetos/expression-evaluator.md)
