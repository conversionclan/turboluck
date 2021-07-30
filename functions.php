<?php // Theme Functions


// Theme setup
add_action( 'after_setup_theme', 'bento_theme_setup' );

function bento_theme_setup() {

	// Features
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails', array( 'post', 'page', 'project', 'product', 'room' ) );
	add_theme_support( 'automatic-feed-links' );
	// add_theme_support( 'post-formats', array( 'aside', 'gallery', 'quote', 'link', 'image' ) );
	add_theme_support( 'custom-background', array ( 'default-color' => '#f4f4f4' ) );

	// Actions
	add_action( 'wp_enqueue_scripts', 'bento_theme_styles_scripts' );
	add_action( 'admin_enqueue_scripts', 'bento_admin_scripts' );
	add_action( 'template_redirect', 'bento_theme_adjust_content_width' );
	add_action( 'init', 'bento_page_excerpt_support' );
	add_action( 'get_header', 'bento_enable_threaded_comments' );
	add_action( 'tgmpa_register', 'bento_register_required_plugins' );
	add_action( 'widgets_init', 'bento_register_sidebars' );
	add_action( 'comment_form_defaults', 'bento_comment_form_defaults' );
	add_action( 'comment_form_default_fields', 'bento_comment_form_fields' );
	add_action( 'init', 'bento_jquerycdn' );
	add_action( 'aioseop_title', 'change_wordpress_seo_title', 5, 1 );
	add_action( 'aioseop_description', 'change_wordpress_seo_description', 5, 1 );

	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

	// Filters
	add_filter( 'excerpt_more', 'bento_custom_excerpt_more' );
	add_filter( 'comment_form_fields', 'bento_rearrange_comment_fields' );
	add_filter( 'get_the_archive_title', 'bento_cleaner_archive_titles' );
	add_filter( 'cmb2_admin_init', 'bento_metaboxes' );
	add_filter( 'wp_head', 'bento_custom_favicon' );
	add_filter( 'upload_mimes', 'tl_mime_types' );
	add_filter( 'get_the_excerpt', 'tl_custom_excerpt' );
	// add_filter( 'wp_head', 'tl_analytics_yandex' );

	remove_filter( 'term_description','wpautop' );

	// Languages
	load_theme_textdomain( 'bento', get_template_directory() . '/languages' );

	// Initialize navigation menus
	register_nav_menus(
		array(
			'primary-menu' => esc_html__( 'Primary Menu', 'bento' ),
			'footer-menu' => esc_html__( 'Footer Menu', 'bento' ),
		)
	);

	// Customizer options
	if ( file_exists( get_template_directory() . '/includes/customizer/customizer.php' ) ) {
		require_once( get_template_directory() . '/includes/customizer/customizer.php' );
	}
	add_action( 'customize_register', 'bento_customize_register' );
	add_action( 'customize_register', 'bento_customizer_rename_sections' );
	add_action( 'customize_controls_print_styles', 'bento_customizer_stylesheet' );
	add_action( 'customize_controls_enqueue_scripts', 'bento_customizer_scripts' );
	add_action( 'admin_notices', 'bento_customizer_admin_notice' );

}



// Register and enqueue CSS and scripts
function bento_theme_styles_scripts() {

	// Scripts
	wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri().'/includes/fitvids/jquery.fitvids.js', array('jquery'), false, true );
	wp_enqueue_script( 'bento-theme-scripts', get_template_directory_uri().'/includes/js/theme-scripts.js', array('jquery'), false, true );

	// Styles
	wp_enqueue_style( 'bento-theme-styles', get_template_directory_uri().'/style.css', array( 'dashicons' ), null, 'all' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/includes/font-awesome/css/fontawesome-all.min.css', array(), null, 'all' );
	wp_enqueue_style( 'google-fonts', bento_google_fonts(), array(), null );

	// Passing php variables to theme scripts
	bento_localize_scripts();

}


// Admin scripts
function bento_admin_scripts() {

	// Enqueue scripts
	$screen = get_current_screen();
	$edit_screens = array( 'post', 'page', 'project', 'product' );
	if ( in_array( $screen->id, $edit_screens ) ) {
		wp_enqueue_script( 'bento-admin-scripts', get_template_directory_uri().'/includes/admin/admin-scripts.js', array('jquery'), false, true );
	}
	$old_options = get_option( 'satori_options', 'none' );
	if ( $old_options != 'none' ) {
		wp_enqueue_script( 'bento-migrate-scripts', get_template_directory_uri().'/includes/js/migrate-scripts.js', array('jquery'), false, true );
	}
	if ( 'appearance_page_about-bento' == $screen->id ) {
		wp_enqueue_style( 'bento-admin-styles', get_template_directory_uri().'/includes/admin/admin-styles.css', array(), null, 'all' );
	}

	// Passing php variables to admin scripts
	bento_localize_migrate_scripts();

}


// Register theme status for the Expansion Pack
function bento_active() {
	$current_theme = wp_get_theme();
	if ( $current_theme == 'Bento' ) {
		return true;
	} else {
		return false;
	}
}


