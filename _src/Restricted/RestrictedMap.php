<?php
namespace Q\Restricted;
/**
 * RestrictedMap is a type-safe implementation of an associative array with
 * restriction on the whole array and single elements.
 *
 * @author rhavin
 */
class RestrictedMap extends \Q\Tools\TypedMap implements Restrictor
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
		$this->setRestrictor($restrictor);
		parent::__construct($size);
	}	
}
