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
	 * Locks the keys of the map. After calling this method, no new keys
	 * can be added or removed.
	 */
	public function lockKeys()
	{
		$this->denyAccess(Restrictor::DENY_CREATE | Restrictor::DENY_DELETE);
	}
	/**
	 * Locks the values of the map. After calling this method, no values
	 * can be changed.
	 */
	public function lockValues()
	{
		$this->denyAccess(
			Restrictor::DENY_CREATE | Restrictor::DENY_DELETE | Restrictor::DENY_WRITE
		);
	}
	public function getAccess() : int {
		return $this->getRestrictor()->getAccess();
	}
}
