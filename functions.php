<?php
/**
 * Enques the scripts appropriately for different scenarios.
 * @return null
 */
function avada_child_scripts() {
	if ( ! is_admin() && ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) {
		$theme_info = wp_get_theme();
		wp_enqueue_style( 'avada-child-stylesheet', get_template_directory_uri() . '/style.css', array(), $theme_info->get( 'Version' ) );
    if ( is_single() ) {
      wp_enqueue_script( 'wodstar-child-script', get_stylesheet_directory_uri() . '/js/page-single.js', array(), '0.1.0', 1 );
    }
	}
}

add_action('wp_enqueue_scripts', 'avada_child_scripts');

/**
 * Overrides default 'wp_head' behavior in the Avada theme
 * @return null 
 */
function eff_with_avada_menu() {
  remove_action( 'wp_head', 'create_avada_menu' );
  add_action('wp_head', 'create_wodstar_menu');
	
  // 'cause avada wraps this in a conditional statement, we're repeating it here...
  function default_menu_fallback( $args ) {
		return $null;
	}
}

// this is wrapped in after_setup_theme because it has to happen later than when the functions.php file runs...
add_action('after_setup_theme', 'eff_with_avada_menu');

/**
 * Inserts a DOM element into the tail end of the navigation menu.
 * @return null 
 */
function create_wodstar_menu() {
  global $main_menu;
  $main_menu = wp_nav_menu(array(
    'theme_location'  => 'main_navigation',
    'depth'        => 5,
    'container'     => false,
    'items_wrap'     => '%3$s' . wodstar_login_logout_link_li(),
    'menu_class'    => 'nav fusion-navbar-nav',
    'fallback_cb'     => 'default_menu_fallback',
    'echo'         => false
  ));
}

/**
 * Provides the DOM element to be inserted by create_wodstar_menu
 * @return String A li element appropriate for insertion into the navigation menu.
 */
function wodstar_login_logout_link_li(){  
  global $current_user;

  if (is_user_logged_in()) {
    get_currentuserinfo();
    $wrapper_start = '<li class="menu-item"><a href="#">' . $current_user->user_login . '</a><ul class="sub-menu">';
    $wrapper_end = '</ul></li>';
    $link_logout = '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="' . esc_url(wp_logout_url()) . '">Logout</a></li>';
    $link_profile = '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="/member-profile/">Profile</a></li>';
    return $wrapper_start . $link_logout . $link_profile . $wrapper_end;
  }
  else {
    $link = '<a href="/login-register">Login / Register</a>';
    return '<li class="menu-item menu-item-type-custom menu-item-object-custom">' . $link . '</li>';
  }

}

/**
 * Custom logged out location
 * @return null
 */
function go_home(){
  wp_redirect( home_url() . "?logged-out=1"); // querystring for future 'flash' functionality...
  exit();
}

add_action('wp_logout','go_home');

/**
 * Provides a login form for 'wodstar_login' shortcode
 * @return [type] [description]
 */
function wodstar_s2member_pro_login_widget() {
  echo wp_login_form();
}

add_shortcode( 'wodstar_login', 'wodstar_s2member_pro_login_widget' );

/**
 * don't display admin bar for simple users...
 */
if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}

/**
 * Direct SQL Query looking up all posts in a given category, saving time in the loop.
 * @param  Array $args An array of category IDs to be looked up
 * @return Array       Tags related to all the posts in the queried category
 */
function wodstar_get_category_tags($args) {
  global $wpdb;
  $tags = $wpdb->get_results
  ("
    SELECT DISTINCT terms2.term_id as tag_id, terms2.name as tag_name, null as tag_link
    FROM
      wp_posts as p1
      LEFT JOIN wp_term_relationships as r1 ON p1.ID = r1.object_ID
      LEFT JOIN wp_term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
      LEFT JOIN wp_terms as terms1 ON t1.term_id = terms1.term_id,

      wp_posts as p2
      LEFT JOIN wp_term_relationships as r2 ON p2.ID = r2.object_ID
      LEFT JOIN wp_term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
      LEFT JOIN wp_terms as terms2 ON t2.term_id = terms2.term_id
    WHERE
      t1.taxonomy = 'category' AND p1.post_status = 'publish' AND terms1.term_id IN (". $args .") AND
      t2.taxonomy = 'post_tag' AND p2.post_status = 'publish'
      AND p1.ID = p2.ID
    ORDER by tag_name
  ");
  $count = 0;
  foreach ($tags as $tag) {
    $tags[$count]->tag_link = get_tag_link($tag->tag_id);
    $count++;
  }
  return $tags;
}

/**
 * Executed after the main page header is rendered on all pages. See the hook in framework/custom_functions.php @line 1078
 * @param  String $title The title of the page (category in this case)
 * @return null          This function echos a compnent to the DOM
 */
function wodstar_search_row($title) {
  if ( is_category() ) {
    $category = get_the_category();
    $ID = $category[0]->cat_ID;
    $tags = wodstar_get_category_tags($ID);
    $tags_select .= "<select class='form-control' id='wodstar-filter-input'>";
    $tags_select .= "<option value=''>Select a filter...</option>";
    foreach ($tags as $tag) {
      $tag_name = $tag->tag_name;
      $tag_slug = $tag->tag_link;
      $tags_select .= "<option value='" . $tag_slug . "'>" . $tag_name . "</option>";
    }
    $tags_select .= "</select>";
    ?>
    <div class="row wodstar-search-row">
      <div class="container">
        <div class="col-sm-12">
          <form action="#" class="form-inline pull-left">
            <div class="form-group">
              <!-- <label for="wodstar-filter-input">Filter <?php echo $title ?></label> -->
              <label for="wodstar-filter-input"></label>
              <?php echo $tags_select ?>
            </div>
          </form>
          <form class="form-inline pull-right">
            <div class="form-group">
              <!-- <label for="wodstar-search-input">Search <?php echo $title ?></label> -->
              <label for="wodstar-search-input"></label>
              <input type="text" class="form-control" placeholder="Search by keyword..." id="wodstar-search-input">
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php
    wp_enqueue_script( 'wodstar-child-script', get_stylesheet_directory_uri() . '/js/search-row.js', array(), '0.1.0', 1 );
  }
}

