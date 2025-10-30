<?php declare(strict_types = 1);

namespace Intellex\NamingConvention\Exceptions;

use Throwable;

/**
 * The supplied convention name is not recognized.
 */
class UnableToDetermineUsedConvention extends NamingConventionException {

	/**
	 * @param string $suppliedVariableName The supplied variable name that couldn't be categorized.
	 */
	public function __construct(private readonly string $suppliedVariableName, ?Throwable $previous = null) {
		parent::__construct(
			"Unable to determine used convention: '{$this->getSuppliedVariableName()}'",
			500,
			$previous
		);
	}

	/** @return string The supplied convention name that couldn't be recognized. */
	public function getSuppliedVariableName(): string {
		return $this->suppliedVariableName;
	}
}
