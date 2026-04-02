<?php

namespace Tests\ValidateRegister\Unit;

use Icmbio\ValidateXpathExpression\Repl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ReplTest extends TestCase
{
    #[Test]
    public function it_evaluates_helper_calls_inside_the_repl(): void
    {
        $repl = new Repl('/tmp/validate-xpath-expression-history');

        $result = $repl->evaluate(
            'validate(\'. >= ${min} and . <= ${max}\', 10, [\'min\' => 1, \'max\' => 100])'
        );

        $this->assertTrue($result);
    }

    #[Test]
    public function it_provides_basic_autocomplete_suggestions(): void
    {
        $repl = new Repl('/tmp/validate-xpath-expression-history');

        $this->assertContains('validate(', $repl->complete('val'));
        $this->assertContains('Xpath::validate(', $repl->complete('Xp'));
        $this->assertContains('string-length(', $repl->complete('string-'));
    }
}
