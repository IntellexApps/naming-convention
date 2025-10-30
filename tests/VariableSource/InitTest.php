<?php declare(strict_types = 1);

namespace Intellex\NamingConvention\Tests\VariableSource;

use Intellex\NamingConvention\Exceptions\UnprocessableVariableName;
use Intellex\NamingConvention\NamingConvention;
use Intellex\NamingConvention\VariableSource;
use PHPUnit\Framework\TestCase;

class InitTest extends TestCase {

	/** @return string[] The list of invalid variable names. */
	public static function invalidVariableNameProvider(): array {
		return [
			[ "" ], [ " " ], [ "  " ], [ " my" ], [ " var " ], [ "name " ]
		];
	}

	/** @dataProvider invalidVariableNameProvider */
	public function testInvalidVariableNames(string $variableName): void {
		$this->expectException(UnprocessableVariableName::class);
		$this->expectExceptionMessage("Supplied variable name cannot be processed: '{$variableName}'");
		new VariableSource(NamingConvention::CAMEL_CASE, $variableName);
	}
}
