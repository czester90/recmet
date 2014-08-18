<?php

return array(
  'service_manager' => array(
    'factories' => array(
      'less_filter' => function($sm) {
        return new \Assetic\Filter\LessFilter('/usr/local/bin/node', array(
          '/usr/local/share/npm/lib/node_modules/',
          '/usr/local/lib/node_modules/',
                ));
      },
    )
  ),
  'asset_manager' => array(
    'resolver_configs' => array(
      'map' => array(
      ),
      'collections' => array(
        'css/styles.css' => array(
          //'css/bootstrap.min.css',
          'css/bootstrap-responsive.min.css',
          'css/bootstrap-select.css',
          'css/vendor/slider.css',
          'css/jquery.mCustomScrollbar.css',
          //views Company
          'css/views/company/price-table.less',
          
          'css/style.css',
        ),
        'js/scripts.js' => array(
          'js/jquery.min.js',
          'js/bootstrap.min.js',
          'js/html5shiv.js',
          'js/vendor/bootstrap-select.min.js',
          'js/vendor/bootstrap-slider.js',
          'js/vendor/gmap3.min.js',
          'js/vendor/jquery.flexslider-min.js',
          'js/vendor/jquery.mCustomScrollbar.min.js',
          'js/vendor/jquery.mousewheel.min.js',
          'js/vendor/jquery.placeholder.min.js',
          'js/vendor/modernizr-2.6.2-respond-1.1.0.min.js',
          'js/vendor/tinynav.min.js',
          'js/jquery.prettyPhoto.js',
          'js/main.js',
        ),
      ),
      'paths' => array(
        __DIR__ . '/../../public',
      ),
    ),
    'filters' => array(
      'css/styles.css' => array(
        array(
          'service' => 'less_filter'
        )
      ),
    ),
  ),
);
