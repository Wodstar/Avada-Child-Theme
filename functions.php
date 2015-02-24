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

// this is wrapped in after_setup_theme because it has to happen later than when the functions.php file runs...
add_action('after_setup_theme', 'eff_with_avada_menu');
function eff_with_avada_menu() {
  remove_action( 'wp_head', 'create_avada_menu' );
  add_action('wp_head', 'create_wodstar_menu');
	
  // 'cause avada wraps this in a conditional statement, we're repeating it here...
  function default_menu_fallback( $args ) {
		return $null;
	}
}

function create_wodstar_menu() {
  global $main_menu;
  $main_menu = wp_nav_menu(array(
      'theme_location'  => 'main_navigation',
      'depth'        => 5,
      'container'     => false,
      'items_wrap'     => '%3$s' . wodstar_login_logout_link_li(),
      'menu_class'    => 'nav fusion-navbar-nav',
      'fallback_cb'     => 'default_menu_fallback',
      // 'fallback_cb'     => 'FusionCoreFrontendWalker::fallback',
      // 'walker'      => new FusionCoreFrontendWalker(),
      'echo'         => false
    ));
}

function wodstar_login_logout_link_li(){  
  if (is_user_logged_in()) {
    $link = '<a href="' . esc_url(wp_logout_url()) . '">Logout</a>';
  }
  else {
    $link = '<a href="' . esc_url(wp_login_url()) . '">Login / Register</a>';
  }
  return '<li class="menu-item menu-item-type-custom menu-item-object-custom">' . $link . '</li>';
}

// custom logged out location...
add_action('wp_logout','go_home');
function go_home(){
  wp_redirect( home_url() . "?logged-out=1"); // querystring for future 'flash' functionality...
  exit();
}

function wodstar_s2member_pro_login_widget() {
  echo s2member_pro_login_widget();
}
add_shortcode( 'wodstar_login', 'wodstar_s2member_pro_login_widget' );

// don't display admin bar for simple users...
if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}

//[phpinfo]
function phpinfo_shortcode( $atts ){
  return phpinfo();
}
add_shortcode( 'phpinfo', 'phpinfo_shortcode' );



