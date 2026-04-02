# Built-in Functions

The library exposes a focused set of built-in XPath-like functions through `FunctionRegistry`.

## Registered functions

| Function | Handler class | Purpose |
| --- | --- | --- |
| `selected` | `SelectedFunction` | Checks whether a token exists in a whitespace-separated set |
| `string-length` | `StringLengthFunction` | Returns string length |
| `string_length` | `StringLengthFunction` | Alias for `string-length` |
| `int` | `IntFunction` | Converts a value to integer |
| `floor` | `FloorFunction` | Applies floor conversion |
| `ceiling` | `CeilingFunction` | Applies ceil conversion |
| `number` | `NumberFunction` | Converts a value to float |
| `string` | `StringFunction` | Converts a value to string |
| `contains` | `ContainsFunction` | Checks substring containment |
| `starts-with` | `StartsWithFunction` | Checks string prefix |
| `normalize-space` | `NormalizeSpaceFunction` | Trims and normalizes whitespace |
| `choose` | `ChooseFunction` | Ternary-like value selection |
| `not` | `NotFunction` | Boolean negation |
| `true` | `TrueFunction` | Returns `true` |
| `false` | `FalseFunction` | Returns `false` |
| `uuid` | `UuidFunction` | Generates a UUID-like value |
| `format-date-time` | `FormatDateTimeFunction` | Formats a date value |
| `substring-after` | `SubstringAfterFunction` | Returns text after a separator |
| `substring-before` | `SubstringBeforeFunction` | Returns text before a separator |

## Handler model

Each function is implemented as a class under [`src/Functions`](../../src/Functions) and implements `XPathFunctionInterface`.

Each handler:

- receives its arguments in the constructor
- exposes a single `handle(): mixed` method
- stays small and independently testable

## Arity validation

Before a handler is instantiated, `ExpressionEvaluator` checks constructor arity using reflection. This prevents unsupported extra or missing arguments from silently slipping through.
