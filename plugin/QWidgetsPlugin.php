<?php

namespace Q\Widgets;

class QWidgetsPlugin
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
        register_widget( '\\widgets\\Company\\Company' );
    } catch (\Exception $e) {
        error_log($e->getMessage());
    }
}

?>