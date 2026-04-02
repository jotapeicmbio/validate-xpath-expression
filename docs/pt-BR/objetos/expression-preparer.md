# ExpressionPreparer

Fonte: [`src/ExpressionPreparer.php`](../../../src/ExpressionPreparer.php)

## Finalidade

`ExpressionPreparer` converte a sintaxe original exposta ao usuário para a sintaxe normalizada consumida pelo parser.

## O que ele transforma

### Placeholder do valor atual

`.` se torna o valor atual.

Exemplo:

```text
. >= 10
```

Pode se tornar:

```text
10 >= 10
```

### Placeholders de contexto

`${nome}` se torna o valor exportado do array de contexto.

Exemplo:

```text
. >= ${min}
```

Pode se tornar:

```text
10 >= 1
```

### Operadores

Ele normaliza operadores em estilo XPath para a forma interna esperada pelo parser:

- `and` -> `&&`
- `or` -> `||`
- `div` -> `/`
- `mod` -> `%`
- `=` -> `==`

## O que ele não faz

- não tokeniza
- não faz parse
- não avalia
- não resolve handlers de função

## Observação de segurança

A preparação é propositalmente limitada. Ela normaliza um dialeto conhecido, mas a fronteira real de segurança é imposta depois, na tokenização e no parse.
