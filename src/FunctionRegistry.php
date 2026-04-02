<?php

namespace Icmbio\ValidateXpathExpression;

use Icmbio\ValidateXpathExpression\Functions\CeilingFunction;
use Icmbio\ValidateXpathExpression\Functions\ChooseFunction;
use Icmbio\ValidateXpathExpression\Functions\ContainsFunction;
use Icmbio\ValidateXpathExpression\Functions\FalseFunction;
use Icmbio\ValidateXpathExpression\Functions\FormatDateTimeFunction;
use Icmbio\ValidateXpathExpression\Functions\FloorFunction;
use Icmbio\ValidateXpathExpression\Functions\IntFunction;
use Icmbio\ValidateXpathExpression\Functions\NormalizeSpaceFunction;
use Icmbio\ValidateXpathExpression\Functions\NotFunction;
use Icmbio\ValidateXpathExpression\Functions\NumberFunction;
use Icmbio\ValidateXpathExpression\Functions\SelectedFunction;
use Icmbio\ValidateXpathExpression\Functions\StartsWithFunction;
use Icmbio\ValidateXpathExpression\Functions\StringFunction;
use Icmbio\ValidateXpathExpression\Functions\StringLengthFunction;
use Icmbio\ValidateXpathExpression\Functions\SubstringAfterFunction;
use Icmbio\ValidateXpathExpression\Functions\SubstringBeforeFunction;
use Icmbio\ValidateXpathExpression\Functions\TrueFunction;
use Icmbio\ValidateXpathExpression\Functions\UuidFunction;
use RuntimeException;

class FunctionRegistry
{
    protected const FUNCTIONS = [
        'selected' => SelectedFunction::class,
        'string-length' => StringLengthFunction::class,
        'string_length' => StringLengthFunction::class,
        'int' => IntFunction::class,
        'floor' => FloorFunction::class,
        'ceiling' => CeilingFunction::class,
        'number' => NumberFunction::class,
        'string' => StringFunction::class,
        'contains' => ContainsFunction::class,
        'starts-with' => StartsWithFunction::class,
        'normalize-space' => NormalizeSpaceFunction::class,
        'choose' => ChooseFunction::class,
        'not' => NotFunction::class,
        'true' => TrueFunction::class,
        'false' => FalseFunction::class,
        'uuid' => UuidFunction::class,
        'format-date-time' => FormatDateTimeFunction::class,
        'substring-after' => SubstringAfterFunction::class,
        'substring-before' => SubstringBeforeFunction::class,
    ];

    public function all(): array
    {
        return self::FUNCTIONS;
    }

    public function resolve(string $name): string
    {
        if (!isset(self::FUNCTIONS[$name])) {
            throw new RuntimeException("Funcao XPath nao suportada: {$name}");
        }

        return self::FUNCTIONS[$name];
    }
}