// Localize scripts
function bento_localize_scripts() {
	$postid = get_queried_object_id();
	$afflink = get_post_meta( $postid, 'bento_afflink', true );
	if ( get_post_meta( $postid, 'bento_afflink_topbar', true ) != '' ) {
		$afflink = get_post_meta( $postid, 'bento_afflink_topbar', true );
	}
    wp_localize_script( 'bento-theme-scripts', 'bentoThemeVars', array(
		'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
    ));
	wp_reset_postdata();
}
function bento_localize_migrate_scripts() {
	wp_localize_script( 'bento-migrate-scripts', 'bentoMigrateVars', array(
		'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
	));
}


// Load custom template tags
if ( file_exists( get_template_directory() . '/includes/template-tags.php' ) ) {
	require_once get_template_directory() . '/includes/template-tags.php';
}


// Set the content width
$GLOBALS['content_width'] = 1440;
function bento_theme_adjust_content_width() {
	$content_width = $GLOBALS['content_width'];
	$postid = get_queried_object_id();
	if ( get_theme_mod( 'bento_content_width', 1080 ) > 0 ) {
		$content_width = get_theme_mod( 'bento_content_width', 1080 ) + 360;
		if ( get_theme_mod( 'bento_website_layout', 0 ) == 1 ) {
			$content_width = $content_width + 120;
		}
	}
	if ( ( is_singular() && get_post_meta( $postid, 'bento_sidebar_layout', true ) != 'full-width' ) || is_home() ) {
		$content_width = $content_width * 0.7;
	}
	$GLOBALS['content_width'] = apply_filters( 'bento_theme_adjust_content_width', $content_width );
}


// Add excerpt support for pages
function bento_page_excerpt_support() {
	add_post_type_support( 'page', 'excerpt' );
}


// Enable threaded comments
function bento_enable_threaded_comments() {
if ( !is_admin() ) {
	if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1) )
		wp_enqueue_script('comment-reply');
	}
}


// Register sidebars
function bento_register_sidebars() {
	register_sidebar(
		array(
			'name' => esc_html__( 'Sidebar', 'bento' ),
			'id' => 'bento_sidebar',
			'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s clear">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
	));
	register_sidebar(
		array(
			'name' => esc_html__( 'Footer', 'bento' ),
			'id' => 'bento_footer',
			'before_widget' => '<div id="%1$s" class="widget widget-footer %2$s clear">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
	));
}


// Comment form defaults
function bento_comment_form_defaults( $defaults ) {
	$defaults['label_submit'] = esc_html__( 'Submit Comment', 'bento' );
    $defaults['comment_notes_before'] = '';
    $defaults['comment_field'] = '
		<div class="comment-form-comment">
			<textarea
				id="comment"
				name="comment"
				placeholder="'.esc_html__( 'Comment', 'bento' ).'"
				cols="45" rows="8"
				aria-required="true"
			></textarea>
		</div>
	';
	return $defaults;
}


// Move the textarea field to the bottom of comment form
function bento_rearrange_comment_fields( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}


// Comment form fields
function bento_comment_form_fields( $fields ) {
	$commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
	$fields['author'] = '
		<div class="comment-form-field comment-form-author">
			<label for="author">'.esc_html__( 'Name', 'bento' ).'</label>
			<input
				id="author"
				name="author"
				type="text"
				placeholder="'.esc_html__( 'Name','bento' ).'"
				value="'.esc_attr( $commenter['comment_author'] ).'"
				size="30"'.$aria_req.
			' />
		</div>
	';
    $fields['email'] = '
		<div class="comment-form-field comment-form-email">
			<label for="email">'.esc_html__( 'Email', 'bento' ).'</label>
			<input
				id="email"
				name="email"
				type="text"
				placeholder="'.esc_html__( 'Email','bento' ).'"
				value="'. esc_attr( $commenter['comment_author_email'] ).'"
				size="30"'.$aria_req.
			' />
		</div>
	';
	$fields['url'] = '';
	return $fields;
}


// Initialize the metabox class
if ( ! class_exists( 'CMB2_Bootstrap_230' ) ) {
	if ( file_exists( get_template_directory() . '/includes/metaboxes/init.php' ) ) {
		require_once ( get_template_directory().'/includes/metaboxes/init.php' );
	}
}


// Custom excerpt ellipses
function bento_custom_excerpt_more( $more ) {
	return esc_html__('Continue reading', 'bento').' &rarr;';
}


// Remove prefixes from archive page titles
function bento_cleaner_archive_titles($title) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>' ;
	}
    return $title;
}


