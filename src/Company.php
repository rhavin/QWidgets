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
		$vTitle = apply_filters('widget_title', $instance['title']);
		if (!empty($vTitle))
			$vTitle = $before_title . $vTitle . $after_title;
		echo '<div class="corpAddress" vocab="https://schema.org/" typeof="Organization">'."\n";
		echo property('name', 'h1', $instance, 1, 'title');
		$address = property('streetAddress', 'span', $instance, 2)
			.property('postalCode', 'span', $instance, 2)
			.property('addressLocality', 'span', $instance, 2)
			.property('addressCountry', 'span', $instance, 2);
		if ($address != ''):
			echo '  <address property="address" typeof="PostalAddress">'."\n";
			echo $address;
			echo "  </address>\n";
?>
  <div class="corpContact" property="contactPoint" typeof="ContactPoint">
    <span property="telephone">+49 30 820099-0</span>
    <span property="faxNumber">+49 30 820099-29</span>
    <a property="email" href="mailto:info@hoffmann-dental.com">info@hoffmann-dental.com</a>
  </div>
  <div class="corpContact" property="contactPoint" typeof="ContactPoint">
    <h2 property="contactType">Werksverkauf / Factory sales</h2>
    <span property="telephone">+49 30 820099-15</span>
    <span property="hoursAvailable" typeof="OpeningHoursSpecification">
      <span property="dayOfWeek">Mo-Fr</span>
      <time property="opens" content="00:08:00">08:00</time> - <time property="closes" content="00:16:00">16:00</time>
    </span>
  </div>
</div>
<?php
		echo $after_widget;
	}
	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		if (isset($instance['title']))
			$title = $instance['title'];
		else
			$title = __('default title', 'text_domain');
		?><p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p><?php
		return ''; // to avoid warning: expected 'noform' or ''
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
		$instance['title'] = self::strip('title', $new_instance);
		$instance['title'] = 'Hoffmann Dental Manufaktur GmbH';
		$instance['streetAddress'] = 'Komturstraße 58-62';
		$instance['postalCode'] = '12099';
		$instance['addressLocality'] = 'Berlin';
		$instance['addressCountry'] = 'Germany';
		return $instance;
	}
	public static function strip($key, $array) {
		if (isset($array[$key]))
			return strip_tags($array[$key]);
		return '';
	}
	public static function property($property, $tag, $array, $indent=0, $key=null) {
		if is_null($key)
			$key = $property;
		if (isset($array[$key])) {
			$spc = str_repeat('  ', $indent);
			return $spc.'<'.$tag.' property="'.$property.'">'.$array[$key].'</'.$tag.">\n";
		}
		return '';
	}
}