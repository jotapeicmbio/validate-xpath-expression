# FunctionRegistry

Fonte: [`src/FunctionRegistry.php`](../../../src/FunctionRegistry.php)

## Finalidade

`FunctionRegistry` é a allowlist das funções suportadas pela biblioteca.

É o objeto que responde:

- quais nomes de função são válidos
- qual classe handler implementa cada função

## Métodos públicos

### `all(): array`

Retorna o mapa completo de funções.

### `resolve(string $name): string`

Retorna a classe handler associada a um nome de função.

Se a função não estiver registrada, lança `RuntimeException`.

## Por que ele importa

Sem um registry explícito, a resolução de funções tende a ficar implícita e mais difícil de auditar. Este objeto mantém a superfície suportada clara e controlada.

## Papel atual no pipeline

`ExpressionEvaluator` chama `resolve()` quando encontra uma chamada de função durante o parse.
