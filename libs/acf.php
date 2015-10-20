<?php
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point( $path ) {

    $path = __DIR__ . '/custom-fields';

    return $path;

}


add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point( $paths ) {

    unset($paths[0]);

    $paths[] = __DIR__ . '/custom-fields';

    return $paths;

}
