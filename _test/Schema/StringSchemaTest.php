<?php
namespace Q\Schema;

class StringSchemaTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @var StringSchema
	 */
	protected ?StringSchema $object = null;
	protected $testrnd = 0;

	#[\Override]
	protected function setUp(): void {
		$this->object = new StringSchema();
	}

	#[\Override]
	protected function tearDown(): void {}
	/**
	 * @covers Q\Schema\AbstractSchema::getType
	 * @todo   Implement testGetType().
	 */
	public function testGetType(): void {
		$this->AssertEquals('string', $this->object->getType(), 'Type should be set to "string".');
	}
}
