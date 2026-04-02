<?php

namespace Tests\ValidateRegister\Unit;

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
use Icmbio\ValidateXpathExpression\Functions\StringLengthFunction;
use Icmbio\ValidateXpathExpression\Functions\StringFunction;
use Icmbio\ValidateXpathExpression\Functions\SubstringAfterFunction;
use Icmbio\ValidateXpathExpression\Functions\SubstringBeforeFunction;
use Icmbio\ValidateXpathExpression\Functions\TrueFunction;
use Icmbio\ValidateXpathExpression\Functions\UuidFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    #[Test]
    public function it_documents_floor_function_behavior(): void
    {
        $this->assertSame(4.0, (new FloorFunction(4.7))->handle());
        $this->assertSame(5.0, (new FloorFunction(5.7))->handle());
    }

    #[Test]
    public function it_documents_ceiling_function_behavior(): void
    {
        $this->assertSame(5.0, (new CeilingFunction(4.3))->handle());
        $this->assertSame(7.0, (new CeilingFunction(6.2))->handle());
    }

    #[Test]
    public function it_documents_string_length_function_behavior(): void
    {
        $this->assertSame(7, (new StringLengthFunction('abacate'))->handle());
        $this->assertSame(11, (new StringLengthFunction('40258997853'))->handle());
    }

    #[Test]
    public function it_documents_int_function_behavior(): void
    {
        $this->assertSame(4, (new IntFunction(4.7))->handle());
        $this->assertSame(12, (new IntFunction('12'))->handle());
    }

    #[Test]
    public function it_documents_number_function_behavior(): void
    {
        $this->assertSame(3.2, (new NumberFunction('3.2'))->handle());
        $this->assertSame(6.0, (new NumberFunction(6))->handle());
    }

    #[Test]
    public function it_documents_string_function_behavior(): void
    {
        $this->assertSame('10', (new StringFunction(10))->handle());
        $this->assertSame('abacate', (new StringFunction('abacate'))->handle());
    }

    #[Test]
    public function it_documents_contains_function_behavior(): void
    {
        $this->assertTrue((new ContainsFunction('abc', 'b'))->handle());
        $this->assertFalse((new ContainsFunction('abc', 'e'))->handle());
    }

    #[Test]
    public function it_documents_starts_with_function_behavior(): void
    {
        $this->assertTrue((new StartsWithFunction('abacate', 'ab'))->handle());
        $this->assertFalse((new StartsWithFunction('abacate', 'ac'))->handle());
    }

    #[Test]
    public function it_documents_normalize_space_function_behavior(): void
    {
        $this->assertSame('abacate verde', (new NormalizeSpaceFunction('   abacate   verde  '))->handle());
    }

    #[Test]
    public function it_documents_choose_function_behavior(): void
    {
        $this->assertSame(1, (new ChooseFunction(true, 1, 2))->handle());
        $this->assertSame(2, (new ChooseFunction(false, 1, 2))->handle());
    }

    #[Test]
    public function it_documents_not_function_behavior(): void
    {
        $this->assertTrue((new NotFunction(false))->handle());
        $this->assertFalse((new NotFunction(true))->handle());
    }

    #[Test]
    public function it_documents_true_function_behavior(): void
    {
        $this->assertTrue((new TrueFunction())->handle());
    }

    #[Test]
    public function it_documents_false_function_behavior(): void
    {
        $this->assertFalse((new FalseFunction())->handle());
    }

    #[Test]
    public function it_documents_selected_function_behavior(): void
    {
        $this->assertTrue((new SelectedFunction('peixe abacate', 'peixe'))->handle());
        $this->assertFalse((new SelectedFunction('peixe abacate', null))->handle());
    }

    #[Test]
    public function it_documents_substring_after_function_behavior(): void
    {
        $this->assertSame('bb', (new SubstringAfterFunction('aa&bb', '&'))->handle());
        $this->assertSame('', (new SubstringAfterFunction('aabb', '&'))->handle());
    }

    #[Test]
    public function it_documents_substring_before_function_behavior(): void
    {
        $this->assertSame('aa', (new SubstringBeforeFunction('aa&bb', '&'))->handle());
        $this->assertSame('', (new SubstringBeforeFunction('aabb', '&'))->handle());
    }

    #[Test]
    public function it_documents_format_date_time_function_behavior(): void
    {
        $function = new FormatDateTimeFunction('2019-05-14T19:13:35.450686Z', '%d/%m/%Y');

        $this->assertSame('14/05/2019', $function->handle());
        $this->assertNull((new FormatDateTimeFunction(null, '%d/%m/%Y'))->handle());
    }

    #[Test]
    public function it_documents_uuid_function_behavior(): void
    {
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f\-]{36}$/',
            (new UuidFunction())->handle()
        );
    }
}
