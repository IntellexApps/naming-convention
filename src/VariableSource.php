<?php declare(strict_types = 1);

namespace Intellex\NamingConvention;

use Intellex\NamingConvention\Exceptions\UnableToDetermineUsedConvention;
use Intellex\NamingConvention\Exceptions\UnprocessableVariableName;

/**
 * Defines common naming conventions used for variables, functions, and classes across various programming languages,
 * databases and data formats.
 */
class VariableSource {

	/**
	 * @param NamingConvention $convention The used naming convention.
	 * @param string           $name       The original variable name.
	 */
	public function __construct(
		public readonly NamingConvention $convention,
		public readonly string $name,
	) {
		if ($name === "" || trim($name) !== $name) {
			throw new UnprocessableVariableName($name);
		}
	}

	/**
	 * Automatically detect convention from the variable name and initialize the source.
	 *
	 * @param string $variableName The variable name to determine the naming convention for.
	 *
	 * @return VariableSource The variable source with its applicable naming convention.
	 */
	public static function from(string $variableName): VariableSource {
		foreach (NamingConvention::cases() as $convention) {
			if ($convention->validate($variableName)) {
				return new VariableSource($convention, $variableName);
			}
		}

		throw new UnableToDetermineUsedConvention($variableName);
	}

	/**
	 * Convert to another convention.
	 *
	 * @param NamingConvention $convention The convention to convert to.
	 *
	 * @return VariableSource The converter variable source.
	 */
	public function convertTo(NamingConvention $convention): VariableSource {
		return match ($this->convention) {
			NamingConvention::CAMEL_CASE           => $this->convertFromCamelCase($convention),
			NamingConvention::SNAKE_CASE           => $this->convertFromSnakeCase($convention),
			NamingConvention::KEBAB_CASE           => $this->convertFromKebabCase($convention),
			NamingConvention::PASCAL_CASE          => $this->convertFromPascalCase($convention),
			NamingConvention::SCREAMING_SNAKE_CASE => $this->convertFromScreamingSnakeCase($convention),
		};
	}

	/**
	 * Convert from {@see NamingConvention::CAMEL_CASE}.
	 *
	 * @param NamingConvention $convention The conversion to convert to.
	 *
	 * @return VariableSource The converted variable name.
	 */
	private function convertFromCamelCase(NamingConvention $convention): VariableSource {
		$name = $this->name;
		$convertedName = match ($convention) {
			NamingConvention::CAMEL_CASE
			=> $name,

			NamingConvention::SNAKE_CASE
			=> strtolower(preg_replace(
					'/([a-z0-9])([A-Z])|([A-Z])([A-Z])([a-z0-9])/',
					'$1$3_$2$4$5',
					$name)
			),

			NamingConvention::PASCAL_CASE
			=> strtoupper($name[0]) . substr($name, 1),

			NamingConvention::KEBAB_CASE
			=> $this
				->convertTo(NamingConvention::SNAKE_CASE)
				->convertTo(NamingConvention::KEBAB_CASE),

			NamingConvention::SCREAMING_SNAKE_CASE
			=> $this
				->convertTo(NamingConvention::SNAKE_CASE)
				->convertTo(NamingConvention::SCREAMING_SNAKE_CASE),
		};

		return $this->init($convention, $convertedName);
	}

	/**
	 * Convert from {@see NamingConvention::SNAKE_CASE}.
	 *
	 * @param NamingConvention $convention The conversion to convert to.
	 *
	 * @return VariableSource The converted variable name.
	 */
	private function convertFromSnakeCase(NamingConvention $convention): VariableSource {
		$name = $this->name;
		$convertedName = match ($convention) {
			NamingConvention::CAMEL_CASE
			=> $this
				->convertTo(NamingConvention::PASCAL_CASE)
				->convertTo(NamingConvention::CAMEL_CASE),

			NamingConvention::SNAKE_CASE
			=> $name,

			NamingConvention::PASCAL_CASE
			=> str_replace(' ', '',
				ucwords(
					strtolower(
						str_replace('_', ' ', $name)))),

			NamingConvention::KEBAB_CASE
			=> str_replace('_', '-', $name),

			NamingConvention::SCREAMING_SNAKE_CASE
			=> strtoupper($name),
		};

		return $this->init($convention, $convertedName);
	}