// Page settings metaboxes
function bento_metaboxes() {

	// Define strings
	$bento_prefix = 'bento_';
	$bento_ep_url = wp_kses(
		'<a href="http://satoristudio.net/bento-free-wordpress-theme/#expansion-pack/?utm_source=disabled&utm_medium=theme&utm_campaign=theme" target="_blank">Expansion Pack</a>',
		array(
			'a' => array(
				'href' => array(),
				'target' => array(),
			),
		)
	);

	// Callback to display a field only on single post types
	function bento_show_field_on_single() {
		$current_screen = get_current_screen();
		if ( $current_screen->id == 'page' ) {
			return false;
		} else {
			return true;
		}
	}

	// Function to add a multicheck with post types
	add_action( 'cmb2_render_multicheck_posttype', 'bento_render_multicheck_posttype', 10, 5 );
	function bento_render_multicheck_posttype( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		if ( version_compare( CMB2_VERSION, '2.2.2', '>=' ) ) {
			$field_type_object->type = new CMB2_Type_Radio( $field_type_object );
		}
		$cpts = array( 'post', 'project' );
		if ( class_exists( 'WooCommerce' ) ) {
			$cpts[] = 'product';
		}
		$options = '';
		$i = 1;
		$values = (array) $escaped_value;
		if ( $cpts ) {
			foreach ( $cpts as $cpt ) {
				$args = array(
					'value' => $cpt,
					'label' => $cpt,
					'type' => 'checkbox',
					'name' => $field->args['_name'] . '[]',
				);
				if ( in_array( $cpt, $values ) ) {
					$args[ 'checked' ] = 'checked';
				}
				if ( $cpt == 'project' && get_option( 'bento_ep_license_status' ) != 'valid' ) {
					$args[ 'disabled' ] = 'disabled';
				}
				$options .= $field_type_object->list_input( $args, $i );
				$i++;
			}
		}
		$classes = false === $field->args( 'select_all_button' ) ? 'cmb2-checkbox-list no-select-all cmb2-list' : 'cmb2-checkbox-list cmb2-list';
		echo $field_type_object->radio( array( 'class' => $classes, 'options' => $options ), 'multicheck_posttype' );
	}

	// Review settings
	$bento_general_settings = new_cmb2_box(
		array(
			'id'            => 'review_settings_metabox',
			'title'         => esc_html__( 'Review settings', 'bento' ),
			'object_types'  => array( 'post' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names' => true,
		)
	);
	$bento_general_settings->add_field(
		array(
			'name' => esc_html__( 'Affiliate link', 'bento' ),
			'desc' => esc_html__( 'Input the affiliate link for this room.', 'bento' ),
			'id' => $bento_prefix . 'afflink',
			'type' => 'text',
		)
	);
	$bento_general_settings->add_field(
		array(
			'name' => esc_html__( 'Front listing affiliate link', 'bento' ),
			'desc' => esc_html__( 'Afflink for the direct mention on the front page list.', 'bento' ),
			'id' => $bento_prefix . 'afflink_listing',
			'type' => 'text',
		)
	);
	$bento_general_settings->add_field(
		array(
			'name' => esc_html__( 'Topbar affiliate link', 'bento' ),
			'desc' => esc_html__( 'Afflink for the mobile topbar.', 'bento' ),
			'id' => $bento_prefix . 'afflink_topbar',
			'type' => 'text',
		)
	);
	$bento_general_settings->add_field(
		array(
			'name' => esc_html__( 'Brand name', 'bento' ),
			'desc' => esc_html__( 'Input the full name of the brand for the front page listing.', 'bento' ),
			'id' => $bento_prefix . 'review_brand',
			'type' => 'text',
		)
	);
	$bento_general_settings->add_field(
		array(
			'name' => esc_html__( 'Special offer', 'bento' ),
			'desc' => esc_html__( 'Add a special offer for the front page listing.', 'bento' ),
			'id' => $bento_prefix . 'review_offer',
			'type' => 'text',
		)
	);
	$bento_general_settings->add_field(
		array(
			'name' => esc_html__( 'Icon', 'bento' ),
			'desc' => esc_html__( 'Add icon for the front page listing.', 'bento' ),
			'id' => $bento_prefix . 'review_icon',
			'type' => 'file',
		)
	);
	$bento_general_settings->add_field(
		array(
			'name' => esc_html__( 'Brand color', 'bento' ),
			'desc' => esc_html__( 'Choose the brand color for the front page icon.', 'bento' ),
			'id' => $bento_prefix . 'review_color',
			'type' => 'colorpicker',
			'default' => '#cccccc',
		)
	);

	// SEO settings
	if ( get_option( 'bento_ep_license_status' ) == 'valid' ) {
		$bento_seo_settings = new_cmb2_box(
			array(
				'id'            => 'seo_settings_metabox',
				'title'         => esc_html__( 'SEO Settings', 'bento' ),
				'object_types'  => array( 'post', 'page', 'project', 'product' ),
				'context'       => 'normal',
				'priority'      => 'low',
				'show_names'	=> true,
			)
		);
		$bento_seo_settings->add_field(
			array(
				'name' => esc_html__( 'Meta title', 'bento' ),
				'desc' => esc_html__( 'Input the meta title - the text to be used by search engines as well as browser tabs (recommended max length - 60 symbols); the post title will be used by default if this field is empty.', 'bento' ),
				'id' => $bento_prefix . 'meta_title',
				'type' => 'text',
			)
		);
		$bento_seo_settings->add_field(
			array(
				'name' => esc_html__( 'Meta description', 'bento' ),
				'desc' => esc_html__( 'Input the meta description - the text to be used by search engines on search result pages (recommended max length - 160 symbols); the first part of the post body will be used by default is this field is left blank.', 'bento' ),
				'id' => $bento_prefix . 'meta_description',
				'type' => 'textarea',
				'attributes' => array(
					'rows' => 3,
				),
			)
		);
	}

	// Page settings
	$bento_page_settings = new_cmb2_box(
		array(
			'id'            => 'page_settings_metabox',
			'title'         => esc_html__( 'Page settings', 'bento' ),
			'object_types'  => array( 'page' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names' => true,
		)
	);
	$bento_page_settings->add_field(
		array(
			'name' => esc_html__( 'Show on main', 'bento' ),
			'desc' => esc_html__( 'Check this to display the page in the main blog feed', 'bento' ),
			'id' => $bento_prefix . 'pagemain',
			'type' => 'checkbox',
		)
	);

}


// Add room post type to main query
function tl_add_custom_post_types_to_query( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'post_type', array('post', 'room') );
    }
}


