<?php
namespace Q\Restricted;

class ItemTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var Attributes
	 */
	protected ?Item $object = null;

	/**
	 * This method is called before a test is executed.
	 */
	#[\Override]
	protected function setUp(): void {
		$this->object = new Item();
	}

	/**
	 * This method is called after a test is executed.
	 */
	#[\Override]
	protected function tearDown(): void {}

	/**
	 * @covers Q\Schema\RestrictedItem::getValue
	 */
	public function testGetValue(): void {
		$this->AssertNull($this->object->getValue(), 'Initial value should be null');
	}
	/**
	 * @covers Q\Schema\RestrictedItem::getValue
	 */
	public function testSetValue(): void {
		$testValue = random_int(1000, 9999);
		$this->AssertTrue($this->object->setValue($testValue), 'Initially, value should be allowed to set.');
		$this->AssertEquals($testValue, $this->object->getValue(), 'Value should be set if not restricted.');
	}
}
