<?php declare(strict_types = 1);

namespace Intellex\NamingConvention;

use Intellex\NamingConvention\Exceptions\UnableToDetermineUsedConvention;
use Intellex\NamingConvention\Exceptions\UnknownConventionName;

/**
 * Defines common naming conventions used for variables, functions, and classes across various programming languages,
 * databases and data formats.
 */
enum NamingConvention: string {

	/** Examples: user, camelCase, greatestCommonFactor */
	case CAMEL_CASE = 'camelCase';

	/** Examples: user, snake_case, greatest_common_factor */
	case SNAKE_CASE = 'snake_case';

	/** Examples: user, camelCase, greatestCommonFactor */
	case SCREAMING_SNAKE_CASE = 'SCREAMING_SNAKE_CASE';

	/** Examples: user, PascalCase, GreatestCommonFactor */
	case PASCAL_CASE = 'PascalCase';

	/** Examples: user, kebab_case, greatest_common_factor */
	case KEBAB_CASE = 'kebab_case';

	/** @return string The regular expression used to validate the variable name. */
	public function getRegex(): string {
		return match ($this) {
			self::CAMEL_CASE           => '/^[a-z]+([A-Z][a-z0-9]*)*$/',
			self::SNAKE_CASE           => '/^[a-z]+(_[a-z0-9]+)*$/',
			self::SCREAMING_SNAKE_CASE => '/^[A-Z]+(_[A-Z0-9]+)*$/',
			self::PASCAL_CASE          => '/^([A-Z][a-z0-9]+)+$/',
			self::KEBAB_CASE           => '/^[a-z]+(-[a-z0-9]+)*$/',
		};
	}

	/**
	 * Check if a variable name is valid for this convention.
	 *
	 * @param string $variableName The name of the variable.
	 *
	 * @return bool True if the variable name is valid, for this particular convention.
	 */
	public function validate(string $variableName): bool {
		return (bool) preg_match($this->getRegex(), $variableName);
	}

	/**
	 * Parse the name of the convention into this enum.
	 *
	 * @param string $conventionName The convention name to parse (ie: camelCase, PascalCase, etc...).
	 *
	 * @return self The appropriate convention.
	 *
	 * @noinspection SpellCheckingInspection
	 */
	public static function parseConventionName(string $conventionName): self {

		// Make it simple
		$simplifiedConventionName = preg_replace('~[^a-z]~', '', strtolower($conventionName));

		// Brute force
		return match ($simplifiedConventionName) {
			"camel", "camelcase"                                => self::CAMEL_CASE,
			"pascal", "pascalcase"                              => self::PASCAL_CASE,
			"snake", "snakecase"                                => self::SNAKE_CASE,
			"screaming", "screamingsnake", "screamingsnakecase" => self::SCREAMING_SNAKE_CASE,
			"kebab", "kebabcase"                                => self::KEBAB_CASE,
			default                                             => throw new UnknownConventionName($conventionName),
		};
	}

	/**
	 * Infer the naming convention from the list of variable names.
	 *
	 * @param string[] $names The list of variable names to infer the convention from.
	 *
	 * @return NamingConvention The naming convention that is applicable to all supplied keys.
	 */
	public static function inferFromList(array $names): NamingConvention {
		$conventions = self::cases();

		// Try every convention that is still not excluded
		foreach (self::cases() as $convention) {
			if (in_array($convention, $conventions)) {

				// Remove from excluded
				foreach ($names as $name) {

					// Must be a string
					if (!is_string($name)) {
						$type = gettype($name);
						throw new UnableToDetermineUsedConvention("({$type}) {$name}");
					}

					// Validate the convention
					if (!$convention->validate($name)) {
						$conventions = array_filter($conventions, static fn($c) => $c !== $convention);
					}
				}
			}

			// Drop if none found
			if (count($conventions) === 0) {
				$keys = implode(', ', $names);
				throw new UnableToDetermineUsedConvention("(array) {$keys}");
			}
		}

		// Return the first applicable
		return reset($conventions);
	}

	/**
	 * Infer the naming convention from the data, by checking each of the property within.
	 *
	 * @param array<string, mixed> $data The data to infer from.
	 *
	 * @return NamingConvention The naming convention that is applicable to all keys within the supplied array.
	 */
	public static function inferFromData(array $data): NamingConvention {
		 return self::inferFromList(array_keys($data));
	}
}