// Language Switcher for the footer
function tl_lang_switch() {
	global $post;
	if ( $post || is_home() ) {
		$post_id = $post->ID;
		$args = array(
			'dropdown' => 0,
			'echo' => 0,
			'show_names' => 1,
			'hide_if_no_translation' => 1,
			'hide_current' => 1,
			'hide_if_empty' => 1,
		);
		$lang_switch = pll_the_languages($args);
		return $lang_switch;
	}
}


// Change post menu labels
function tl_change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Reviews';
    $submenu['edit.php'][5][0] = 'Reviews';
    $submenu['edit.php'][10][0] = 'Add Review';
    $submenu['edit.php'][15][0] = 'Categories'; // Change name for categories
    $submenu['edit.php'][16][0] = 'Tags'; // Change name for tags
    echo '';
}

function tl_change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = 'Reviews';
	$labels->singular_name = 'Review';
	$labels->add_new = 'Add Review';
	$labels->add_new_item = 'Add Review';
	$labels->edit_item = 'Edit Review';
	$labels->new_item = 'Review';
	$labels->view_item = 'View Review';
	$labels->search_items = 'Search Reviews';
	$labels->not_found = 'No Reviews found';
	$labels->not_found_in_trash = 'No Reviews found in Trash';
}
add_action( 'init', 'tl_change_post_object_label' );
add_action( 'admin_menu', 'tl_change_post_menu_label' );


// Output room rating
function tl_room_rating($id = '') {
	if ( function_exists( 'mrp_rating_result' ) ) {
		if ( $id == '' ) {
			$id = get_the_ID();
		}
		$rating_result_raw = mrp_rating_result(
			array(
				'result_type' => 'score',
				'show_count' => 'false',
				'echo' => false,
				'post_id' => $id,
			)
		);
		$rating_result_stripped = strip_tags($rating_result_raw);
		$rating_result = substr($rating_result_stripped, 0, strpos($rating_result_stripped, '/'));
		$rating_num = number_format( floatval( $rating_result ), 1 );
		return $rating_num;
	} else {
		return 0;
	}
}

function tl_room_rating_count($id = '') {
	if ( function_exists( 'mrp_rating_result' ) ) {
		if ( $id == '' ) {
			$id = get_the_ID();
		}
		$rating_result_raw = mrp_rating_result(
			array(
				'result_type' => 'score',
				'echo' => false,
				'before_count' => '-',
				'post_id' => $id,
			)
		);
		$rating_result_stripped = strip_tags($rating_result_raw);
		$rating_result = substr($rating_result_stripped, strpos($rating_result_stripped, '-')+1 );
		$rating_count = intval( $rating_result );
		return $rating_count;
	} else {
		return 0;
	}
}


// Shortcode to display affiliate link in review body
function sh_afflink( $atts ) {
	global $post;
	return '
		<div class="review-visit">
			<a href="'.get_post_meta( $post->ID, 'bento_afflink', true ).'" target="_blank">'.
				__( 'Visit Site', 'default' ).' <span class="visit-arrow">&rarr;</span>
			</a>
		</div>
	';
}
add_shortcode( 'afflink', 'sh_afflink' );


// Display a star rating
function star_rating() {
	$comment_rating = tl_room_rating();
	$rating_count = tl_room_rating_count();
	$rating_width = ( $comment_rating / 5 ) * 100;
	if ( $comment_rating > 0 ) {
		echo '<div class="review-stars">'.$comment_rating.' / 5';
		echo '
			<script type="application/ld+json">
				{
					"@context": "http://schema.org",
					"@type": "Product",
					"name": "'.do_shortcode( get_the_title() ).'",
					"aggregateRating":
					{	"@type": "AggregateRating",
						"ratingValue": "'.$comment_rating.'",
						"reviewCount": "'.$rating_count.'"
					}
				}
			</script>
		';
		?>
			<div class="star-rating-outer">
				<div class="star-rating">
					<span style="width:<?php echo $rating_width.'%'; ?>">5</span>
				</div>
			</div>
		</div>
		<?php
	}
}


// Custom favicon
function bento_custom_favicon() {

	echo '
		<link rel="apple-touch-icon" sizes="180x180" href="/wp-content/themes/turboluck/images/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/wp-content/themes/turboluck/images/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/wp-content/themes/turboluck/images/favicon/favicon-16x16.png">
		<link rel="manifest" href="/wp-content/themes/turboluck/images/favicon/site.webmanifest">
		<link rel="mask-icon" href="/wp-content/themes/turboluck/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
		<link rel="shortcut icon" href="/wp-content/themes/turboluck/images/favicon/favicon.ico">
		<meta name="msapplication-TileColor" content="#00a300">
		<meta name="msapplication-config" content="/wp-content/themes/turboluck/images/favicon/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
	';

}


