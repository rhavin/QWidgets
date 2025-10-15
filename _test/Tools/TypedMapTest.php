<?php

namespace Q\Tools;

class TypedMapTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @var TypedMap
	 */
	protected ?TypedMap $object = null;

	#[\Override]
	protected function setUp(): void {
		$this->object = new TypedMap();
	}

	#[\Override]
	protected function tearDown(): void {
		
	}

	/**
	 * @covers Q\Tools\Map::count
	 */
	public function testResize(): void {
		$this->AssertEquals(0, $this->object->count(),'Itialial Map must be empty.');
		$this->addAndCount('keyA', 'valueA', true);
		$this->addAndCount('keyB', 'valueB', true);
		$this->addAndCount('keyB', 'valueBagain', false);
		$this->addAndCount(1, 'valueNum1', true);
		$this->addAndCount("1", 'valueString1', true);
		$this->addAndCount(1.0, 'valueFloat1', true);
		$this->addAndCount("1", 'valueString1again', false);
		$this->addAndCount(null, 'valueNull', true);
		$this->addAndCount(1.0, 'valueFloat1again', false);
		$this->addAndCount(null, 'valueNullagain', false);
	}
		/**
	 * @covers Q\Tools\Map::count
	 */
	public function testInternalSize(): void
	{
		$intInitial = $this->object->getReservedSize();
		$this->AssertEquals(TypedMap::DEF_SIZE, $intInitial,
						'Without ctor arguments, internal size must be Map::DEF_SIZE.');
		
		// we add a bunch of elements, including fringe cases
		$pairs = self::_pollute($this->object,20);
		$sizePairs = count($pairs);
		$this->AssertEquals($sizePairs, $this->object->count(),
						'Polluted Map must count all added elements.');
		$this->AssertTrue(($sizePairs>TypedMap::DEF_SIZE),
						'Test-Criterium not met! Testclasses pollution function must'
						.'return more elements than Map::DEF_SIZE to test resizing.');
		
		$intExpected = $intInitial;
		while ($intExpected < $sizePairs) {
			$intExpected += TypedMap::DEF_INCREMENT;
		}
		$intPolluted = $this->object->getReservedSize();
		$this->AssertEquals($intPolluted, $intExpected,
						'Internal maps must have been resized now by DEF_INCREMENT.');

		// and we remove them again.
		foreach ($pairs as $p)
		{
			$this->AssertEquals(1, $this->object->remove($p['key']),
						'Each element must be removable.');
		}
		$this->AssertEquals(0, $this->object->count(),
						'Empty maps external size must be 0.');
		$this->object->resize(0);
		$intShrinked = $this->object->getReservedSize();
		$this->AssertTrue(($intShrinked<$intPolluted),
						'Empty map must have shrunken its internal size.');
	}
	protected function addAndCount(mixed $key, mixed $value, bool $is_new) {
		$count = $this->object->count();
		if ($is_new) {
			$this->AssertEquals(0, $this->object->offsetExists($key), 'Offset must not exist.');
			++$count;
		}
		else
			$this->AssertEquals(1, $this->object->offsetExists($key), 'Offset must exist.');
		$this->AssertEquals(1, $this->object->set($key,$value), 'setting a value must be possible.');
		$this->AssertEquals(1, $this->object->offsetExists($key), 'Offset must exist after setting it.');
		$this->AssertEquals($count, $this->object->count(),'Count must increase if key is new.');
		
		$retArray = $this->object->get($key);
		$this->AssertIsArray($retArray, 'Get must return an array.');
		$this->AssertEquals(1, count($retArray), 'Get must return only one key here.');
		$this->AssertEquals($value, $retArray[0],'Existing key must return set value.');
	}
	public function testAddAndRemove(): void {
		$this->AssertEquals(0, $this->object->count(),'Itialial Map must be empty.');
		// fill with 5 Items..
		$this->AssertEquals(1,$this->object->set('key1','value1'), 'Setting a value must be possible.');
		$this->AssertEquals(1,$this->object->set('key2','value2'), 'Setting a value must be possible.');
		$this->AssertEquals(1,$this->object->set('key3','value3'), 'Setting a value must be possible.');
		$this->AssertEquals(1,$this->object->set('key4','value4'), 'Setting a value must be possible.');
		$this->AssertEquals(1,$this->object->set('key5','value5'), 'Setting a value must be possible.');
		$this->AssertEquals(5, $this->object->count(),'Count must return 5 with 5 Items.');
		// remove item 4..
		$this->do_remove('key4', 'value4', 4);
		// remove item 5..testing the end..
		$this->do_remove('key5', 'value5', 3);
		// remove item 1..testing the start..
		$this->do_remove('key1', 'value1', 2);
		// remove item 3..
		$this->do_remove('key3', 'value3', 1);
		// add another 2 items between removals, one previous existing..
		$this->AssertEquals(1,$this->object->set('key6','value6'), 'Setting a value must be possible.');
		$this->AssertEquals(1,$this->object->set('key4','value4'), 'Setting a value must be possible.');
		// now test removing until 0..
		$this->do_remove('key2', 'value2', 2);
		$this->do_remove('key4', 'value4', 1);
		$this->do_remove('key6', 'value6', 0);
	}
	private function do_remove($key, $expected_value, $expected_count) {
		$this->AssertEquals($expected_value, $this->object->get($key)[0],'Existing key must return set value.');
		$this->AssertEquals(1,$this->object->remove($key), 'Removing extisting key must affect 1.');
		$this->AssertEquals(0,$this->object->remove($key), 'Removing a key again must affect 0.');
		$this->AssertEquals($expected_count, $this->object->count(),'Count must return '.$expected_count.' with '.$expected_count.' Items.');
	}
	public function testArrayAccess(): void {
		$this->AssertEquals(0, $this->object->count(),'Itialial Map must be empty.');
		$this->object['key'] = 'value';
		$this->AssertEquals('value', $this->object['key'],'Array-key must return given value');
		$this->object[null] = 'nullvalue';
		$this->object[""] = 'nullstring';
		$this->object[false] = 'false';
		$this->AssertEquals('nullvalue', $this->object[null],'Array-key must return given value');
		$this->AssertEquals('nullstring', $this->object[""],'Array-key must return given value');
		$this->AssertEquals('false', $this->object[false],'Array-key must return given value');
		$this->AssertEquals(4, $this->object->count(),'Map must be of size 4 now.');
		unset($this->object[""]);
		$this->AssertEquals(3, $this->object->count(),'Map must be of size 3 now.');
		$this->AssertNull($this->object[""],'Array-key "" has been removed and must return null.');
	}
	public function testTraversion(): void
	{
		$pairs = self::_pollute($this->object);
		// traverse thru Map calls Map::getIterator()
		foreach ($this->object as $key => $value)
		{
			// check if values have the correct type..
			$pair =& self::find_in_pairs($key, $pairs);
			$this->AssertNotNull($pair, 'Key was not found in pairs.');
			$this->AssertFalse($pair['checked'], 'Key must not appear twice in this test.');
			$this->AssertEquals($pair['value'], $value, 'Value must be equal.');
			$this->AssertTrue($pair['value']===$value, 'Value must be of exactly the same type.');
			
			//check if we can access as array.
			$this->AssertTrue(($value === $this->object[$key]),'Array-key must return given value');
			$pair['checked'] = true;
		}
		// check if all have been checked..
		foreach ($pairs as &$p) {
			$key   = $p['key'];
			$value = $p['value'];
			if (is_null($key))
				$key = 'null';
			$this->AssertTrue($p['checked'],'Key ['.$key.'] of value ['.$value.'] not checked in foreach.');
		}
	}
	private static function &find_in_pairs($key, &$pairs) {
		foreach ($pairs as &$p) {
			if ($p['key'] === $key)
				return $p;
		}
		return null;
	}
	private static function _pollute(TypedMap &$map, int $count = 8) : array {
		// keys and values can be int, string, float, bool or null
		$pairs = [];
		foreach (self::_getMapTestEntry() as $entry) {
			$pairs[] = $entry;
			if (count($pairs) >= $count)
				break;
		}
		// pollute map with entries..
		foreach ($pairs as &$p) {
			$p['checked'] = false;
			$map->set($p['key'], $p['value']);
		}
		return $pairs;
	}
	private static function _getMapTestEntry() : \Traversable {
		yield	['key' => 1,    'value' => 5.3];
		yield ['key' => 1.0,  'value' => 5];
		yield ['key' => false,'value' => true];
		yield ['key' => 's',  'value' => 'foo'];
		yield ['key' => null, 'value' => false];
		yield ['key' => 2.3,  'value' => 1];
		yield ['key' => true, 'value' => ''];
		yield ['key' => "",   'value' => null];
		// rest is random
		yield ['key' => 'K'. random_int(1000, 9999),
				'value' => 'V'. random_int(1000, 9999)];
	}

}
