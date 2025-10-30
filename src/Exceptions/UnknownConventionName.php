<?php declare(strict_types = 1);

namespace Intellex\NamingConvention\Exceptions;

use Throwable;

/**
 * The supplied convention name is not recognized.
 */
class UnknownConventionName extends NamingConventionException {

	/**
	 * @param string $suppliedConventionName The supplied convention name that couldn't be recognized.
	 */
	public function __construct(private readonly string $suppliedConventionName, ?Throwable $previous = null) {
		parent::__construct(
			"Unknown convention name: '{$this->getSuppliedConventionName()}'",
			500,
			$previous
		);
	}

	/** @return string The supplied convention name that couldn't be recognized. */
	public function getSuppliedConventionName(): string {
		return $this->suppliedConventionName;
	}
}
