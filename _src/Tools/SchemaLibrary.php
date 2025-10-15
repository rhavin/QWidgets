<?php

namespace Q\Tools;

class SchemaLibrary extends ErrorStack {
	private $my_schemes = [];
	public function __construct() {}
	public function countSchemes() {
		return count($this->my_schemes);
	}
	public function addSchema(array $schema, $id = null) {
		if (empty($schema))
			return $this->fail(false, 'Schema is empty');
		if (!array_key_exists('type', $schema))
			return $this->fail(false, 'Schema has no type-key.');;
		if (!array_key_exists('$id', $schema)) {
			if (!is_null($id))
				$schema['$id'] = $id;
		}
		if (array_key_exists('$defs', $schema)) {
			foreach ($schema['$defs'] as $key => $value) {
				$this->addSchema($value, $key);
			}
		}
		
		$this->my_schemes[$schema['$id']] = $schema;
		return true;
	}
	public function getSchema(string $schema_id) {
		list($hive, $fragment) = explode('#',$schema_id, 2);
		$schema = $this->my_schemes[$hive] ?? null;
		if (!$fragment) {
			return $schema;
		}
		$pointer = $schema;
		$path = '';
		foreach (explode('/',$fragment) as $part) {
			if ($part === '')
				continue;
			if (!array_key_exists($part, $pointer)) {
				return $this->fail(null, 'Part ['.$part.'] missing in ['.$path.'].');
			}
			$pointer = $pointer[$part];
			$path .= '/'.$part;
		}
		return $pointer;
	}
}