<?php
namespace Q\Restricted;
/**
 * Class Item.
 *
 * @author rhavin
 */
class Item extends RestrictedClass {
	/**
	 * @var mixed The arbitrary value we're holding.
	 */
	private mixed $value = null;
	/**
	 * Constructor. Without a given Restrictor, full access is granted.
	 * @param ?Restrictor $restrictor
	 */
	public function __construct(
					?Restrictor $restrictor = null, mixed $value = null)
	{
		if (is_null($restrictor))
			$restrictor = new FlagRestrictor();
		$this->setRestrictor($restrictor);
		$this->value = $value;
	}
	/**
	 * Gets the value if access is granted or null.
	 * @return mixed the value
	 */
	public final function getValue() : mixed {
		if ($this->isDeniedRead())
			return null;
		return $this->value;
	}
	/**
	 * Sets the value if access is granted.
	 * @param mixed $newValue
	 * @return bool True is value has been set, else false.
	 */
	public final function setValue(mixed $newValue) : bool {
		if ($this->isDeniedWrite())
			return false;
		$this->value = $newValue;
		return true;
	}
}
