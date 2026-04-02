# Visão geral

`validate-xpath-expression` é uma biblioteca PHP focada em validar expressões em estilo XPath contra um valor e um contexto opcional.

Ela foi pensada para cenários que precisam de regras leves, como:

- faixas numéricas
- condicionais
- manipulação de strings
- verificações de data
- checagem de pertencimento em conjuntos

A biblioteca não tenta implementar XPath completo. Em vez disso, suporta um dialeto restrito, mais simples de entender, testar e proteger.

## Objetivos principais

- manter a API pública pequena
- isolar a validação de expressões do código da aplicação
- suportar regras inspiradas em XPath comuns no domínio
- evitar execução insegura de código PHP
- manter cobertura com testes positivos e de rejeição

## API pública

O principal ponto de entrada é `Icmbio\ValidateXpathExpression\Xpath`.

Uso típico:

```php
use Icmbio\ValidateXpathExpression\Xpath;

$result = Xpath::validate('. >= ${min} and . <= ${max}', 10, [
    'min' => 1,
    'max' => 100,
]);
```

## Fluxo de processamento

1. `Xpath` recebe a expressão, o valor atual, o contexto e o modo de retorno.
2. `ExpressionPreparer` substitui `.` e `${nome}` e normaliza operadores.
3. `ExpressionTokenizer` transforma a expressão normalizada em tokens.
4. `ExpressionEvaluator` faz o parse e avalia a expressão.
5. `FunctionRegistry` resolve nomes de funções para classes handler.

## Leitura relacionada

- [Arquitetura](./arquitetura.md)
- [Funções nativas](./funcoes.md)
- [Objeto Xpath](./objetos/xpath.md)
