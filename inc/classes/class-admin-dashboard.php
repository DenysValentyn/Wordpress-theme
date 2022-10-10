<?php
/**
 * Admin Dashboard
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class admin_dashboard{ //Admin Dashboard Template

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks function
        
         /**
          * Actions and Filters
          */

        //Adding functions to the hooks
        add_action('admin_init', [$this,'remove_dashboard_meta']);
        add_filter( 'get_user_option_admin_color', [$this,'update_user_option_admin_color']);
        add_filter( 'admin_head-profile.php', [$this,'remove_color_scheme']);
        add_action( 'admin_enqueue_scripts', [$this,'load_admin_assets']);
        add_action('admin_init', [$this,'dashboard_theme_admin_color_scheme']);
    }

    function load_admin_assets() { //Load Admin Assets

        wp_register_style( 'admin_css', LNARCHIVE_DIR_URI . '/admin-style.css', false, filemtime(LNARCHIVE_DIR_PATH . '/admin-style.css'), 'all' ); //Register the Style
        wp_enqueue_style( 'admin_css'); //Enqueue the Style

        wp_register_script( 'admin_js', LNARCHIVE_BUILD_JS_URI . '/admin.js', ['jquery'], filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/admin.js'), true ); //Admin Javascript File
        wp_enqueue_script( 'admin_js' ); //Enqueue the Script
    }

    function remove_dashboard_meta() { //Function to remove dashboard functionalities on admin-init

        //Hide Dashboard Widgets
        remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Wordpress News
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //At a glance
        remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Recent Activity
        remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); //Quick Draft

        remove_action( 'welcome_panel', 'wp_welcome_panel' ); //Welcome Message
    }

    function update_user_option_admin_color( $color_scheme ) { //Function to have default admin color scheme
        $color_scheme = 'dashboard-theme'; //default color scheme
        return $color_scheme; //Return the default color scheme
    }

    function remove_color_scheme() { //Function to remove the color scheme picker feature
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' ); //Remvoe Color Scheme picker
    }

    function dashboard_theme_admin_color_scheme() { //Function to add the color scheme for the Dashboard

        //Get the theme directory
        $theme_dir = get_stylesheet_directory_uri();
      
        //dashboard-theme
        wp_admin_css_color( 'dashboard-theme', 'dashboard-theme', //Define the dashboard CSS colors
          $theme_dir.'/dashboard-theme.css', //URL
          array( '#1d2327', '#fff', '#23c247' , '#4180e0') //Colors
        );
    }
}
?>