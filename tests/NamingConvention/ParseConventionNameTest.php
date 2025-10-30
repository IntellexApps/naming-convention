<?php declare(strict_types = 1);

namespace Intellex\NamingConvention\Tests\NamingConvention;

use Intellex\NamingConvention\Exceptions\UnknownConventionName;
use Intellex\NamingConvention\NamingConvention;
use PHPUnit\Framework\TestCase;

class ParseConventionNameTest extends TestCase {

	/** @return array<array, NamingConvention> The list of valid conventions names and expected values. */
	public static function validConventionNameProvider(): array {
		return [
			//
			// CAMEL_CASE
			[ "camel", NamingConvention::CAMEL_CASE ],
			[ "camel ", NamingConvention::CAMEL_CASE ],
			[ "camel case", NamingConvention::CAMEL_CASE ],
			[ "camel_case", NamingConvention::CAMEL_CASE ],
			[ "camelCase", NamingConvention::CAMEL_CASE ],
			[ "CamelCase", NamingConvention::CAMEL_CASE ],
			//
			// PASCAL_CASE
			[ "pascal", NamingConvention::PASCAL_CASE ],
			[ "pascalCase", NamingConvention::PASCAL_CASE ],
			[ " PaS-CaL  ", NamingConvention::PASCAL_CASE ],
			[ "[pas][cal][case]", NamingConvention::PASCAL_CASE ],
			//
			// SNAKE_CASE
			[ "snake", NamingConvention::SNAKE_CASE ],
			[ "snake_case", NamingConvention::SNAKE_CASE ],
			[ "| snake |", NamingConvention::SNAKE_CASE ],
			[ "| 🐍 | snake | case |", NamingConvention::SNAKE_CASE ],
			//
			// SCREAMING_SNAKE_CASE
			[ "screaming_snake_case", NamingConvention::SCREAMING_SNAKE_CASE ],
			[ "screaming , ", NamingConvention::SCREAMING_SNAKE_CASE ],
			[ "screaming snake ???", NamingConvention::SCREAMING_SNAKE_CASE ],
			[ "screaming], snake], case", NamingConvention::SCREAMING_SNAKE_CASE ],
			//
			// KEBAB_CASE
			[ "kebab", NamingConvention::KEBAB_CASE ],
			[ "kebab-case", NamingConvention::KEBAB_CASE ],
			[ "***ke***bab***", NamingConvention::KEBAB_CASE ],
			[ "-ke-bab-ca-se-", NamingConvention::KEBAB_CASE ],
		];
	}

	/** @dataProvider validConventionNameProvider */
	public function testParseConventionName(string $conventionName, NamingConvention $expectedConvention): void {
		$actualConvention = NamingConvention::parseConventionName($conventionName);
		$this->assertEquals(
			$expectedConvention,
			$actualConvention,
			implode(" => ", [
				"Given: '{$conventionName}'",
				"Expected: '{$expectedConvention->name}'",
				"Actual: '{$actualConvention->name}'"
			]));
	}

	/** @return string[] The list of invalid conventions names. */
	public static function invalidConventionNameProvider(): array {
		return [
			[ "" ], [ " " ], [ "null" ],
			[ "camilaCase" ], [ "crab-case" ],
		];
	}

	/** @dataProvider invalidConventionNameProvider */
	public function testParseConventionExceptionName(mixed $invalidConventionName): void {
		$this->expectException(UnknownConventionName::class);
		$this->expectExceptionMessage("Unknown convention name: '{$invalidConventionName}'");
		NamingConvention::parseConventionName($invalidConventionName);
	}
}
