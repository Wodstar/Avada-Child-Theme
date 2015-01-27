<?php
function avada_child_scripts() {
	if ( ! is_admin() && ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) {
		$theme_info = wp_get_theme();
		wp_enqueue_style( 'avada-child-stylesheet', get_template_directory_uri() . '/style.css', array(), $theme_info->get( 'Version' ) );
	}
}
add_action('wp_enqueue_scripts', 'avada_child_scripts');

/*
wodstar_page_title_bar
Supplements the existing Avada function of the same name.
This is for presenting an alternative header for Wodstar posts that warrant them.
header.php refers to this function.
 */
if( ! function_exists( 'wodstar_page_title_bar' ) ) {
function wodstar_page_title_bar( $title, $subtitle, $secondary_content ) {
  global $smof_data;

  $content_type = 'none';
  $extra_classes = '';

  if( strpos( $secondary_content, 'searchform' ) !== false ) {
    $content_type = 'search';
  } elseif ( $secondary_content != '' ) {
    $content_type = 'breadcrumbs';
  }

  if( metadata_exists( 'post', get_queried_object_id(), 'pyre_page_title_text_alignment' ) && get_post_meta(get_queried_object_id(), 'pyre_page_title_text_alignment', true) != 'default' ) {
    $alignment = get_post_meta( get_queried_object_id(), 'pyre_page_title_text_alignment', true );
  } elseif( $smof_data['page_title_alignment'] ) {
    $alignment = $smof_data['page_title_alignment'];
  }

  if( $alignment == 'right' && ! is_rtl() ) {
    $extra_classes .= ' rtl';
  }

  /*
  This is to determine if the post is a Wod or a Movement (which require special layouts).
  Wods have movements, etc.
   */
  $categories = get_the_category();
  $category = strtolower($categories[0] -> cat_name);
  $is_movement = $category === "movements";
  $is_wod = $category === "wods";
  $is_article = $category === "articles";
  $is_full_header = $is_article === true || $is_wod === true || $is_movement === true && !is_archive();
?>
  <div class="page-title-container page-title-container-<?php echo $content_type; ?> page-title-<?php echo $alignment; ?> <?php if ($is_full_header) echo "full-header"; ?> <?php echo $extra_classes; ?>">
    <div class="page-title">
      <div class="page-title-wrapper">
        <div class="page-title-captions">
          <?php if( $title ): ?>
            <h1<?php if( ! $smof_data['disable_date_rich_snippet_pages'] ) { echo ' class="entry-title"'; } ?>><?php echo $title; ?></h1>
            <?php if( $subtitle ): ?>
            <h3><?php echo $subtitle; ?></h3>
            <?php endif; ?>
          <?php endif; ?>
          <?php
          if( $alignment == 'center') {
            echo $secondary_content;
          }
        ?>
        </div>
        <?php
        if( $alignment != 'center') {
          echo $secondary_content;
        }
        ?>
      </div>
    </div>
  </div>
<?php }
}

/*
wodstar_current_page_title_bar
Supplements the existing Avada function of the same name.
This is for presenting an alternative header for Wodstar posts that warrant them.
header.php refers to this function.
 */
