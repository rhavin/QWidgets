<?php

namespace Q\Tools;

function get_example_schema_simple() {
	return [
		'type'=>   'string',
		'$id' =>   'sample-schema'
	];
}

function get_example_object_defs() {
	$obj = new \stdClass();
	$obj->propOfInteger = 1;
	$obj->propOfString  = 'Hallo Test';
	return $obj;
}
function get_example_array() {
	$array = [1,2,3,4,5];
	return $array;
}

function get_example_schema_defs() {
	return [
		'type'=> 'object',
		// id purposefully missing, should be set as #.
		'properties'=> [
			'propOfInteger' => [ 'type' => 'integer' ],
			'propOfString' =>  [ 'type' => 'string' ],
			'propOfMixed' =>  [ 'type' => ['string', 'integer'] ]
		],
		'$defs'=> [
			'sub-schema1'=> [
				'type'=>   'string',
				// id purposefully missing, should be contructed from key.
			],
			'sub-schema2'=> [
				'type'=>   'string',
				'$id' =>   'sub-schema2'
			],
			'sub-schema3'=> [
				'type'=>   ['string', 'null' ],
				// id purposefully missing, should be contructed from key.
			],
			'array-type'=> [
				'type'=>   'array'
				// id purposefully missing, should be contructed from key.
			],
		]
	];
}

function get_example_schema_complex() {
	return [
		'type'=>   'object',
		'typeof'=> 'Organization',
		'vocab'=>  'https://schema.org/',
		'properties'=> [
			'title'=> [
				'type'=>        'string',
				'default'=>     'Acme GmbH',
				'description'=> 'Title',
				'alias'=>       'name'
			],
			'address'=> [
				'$ref'=>        '#/$defs/address'
			],
			'contacts'=> [
				'type'=>        'array',
				'items' => [
					'$ref'=>    '#/$defs/contactPoint'
				]
			]
		],
		'$defs' => [
			'address'=> [
				'type'=>   'object',
				'typeof'=> 'PostalAddress',
				'tag'=>    'address',
				'properties'=> [
					'streetAddress'=> [
						'type'=>        'string',
						'default'=>     'Am Acker 1-15',
						'description'=> 'Street address'
					],
					'postalCode'=> [
						'type'=>        'string',
						'default'=>     '12345',
						'description'=> 'Postal code'
					],
					'addressLocality'=> [
						'type'=>        'string',
						'default'=>     'Berlin',
						'description'=> 'City / Village'
					],
					'addressCountry'=> [
						'type'=>        'string',
						'default'=>     'Germany',
						'description'=> 'Country'
					]
				]
			], // end: address
	 		'contactPoint' => [
				'type'=>   'object',
				'typeof'=> 'ContactPoint',
				'properties'=> [
					'contactType'=> [
						'type'=>        'string',
						'default'=>     'General Manager',
						'description'=> 'Contact Type'
					],
					'telephone'=> [
						'type'=>        'string',
						'default'=>     '+49 30 1234567-0',
						'description'=> 'Telephone'
					],
					'faxNumber'=> [
						'type'=>        'string',
						'default'=>     '+49 30 1234567-9',
						'description'=> 'Fax Number'
					],
					'email'=> [
						'type'=>        'string',
						'default'=>     'a@bc.org',
						'description'=> 'Email',
						'tag'=>         'a'
					]
				],
			] // end: contact point
		] // end: '$defs'
	];
}