function tl_analytics_yandex() {

	echo '
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript" >
		   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
		   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
		   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
		   ym(53308480, "init", {
				clickmap:true,
				trackLinks:true,
				accurateTrackBounce:true
		   });
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/53308480" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
	';

}


// Check if login page
function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}


// Load jQuery from a CDN
function bento_jquerycdn() {
	if ( is_admin() || is_login_page() ) {
		return;
	}
	$ver = '1.12.4';
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/'.$ver.'/jquery.min.js', false, $ver );
}

// Add backend for the front page lists
add_action( 'admin_menu', 'tl_front_list_admin' );
function tl_front_list_admin() {
	add_submenu_page( 'edit.php', 'Front Page List', 'Front Page List', 'manage_options', 'front-list', 'tl_front_list');
}
function tl_front_list() {
	?>
	<style type="text/css">
		.front-list-language {
			width: 100%;
			padding-bottom: 8px;
		}
		.front-list-language-name {
			font-size: 20px;
			font-weight: bold;
			text-transform: uppercase;
			padding: 20px;
			background-color: #ddd;
			margin: 50px 0 0 0;
		}
		.front-list-language select {
			max-width: 100%;
		}
		.front-list-language p {
			margin: 0 0 6px 0;
		}
		.list-name {
			font-size: 16px;
			font-weight: 700;
			padding: 20px 0;
			font-style: italic;
		}
		.front-list-language-type,
		.front-list-language-tag {
			width: 33%;
			box-sizing: border-box;
			padding-right: 5%;
			display: inline-block;
		}
		.front-list-language-type-name,
		.front-list-language-tag-name {
			padding: 6px 0;
			font-style: italic;
		}
	</style>
    <div class="wrap">
        <h1><?php _e( 'Front Page List', 'bento' ); ?></h1>
        <p><?php _e( 'Set the reviews to be features in the list on front page.', 'bento' ); ?></p>
		<form method="POST" action="<?php echo esc_url( admin_url('admin.php') ); ?>">
			<?php
			submit_button(  __( 'Save changes', 'bento' ), 'primary', 'tl_set_front_list' );
			$langs = pll_languages_list(array('hide_empty' => 1, 'fields' => 'slug' ));
			foreach ( $langs as $lang ) {
				?>
					<div class="front-list-language">
						<div class="front-list-language-name"><?php echo $lang; ?></div>
						<?php
						$types_args = array(
							'taxonomy' => 'type',
							'hide_empty' => 0,
							'orderby' => 'name',
							'order' => 'DESC',
							'include' => array( 90, 91, 92 ),
						);
						$types = get_terms( $types_args );
						echo '<div class="list-name">'.__( 'Front page', 'bento' ).'</div>';
						foreach ( $types as $type ) {
							?>
							<div class="front-list-language-type">
								<div class="front-list-language-type-name">
									<?php echo $type->name; ?>
								</div>
								<?php
								$type_posts = get_posts(
									array(
										'post_type' => 'post',
										'lang' => $lang,
										'posts_per_page' => -1,
										'tax_query' => array(
											array(
												'taxonomy' => 'type',
												'field' => 'term_id',
												'terms' => $type->term_id,
											)
										)
									)
								);
								$type_posts_array = array();
								foreach ( $type_posts as $type_post ) {
									$type_posts_array[] = $type_post->ID;
								}
								$typename = $type->slug;
								$options_array = get_option( 'front_list_'.$lang );
								$current_settings_array = array();
								if ( !empty( $options_array ) ) {
									$current_settings_array = $options_array[$typename];
								}
								for ( $i=1; $i<4; $i++ ) {
									$selected = 'none';
									$j = $i - 1;
									if ( isset( $current_settings_array[$j] ) ) {
										if ( $current_settings_array[$j] != 'none' ) {
											$selected = $current_settings_array[$j];
										}
									}
									$dropdown_args = array(
										'post_type' => 'post',
										'lang' => $lang,
										'echo' => 0,
										'name' => 'frontlist-'.$lang.'-'.$typename.'-'.$i,
										'show_option_none' => 'None',
										'option_none_value' => 'none',
										'selected' => $selected,
										'id' => 'frontlist-'.$lang.'-'.$typename.'-'.$i,
									);
									$dropdown_args_lang = $dropdown_args;
									$dropdown_args_lang['include'] = $type_posts_array;
									if ( wp_dropdown_pages( $dropdown_args_lang ) ) {
										echo '<p>'.wp_dropdown_pages( $dropdown_args_lang ).'</p>';
									} else {
										echo '<p>'.wp_dropdown_pages( $dropdown_args ).'</p>';
									}
								}
								?>
							</div>
							<?php
						}

						$tags_args = array(
							'taxonomy' => 'post_tag',
							'hide_empty' => 0,
							'orderby' => 'name',
							'order' => 'DESC',
						);
						$tags = get_terms( $tags_args );
						echo '<div class="list-name">'.__( 'Inner sections', 'bento' ).'</div>';
						foreach ( $tags as $tag ) {
							?>
							<div class="front-list-language-tag">
								<div class="front-list-language-tag-name">
									<?php echo $tag->name; ?>
								</div>
								<?php
								$tag_posts = get_posts(
									array(
										'post_type' => 'post',
										'lang' => $lang,
										'posts_per_page' => -1,
										/*
										'tax_query' => array(
											array(
												'taxonomy' => 'post_tag',
												'field' => 'term_id',
												'terms' => $tag->term_id,
											)
										)
										*/
									)
								);
								$tag_posts_array = array();
								foreach ( $tag_posts as $tag_post ) {
									$tag_posts_array[] = $tag_post->ID;
								}
								$tagname = $tag->slug;
								$options_array = get_option( 'tag_list_'.$lang );
								$current_settings_array = array();
								if ( !empty( $options_array ) ) {
									$current_settings_array = $options_array[$tagname];
								}
								for ( $i=1; $i<4; $i++ ) {
									$selected = 'none';
									$j = $i - 1;
									if ( isset( $current_settings_array[$j] ) ) {
										if ( $current_settings_array[$j] != 'none' ) {
											$selected = $current_settings_array[$j];
										}
									}
									$dropdown_args = array(
										'post_type' => 'post',
										'lang' => $lang,
										'echo' => 0,
										'name' => 'taglist-'.$lang.'-'.$tagname.'-'.$i,
										'show_option_none' => 'None',
										'option_none_value' => 'none',
										'selected' => $selected,
										'id' => 'taglist-'.$lang.'-'.$tagname.'-'.$i,
									);
									$dropdown_args_lang = $dropdown_args;
									$dropdown_args_lang['include'] = $tag_posts_array;
									if ( wp_dropdown_pages( $dropdown_args_lang ) ) {
										echo '<p>'.wp_dropdown_pages( $dropdown_args_lang ).'</p>';
									} else {
										echo '<p>'.wp_dropdown_pages( $dropdown_args ).'</p>';
									}
								}
								?>
							</div>
							<?php
						}
						?>
					</div>
				<?php
			}
			submit_button(  __( 'Save changes', 'bento' ), 'primary', 'tl_save_front_list' );
			?>
			<input type="hidden" name="action" value="savefrontlist" />
		</form>
    </div>
    <?php
}
add_action( 'admin_init', 'tl_register_front_list_options' );
function tl_register_front_list_options() {
	$langs = pll_languages_list(array('hide_empty' => 1, 'fields' => 'slug' ));
	foreach ( $langs as $lang ) {
		register_setting( 'front_list', 'front_list_'.$lang );
		register_setting( 'tag_list', 'tag_list_'.$lang );
	}
}
add_action( 'admin_action_savefrontlist', 'tl_set_front_list' );
function tl_set_front_list() {
	if ( isset( $_POST ) ) {
		$langs = pll_languages_list(array('hide_empty' => 1, 'fields' => 'slug' ));
		foreach ( $langs as $lang ) {

			$type_settings_array = array();
			$types_args = array(
				'taxonomy' => 'type',
				'hide_empty' => 0,
				'orderby' => 'name',
				'order' => 'DESC',
				'include' => array( 90, 91, 92 ),
			);
			$types = get_terms( $types_args );
			foreach ( $types as $type ) {
				$typename = $type->slug;
				$settings_type_array = array();
				for ( $i=1; $i<4; $i++ ) {
					$settings_type_array[] = $_POST[ 'frontlist-'.$lang.'-'.$typename.'-'.$i ];
				}
				$type_settings_array[$typename] = $settings_type_array;
			}
			update_option( 'front_list_'.$lang, $type_settings_array );

			$tag_settings_array = array();
			$tags_args = array(
				'taxonomy' => 'post_tag',
				'hide_empty' => 0,
				'orderby' => 'name',
				'order' => 'DESC',
			);
			$tags = get_terms( $tags_args );
			foreach ( $tags as $tag ) {
				$tagname = $tag->slug;
				$settings_tag_array = array();
				for ( $i=1; $i<4; $i++ ) {
					$settings_tag_array[] = $_POST[ 'taglist-'.$lang.'-'.$tagname.'-'.$i ];
				}
				$tag_settings_array[$tagname] = $settings_tag_array;
			}
			update_option( 'tag_list_'.$lang, $tag_settings_array );

		}
	}
	wp_redirect( $_SERVER['HTTP_REFERER'] );
	exit();
}
add_action( 'registered_post_type', 'tl_make_posts_hierarchical', 10, 2 );
function tl_make_posts_hierarchical( $post_type, $pto ) {
    if ( $post_type != 'post' ) return;
    global $wp_post_types;
    $wp_post_types['post']->hierarchical = 1;
    add_post_type_support( 'post', 'page-attributes' );
}
add_action( 'init', 'tl_register_review_types', 8 );
function tl_register_review_types() {
	$labels = array(
		'name'                       => _x( 'Type', 'taxonomy general name', 'bento' ),
		'singular_name'              => _x( 'Type', 'taxonomy singular name', 'bento' ),
		'search_items'               => __( 'Search Types', 'bento' ),
		'popular_items'              => __( 'Popular Types', 'bento' ),
		'all_items'                  => __( 'All Types', 'bento' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Type', 'bento' ),
		'update_item'                => __( 'Update Type', 'bento' ),
		'add_new_item'               => __( 'Add New Type', 'bento' ),
		'new_item_name'              => __( 'New Type Name', 'bento' ),
		'separate_items_with_commas' => __( 'Separate types with commas', 'bento' ),
		'add_or_remove_items'        => __( 'Add or remove types', 'bento' ),
		'choose_from_most_used'      => __( 'Choose from the most used types', 'bento' ),
		'not_found'                  => __( 'No types found.', 'bento' ),
		'menu_name'                  => __( 'Types', 'bento' ),
	);
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'public'				=> false,
		'rewrite'               => array( 'slug' => 'type' ),
	);
	register_taxonomy( 'type', array( 'post' ), $args );
}


