<?php
namespace Q\Tools;
/**
 * A key/value paired map, allowing for arbitrary keys including null.
 * In the default implementation, keys are typed and can occur just
 * once - "0" is another key than 0 or 0.0!
 * 
 * @author .rhavin;)
 */
class TypedMap implements Map
{
	const DEF_SIZE = 5;
	const DEF_INCREMENT = 5;
	/** @var ?\SplFixedArray the array holding the keys */
	private ?\SplFixedArray $my_keys   = null;
	/** @var ?\SplFixedArray the array holding the values */
	private ?\SplFixedArray $my_values = null;
	/** @var int space reserved to hold elements */
	private int $my_dim  = 0;
	/** @var int number of actual used elements */
	private int $my_count = 0;
	/**
	 * Constructor
	 * @param int $size pre-reserved size of the map.
	 */
	public function __construct(int $size = self::DEF_SIZE)
	{
		$this->my_keys   = new \SplFixedArray($size);
		$this->my_values = new \SplFixedArray($size);
		$this->my_dim    = $size;
	}
	/**
	 * Get current size of the map.
	 * @return int current size of the map.
	 */
	public function count(): int
	{
		return $this->my_count;
	}
	/**
	 * Get current reserved size of the internal map.
	 * @return int current reserved size of the internal map.
	 */
	public function getReservedSize(): int
	{
		return $this->my_dim;
	}
	/**
	 * Get the values from the map that are considered targeted (via the
	 * keyCompare-function) by the provided key. Default implementation is:
	 * Keys are typed and can occur just once - "0" is another key than 0
	 * or false or 0.0, so the default implementation will always return an
	 * array with either 1 or 0 elements.
	 * 
	 * @param mixed $key the key to get. Null is allowed.
	 * @return array of values targeted by that key. Might be empty.
	 */
	public function get(mixed $key): array
	{
		$ret = [];
		foreach ($this->_getIndices($key) as $i) {
			$ret[] = $this->my_values[$i];
		}
		return $ret;
	}
	/**
	 * Set values that are considered targeted (via the keyCompare-function)
	 * by the provided key to the specified value. Default implementation is:
	 * Keys are typed and can occur just once - "0" is another key than 0 or
	 * false or 0.0!
	 * 
	 * @param mixed $key the key to set. Null is allowed.
	 * @param mixed $value the value to set. Null is allowed.
	 * @return int the number of affected keys.
	 */
	public function set(mixed $key, mixed $value) : int
	{
		$ret = 0;
		$affected = $this->_getIndices($key);
		if (count($affected) == 0)
		{
			$idx = $this->_createNewIndex();
			if ($idx > -1)
				$affected[] = $idx;
				$this->my_keys[$idx] = $key;
		}
		foreach($affected as $i)
		{
			$this->my_values[$i] = $value;
			++$ret;
		}
		return $ret;
	}
	/**
	 * Remove entries that are considered targeted (via the keyCompare-function)
	 * by the provided key. Default implementation is: Keys are typed and can
	 * occur just once - "0" is another key than 0 or false or 0.0!
	 * 
	 * @param mixed $key the key to remove. Null is allowed.
	 * @return int the number of affected keys.
	 */
	public function remove(mixed $key) : int
	{
		$ret = 0;
		foreach($this->_getIndices($key) as $i)
		{
			if ($this->removeIndex($i))
				++$ret;
		}
		return $ret;
	}
	/**
	 * Check if any entry is considered targeted (via the keyCompare-function)
	 * by the provided key. Default implementation is: Keys are typed and can
	 * occur just once - "0" is another key than 0 or false or 0.0!
	 * Check if a key targets an valid entry. Method from interface ArrayAccess.
	 *
	 * @param mixed $key the key togetIndices check. Null is allowed.
	 * @return bool true if existent, otherwise false.
	 */
	public function offsetExists(mixed $key): bool
	{
		return (count($this->get($key)) > 0);
	}
	/**
	 * Get the first value from the map that is considered targeted (via the
	 * keyCompare-function) by the provided key. Default implementation is:
	 * Keys are typed and can occur just once - "0" is another key than 0
	 * or false or 0.0! Method from interface ArrayAccess.
	 * 
	 * @param mixed $key the key to set. Null is allowed.
	 * @return mixed the first found value
	 */
	public function offsetGet(mixed $key): mixed
	{
		$values = $this->get($key);
		if ($this->count($values) == 0)
			return null;
		return $values[0];
	}
	/**
	 * Set values that are considered targeted (via the keyCompare-function)
	 * by the provided key to the specified value. Default implementation is:
	 * Keys are typed and can occur just once - "0" is another key than 0 or
	 * false or 0.0! Method from interface ArrayAccess that only calls the
	 * set-function.
	 * 
	 * @param mixed $key the key to set. Null is allowed.
	 * @param mixed $value the value to set. Null is allowed.
	 * @return void.
	 */
	public function offsetSet(mixed $key, mixed $value): void {
		$this->set($key, $value);
	}
	/**
	 * Remove entries that are considered targeted (via the keyCompare-function)
	 * by the provided key to the specified value. Default implementation is:
	 * Keys are typed and can occur just once - "0" is another key than 0 or
	 * false or 0.0! Method from interface ArrayAccess that only calls the
	 * remove-function.
	 * 
	 * @param mixed $key the key to remove. Null is allowed.
	 * @return void.
	 */
	public function offsetUnset(mixed $key): void
	{
		$this->remove($key);
	}
	/**
	 * Threadsafe iterator, method from interface Traversable.
	 * @return \Traversable
	 */
	public function getIterator(): \Traversable
	{
		for ($i=0; $i<$this->my_count; ++$i)
		{
			yield $this->my_keys[$i] => $this->my_values[$i];
		}
	}
	public function resize(int $elements) : int
	{
		$reserve = $this->my_dim - $this->my_count;
		while ($reserve > self::DEF_INCREMENT)
		{
			$reserve -= self::DEF_INCREMENT;
		}
		$this->my_dim = $this->my_count + $reserve;
		$this->my_keys->setSize($this->my_dim);
		$this->my_values->setSize($this->my_dim);
		return $this->my_dim;
	}
	/**
	 * Check if the first key (entry) is considered targeted by the second key.
	 * The default implementation just checks for identity.
	 * 
	 * @param mixed $entry the entry from theinternal keys
	 * @param mixed $key   the provided key.
	 * @return bool true if key targets entry, otherwise false.
	 */
	protected function keyCompare(mixed $entry, mixed $key) : bool
	{
		return ($entry === $key);
	}
	/**
	 * Create a new index in the internal arrays for keys and values.
	 * 
	 * @return int the newly created index.
	 */
	private function _createNewIndex() : int
	{
		if ($this->my_count >= $this->my_dim)
		{
			$this->my_dim += self::DEF_INCREMENT;
			$this->my_keys->setSize($this->my_dim);
			$this->my_values->setSize($this->my_dim);
		}
		$idx = $this->my_count;
		++$this->my_count;
		return $idx;
	}
	/**
	 * Remove an index from the internal arrays for keys and values.
	 * 
	 * @return true if successful, otherwise false
	 */
	protected function removeIndex(int $idx) : bool
	{
		--$this->my_count;
		if ($idx < $this->my_count)
		{
			// fill gap with last element.
			$this->my_keys[$idx] = $this->my_keys[$this->my_count];
			$this->my_values[$idx] = $this->my_values[$this->my_count];
		}
		return true;
	}
	/**
	 * Get an array of all indices targeted by the provided key.
	 * 
	 * @param mixed $key targeting key
	 * @return array of targeted indices.
	 */
	private function _getIndices(mixed $key): array
	{
		$ret = [];
		for ($i=0; $i<$this->my_count; ++$i) {
			if ($this->keyCompare($this->my_keys[$i], $key))
				$ret[] = $i;
		}
		return $ret;
	}
}