<?php
namespace Q\WPWidgets;

/**
 * Adds Company widget.
 */
class Company extends \WP_Widget {
	public function __construct() {
		$id_base = 'company_widget';
		$name    = 'QCompany';
		$wid_options = array(
			'classname'   => 'company_widget',
			'description' => __( 'Company Contact', 'text_domain' )
		);
		parent::__construct($id_base, $name, $wid_options);
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
		extract($args);
		echo $before_widget;
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
		echo "</div>\n";
		echo $after_widget;
	}

	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		$this->input('name', $instance);
		$this->input('streetAddress', $instance);
		$this->input('postalCode', $instance);
		$this->input('addressLocality', $instance);
		$this->input('addressCountry', $instance);
		$this->input('addressCountry', $instance);
		$cid = 0;
		foreach ($instance['contacts'] as $contact)  {
			echo '<hr><strong>Contact #'.($cid).'</strong><br>';
			$this->input('contactType', $contact);
			$this->input('telephone', $contact);
			$this->input('faxNumber', $contact);
			$this->input('email', $contact);
			if (isset($contact['hoursAvailable'])) {
				$hours = $contact['hoursAvailable'];
				$this->input('dayOfWeek', $hours);
				$this->input('opens', $hours, 'time');
				$this->input('closes', $hours, 'time');
			}
			++$cid;
		}

		return ''; // to avoid warning: expected 'noform' or ''
	}
	public function input($property, $array, $type='text')
	{
		if (isset($array[$property]))
			$value = $array[$property];
		else {
			if $type == 'number'
				$value = 0;
			else
				$value = '';
		}
		echo \Q\Tools\HTML::inputfield(
			$this->get_field_id($property),
			$this->get_field_name($property),
			$property,
			esc_attr($value),
			$type
		);
	}
	public function toDefault() {
		$instance = array();
		$instance['title'] = 'QCormpany';
		$instance['name'] = 'Acme GmbH';
		$instance['streetAddress'] = 'Am Acker 1-15';
		$instance['postalCode'] = '12345';
		$instance['addressLocality'] = 'Berlin';
		$instance['addressCountry'] = 'Germany';
		$instance['contacts'] = [
			'contactType' => 'General Manager',
			'telephone' => '+49 30 1234567-0',
			'faxNumber' => '+49 30 1234567-9',
			'email' => 'noreply@example.com',
			'hoursAvailable' => [
				'dayOfWeek' => 'Mo-Fr',
				'opens' => '08:00',
				'closes' => '16:00'
			]
		];
		return $instance;
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
		$instance = array();
		$instance['title'] = 'QCormpany';
		$instance['name'] = 'Hoffmann Dental Manufaktur GmbH';
		$instance['streetAddress'] = 'Komturstraße 58-62';
		$instance['postalCode'] = self::strip('postalCode', $new_instance);
		$instance['addressLocality'] = self::strip('addressLocality', $new_instance);
		$instance['addressCountry'] = 'Germany';
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
		return $instance;
	}
	public static function strip($key, $array) {
		if (isset($array[$key]))
			return strip_tags($array[$key]);
		return '';
	}
	public static function getValue($key, $array, $default = null)
	{	if (isset($array[$key]))
			return $array[$key];
		return $default;
	}
}