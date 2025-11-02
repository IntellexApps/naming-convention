<?php declare(strict_types = 1);

namespace Intellex\NamingConvention\Tests\NamingConvention;

use Intellex\NamingConvention\Exceptions\UnableToDetermineUsedConvention;
use Intellex\NamingConvention\NamingConvention;
use PHPUnit\Framework\TestCase;

class InferFromDataTest extends TestCase {

	/** @return array<array, NamingConvention> The list of valid conventions names and expected values. */
	public static function validArrayProvider(): array {
		return [
			//
			// CAMEL_CASE
			[ NamingConvention::CAMEL_CASE, [
			] ],
			[ NamingConvention::CAMEL_CASE, [
				"a" => "OK",
			] ],
			[ NamingConvention::CAMEL_CASE, [
				"myVar"          => "",
				"success"        => true,
				"additionalData" => []
			] ],
			//
			// SNAKE_CASE
			[ NamingConvention::SNAKE_CASE, [
				"yes"            => "YES",
				"my_var_case"    => "snake...",
				"administrative" => true,
			] ],
			//
			// SCREAMING_SNAKE_CASE
			[ NamingConvention::SCREAMING_SNAKE_CASE, [
				"A" => null,
			] ],
			[ NamingConvention::SCREAMING_SNAKE_CASE, [
				"INCOMING" => "math",
				"PI_VALUE" => 3.14,
			] ],
			//
			// PASCAL_CASE
			[ NamingConvention::PASCAL_CASE, [
				"More"    => true,
				"PiValue" => 3.14,
			] ],
			//
			// KEBAB_CASE
			[ NamingConvention::KEBAB_CASE, [
				"in-the-air" => "ON",
				"in-private" => false,
			] ],
		];
	}

	/** @dataProvider validArrayProvider */
	public function testInferFromValidArray(NamingConvention $expectedConvention, array $inputArray): void {
		$actualConvention = NamingConvention::inferFromData($inputArray);
		$this->assertEquals(
			$expectedConvention,
			$actualConvention,
			implode(" => ", [
				"Expected: '{$expectedConvention->name}'",
				"Actual: '{$actualConvention->name}'"
			]));
	}

	/** @return string[] The list of invalid conventions names. */
	public static function invalidArrayProvider(): array {
		return [
			[ [
				"only",
				"numbers",
			] ],
			[ [
				true => true,
			] ],
			[ [
				true   => true,
				"true" => "true",
			] ],
			[ [
				0   => "mixed",
				"a" => "keys",
			] ],
			[ [
				""    => "empty",
				"key" => "value",
			] ],
			[ [
				"camel"      => "camel",
				"camelCase"  => "case",
				"snake_case" => "snake",
			] ],
			[ [
				"camelCase"  => "camel",
				"PascalCase" => "pascal",
			] ],
			[ [
				"snake_case" => "!",
				"SNAKE_CASE" => "!!!",
			] ],
			[ [
				"kebab-case" => "-",
				"snake_case" => "_",
			] ],
		];
	}

	/** @dataProvider invalidArrayProvider */
	public function testInferFromInvalidArray(mixed $invalidInputArray): void {
		$this->expectException(UnableToDetermineUsedConvention::class);
		NamingConvention::inferFromData($invalidInputArray);
	}
}
