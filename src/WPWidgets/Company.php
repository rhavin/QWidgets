<?php
namespace Q\WPWidgets;
// Version 0.1.31

/**
 * Adds Company widget.
 */
class Company extends \WP_Widget {
	const METAPREFIX = 'add';
	public function __construct() {
		$id_base = 'company_widget';
		$name    = 'qcompany';
		$wid_options = array(
			'classname'                   => $id_base,
			'description'                 => __( 'Company Contact', 'text_domain' ),
			'customize_selective_refresh' => true,
			'show_instance_in_rest'       => true,
		);
		$ctl_options = array(
			'width'  => 400,
			'height' => 350,
		);
		parent::__construct($id_base, $name, $wid_options, $ctl_options);
	}
	public function toDefault() {
		$instance = array();
		$instance['title']           = 'QCompany';
		$instance['name']            = 'Acme GmbH';
		$instance['streetAddress']   = 'Am Acker 1-15';
		$instance['postalCode']      = '12345';
		$instance['addressLocality'] = 'Berlin';
		$instance['addressCountry']  = 'Germany';
		$instance['contacts'] = [[
			'contactType' => 'General Manager',
			'telephone' => '+49 30 1234567-0',
			'faxNumber' => '+49 30 1234567-9',
			'email' => 'noreply@example.com',
			'hoursAvailable' => [
				'dayOfWeek' => 'Mo-Fr',
				'opens' => '08:00',
				'closes' => '16:00'
			]
		]];
		return $instance; 
	}

