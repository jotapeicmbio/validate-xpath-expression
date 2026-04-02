# Funções nativas

A biblioteca expõe um conjunto focado de funções em estilo XPath através de `FunctionRegistry`.

## Funções registradas

| Função | Classe handler | Finalidade |
| --- | --- | --- |
| `selected` | `SelectedFunction` | Verifica se um token existe em um conjunto separado por espaços |
| `string-length` | `StringLengthFunction` | Retorna o tamanho da string |
| `string_length` | `StringLengthFunction` | Alias para `string-length` |
| `int` | `IntFunction` | Converte um valor para inteiro |
| `floor` | `FloorFunction` | Aplica `floor` |
| `ceiling` | `CeilingFunction` | Aplica `ceil` |
| `number` | `NumberFunction` | Converte um valor para float |
| `string` | `StringFunction` | Converte um valor para string |
| `contains` | `ContainsFunction` | Verifica se uma string contém outra |
| `starts-with` | `StartsWithFunction` | Verifica prefixo |
| `normalize-space` | `NormalizeSpaceFunction` | Remove excessos e normaliza espaços |
| `choose` | `ChooseFunction` | Seleção de valor em estilo ternário |
| `not` | `NotFunction` | Negação booleana |
| `true` | `TrueFunction` | Retorna `true` |
| `false` | `FalseFunction` | Retorna `false` |
| `uuid` | `UuidFunction` | Gera um valor em formato UUID |
| `format-date-time` | `FormatDateTimeFunction` | Formata uma data |
| `substring-after` | `SubstringAfterFunction` | Retorna o texto depois de um separador |
| `substring-before` | `SubstringBeforeFunction` | Retorna o texto antes de um separador |

## Modelo dos handlers

Cada função é implementada como uma classe em [`src/Functions`](../../src/Functions) e implementa `XPathFunctionInterface`.

Cada handler:

- recebe os argumentos no construtor
- expõe um único método `handle(): mixed`
- permanece pequeno e fácil de testar isoladamente

## Validação de aridade

Antes de instanciar um handler, `ExpressionEvaluator` valida a aridade do construtor via reflexão. Isso evita que argumentos a mais ou a menos passem silenciosamente.
