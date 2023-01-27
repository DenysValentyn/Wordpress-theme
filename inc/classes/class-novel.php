<?php
/**
 * Novel Post Type
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class novel{ //Assests Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor function

        //Load Class
         $this->set_hooks(); //Setting the hook below
    }

    protected function set_hooks() {
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'init', [ $this, 'register_novel']);
        add_action('save_post_novel', [$this, 'auto_novel']);
        add_action( 'rest_api_init', [$this, 'register_routes']);
    }

    public function register_novel() {

        //Labels for various actions
        $labels = array(
            'name'                => 'Novels', //General Name of the post type
            'singular_name'       => 'Novel',  //Singular Name of the post type
            'menu_name'           => 'Novels', //Name of the post type in the menu
            'all_items'           => 'All Novels',  //All listing
            'view_item'           => 'View Novel', //View button
            'view_items'           => 'View Novels', //View button
            'add_new_item'        => 'Add New Novel', //Adding a new post type
            'add_new'             => 'Add New', //Add a new post type
            'edit_item'           => 'Edit Novel', //Edit the post type
            'update_item'         => 'Update Novel', //Update the post type
            'search_items'        => 'Search Novel', //Seardh Post type list
            'not_found'           => 'The Novel was not found', //When the post type is not found
            'not_found_in_trash'  => 'The Novel was not found in the trash', //When the novel is not found in the trash
            'archives' => 'Novels Library', //Archive
            'attributes' => 'Novel Attributes', //Novel attributes meta
            'insert_into_item' => 'Insert into Novel', //Label for the media frame button
            'uploaded_to_this_item' => 'Uploaded to this Novel', //Label for the media frame filter
            'featured_image' => 'Cover', //Novel Cover
            'set_featured_image' => 'Set Cover', //Set Novel Cover
            'remove_featured_image' => 'Remove Cover', //Remove Cover
            'use_featured_image' => 'Use Cover', //Use Cover
            'filter_items_list' => 'Filter Novels Library', //Fitler the novels
            'filter_by_date' => 'Filter by release date', 
            'items_list_navigation' => 'Novels Library navigation', //Label for the table pagination
            'items_list' => 'Novels Library', //Novels list
            'item_published' => 'Novel published', //Novel published
            'item_published_privately' => 'Novel published privately', //Novel published privately
            'item_reverted_to_draft' => 'Novel reverted to Draft', //Novel reverted to draft
            'item_scheduled' => 'Novel release scheduled', //Novel release scheduled
            'item_updated' => 'Novel Updated', //Novel updated
            'item_link' => 'Novel Link', //Title for Nav Link
            'item_link_description' => 'A link to a Novel', //Title for the Block Variation
        );

        //Options for the Novel Custom Post Type  
        $args = array(
            'label'               => 'Novel', //the name shown in the menu
            'description'         => 'All novels data', //The desctription of the post type 
            'labels'              => $labels, //All the labels inserted using an array
            'public'              => true, //Visibility
            'hierarchical'        => true, //If sub novels possible
            'exclude_from_search' => false, //If to exclude from search
            'publicly_queryable'  => true, //For public
            'show_ui'             => true, //Show in User Interface
            'show_in_menu'        => true, //Show in Menu
            'show_in_nav_menus'   => true, //Show in Nav Menu
            'show_in_admin_bar'   => true, //Show in Admin Bar
            'show_in_rest'        => true, //If to include the post type in Rest API
            'rest_base'           => "novels", //REST API base URL
            'menu_position'       => null, //Menu index position
            'menu_icon'           => 'dashicons-book', //Menu Icon
            'capability_type'     => 'post', //Capability required for the novel post type
            'map_meta_cap'        => true, //Whether to use the internal default meta map capability handling
            
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title', 'author', 'comments', 'thumbnail', 'revisions', 'custom-fields','page-attributes'),

            'register_meta_box_cb' => null, //Callback function to setup the metabox in edit form

            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies'          => array( 'genre', 'publisher', 'writer', 'translator', 'post_tag', 'illustrator','novel_status','language'),

            'has_archive' => true, //Whether the post type has archive

            'rewrite'   => [ //Post Types rewrite
                            'slug'  => 'novel', //slug
                            'with_front'    => true,
                            'feeds' => false, //if to generate feeds
                            'pages' => false, //IF permastruct should provide for pagination
            ],

            'query_var' => 'novel',
            'can_export'          => true, //Export Functionality
            'delete_with_user'  => false, //Whether to delete the post type with the user

            /*
                Post Type Template and Template Lock
            */
        );

        //Register the Novel post type
        register_post_type( 'novel', $args );
    }

    public function register_routes(){
        register_rest_route( 'lnarchive/v1', 'novel_filters', array( //Register Comment Submit Route
            'methods' => 'GET', //Method
            'callback' => [ $this, 'get_novel_filters'], //Callback after receving request
            'permission_callback' => function(){ //Permission Callback
                return is_user_logged_in();
            },
        ));
    }

    public function get_novel_filters( $request ){ //Function to return novels filter taxonomy data

        $filter_taxonomies = array( 'novel_status', 'language', 'publisher', 'writer', 'illustrator' );
        $response = array();

        foreach( $filter_taxonomies as $tax){

            $terms = get_terms( $tax, array(
                'hide_empty' => true,
            )); //Get all the term objects
            
            $terms_list=array();
            foreach( $terms as $term){
                array_push($terms_list, array(
                    'term_id' => $term->term_id,
                    'term_name' => $term->name,  
                ));
            }

            array_push($response, array(
                'tax_label' => get_taxonomy($tax)->label,
                'list' => $terms_list,
            ));
        }

        return $response;
    }

    public function auto_novel( $post_id ){ //Auto update Novel Post Type

        $status = wp_get_post_terms( $post_id, 'novel_status');
        
        if( $status != null ){
            $oneshot = 'Oneshot';
            $args = array(
                    'posts_per_page' => -1,
                    'numberposts' => -1,
                    'post_type' => 'volume',
                    'meta_key'     => 'series_value',
                    'meta_value'   => $post_id,
            );
            $posts = get_posts( $args);

            if( has_tag($oneshot) && (count($posts) != 1 || $status[0]->name !='Completed'))
                wp_remove_object_terms($post_id, $oneshot, 'post_tag'); //Remove the term */
            else if( count($posts) == 1 && $status[0]->name =='Completed')
                wp_set_post_terms( $post_id, [$oneshot], 'post_tag', true);
        }
    }
}//End of Class
?>