<?php
function avada_child_scripts() {
	if ( ! is_admin() && ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) {
		$theme_info = wp_get_theme();
		wp_enqueue_style( 'avada-child-stylesheet', get_template_directory_uri() . '/style.css', array(), $theme_info->get( 'Version' ) );
    if ( is_single() ) {
      wp_enqueue_script( 'wodstar-child-script', get_stylesheet_directory_uri() . '/js/main.js', array(), '0.1.0', 1 );
    }
    wp_enqueue_script( 'wodstar-child-script', get_stylesheet_directory_uri() . '/js/wodstar.js', array(), '0.1.0', 1 );
	}
}
add_action('wp_enqueue_scripts', 'avada_child_scripts');


function head_hook() {
  echo "<div class=\"modal fade\" id=\"wodstarModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">".
  "<div class=\"modal-dialog\">".
  "  <div class=\"modal-content\"></div>".
  "</div>".
  "</div>";
}

add_action('wp_head', 'head_hook');