<?php

namespace Q\Tools;

/*
 * Process objects by a given schema
 */
class SchemaProcessor extends ErrorStack {
	/*
	 * The SchemaLibrary providing the schema. */
	protected ?SchemaLibrary $my_library = null;
	/*
	 * SchemaLibrary is shared */
	public function __construct(SchemaLibrary &$library) {
		$this->my_library =& $library;
	}
	public function checkType(mixed $obj_to_check, $listOfTypes, $path = '') : bool {
		if (!is_array($listOfTypes))
			$listOfTypes = [$listOfTypes];
		$obj_type = strtolower(gettype($obj_to_check));
		foreach ($listOfTypes as $type) {
			if ($type === '')
				continue;
			if ($obj_type === strtolower($type))
				return true;
		}
		if ($path)
			$path = '['.$path.'] ';
		return $this->fail(false, 'Object '.$path.'of type ['.$obj_type
			.'] given, ['.implode(',',$listOfTypes).'] expected.');
	}
	public function validate(mixed $object, $schema_id = '') : bool {
		$ref_schema = $this->my_library->getSchema($schema_id);
		return $this->do_validate($object, $ref_schema);
	}
	protected function do_validate($object, $ref_schema) : bool {
		if (is_null($ref_schema))
			return false;
		if (!array_key_exists('type', $ref_schema))
			return false;
		$type = $ref_schema['type'];
		if (!$this->checkType($object, $type))
			return false;
		switch ($type) {
		case 'object':
			if (!$this->_objectLoopProperties($object, $ref_schema))
				return false;
			break;
		case 'array':
			if (!$this->_arrayLoopItems($object, $ref_schema))
				return false;
		}
		return true;
	}
	private function _objectLoopProperties($object, $ref_schema) {
		foreach ($ref_schema['properties'] ?? [] as $key => $schema) {
			if (property_exists($object, $key)) {
				if (!$this->do_validate($object->$key, $schema, ''))
					return false;
			}
		}
		return true;
	}
	private function _arrayLoopItems($array, $ref_schema) {
		print "\nARRAY TEST\n";
		print_r($array);
		print "\n------------------------\n";
		print_r($ref_schema);
		print "\n========================\n";
		return true;
	}
}
