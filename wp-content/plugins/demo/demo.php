<?php

/**
 * Plugin Name: Search and Stay Wordpress Test
 */


function dd() {
  foreach(func_get_args() as $data) {
    echo '<pre>'. print_r($data, true) .'</pre>';
  }
}

foreach(['wp_enqueue_scripts', 'admin_enqueue_scripts'] as $action) {
	add_action($action, function() {
    wp_enqueue_script('vue', '//unpkg.com/vue@3/dist/vue.global.js');
    wp_enqueue_style('vuetify', '//cdn.jsdelivr.net/npm/vuetify@3.0.1/dist/vuetify.min.css');
    wp_enqueue_script('vuetify', '//cdn.jsdelivr.net/npm/vuetify@3.0.1/dist/vuetify.min.js');
    wp_enqueue_script('axios', '//cdn.jsdelivr.net/npm/axios/dist/axios.min.js');
    wp_enqueue_style('mdi', '//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css');
	});
}

register_activation_hook(__FILE__, function() {
  foreach(Demo::settingsDefault() as $key => $value) {
    if (! get_option($key)) {
      update_option($key, $value);
    }
  }
});


include __DIR__ . '/classes/Demo.php';
include __DIR__ . '/endpoints.php';
include __DIR__ . '/admin/settings.php';