function tl_display_front_list() {
	if ( ! is_home() && ! is_tag() ) {
		return;
	}
	$output = '';
	$currlang = pll_current_language();
	$complete = 0;
	$output .= '<div class="front-list">';
	if ( is_home() ) {

		$settings = get_option( 'front_list_'.$currlang );
		$tabs = array(
			'bookmaker' => array(
				'name' => __( 'Bet', 'bento' ),
				'slogan' => __( 'Best places to bet online', 'bento' ),
			),
			'casino' => array(
				'name' => __( 'Play', 'bento' ),
				'slogan' => __( 'Best places to try your luck online', 'bento' ),
			),
			'broker' => array(
				'name' => __( 'Trade', 'bento' ),
				'slogan' => __( 'Best places to trade online', 'bento' ),
			),
		);

		$output .= '<div class="front-list-tabs">';
		foreach ( $tabs as $key => $val ) {
			$tab_class = '';
			if ( $key == 'bookmaker' ) {
				$tab_class = 'front-list-tab-active';
			}
			$settings = get_option( 'front_list_'.$currlang );
			$count = 0;
			if ( ! empty( $settings ) ) {
				foreach ( $settings[$key] as $v ) {
					if ( $v == 'none' ) {
						$count++;
					}
				}
			}
			if ( $count == 3 ) {
				$tab_class .= ' front-list-tab-hidden';
			}
			$output .= '<div class="front-list-tab front-list-tab-clickable '.$tab_class.'" data-type="'.$key.'" data-slogan="'.$val['slogan'].'">'.$val['name'].'</div>';
		}
		$output .= '<div class="front-list-tab front-list-tab-slogan">'.$tabs['bookmaker']['slogan'].':</div></div>';

	} else {

		$settings = get_option( 'tag_list_'.$currlang );
		$tag_slug = get_queried_object()->slug;
		$tabs = array(
			$tag_slug => array(),
		);

	}
	$i = 1;
	$output .= '<div class="front-list-items-container">';
	foreach ( $tabs as $key => $val ) {
		$items = '<div class="front-list-items front-list-'.$key.'">';
		if ( ! empty( $settings ) ) {
			foreach ( $settings[$key] as $val ) {
				if ( $val != 'none' ) {
					if($complete == 3){
						$complete = 0; // changing count value
									  //back to zero because we need 1,2,3
					}
					$complete++;
				} else {
					break;
				}
				$val_eng = pll_get_post( $val, 'en' );
				$brand = get_post_meta( $val, 'bento_review_brand', true );
				if ( $brand == '' ) {
					$brand = get_post_meta( $val_eng, 'bento_review_brand', true );
				}
				$icon_svg = get_post_meta( $val, 'bento_review_icon', true );
				if ( $icon_svg == '' ) {
					$icon_svg = get_post_meta( $val_eng, 'bento_review_icon', true );
				}
				//$icon_brand = '<img src="'.$icon_svg.'" alt="'.$brand.'">';
				if ( is_tag() ) {
					$icon_brand = $i;
				}
				//$rev_col = get_post_meta( $val, 'bento_review_color', true );

				$rev_col = '#00AC7D'; //added fixed color as per requirement
				// if ( $rev_col == '#cccccc' ) {
				// 	$rev_col = get_post_meta( $val_eng, 'bento_review_color', true );
				// }
				// if ( is_tag() ) {
				// 	$rev_col = '#00b285';
					/*
					if ( get_post_meta( $val, 'bento_review_color', true ) != '#cccccc' ) {
						$rev_col = get_post_meta( $val, 'bento_review_color', true );
					} else {
						if ( get_post_meta( $val_eng, 'bento_review_color', true ) != '#cccccc' ) {
							$rev_col = get_post_meta( $val_eng, 'bento_review_color', true );
						}
					}
					*/
				//}
				$icon = '
					<div class="front-list-item-icon-circle" style="background-color:'.$rev_col.'">
						'.$complete.'
					</div>
				';
				$rating_width = ( tl_room_rating($val) / 5 ) * 100;
				$rating = '
					<div class="star-rating-number">
						<strong>'.tl_room_rating($val).'</strong> / 5
					</div>
					<div class="star-rating-outer">
						<div class="star-rating">
							<span style="width:'.$rating_width.'%">5</span>
						</div>
					</div>
				';
				$toplink = get_post_meta( $val, 'bento_afflink', true );
				if ( get_post_meta( $val, 'bento_afflink_listing', true ) != '' ) {
					$toplink = get_post_meta( $val, 'bento_afflink_listing', true );
				}
				$offer = get_post_meta( $val, 'bento_review_offer', true );
				$direct = '<a href="'.$toplink.'" target="_blank">Official website</a> <span class="dashicons dashicons-external"></span>';
				$review = '<a href="'.get_permalink( $val ).'">Our review</a> <span class="review-arrow">&rarr;</span>';
				$items .= '
					<div class="front-list-item">
						<div class="front-list-item-brand front-list-item-el clear">
							<div class="front-list-item-brand-inner">
								<div class="front-list-item-icon">'.$icon.'</div>
								<div class="front-list-item-name">'.$brand.'</div>
							</div>
						</div>
						<div class="front-list-item-rating front-list-item-el">'.$rating.'</div>
						<div class="front-list-item-offer front-list-item-el">'.$offer.'</div>
						<div class="front-list-item-direct front-list-item-el">'.$direct.'</div>
						<div class="front-list-item-review front-list-item-el">'.$review.'</div>
					</div>
				';
				$i++;
			}
		}
		$items .= '</div>';
		$output .= $items;
	}
	$output .= '</div></div>';
	if ( $complete > 0 ) {
		echo $output;
	}
}


