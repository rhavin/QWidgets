<?php
namespace Q\Tools;
/**
 * A Map is a generic collection that allows access to one or more elements
 * by providing an arbitrary key.
 *
 * @author rhavin
 */
interface Map extends \Countable, \ArrayAccess, \IteratorAggregate {
	/**
	 * Get the values from the map that are considered targeted by the
	 * provided key.
	 * 
	 * @param mixed $key the key to get. Null is allowed.
	 * @return array of values targeted by that key. Might be empty.
	 */
	public function get(mixed $key): array;
	/**
	 * Set values that are considered targeted by the provided key
	 * to the specified value.
	 * 
	 * @param mixed $key the key to set. Null is allowed.
	 * @param mixed $value the value to set. Null is allowed.
	 * @return int the number of affected keys.
	 */
	public function set(mixed $key, mixed $value) : int;
	/**
	 * Remove entries that are considered targeted by the provided key.
	 * 
	 * @param mixed $key the key to remove. Null is allowed.
	 * @return int the number of affected keys.
	 */
	public function remove(mixed $key) : int;
}
