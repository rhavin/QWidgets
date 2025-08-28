<?php

namespace Q\WPWidgets;

class Plugin
{
    public function __construct(string $file)
    {
        (new GitHubUpdater($file))
            ->setBranch('main')
            ->setPluginIcon('assets/icon.png')
            ->setPluginBannerSmall('assets/banner-772x250.jpg')
            ->setPluginBannerLarge('assets/banner-1544x500.jpg')
            ->setChangelog('CHANGELOG.md')
            ->enableSetting()
            ->add();
    }
}

add_action( 'widgets_init', __NAMESPACE__ .'\\register_widgets' );

function register_widgets() {
    try {
      register_widget(__NAMESPACE__ .'\\Company');
    } catch (\Exception $e) {
        error_log($e->getMessage());
    }
}

function qwidget($atts) {
	global $wp_widget_factory;
	$parameters = shortcode_atts(array(
		'name' => false,
		'class' => false,
		'instance' => array(),
		'id' => false
	), $atts);
	$name = esc_html($parameters->$name);
	if (!is_a($wp_widget_factory->widgets[$name], 'WP_Widget')) {
		$wp_class = 'WP_Widget_'.ucwords(strtolower($parameters->$class));
		if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')) {
			$message = sprintf(__('[%s]: Widget class not found.'), $class);
			return '<p>'. $message .'</p>';
		} else {
			$class = $wp_class;
		}
	}
	if (!$parameters->id)
		$parameters->id = 'QPWGT' . rand(1000,9999);
	ob_start();
	the_widget($name, $parameters->instance,
		array(
			'widget_id' => $parameters->id,
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		)
	);
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
// tag, function_callback
add_shortcode('qwidget', 'qwidget');

?>