// Display front category grid
function tl_display_front_categories() {

	if ( pll_current_language() == 'en' && is_front_page() ) {
		$cats = array(
			'new' => array(
				'adj' => __( 'finest', 'bento' ),
				'cat' => __( 'new', 'bento' ),
			),
			'mobile' => array(
				'adj' => __( 'top', 'bento' ),
				'cat' => __( 'mobile', 'bento' ),
			),
			'bonus' => array(
				'adj' => __( 'best', 'bento' ),
				'cat' => __( 'bonus', 'bento' ),
			),
		);
		$cats_output = '';
		foreach ( $cats as $name => $text ) {
			$cats_output .= '
				<div class="front-category front-category-'.$name.'">
					<a href="'.home_url( '/tag/'.$name ).'">
						<div class="front-category-inner">
							<div class="front-category-image">
								<img src="'.get_template_directory_uri().'/images/front-section-'.$name.'.svg">
							</div>
							<div class="front-category-title">
								<span class="front-category-title-adj">'.$text['adj'].'</span>
								<span class="front-category-title-cat">'.$text['cat'].'</span>
							</div>
						</div>
					</a>
				</div>
			';
		}
		$cats_output = '<div class="front-categories">'.$cats_output.'</div>';
		echo $cats_output;
	}

}


// Allow svg uploads
function tl_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}


