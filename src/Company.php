<?php
namespace Q\WPWidgets;
/**
 * Adds Company widget.
 */
class Company extends \WP_Widget {
	public function __construct() {
		parent::__construct(
			'company_widget', // Base ID
			'QCompany', // Name
			array( 'description' => __( 'Company Contact', 'text_domain' ) )
		);
	}
	/**
	 * Front-end display of widget.
	 * @see WP_Widget::widget()
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		extract($args);
		echo $before_widget;
		echo '<div class="corpAddress" vocab="https://schema.org/" typeof="Organization">'."\n";
		echo self::keyproperty('name', 'h1', $instance, 2);
		$address = self::keyproperty('streetAddress', 'span', $instance, 2).', '
			.self::keyproperty('postalCode', 'span', $instance, 2).' '
			.self::keyproperty('addressLocality', 'span', $instance, 2).', '
			.self::keyproperty('addressCountry', 'span', $instance, 2);
		if ($address != '') {
			echo '  <address property="address" typeof="PostalAddress">'."\n";
			echo $address;
			echo "  </address>\n";
		}
		foreach ($instance['contacts'] as $contact)  {
			$html = self::keyproperty('contactType', 'h2', $contact, 4)
				.self::keyproperty('telephone', 'span', $contact, 4)
				.self::keyproperty('faxNumber', 'span', $contact, 4)
				.self::keyproperty('email', 'a', $contact, 4);
			if (isset($contact['hoursAvailable'])) {
				$hours = $contact['hoursAvailable'];
				$html .= "    <span property=\"hoursAvailable\" typeof=\"OpeningHoursSpecification\">\n";
				$html .= self::keyproperty('dayOfWeek', 'span', $hours, 6);
				$html .= self::keyproperty('opens', 'time', $hours, 6);
				$html .= " - ";
				$html .= self::keyproperty('closes', 'time', $hours, 6);
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
		return ''; // to avoid warning: expected 'noform' or ''
	}
	public function input($property, $array)
	{
		if (isset($array[$property]))
			$value = $array[$property];
		else
			$value = '';
		echo self::inputfield(
			$this->get_field_id($property),
			$this->get_field_name($property),
			$property,
			esc_attr($value)
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
	public static function keyproperty($property, $tag, $array, $indent=0, $key=null) {
		if (is_null($key))
			$key = $property;
		if (!isset($array[$key]))
		    return '';
		$content = $array[$key];
		$attr = [];
		if ($property == 'email')
		    $attr['href'] = 'mailto:'.$content;
		if ($tag == 'time')
		    $attr['content'] = self::timeparse($content);
		return self::property($property, $tag, $content, $indent, $attr);
	}
	public static function property($property, $tag, $content, $indent=0, $attr=null) {
		$spc = str_repeat('  ', $indent);
        return $spc.'<'.$tag.' property="'.$property.' '.self::attrlist($attr).'">'.$content.'</'.$tag.">\n";
	}

	public static function timeparse($time) {
		$parts = explode(':', $time);
		if (count($parts) == 2)
			return '00:'.$parts[0].':'.$parts[1];
		if (count($parts) == 3)
			return $parts[0].':'.$parts[1].':'.$parts[2];
		return '00:00:00';
	}	
	public static function attrlist($attr) {
		if (is_null($attr))
			return '';
		$list = '';
		foreach ($attr as $k => $v) {
			$list .= ' '.$k.'="'.$v.'"';
		}
		return $list;
	}
	public static function inputfield($id, $name, $label, $value, $type='text', $size=100) {
		return '<label for="'.$name.'">'.$label.':</label>'
			.'<input class="widefat" id="'.$id.'" name="'.$name.'" type="'
			.$type.'" value="'.$value.'" />';

	}
}