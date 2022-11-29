<?php

class Demo
{
  static function settingsDefault()
  {
    return [
      'demo_api_url' => 'https://sys-dev.searchandstay.com/api/vendor/wp_status',
      'demo_api_token' => 'Search_and_Stay',
    ];
  }

  static function settings()
  {
    $settings = [];
    foreach(self::settingsDefault() as $key => $value) {
      $settings[ $key ] = get_option($key, false);
    }
    return $settings;
  }

  static function settingsSave($data)
  {
    $default = self::settingsDefault();
    foreach($data as $key => $value) {
      if (! isset($default[ $key ])) continue;
      update_option($key, $value);
    }
    return self::settings();
  }

  static function endpointUrl($path)
  {
    $path = ltrim($path, '/');
    $url = site_url("/wp-json/demo/v1/{$path}");
    $url = str_replace('_wpnonce', '_wpnonce='.wp_create_nonce('wp_rest'), $url);
    return $url;
  }

  static function endpoint($method, $path, $callback)
  {
    add_action('rest_api_init', function() use($method, $path, $callback) {
      register_rest_route('demo/v1', $path, [
        'methods' => strtoupper($method),
        'permission_callback' => '__return_true',
        'callback' => function() use($callback) {
          $request = json_decode(file_get_contents('php://input'), true);
          return call_user_func($callback, $request);
        },
      ]);
    });
  }
}
