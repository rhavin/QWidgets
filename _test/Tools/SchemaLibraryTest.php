<?php

namespace Q\Tools;
//require "test_helpers.php";

class SchemaLibraryTest extends \PHPUnit\Framework\TestCase {

		protected ?SchemaLibrary $object = null;

		#[\Override]
		protected function setUp(): void {
				$this->object = new SchemaLibrary();
		}

		#[\Override]
		protected function tearDown(): void {}

		public function testInitialEmpty(): void {
			$this->assertEquals(0, $this->object->countSchemes(),'Initial schemacount must be 0.');
		}

		public function testAddSchemaEmptyInvalid(): void {
			$this->assertFalse($this->object->addSchema([]), 'Adding empty schema must return false.');
			$this->assertEquals(0, $this->object->countSchemes(),'Schemacount after failed add must not change.');
			$this->assertFalse($this->object->addSchema(['irgendwas']), 'Adding malformed schema must return false.');
			$this->assertEquals(0, $this->object->countSchemes(),'Schemacount after failed add must not change.');
		}
		public function testAddSchema(): void {
			$schema = get_example_schema_simple();
			$count = $this->object->countSchemes() + 1;
			$this->assertTrue($this->object->addSchema($schema), 'Adding valid schema must return true.');
			$this->assertEquals($count, $this->object->countSchemes(), 'Schemacount must increase after adding one single schema by 1.');
		}
		public function testGetSchema(): void {
			$schema = get_example_schema_simple();
			$id = $schema['$id'] ?? null;
			$this->assertNotEmpty($id, 'The sample-schema must have $id set.');
			$this->assertTrue($this->object->addSchema($schema), 'Adding valid schema must return true.');
			$this->assertNull($this->object->getSchema('unknown_schema'),'Asking for a unknown schema must return null.');
			$got_schema = $this->object->getSchema($id);
			$this->assertNotNull($got_schema,'Asking for a known schema must not return null.');
			$this->assertTrue($got_schema === $schema,'returned schema must be identical.');
		}
		public function testGetSchemaWithoutID(): void {
			$schema = get_example_schema_defs();
			$id = $schema['$id'] ?? null;
			$this->assertNull($id, 'The has_defs schema should have no $id for this test.');
			$this->assertTrue($this->object->addSchema($schema), 'Adding valid annonymous schema must return true.');
			$got_by_empty_id = $this->object->getSchema('');
			$got_by_top_id = $this->object->getSchema('#');
			$this->assertNotNull($got_by_empty_id,'Annonymous schema should be returned.');
			$this->assertNotNull($got_by_top_id,'Annonymous schema should be returned.');
			$this->assertTrue($got_by_empty_id === $schema,'Returned schema must be identical.');
			$this->assertTrue($got_by_top_id === $schema,'Returned schema must be identical.');
		}
		public function testDefsInSchema(): void {
			$schema = get_example_schema_defs();
			$count = $this->object->countSchemes();
			$count += count($schema['$defs']) + 1; // expected schema + sub-schemes
			$this->assertTrue($this->object->addSchema($schema), 'Adding valid schema must return true.');
			$this->assertEquals($count, $this->object->countSchemes(), 'Failed to add schema and sub-schemes.');
			
			$this->do_subschema_test($schema, 'sub-schema1');
			$this->do_subschema_test($schema, 'sub-schema2');
		}
		public function do_subschema_test($schema, $subschema_id) {
			$sub_schema = $schema['$defs'][$subschema_id];
			$this->assertNotNull($sub_schema, 'Subschema must be in $defs-section of schema for this test.');
			$got_by_id = $this->object->getSchema($subschema_id);
			$this->assertNotNull($got_by_id, 'Subschema not returned on id.');
			$this->assertTrue($this->difftest($got_by_id, $sub_schema),'Returned schema must be logically the same.');
			$got_by_path = $this->object->getSchema('#/$defs/'.$subschema_id);
			$this->assertNotNull($got_by_path, 'Subschema not returned on path.');
			$this->assertTrue($this->difftest($got_by_path, $sub_schema),'Returned schema must be logically the same.');
		}
		public function difftest(array $array1, array $array2) {
			$array_diff = array_diff($array1, $array2);
			foreach ($array_diff as $key => $value) {
				if ($key == '$id')
					continue;
				print 'Found diff: ['.$key.']=>['.$value."]\n";
				print_r($array1);
				print_r($array2);
				return false;
			}
			return true;
		}
}
