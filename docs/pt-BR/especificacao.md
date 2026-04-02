# Especificação do dialeto de expressão

Este documento define o dialeto de expressão suportado por este repositório.

Ele é intencionalmente mais restrito do que XPath completo.

## Status

Esta é a especificação normativa, voltada para a implementação, do comportamento atual da biblioteca.

Se o código e este documento divergirem, ambos devem ser atualizados em conjunto.

## Modelo de avaliação

As expressões são avaliadas contra:

- um valor atual, referenciado por `.`
- um array opcional de contexto, referenciado por `${nome}`

A API pública pode retornar:

- um resultado booleano
- o valor bruto da expressão, quando solicitado explicitamente

## Literais suportados

### Números

Suportados:

- inteiros, como `10`
- números decimais, como `10.7`

Ainda não suportados:

- notação científica, como `1e3`

### Strings

Suportadas:

- strings com aspas simples
- strings com aspas duplas

### Palavras-chave

Suportadas:

- `true`
- `false`
- `null`

## Placeholders suportados

### Valor atual

`.` referencia o valor atual.

Exemplo:

```text
. >= 10
```

### Valores de contexto

`${nome}` referencia uma entrada nomeada do contexto.

Exemplo:

```text
. >= ${min}
```

## Operadores suportados

### Aritméticos

- `+`
- `-`
- `*`
- `div`
- `mod`

Normalização interna:

- `div` -> `/`
- `mod` -> `%`

### Comparação

- `=`
- `!=`
- `<`
- `<=`
- `>`
- `>=`

Normalização interna:

- `=` isolado -> `==`

### Booleanos

- `and`
- `or`

Normalização interna:

- `and` -> `&&`
- `or` -> `||`

### Unário

- menos unário, como em `-5`

## Agrupamento

Parênteses são suportados.

Exemplo:

```text
(. div 5) = 2
```

## Chamadas de função

Apenas funções registradas em `FunctionRegistry` são suportadas.

Nomes de função desconhecidos devem ser rejeitados.

### Funções nativas

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

### Validação de argumentos

- argumentos insuficientes devem ser rejeitados
- argumentos em excesso devem ser rejeitados
- a validação é baseada na aridade do construtor do handler

## Parsing e precedência

As expressões são avaliadas com a seguinte precedência, da menor para a maior:

1. `or`
2. `and`
3. operadores de comparação
4. operadores aditivos
5. operadores multiplicativos
6. menos unário
7. literais, agrupamentos e chamadas de função

## Sintaxe explicitamente não suportada

Os itens abaixo não fazem parte do dialeto atual e devem ser rejeitados:

- `..`
- `->`
- `::`
- `$nome`
- backticks
- `{` e `}`
- `? :`
- sintaxe de array como `[1, 2]`
- pontuação não suportada
- múltiplas expressões na mesma entrada

## Modelo de erro

Expressões malformadas ou não suportadas devem falhar com `RuntimeException`.

A rejeição faz parte do contrato da biblioteca.

## Modelo de segurança

O motor não deve avaliar código PHP arbitrário.

Apenas o dialeto suportado pela biblioteca pode ser interpretado.
