<?php declare(strict_types = 1);

namespace Intellex\NamingConvention\Tests\VariableSource;

use Intellex\NamingConvention\Exceptions\UnableToDetermineUsedConvention;
use Intellex\NamingConvention\NamingConvention;
use Intellex\NamingConvention\VariableSource;
use PHPUnit\Framework\TestCase;

class FromTest extends TestCase {

	/** @return array<array, NamingConvention> The list of valid conventions names and expected values. */
	public static function validConventionStrings(): array {
		return [
			//
			// All lowercase -> CAMEL_CASE
			[ "a", NamingConvention::CAMEL_CASE ],
			[ "ab", NamingConvention::CAMEL_CASE ],
			[ "case", NamingConvention::CAMEL_CASE ],
			[ "camel", NamingConvention::CAMEL_CASE ],
			//
			// All uppercase -> SCREAMING_CAMEL_CASE
			[ "A", NamingConvention::SCREAMING_SNAKE_CASE ],
			[ "AB", NamingConvention::SCREAMING_SNAKE_CASE ],
			[ "CASE", NamingConvention::SCREAMING_SNAKE_CASE ],
			[ "SCREAM", NamingConvention::SCREAMING_SNAKE_CASE ],
			//
			// Only first uppercase -> PASCAL_CASE
			[ "Ab", NamingConvention::PASCAL_CASE ],
			[ "Pascal", NamingConvention::PASCAL_CASE ],
			[ "Leibniz", NamingConvention::PASCAL_CASE ],
			//
			// CAMEL_CASE
			[ "camelCase", NamingConvention::CAMEL_CASE ],
			[ "bannerTop", NamingConvention::CAMEL_CASE ],
			[ "intermediateActionButtonLabel", NamingConvention::CAMEL_CASE ],
			//
			// PASCAL_CASE
			[ "PascalCase", NamingConvention::PASCAL_CASE ],
			[ "MyGoodNeighbour", NamingConvention::PASCAL_CASE ],
			[ "SoInAnyCaseWhatever", NamingConvention::PASCAL_CASE ],
			//
			// SNAKE_CASE
			[ "snake_case", NamingConvention::SNAKE_CASE ],
			[ "is_activated", NamingConvention::SNAKE_CASE ],
			[ "has_admin_privileges", NamingConvention::SNAKE_CASE ],
			//
			// SCREAMING_SNAKE_CASE
			[ "SCREAMING_SNAKE_CASE", NamingConvention::SCREAMING_SNAKE_CASE ],
			[ "E_ALL", NamingConvention::SCREAMING_SNAKE_CASE ],
			[ "WHERE_IS_YOUR_APPLE", NamingConvention::SCREAMING_SNAKE_CASE ],
			//
			// KEBAB_CASE
			[ "kebab-case", NamingConvention::KEBAB_CASE ],
			[ "case-of-kebabs", NamingConvention::KEBAB_CASE ],
			[ "in-the-middle-of-something", NamingConvention::KEBAB_CASE ],
		];
	}

	/** @dataProvider validConventionStrings */
	public function testDetermineApplicableConvention(string $variableName, NamingConvention $expectedConventionEnum): void {
		$variableSource = VariableSource::from($variableName);
		$this->assertEquals(
			$expectedConventionEnum,
			$variableSource->convention,
			implode(" => ", [
				"Given: '{$variableName}'",
				"Expected: '{$expectedConventionEnum->name}'",
				"Actual: '{$variableSource->convention->name}'"
			]));
	}

	/** @return string[] The list of invalid names for any convention. */
	public static function invalidStringsForAnyConvention(): array {
		return [
			[ "" ], [ " " ], [ "." ], [ "~" ], [ "!" ], [ "?" ], [ "🙂" ],
			[ "-" ], [ "_" ], [ "-_" ], [ "_-_" ],
			[ " camel" ], [ " camel " ], [ "camel " ], [ "cam el" ], [ "super man" ],
			[ "_almost" ], [ "_but_" ], [ "not_quite_" ],
			[ "-kebab-" ], [ "-ke-B-ab-" ], [ "UPPER-CASE" ],
			[ "dot.com" ], [ "who?where" ], [ "overdraft!" ],
		];
	}

	/** @dataProvider invalidStringsForAnyConvention */
	public function testParseConventionExceptionName(mixed $invalidString): void {
		$this->expectException(UnableToDetermineUsedConvention::class);
		$this->expectExceptionMessage("Unable to determine used convention: '{$invalidString}'");
		VariableSource::from($invalidString);
	}
}
