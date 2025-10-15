<?php

namespace Q\Tools;

require_once(__DIR__ .'/../autoloader.php');
require_once('test_helpers.php');

class SchemaProcessorTest extends \PHPUnit\Framework\TestCase {

	protected ?SchemaLibrary $my_library = null;
	protected ?SchemaProcessor $object = null;

	#[\Override]
	protected function setUp(): void {
		$this->my_library = new SchemaLibrary();
		$this->object = new SchemaProcessor($this->my_library);
	}

	#[\Override]
	protected function tearDown(): void {}
	
	public function testInitialState(): void {
		$this->assertNotNull($this->object, 'Test-Object not constructed.');
	}
	public function testValidationWithoutSchema(): void {
		$this->assertFalse($this->object->validate(null), 'Without a schema, everything must be invalid.');
		$obj = get_example_object_defs();
		$this->assertFalse($this->object->validate($obj), 'Without a schema, everything must be invalid.');
		
	}
	public function testTypeCheck(): void {
		$this->assertTrue($this->object->checkType(null, 'null'), 'Null should be of type null.');
		$this->assertTrue($this->object->checkType(null, ['null', 'string']), 'Null should be of type null or string.');
		$this->assertFalse($this->object->checkType(null, 'string'), 'Null should not be of type string.');
		$this->assertFalse($this->object->checkType(null, ['integer', 'string']), 'Null should not be of type null or integer.');
		$this->assertFalse($this->object->checkType('A', 'null'), 'String should not be of type null.');
		$this->assertTrue($this->object->checkType('A', ['null', 'string']), 'String should be of type null or string.');
		$this->assertTrue($this->object->checkType('A', 'string'), 'String should be of type string.');
		$this->assertTrue($this->object->checkType(3, ['integer', 'string']), 'Integer should be of type null or integer.');
	}
	public function testSimpleValidation(): void {
		$this->my_library->addSchema(get_example_schema_defs());
		$this->assertFalse($this->object->validate(null), 'Root of simple def does not allow null.');
		$this->assertTrue($this->object->validate(null,'sub-schema3'), 'sub-schema3 does allow null.');
		$obj = get_example_object_defs();
		$this->assertTrue($this->object->validate($obj), 'Simple defs object must be valid.');
		$obj->propOfInteger = 'A String';
		$this->assertFalse($this->object->validate($obj), 'Object with string for must be valid.');
		$obj->propOfInteger = 1;
		$this->assertTrue($this->object->validate($obj), 'Simple defs object must be valid.');
	}
	public function testMixedProperties(): void {
		$this->my_library->addSchema(get_example_schema_defs());
		$obj = get_example_object_defs();
		$this->assertTrue($this->object->validate($obj), 'Simple defs object must be valid.');
		$obj->propOfMixed = 'A String';
		$this->assertTrue($this->object->validate($obj), 'String must be allowed as type.');
		$obj->propOfMixed = 1;
		$this->assertTrue($this->object->validate($obj), 'Integer must be allowed as type.');
		$obj->propOfMixed = null;
		$this->assertFalse($this->object->validate($obj), 'Null must be forbidden as type.');
	}
	public function testArray(): void {
		$this->my_library->addSchema(get_example_schema_defs());
		$array = get_example_array();
		$this->assertTrue($this->object->validate($array,'array-type'), 'Array must be valid by given schema id.');
	}
	
	/*
	 * Items array: can be false to allow no further items
	 *              can be [list] to allow any of listed items

	 */
}
