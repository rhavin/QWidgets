<?php
namespace Q\Restricted;

class MapTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var Attributes
	 */
	protected ?Map $object = null;

	/**
	 * This method is called before a test is executed.
	 */
	#[\Override]
	protected function setUp(): void {
		$this->object = new Map();
	}
	/**
	 * This method is called after a test is executed.
	 */
	#[\Override]
	protected function tearDown(): void {}
	
	public function testInit() {
		$this->AssertEquals(0, count($this->object),
						'Initial Restricted Map must be empty.');
		$this->AssertFalse($this->object->isDeniedCreate(),
						'Initial Restricted Map must be unrestricted to create.');
		$this->AssertFalse($this->object->isDeniedDelete(),
						'Initial Restricted Map must be unrestricted to delete.');
		$this->AssertFalse($this->object->isDeniedRead(),
						'Initial Restricted Map must be unrestricted to read.');
		$this->AssertFalse($this->object->isDeniedWrite(),
						'Initial Restricted Map must be unrestricted to write.');
		$this->AssertFalse($this->object->isDeniedRestrictor(),
						'Initial Restricted Map must allow to change restrictor.');
	}
	public function testLocks() {
		$testValue = 'V'. random_int(1000, 9999);
		$testKey   = 'K'. random_int(1000, 9999);
		$this->object->lockKeys(true);
		$this->AssertTrue($this->object->isDeniedCreate(),
						'Restricted Map must be restricted to create after lockKeys().');
		$this->AssertTrue($this->object->isDeniedDelete(),
						'Restricted Map must be restricted to delete after lockKeys().');
		$this->AssertFalse($this->object->isDeniedRead(),
						'Restricted Map must be unrestricted to read after lockKeys().');
		$this->AssertFalse($this->object->isDeniedWrite(),
						'Restricted Map must be unrestricted to write after lockKeys().');
		$this->AssertEquals(0, $this->object->set($testKey, $testValue),
						'Restricted Map must disallow write after lockKeys().');
		$this->object[$testKey] = $testValue;
		$this->AssertNull($this->object[$testKey],
						'Restricted Map must disallow create after lockKeys().');
		$this->object->lockKeys(false);
		$this->AssertEquals(1, $this->object->set($testKey, $testValue),
						'Restricted Map must allow create after lockKeys(false).');
		$this->AssertEquals($testValue, $this->object[$testKey],
						'Restricted Map must allow create after lockKeys(false).');
		
		$testValue2 = 'V'. random_int(1000, 9999);
		$testValue3 = 'V'. random_int(1000, 9999);
		
		$this->object->lockKeys(true);
		$this->AssertEquals(1, $this->object->set($testKey, $testValue2),
						'Restricted Map must allow write after lockKeys(true).');
		$this->AssertEquals($testValue2, $this->object[$testKey],
						'Restricted Map must allow write after lockKeys(true).');

		$this->object->lockValues();
		$this->AssertTrue($this->object->isDeniedCreate(),
						'Restricted Map must be restricted to create after lockValues().');
		$this->AssertTrue($this->object->isDeniedDelete(),
						'Restricted Map must be restricted to delete after lockValues().');
		$this->AssertFalse($this->object->isDeniedRead(),
						'Restricted Map must be unrestricted to read after lockValues().');
		$this->AssertTrue($this->object->isDeniedWrite(),
						'Restricted Map must be restricted to write after lockValues().');
		$this->AssertEquals(0, $this->object->set($testKey, $testValue3),
						'Restricted Map must restrict write after lockValues(true).');
		$this->AssertEquals($testValue2, $this->object[$testKey],
						'Restricted Map must restrict write after lockValues(true).');
	}
}