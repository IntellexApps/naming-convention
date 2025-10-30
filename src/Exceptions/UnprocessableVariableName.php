<?php declare(strict_types = 1);

namespace Intellex\NamingConvention\Exceptions;

use Throwable;

/**
 * The variable name cannot be accepted.
 */
class UnprocessableVariableName extends NamingConventionException {

	/**
	 * @param string $variableName The supplied variable name.
	 */
	public function __construct(private readonly string $variableName, ?Throwable $previous = null) {
		parent::__construct(
			"Supplied variable name cannot be processed: '{$this->getVariableName()}'",
			500,
			$previous
		);
	}

	/** @return string The supplied variable name. */
	public function getVariableName(): string {
		return $this->variableName;
	}
}