if( ! function_exists( 'wodstar_current_page_title_bar' ) ) {
  function wodstar_current_page_title_bar( $post_id ) {
    global $smof_data;

    ob_start();
    if( $smof_data['breadcrumb'] ) {
      if ( ( $smof_data['page_title_bar_bs'] == 'Breadcrumbs' && get_post_meta($post_id, 'pyre_page_title_breadcrumbs_search_bar', true) == 'breadcrumbs' ) ||
        ( $smof_data['page_title_bar_bs'] != 'Breadcrumbs' && get_post_meta($post_id, 'pyre_page_title_breadcrumbs_search_bar', true) == 'breadcrumbs' ) ||
        ( $smof_data['page_title_bar_bs'] == 'Breadcrumbs' && ( get_post_meta($post_id, 'pyre_page_title_breadcrumbs_search_bar', true) == 'default' || get_post_meta($post_id, 'pyre_page_title_breadcrumbs_search_bar', true) == '' ) ) ) {
          if( ( class_exists( 'Woocommerce' ) && is_woocommerce() ) || ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) {
            woocommerce_breadcrumb(array(
              'wrap_before' => '<ul class="breadcrumbs">',
              'wrap_after' => '</ul>',
              'before' => '<li>',
              'after' => '</li>',
              'delimiter' => ''
            ));
          } else if( class_exists( 'bbPress' ) && is_bbpress() ) {
            bbp_breadcrumb( array ( 'before' => '<ul class="breadcrumbs">', 'after' => '</ul>', 'sep' => ' ', 'crumb_before' => '<li>', 'crumb_after' => '</li>', 'home_text' => __('Home', 'Avada')) );
          } else {
            themefusion_breadcrumb();
          }
      } else if( ( $smof_data['page_title_bar_bs'] == 'Search Box' && get_post_meta($post_id, 'pyre_page_title_breadcrumbs_search_bar', true) == 'searchbar' ) ||
        ( $smof_data['page_title_bar_bs'] != 'Search Box' && get_post_meta($post_id, 'pyre_page_title_breadcrumbs_search_bar', true) == 'searchbar' ) ||
        ( $smof_data['page_title_bar_bs'] == 'Search Box' && ( get_post_meta($post_id, 'pyre_page_title_breadcrumbs_search_bar', true) == 'default' || get_post_meta($post_id, 'pyre_page_title_breadcrumbs_search_bar', true) == '' ) ) ) {
        get_search_form();
      }
    }
    $secondary_content = ob_get_contents();
    ob_get_clean();

    $title = '';
    $subtitle = '';

    if( get_post_meta( $post_id, 'pyre_page_title_custom_text', true ) != '' ) {
      $title = get_post_meta( $post_id, 'pyre_page_title_custom_text', true );
    }

    if( get_post_meta( $post_id, 'pyre_page_title_custom_subheader', true ) != '' ) {
      $subtitle = get_post_meta( $post_id, 'pyre_page_title_custom_subheader', true );
    }

    if( ! $title ) {
      $title = get_the_title();

      if( is_home() ) {
        $title = $smof_data['blog_title'];
      }

      if( is_search() ) {
        $title = __('Search results for: ', 'Avada') . get_search_query();
      }

      if( is_404() ) {
        $title = __('Error 404 Page', 'Avada');
      }

      if( ( class_exists( 'TribeEvents' ) && tribe_is_event() && ! is_single() && ! is_home() ) ||
        ( class_exists( 'TribeEvents' ) && is_events_archive() ) ||
        ( class_exists( 'TribeEvents' ) && is_events_archive() && is_404() )
      ) { 
        $title = tribe_get_events_title();
        
      }

      if( is_archive() && 
        ! is_bbpress()
      ) {
        if ( is_day() ) {
          $title = __( 'Daily Archives:', 'Avada' ) . '<span> ' . get_the_date() . '</span>';
        } else if ( is_month() ) {
          $title = __( 'Monthly Archives:', 'Avada' ) . '<span> ' . get_the_date( _x( 'F Y', 'monthly archives date format', 'Avada' ) ) . '</span>';
        } elseif ( is_year() ) {
          $title = __( 'Yearly Archives:', 'Avada' ) . '<span> ' . get_the_date( _x( 'Y', 'yearly archives date format', 'Avada' ) ) . '</span>';
        } elseif ( is_author() ) {
          $curauth = get_user_by( 'id', get_query_var( 'author' ) );
          $title = $curauth->nickname;
        } elseif( is_post_type_archive() ) {        
          $title = post_type_archive_title( '', false );
          
          $sermon_settings = get_option('wpfc_options');
          if( is_array( $sermon_settings ) ) {
            $title = $sermon_settings['archive_title'];
          }       
        } else {
          $title = single_cat_title( '', false );
        }
      }

      if( class_exists( 'Woocommerce' ) && is_woocommerce() && ( is_product() || is_shop() ) && ! is_search() ) {
        if( ! is_product() ) {
          $title = woocommerce_page_title( false );
        }
      }
    }

    if ( ! $subtitle ) {
      if( is_home() ) {
        $subtitle = $smof_data['blog_subtitle'];
      }
    }

    if( ! is_archive() && ! is_search() && ! ( is_home() && ! is_front_page() ) ) {
      if( get_post_meta( $post_id, 'pyre_page_title', true ) == 'yes' ||
        ( $smof_data['page_title_bar'] && get_post_meta( $post_id, 'pyre_page_title', true ) != 'no' )
      ) {   

        if( get_post_meta( $post_id, 'pyre_page_title_text', true ) == 'no' ) {
          $title = '';
          $subtitle = '';
        }

        if( is_home() && is_front_page() && ! $smof_data['blog_show_page_title_bar'] ) {
          // do nothing
        } else {
          wodstar_page_title_bar( $title, $subtitle, $secondary_content );
        }
      }
    } else {

      if( is_home() && ! $smof_data['blog_show_page_title_bar'] ) {
        // do nothing
      } else {

        if( $smof_data['page_title_bar'] ) {
          wodstar_page_title_bar( $title, $subtitle, $secondary_content );
        }
      }
    }
  }
}