	/**
	 * Convert from {@see NamingConvention::SCREAMING_SNAKE_CASE}.
	 *
	 * @param NamingConvention $convention The conversion to convert to.
	 *
	 * @return VariableSource The converted variable name.
	 */
	private function convertFromScreamingSnakeCase(NamingConvention $convention): VariableSource {
		$name = $this->name;
		$convertedName = match ($convention) {
			NamingConvention::CAMEL_CASE
			=> $this
				->convertTo(NamingConvention::PASCAL_CASE)
				->convertTo(NamingConvention::CAMEL_CASE),

			NamingConvention::SNAKE_CASE
			=> strtolower($name),

			NamingConvention::PASCAL_CASE
			=> str_replace(' ', '',
				ucwords(
					strtolower(
						str_replace('_', ' ', $name)))),

			NamingConvention::KEBAB_CASE
			=> strtolower(str_replace('_', '-', $name)),

			NamingConvention::SCREAMING_SNAKE_CASE
			=> $name,
		};

		return $this->init($convention, $convertedName);

	}

	/**
	 * Convert from {@see NamingConvention::PASCAL_CASE}.
	 *
	 * @param NamingConvention $convention The conversion to convert to.
	 *
	 * @return VariableSource The converted variable name.
	 */
	private function convertFromPascalCase(NamingConvention $convention): VariableSource {
		$name = $this->name;
		$convertedName = match ($convention) {
			NamingConvention::CAMEL_CASE
			=> strtolower($name[0]) . substr($name, 1),

			NamingConvention::SNAKE_CASE
			=> strtolower(preg_replace(
					'/([a-z0-9])([A-Z])|([A-Z])([A-Z])([a-z0-9])/',
					'$1$3_$2$4$5',
					$name)
			),

			NamingConvention::PASCAL_CASE
			=> $name,

			NamingConvention::KEBAB_CASE
			=> $this
				->convertTo(NamingConvention::SNAKE_CASE)
				->convertTo(NamingConvention::KEBAB_CASE),

			NamingConvention::SCREAMING_SNAKE_CASE
			=> $this
				->convertTo(NamingConvention::SNAKE_CASE)
				->convertTo(NamingConvention::SCREAMING_SNAKE_CASE),
		};

		return $this->init($convention, $convertedName);
	}

	/**
	 * Convert from {@see NamingConvention::KEBAB_CASE}.
	 *
	 * @param NamingConvention $convention The conversion to convert to.
	 *
	 * @return VariableSource The converted variable name.
	 */
	private function convertFromKebabCase(NamingConvention $convention): VariableSource {
		$name = $this->name;
		$convertedName = match ($convention) {
			NamingConvention::CAMEL_CASE
			=> $this
				->convertTo(NamingConvention::SNAKE_CASE)
				->convertTo(NamingConvention::CAMEL_CASE),

			NamingConvention::SNAKE_CASE
			=> str_replace('-', '_', $name),

			NamingConvention::PASCAL_CASE
			=> $this
				->convertTo(NamingConvention::SNAKE_CASE)
				->convertTo(NamingConvention::PASCAL_CASE),

			NamingConvention::KEBAB_CASE
			=> $name,

			NamingConvention::SCREAMING_SNAKE_CASE
			=> strtoupper(str_replace('-', '_', $name)),
		};

		return $this->init($convention, $convertedName);
	}

	/**
	 * Initialize from a string or another variable source.
	 *
	 * @param NamingConvention      $convention     The used naming convention.
	 * @param string|VariableSource $variableSource Either the used name (as string), or instance of a source.
	 *
	 * @return self An instance of variable source.
	 */
	private function init(NamingConvention $convention, string|self $variableSource): self {
		return !$variableSource instanceof self
			? new self($convention, $variableSource)
			: $variableSource;
	}
}