// Add time shortcodes
function shortcode_thisyear() {
	$year = date('Y');
	return $year;
}
add_shortcode( 'thisyear', 'shortcode_thisyear' );

function shortcode_thismonth() {

	$month = date( 'F' );

	$currlang = pll_current_language();
	if ( $currlang == 'ru' ) {
		$ru_months = array( 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь' );
		$monthstr = date( 'n' );
		$monthnum = (int)$monthstr - 1;
		$month = $ru_months[$monthnum];
	}

	return $month;
}
add_shortcode( 'thismonth', 'shortcode_thismonth' );


// Allow shortcodes in AiOSP titles
function change_wordpress_seo_title( $title ) {
    $title = do_shortcode( $title );
    return $title;
}
function change_wordpress_seo_description( $description ) {
    $description = do_shortcode( $description );
    return $description;
}


function tl_custom_excerpt($excerpt) {
	global $post;
	if ( is_single( $post ) || is_home() ) {
		return $excerpt.'
			<div class="blog-review-link">
				<a href="'.get_permalink($post->ID).'">
					<span class="visit-arrow">&rarr;</span>
				</a>
			</div>
		';
	} else {
		return $excerpt;
	}
}


function tl_modify_main_query( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$query->query_vars['post_type'] = array( 'post', 'page' );
		$fp_args = array(
			'post_type'  => 'page',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => 'bento_pagemain',
					'value'   => 'on',
					'compare' => '!=',
				),
				array(
					'key'     => 'bento_pagemain',
					'value'   => '',
					'compare' => 'NOT EXISTS',
				),
			),
		);
		$fp_query = get_posts( $fp_args );
		$fp_array = array();
		foreach ( $fp_query as $fp_page ) {
			$fp_array[] = $fp_page->ID;
		}
		$query->query_vars['post__not_in'] = $fp_array;
	}
}
add_action( 'pre_get_posts', 'tl_modify_main_query' );


function tl_display_tag_header() {
	if ( ! is_tag() ) {
		return;
	}
	$tag_slug = get_queried_object()->slug;
	$tag_name = get_queried_object()->name;
	$tag_desc = '';
	if ( tag_description() ) {
		$tag_desc = '<div class="tag-header-desc"><div class="tag-header-desc-inner">'.tag_description().'</div></div>';
	}
	$output = '';
	$output .= '
		<div class="tag-header">
			<div class="tag-header-inner">
				<div class="tag-header-name">
					'.$tag_name.'
				</div>
				<div class="tag-header-image">
					<img src="'.get_template_directory_uri().'/images/front-section-'.$tag_slug.'.svg">
				</div>
				'.$tag_desc.'
			</div>
		</div>
	';
	echo $output;
}

//adding styles to head

add_action('wp_head', function(){

	/* icons fix by shan */

	echo '<style>.front-list-item-icon-circle {
			text-align: center;
			color: #fff;
			font-family: Merriweather;
			font-weight: 400;
			overflow: hidden;
			font-style: italic;}
		</style>';
});


?>