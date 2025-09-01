<?php
namespace Q\WPWidgets;

class Plugin
{
    public function __construct(string $file)
    {
        (new \Q\Tools\GitHubUpdater($file))
            ->setBranch('main')
            ->setPluginIcon('assets/icon.png')
            ->setPluginBannerSmall('assets/banner-772x250.jpg')
            ->setPluginBannerLarge('assets/banner-1544x500.jpg')
            ->setChangelog('CHANGELOG.md')
            ->enableSetting()
            ->add();

		// tag, function_callback
		add_shortcode('qwidget', __NAMESPACE__ .'\\qwidget');
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

function qwidget($atts, $content = null) {
	global $wp_widget_factory;
	$parameters = shortcode_atts(array(
		'name' => false,
		'class' => false,
		'instance' => array(),
		'id' => false
	), $atts,'qwidget');
	$name = esc_html($parameters['name']);
	$wgclass = esc_html($parameters['class']);
	if (!$name && !$wgclass) {
		return '<p>'. __('QWidget: Widget name ['.$name.'] or class ['.$wgclass.'] not specified.') .'</p>';
	}
	if ($wgclass)
		$name = $wgclass;
	else {
		// find class by name
		foreach ($wp_widget_factory->widgets as $class => $obj) {
			if ($obj->name !== $name)
				continue;
			$name = $class;
			break;
		}
	}
	if (!$name) {
		$message = sprintf(__('[%s]: Widget class not found.'), $wgclass);
		return '<p>'. $message .'</p>';
	}
	if (!$parameters['id'])
		$parameters['id'] = 'QPWGT' . rand(1000,9999);
	ob_start();
	the_widget($name, $parameters['instance'],
		array(
			'widget_id' => $parameters['id'],
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

?>