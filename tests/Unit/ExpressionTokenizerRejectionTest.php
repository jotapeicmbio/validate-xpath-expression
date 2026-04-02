<?php

namespace Tests\ValidateRegister\Unit;

use Icmbio\ValidateXpathExpression\ExpressionTokenizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ExpressionTokenizerRejectionTest extends TestCase
{
    #[Test]
    #[DataProvider('invalidExpressionProvider')]
    public function it_rejects_invalid_tokens_during_tokenization(string $expression)
    {
        $this->expectException(RuntimeException::class);

        (new ExpressionTokenizer())->tokenize($expression);
    }

    public static function invalidExpressionProvider(): array
    {
        return [
            'semicolon injection' => ['1; phpinfo()'],
            'curly brace open' => ['{1 + 1}'],
            'curly brace close' => ['1 + 1}'],
            'question mark ternary' => ['1 ? 2 : 3'],
            'colon ternary' => ['1 : 2'],
            'dollar variable' => ['$foo'],
            'backtick execution' => ['`id`'],
            'double dot' => ['..'],
            'object accessor' => ['floor(1)->run()'],
            'static accessor' => ['floor(1)::run()'],
            'unterminated single quote' => ["'abc"],
            'unterminated double quote' => ['"abc'],
            'dangling backslash in string' => ["'abc\\"],
            'unsupported unicode operator' => ['1 × 2'],
            'at sign' => ['@floor(1)'],
            'hash sign' => ['#comment'],
            'square brackets' => ['[1, 2]'],
            'escaped variable marker' => ['\${min}'],
            'raw dot access chain' => ['true().x'],
            'scientific notation currently unsupported' => ['1e3'],
        ];
    }
}
