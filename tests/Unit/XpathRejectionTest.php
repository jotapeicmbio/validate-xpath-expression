<?php

namespace Tests\ValidateRegister\Unit;

use Icmbio\ValidateXpathExpression\Xpath;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class XpathRejectionTest extends TestCase
{
    #[Test]
    #[DataProvider('rejectedByPublicApiProvider')]
    public function it_rejects_invalid_expressions_through_public_api(string $expression)
    {
        $this->expectException(RuntimeException::class);

        Xpath::validate($expression, 10);
    }

    #[Test]
    #[DataProvider('invalidArityThroughPublicApiProvider')]
    public function it_rejects_invalid_function_arity_through_public_api(string $expression)
    {
        $this->expectException(RuntimeException::class);

        Xpath::validate($expression, 10);
    }

    public static function rejectedByPublicApiProvider(): array
    {
        return [
            'semicolon payload' => ['1; phpinfo()'],
            'backtick payload' => ['`id`'],
            'unknown function payload' => ['shell_exec("id")'],
            'question mark payload' => ['1 ? 2 : 3'],
            'curly braces payload' => ['{1 + 1}'],
            'raw dollar payload' => ['$foo'],
            'unterminated string payload' => ["'abc"],
            'unexpected identifier payload' => ['system'],
            'broken grouping payload' => ['(1 + 2'],
            'extra closing group payload' => ['1 + 2)'],
            'multiple expressions payload' => ['1 2'],
            'double dot payload' => ['..'],
        ];
    }

    public static function invalidArityThroughPublicApiProvider(): array
    {
        return [
            'floor without args' => ['floor()'],
            'uuid with arg' => ['uuid(1)'],
            'choose missing arg' => ['choose(true(), 1)'],
            'contains missing arg' => ['contains("abc")'],
            'format date time missing arg' => ['format-date-time(.)'],
        ];
    }
}
