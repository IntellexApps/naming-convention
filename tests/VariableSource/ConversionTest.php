<?php declare(strict_types = 1);

namespace Intellex\NamingConvention\Tests\VariableSource;

use Intellex\NamingConvention\NamingConvention;
use Intellex\NamingConvention\VariableSource;
use PHPUnit\Framework\TestCase;

class ConversionTest extends TestCase {

	/** @return string[] The list of invalid conventions names. */
	public static function invalidConventionNameProvider(): array {
		return [
			[
				[
					new VariableSource(NamingConvention::CAMEL_CASE, "a"),
					new VariableSource(NamingConvention::SNAKE_CASE, "a"),
					new VariableSource(NamingConvention::KEBAB_CASE, "a"),
					new VariableSource(NamingConvention::PASCAL_CASE, "A"),
					new VariableSource(NamingConvention::SCREAMING_SNAKE_CASE, "A"),
				]
			],
			[
				[
					new VariableSource(NamingConvention::CAMEL_CASE, "var"),
					new VariableSource(NamingConvention::SNAKE_CASE, "var"),
					new VariableSource(NamingConvention::KEBAB_CASE, "var"),
					new VariableSource(NamingConvention::PASCAL_CASE, "Var"),
					new VariableSource(NamingConvention::SCREAMING_SNAKE_CASE, "VAR"),
				]
			],
			[
				[
					new VariableSource(NamingConvention::CAMEL_CASE, "varName"),
					new VariableSource(NamingConvention::SNAKE_CASE, "var_name"),
					new VariableSource(NamingConvention::KEBAB_CASE, "var-name"),
					new VariableSource(NamingConvention::PASCAL_CASE, "VarName"),
					new VariableSource(NamingConvention::SCREAMING_SNAKE_CASE, "VAR_NAME"),
				]
			],
			[
				[
					new VariableSource(NamingConvention::CAMEL_CASE, "myVarName"),
					new VariableSource(NamingConvention::SNAKE_CASE, "my_var_name"),
					new VariableSource(NamingConvention::KEBAB_CASE, "my-var-name"),
					new VariableSource(NamingConvention::PASCAL_CASE, "MyVarName"),
					new VariableSource(NamingConvention::SCREAMING_SNAKE_CASE, "MY_VAR_NAME"),
				]
			],
		];
	}

	/** @dataProvider invalidConventionNameProvider */
	public function testParseConventionExceptionName(array $cases): void {
		foreach ($cases as $case) {
			foreach ($cases as $expected) {
				$actual = $case->convertTo($expected->convention);
				$this > self::assertEquals(
					$expected,
					$actual,
					implode(" => ", [
						"Given: ({$case->convention->name}) '{$case->name}'",
						"To: {$expected->convention->name}",
						"Expected: '{$expected->name}'",
						"Actual: '{$actual->name}'",
					]));
			}
		}
	}
}
