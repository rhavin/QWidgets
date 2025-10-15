<?php
namespace Q\Restricted;
/**
 * RestrictedMap is a type-safe implementation of an associative array with
 * restriction on the whole array and single elements.
 *
 * @author rhavin
 */
class Map extends \Q\Tools\TypedMap implements Restrictor
{
	// trait TRestricted implements IRestrictor
	use Restricted;
	/**
	 * Constructor
	 * @param int $size pre-reserved size of the map.
	 */
	public function __construct(
				int $size = \Q\Tools\TypedMap::DEF_SIZE,
				?Restrictor $restrictor = null
	)
	{
		if (is_null($restrictor))
			$restrictor = new FlagRestrictor();
		$this->setRestrictor($restrictor);
		parent::__construct($size);
	}
		/**
	 * Locks or unlocks rename, adding and removal of keys.
	 * 
	 * @param bool $lock true to lock, false to unlock.
	 * @return bool true if successful, otherwise false.
	 */
	public function lockKeys(bool $lock = true) : bool
	{
		if ($lock)
			return $this->denyAccess(Restrictor::DENY_CREATE | Restrictor::DENY_DELETE);
		return $this->allowAccess(Restrictor::DENY_CREATE | Restrictor::DENY_DELETE);
	}
	/**
	 * Locks or unlocks write access to values.
	 * 
	 * @param bool $lock true to lock, false to unlock.
	 * @return bool true if successful, otherwise false.
	 */
	public function lockValues(bool $lock = true) : bool
	{
		if ($lock)
			return $this->denyAccess(Restrictor::DENY_CREATE
							| Restrictor::DENY_DELETE | Restrictor::DENY_WRITE
		);
			return $this->allowAccess(Restrictor::DENY_CREATE
							| Restrictor::DENY_DELETE | Restrictor::DENY_WRITE);
	}
	public function getAccess2deprecated() : int {
		return $this->getRestrictor()->getAccess();
	}
	public function get(mixed $key): array {
		if ($this->isDeniedRead())
			return [];
		return parent::get($key);
	}
	public function set(mixed $key, $value): int {
		if ($this->isDeniedWrite())
			return 0;
		if (!$this->offsetExists($key) && $this->isDeniedCreate())
			return 0;
		return parent::set($key,$value);
	}
	public function remove(mixed $key): int {
		if ($this->isDeniedDelete())
			return 0;
		return parent::remove($key);
	}
}