	/**
	 * Schema for widget settings.
	 *
	 * @return array
	 */
	public function get_instance_schema() {
		$schema = [
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
					'$ref'=> '#/$defs/address'
				],
			],
			'$defs'=> [
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
				]
			]
		];

		$schema = apply_filters( "widget_{$this->id_base}_instance_schema", $schema, $this );
		return $schema;
	}

	/**
	 * Front-end display of widget.
	 * @see WP_Widget::widget()
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		if (empty($instance)) {
			$instance = $this->toDefault();
		}
		echo $args['before_widget']."\n";
		echo '<div class="corpAddress" vocab="https://schema.org/" typeof="Organization">'."\n";
		echo \Q\Tools\HTML::keyproperty('name', 'h1', $instance, 2);
		$address = \Q\Tools\HTML::keyproperty('streetAddress', 'span', $instance, 2).', '
			.\Q\Tools\HTML::keyproperty('postalCode', 'span', $instance, 2).' '
			.\Q\Tools\HTML::keyproperty('addressLocality', 'span', $instance, 2).', '
			.\Q\Tools\HTML::keyproperty('addressCountry', 'span', $instance, 2);
		if ($address != '') {
			echo '  <address property="address" typeof="PostalAddress">'."\n";
			echo $address;
			echo "  </address>\n";
		}
		foreach ($instance['contacts'] as $contact)  {
			$html = \Q\Tools\HTML::keyproperty('contactType', 'h2', $contact, 4)
				.\Q\Tools\HTML::keyproperty('telephone', 'span', $contact, 4, null, '☏: ')
				.\Q\Tools\HTML::keyproperty('faxNumber', 'span', $contact, 4, null, '🖷: ')
				.\Q\Tools\HTML::keyproperty('email', 'a', $contact, 4);
			if (isset($contact['hoursAvailable'])) {
				$hours = $contact['hoursAvailable'];
				$html .= "    <span property=\"hoursAvailable\" typeof=\"OpeningHoursSpecification\">\n";
				$html .= \Q\Tools\HTML::keyproperty('dayOfWeek', 'span', $hours, 6);
				$html .= \Q\Tools\HTML::keyproperty('opens', 'time', $hours, 6);
				$html .= " - ";
				$html .= \Q\Tools\HTML::keyproperty('closes', 'time', $hours, 6);
				$html .= "    </span>\n";
			}
			if ($html != '') {
				echo "  <div class=\"corpContact\" property=\"contactPoint\" typeof=\"ContactPoint\">\n";
				echo $html;
				echo "  </div>\n";
			}
		}
		echo "</div>\n".$args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		$schema = $this->get_instance_schema();
		echo self::process_schema($schema, $instance);
		return;


		if (empty($instance)) {
			$this->get_settings($instance);
			if (empty($instance))
				$instance = $this->toDefault();
		}
		// title is currenty mandatory as an input field, because else widget.js breaks
		$this->input('title', $instance);
		$this->input('name', $instance);
		$this->input('streetAddress', $instance);
		$this->input('postalCode', $instance);
		$this->input('addressLocality', $instance);
		$this->input('addressCountry', $instance);
		$cid = 0;
		if (array_key_exists('contacts', $instance)) {
			foreach ($instance['contacts'] as $contact) {
				echo '<details><summary>Contact #'.$cid.'</summary>';
				$this->input('contactType', $contact);
				$this->input('telephone', $contact);
				$this->input('faxNumber', $contact);
				$this->input('email', $contact);
				if (is_array($contact) && array_key_exists('hoursAvailable', $contact)) {
					$hours = $contact['hoursAvailable'];
					$this->input('dayOfWeek', $hours);
					$this->input('opens', $hours, 'time');
					$this->input('closes', $hours, 'time');
				}
				$this->input('remove_'.$cid, $instance, 'remove this contact', 'checkbox');
				echo '</details>';
				++$cid;
			}
		}
		$this->input('add_'.$cid, $instance, 'add new contact', 'checkbox');
	}
	public function input($property, $array, $label = null, $type='text')
	{
		$value = '';
		if (is_array($array) && array_key_exists($property, $array))
			$value = $array[$property];
		else {
			if ($type == 'number')
				$value = 0;
		}
		if (is_null($label))
			$label = $property;
		$property = self::meta_escape($property);
		echo \Q\Tools\HTML::inputfield(
			$this->get_field_id($property),
			$this->get_field_name($property),
			$label,
			esc_attr($value),
			$type
		);
	}
	/**
	 * Sanitize widget form values as they are saved.
	 * @see WP_Widget::update()
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$new_instance = self::fold($new_instance);
		foreach ($new_instance as $k => $v) {
			error_log( $k . ' => ' . $v );
		}
		$instance = array();
		self::keytransfer('title', $new_instance, $instance);
		self::keytransfer('name', $new_instance, $instance);
		self::keytransfer('streetAddress', $new_instance, $instance);
		self::keytransfer('postalCode', $new_instance, $instance);
		self::keytransfer('addressLocality', $new_instance, $instance);
		self::keytransfer('addressCountry', $new_instance, $instance);



		foreach ($new_instance['contacts'] = [] as $cid => $contact) {
			error_log( 'contact ' . $cid );
			error_log('remove is '.self::getValue('remove_'.$cid, $new_instance, 0));
			continue;

			if (self::getValue('remove_'.$cid, $new_instance, 0) !== 0)
				continue;
			$ct = [];
			$ct['contactType'] = self::strip('contactType', $contact);
			$ct['telephone']   = self::strip('telephone', $contact);
			$ct['faxNumber']   = self::strip('faxNumber', $contact);
			$ct['email']       = self::strip('email', $contact);
			if (is_array($contact) && (array_key_exists('dayOfWeek', $contact)
					|| array_key_exists('opens', $contact)
					|| array_key_exists('closes', $contact))) {
				$hours = array();
				$hours['dayOfWeek'] = self::strip('dayOfWeek', $contact);
				$hours['opens']     = self::strip('opens', $contact);
				$hours['closes']    = self::strip('closes', $contact);
				$ct['hoursAvailable'] = $hours;
			}
			$instance['contacts'][] = $ct;
		}
		$instance['contacts'] = [
			[
				'telephone' => '+49 30 820099-0',
				'faxNumber' => '+49 30 820099-29',
				'email' => 'info@hoffmann-dental.com'
			], [
				'contactType' => 'Werksverkauf / Factory sales',
				'telephone' => '+49 30 820099-15',
				'hoursAvailable' => [
					'dayOfWeek' => 'Mo-Fr',
					'opens' => '08:00',
					'closes' => '16:00'
				]
			]
		];
		// save into options on customizer
		if ($this->is_preview())
			$this->save_settings($instance);
		return $instance;
	}
	public static function strip($key, $array) {
		if (array_key_exists($key, $array))
			return strip_tags($array[$key]);
		return '';
	}
	public static function keytransfer($key, &$array_src, &$array_dst) {
		if (!is_array($array_src))
			return;
		if (!array_key_exists($key, $array_src)) {
			unset($array_dst[$key]);
			return;
		}
		$array_dst[$key] = strip_tags($array_src[$key]);
	}
	public static function getValue($key, $array, $default = null)
	{
		if (is_array($array) && array_key_exists($key, $array))
			return $array[$key];
		return $default;
	}
	public static function meta_escape($key) {
		if (str_starts_with($key, self::METAPREFIX))
			return self::METAPREFIX.$key;
		return $key;
	}
	public static function fold($instance_src) {
		$instance_dst = [];
		foreach ($instance_src as $k => $v) {
			if (!str_starts_with($k, self::METAPREFIX)) {
				$instance_dst[$k] = $v;
				continue;
			}
			$nk = substr($k, strlen(self::METAPREFIX));
			if (str_starts_with($nk, self::METAPREFIX)) {
				// escaped METAPREFIX
				$instance_dst[$nk] = $v;
				continue;
			}
			list($cmd, $rest) = preg_split('.', $nk, 2);
			switch ($cmd) {
			}
		}
		return $instance_dst;
	}

	public static function process_schema($schema, $instance, $mode = 'text', $defs = [], $key = '') {
		if (array_key_exists('$defs', $schema))
			$defs = array_merge($defs, $schema['$defs']);
		if (array_key_exists('$ref', $schema)) {
			$ref = $schema['$ref'];
			if (str_starts_with($ref, '#/$defs/')) {
				$defname = substr($ref, strlen('#/$defs/'));
				if (array_key_exists($defname, $defs)) {
					$schema = $defs[$defname];
				}
			}
		}
		if (is_null($instance)) {
			if (array_key_exists('default', $schema))
				$instance = $schema['default'];
			else
				$instance = '<p>missing schema property <i>'.$key.'</i>.</p>';
		}

		if (!array_key_exists('type', $schema))
			return;
		$content = '';
		switch ($schema['type']) {
		case 'object':
			foreach ($schema['properties'] as $property => $value) {
				$content .= self::process_schema($value, $instance[$property] ?? null, $mode, $defs, $property);
			}
			break;
		case 'string':
			$content = $instance;
			break;
		}
		$tag = 'span';
		if (array_key_exists('tag', $schema))
			$tag = $schema['tag'];
		$attrs = [];
		$attrs['property'] = $key;

		return \Q\Tools\HTML::to_tag($tag, $content, 0, $attrs);
	}

}