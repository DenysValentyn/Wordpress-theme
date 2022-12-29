<?php
/**
 * The Main Theme Class
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition

use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class lnarchive_theme{ //LNarchive Theme Class

     use Singleton; //Use Singleton

     protected function __construct(){ //Default Constructor

         //Load all Classes
         assets::get_instance();
         custom_api::get_instance();
         menus::get_instance();
         sidebars::get_instance();
         admin_dashboard::get_instance();
         custom_settings::get_instance();
         security::get_instance();
         novel::get_instance();
         volume::get_instance();
         taxonomies::get_instance();
         taxonomies_metafields::get_instance();
         post_metafields::get_instance();
         post_filter::get_instance();

         $this->set_hooks(); //Setting the hook below
     }

     protected function set_hooks() { 
         /**
          * Actions
          */
          add_action( 'after_setup_theme',[ $this, 'setup_theme']);
          add_action( 'template_redirect', [$this, 'rewrite_search_url']);
          add_action( 'rest_api_init', [$this, 'register_meta']);
          add_action('after_switch_theme', [$this, 'create_datbases']);

          //Disable Global RSS Feeds
          add_action('do_feed', [$this, 'wp_disable_feeds']);
          add_action('do_feed_rdf', [$this, 'wp_disable_feeds']);
          add_action('do_feed_rss', [$this, 'wp_disable_feeds']);
          add_action('do_feed_rss2', [$this, 'wp_disable_feeds']);
          add_action('do_feed_atom', [$this, 'wp_disable_feeds']);

          //Disable Comment Feeds
          add_action('do_feed_rss2_comments', [$this, 'wp_disable_feeds']);
          add_action('do_feed_atom_comments', [$this, 'wp_disable_feeds']);

          //Remove the RSS Links from HTML
          add_action( 'feed_links_show_posts_feed', '__return_false', - 1 );
          add_action( 'feed_links_show_comments_feed', '__return_false', - 1 );
          remove_action( 'wp_head', 'feed_links', 2 );
          remove_action( 'wp_head', 'feed_links_extra', 3 );
     }

     public function setup_theme() { //Main Setup Theme

         add_theme_support( 'align-wide' ); //Wide Alignment for Blocks
         add_theme_support( 'custom-background', array( //Custom Background
            'default-color' => '3a7de8',
            )
         );
         add_theme_support( 'custom-logo', [
            'header-text'          => array( 'site-title', 'site-description' ), //Replace Title/Desc by Logo
         ]); //Custom Logo
         add_theme_support( 'customize_selective_refresh_widgets' ); //Selective Refresh Support for Widgets
         add_theme_support('widgets-block-editor'); //Widgets Blocks Editor
         add_theme_support( 'post-thumbnails'); //Post Thumbnails
         add_theme_support('widgets'); //Add Widgets support

         //Register Image Sizes
         add_image_size('featured-thumbnail', 350, 300, true); //Thumbnail Size
         add_image_size('novel-cover', 1240, 1748, true); //Novel Cover Size
     
         global $content_width; //Global Content Width Variable
         if( ! isset( $content_width) ) { //If $content_width is not set
            $content_width=1240; //Set Default Content Width
         }
      }

      function register_meta(){ //Register metadata

         register_meta('comment', 'likes', [ //Register Like Meta for Comments
            'type' => 'number', //Datatype
            'single' => true, //Only one value
            'show_in_rest' => true, //Show in REST API
         ]);

         register_meta('comment', 'dislikes', [ //Register Dislike Meta for Comments
            'type' => 'number', //Datatype
            'single' => true, //Only one value
            'show_in_rest' => true, //Show in REST API
         ]);

         $formats = get_terms('format', array( //Get all the format terms
            'hide_empty' => false, //Include the terms with no enteries
         ));

         foreach( $formats as $format ){ //Loop through all the formats

            if( $format->name == "None") //Continue the loop if its the default format
               continue;

            register_meta( 'post', 'isbn_'.$format->name.'_value', array( //Register ISBN values
               'object_subtype'  => 'volume',
               'type'   => 'string',
               'show_in_rest' => true,
            ));

            register_meta( 'post', 'published_date_value_'.$format->name, array( //Register Publication Date values
               'object_subtype'  => 'volume',
               'type'   => 'string',
               'show_in_rest' => true,
            ));
         }
      }

      function rewrite_search_url() { //Rewrite the search result url for better SEO
         if ( is_search() && ! empty( $_GET['s'] ) ) { //If search and the search query not empty
             wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) ); //Restructure the URL
             exit(); //Exit
         }
      }

      function wp_disable_feeds() { //Disable all Feeds
         wp_redirect( home_url() ); //Redirect to Homepage if trying to access Feeds
         wp_die( __('Error: Feeds are disabled') ); //Error Message
      }

      function create_datbases() { //Function to create custom databases

         global $wpdb; //Wpdb Class
         $charset_collate = $wpdb->get_charset_collate(); //Get the Charset Collate
         require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //Make sure Upgrade.php is imported
         
         $comment_response_table_name = $wpdb->prefix . 'comment_response'; //Comment Response Table Name

         $comment_response_query = "CREATE TABLE " . $comment_response_table_name . " (
         response_id bigint(20) NOT NULL AUTO_INCREMENT,
         comment_id bigint(20) NOT NULL,
         user_id bigint(20) NOT NULL,
         response_type VARCHAR(100) NOT NULL,
         PRIMARY KEY  (response_id)
         ) $charset_collate;"; //Create the Table Args
         
         dbDelta($comment_response_query);//Execute the Query
      }
}
?>