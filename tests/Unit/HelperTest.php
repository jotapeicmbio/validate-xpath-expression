<?php

namespace Tests\ValidateRegister\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    #[Test]
    public function it_validates_expressions_through_the_namespaced_helper(): void
    {
        $this->assertTrue(\Icmbio\ValidateXpathExpression\validate('. >= ${min} and . <= ${max}', 10, [
            'min' => 1,
            'max' => 100,
        ]));

        $this->assertSame(7, \Icmbio\ValidateXpathExpression\validate('string-length(.)', 'abacate', [], false));
    }

    #[Test]
    public function it_escapes_context_placeholders_through_the_namespaced_helper(): void
    {
        $this->assertSame('\${value}', \Icmbio\ValidateXpathExpression\escape_expression('${value}'));
    }
}
