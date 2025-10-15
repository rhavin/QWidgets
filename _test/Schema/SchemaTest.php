<?php
namespace Q\Schema;

class MockupSchema extends Schema {}

class SchemaTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @var Schema
	 */
	protected ?MockupSchema $object = null;
	protected $testID   = 0;
	protected $testType = 0;
	#[\Override]
	protected function setUp(): void {
		// use random but consistent ID and type
		$this->testID   = random_int(1000, 9999);
		$this->testType = random_int(1000, 9999);
		$this->object = new MockupSchema($this->testID, $this->testType);
	}
	#[\Override]
	protected function tearDown(): void {}
	/**
	 * @covers Q\Schema\Schema::getType
	 * @todo   Implement testGetType().
	 */
	public function testGetType(): void {
		$this->AssertEquals($this->testType, $this->object->getType(), 'Type should be set to constructor value.');
		$this->AssertIsString($this->object->getType(), 'Type must return a string if not null.');
	}
	/**
	 * @covers Q\Schema\Schema::getID
	 * @todo   Implement testGetID().
	 */
	public function testGetID(): void {
		$this->AssertEquals($this->testID, $this->object->getID(), 'ID should be set to constructor value.');
		$this->AssertIsString($this->object->getID(), 'ID must return a string if not null.');
	}
	/**
	 * @covers Q\Schema\Schema::setID
	 * @todo   Implement testSetID().
	 */
	public function testSetID(): void {
		$newid = 'testID'. random_int(1000, 9999);
		$this->AssertTrue($this->object->setID($newid),'Change of ID should be possible');
		$this->AssertEquals($newid, $this->object->getID(), 'ID should be set to new value now.');
		$this->AssertIsString($this->object->getID(), 'ID must return a string if not null.');
		$this->AssertTrue($this->object->setID(null),'ID must be allowed to be null for base schema.');
		$this->AssertNull($this->object->getID(), 'ID must return null if set to.');
	}
	/**
	 * @covers Q\Schema\Schema::setAttribute
	 * @todo   Implement testSetAttribute().
	 */
	public function testSetAttribute(): void {
		$newattr = 'testAttribute'. random_int(1000, 9999);
		$this->AssertFalse($this->object->setAttribute($newattr, null),'Basic Schema should not allow random attributes.');
		$this->AssertFalse($this->object->setAttribute('type', $newattr),'Basic Schema must not allow changing of type.');
		$this->AssertTrue($this->object->setAttribute('id', $newattr),'Basic Schema must allow changing of ID.');
		$this->AssertEquals($newattr, $this->object->getID(), 'ID should be set to new value now.');
	}
}
