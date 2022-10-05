<?php
/**
 * Novel Post Type Taxonomies
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class novel_tax{ //Novel Taxonomy Class

    use Singleton; //Using Sinlgetons

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks Function
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'init', [ $this, 'register_novel_volume_taxonomies']);
        add_action('save_post',[ $this, 'save_post_function']);
    }

    public function register_novel_volume_taxonomies() { //Register all the novel taxonomies

        //Register Publisher Taxonomy
        register_taxonomy('publisher', ['novel'], array(
            
            //All Publisher Labels
            'labels' => array(
                'name' => 'Publisher', //General Name
                'singular_name' => 'Publisher', //Singular Taxonomy Name
                'search_items' =>  'Search Publisher', //Search
                'all_items' => 'All Publishers', //List of all
                'parent_item' => 'Parent Publisher', //Parent
                'parent_item_colon' => 'Parent Publisher: ', //Parent with colon
                'name_field_description' => 'Name of the Publisher/Publishing Label', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'parent_field_description' => 'Assign a publisher if its a publishing label.', //Desc for the Parent field
                'desc_field_description' => 'A short informational description of the publisher/publishing label', //Desc of the Description field
                'edit_item' => 'Edit Publisher', //Edit
                'view_item' => 'View Publisher', //View
                'update_item' => 'Update Publisher', //Update
                'add_new_item' => 'Add New Publisher', //Add New
                'new_item_name' => 'New Publisher Name', //New Item Name
                'not_found' => 'No publishers found', //Not Found Msg
                'no_terms' => 'No publishers', //Post and Media tables
                'filter_by_item' => 'FIlter by Publisher', //Filter msg
                'most_used' => 'Most Used Publisher', //Most Used Msg
                'back_to_items' => 'Back to Publishers', //Back to Publishers List
                'item_link' => 'Publisher Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the publisher', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Publisher', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'publisher', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A company or label publishing the novels', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'publisher', //Query name for the wp_query
            'hierarchical' => true, //Hierarchy

            //Default Publisher Term
            'default_term' => array(
                'name' => 'No Publisher', //Name
                'slug' => 'no_publisher', //Slug
                'description' => 'Default term for when no publisher is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'publisher',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Publisher Taxonomy

        //Register Author Taxonomy
        register_taxonomy('writer', ['novel'], array(
            
            //All Author Labels
            'labels' => array(
                'name' => 'Author', //General Name
                'singular_name' => 'Author', //Singular Taxonomy Name
                'search_items' =>  'Search Author', //Search
                'popular_items' => 'Popular Authors', //Popular
                'all_items' => 'All Authors', //List of all
                'name_field_description' => 'Name of the Author of the novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the Author', //Desc of the Description field
                'edit_item' => 'Edit Author', //Edit
                'view_item' => 'View Auhtor', //View
                'update_item' => 'Update Author', //Update
                'add_new_item' => 'Add New Author', //Add New
                'new_item_name' => 'New Author Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove author', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No author found', //Not Found Msg
                'no_terms' => 'No authors', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Authors', //Back to Authors List
                'item_link' => 'Author Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the author', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Author', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'writer', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'An author is the creator or originator of any written work', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'writer', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Writer Term
            'default_term' => array(
                'name' => 'No Author', //Name
                'slug' => 'no_writer', //Slug
                'description' => 'Default term for when no author is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'writer',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Author Taxonomy

        //Register Illustrator Taxonomy
        register_taxonomy('illustrator', ['novel'], array(
            
            //All Illustrator Labels
            'labels' => array(
                'name' => 'Illustrator', //General Name
                'singular_name' => 'Illustrator', //Singular Taxonomy Name
                'search_items' =>  'Search Illustrator', //Search
                'popular_items' => 'Popular Illustrators', //Popular
                'all_items' => 'All Illustrators', //List of all
                'name_field_description' => 'Name of the Illustrator of the novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the Illustrator', //Desc of the Description field
                'edit_item' => 'Edit Illustrator', //Edit
                'view_item' => 'View Illustrator', //View
                'update_item' => 'Update Illustrator', //Update
                'add_new_item' => 'Add New Illustrator', //Add New
                'new_item_name' => 'New Illustrator Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove illustrator', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No illustrator found', //Not Found Msg
                'no_terms' => 'No illustrators', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Illustrators', //Back to List
                'item_link' => 'Illustrator Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the illustrator', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Illustrator', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'illustrator', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'An illustrator is an artist who specializes in enhancing writing or elucidating concepts by providing a visual representation that corresponds to the content of the associated text or idea.',
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'illustrator', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Illustrator Term
            'default_term' => array(
                'name' => 'No Illustrator', //Name
                'slug' => 'no_illustrator', //Slug
                'description' => 'Default term for when no illustrator is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'illustrator',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Illustrator Taxonomy

        //Register Language Taxonomy
        register_taxonomy('language', ['novel'], array(
            
            //All Language Labels
            'labels' => array(
                'name' => 'Language', //General Name
                'singular_name' => 'Language', //Singular Taxonomy Name
                'search_items' =>  'Search Language', //Search
                'popular_items' => 'Popular Languages', //Popular
                'all_items' => 'All Languages', //List of all
                'name_field_description' => 'Name of the language of the novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the language', //Desc of the Description field
                'edit_item' => 'Edit Language', //Edit
                'view_item' => 'View Language', //View
                'update_item' => 'Update Language', //Update
                'add_new_item' => 'Add New Language', //Add New
                'new_item_name' => 'New Language Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove language', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No language found', //Not Found Msg
                'no_terms' => 'No languages', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Languages', //Back to List
                'item_link' => 'Language Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the language', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Language', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'language', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'The source language of the novel from which its translated from.',
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'language', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Language Term
            'default_term' => array(
                'name' => 'Japanese', //Name
                'slug' => 'japanese', //Slug
                'description' => 'Official language of Japan and primary language of the light novels.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'language',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Language Taxonomy

        //Register Status Taxonomy
        register_taxonomy('novel_status', ['novel'], array(
            
            //All Status Labels
            'labels' => array(
                'name' => 'Status', //General Name
                'singular_name' => 'Status', //Singular Taxonomy Name
                'search_items' =>  'Search Statsu type', //Search
                'popular_items' => 'Popular Status types', //Popular
                'all_items' => 'All Status types', //List of all
                'name_field_description' => 'Name of the Status type', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the status', //Desc of the Description field
                'edit_item' => 'Edit Status', //Edit
                'view_item' => 'View Status', //View
                'update_item' => 'Update Status', //Update
                'add_new_item' => 'Add New Status', //Add New
                'new_item_name' => 'New Status Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove status type', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No status types found', //Not Found Msg
                'no_terms' => 'No status types', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Status', //Back to List
                'item_link' => 'Status Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the status', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Status', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'novel_status', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'The current publishing status of the series.',
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'status', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Status Term
            'default_term' => array(
                'name' => 'Ongoing', //Name
                'slug' => 'ongoing', //Slug
                'description' => 'The novel is in-print that is the story is ongoing.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'status',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Status Taxonomy

        //Register Translator Taxonomy
        register_taxonomy('translator', ['novel', 'volume'], array(
            
            //All Translator Labels
            'labels' => array(
                'name' => 'Translator', //General Name
                'singular_name' => 'Translator', //Singular Taxonomy Name
                'search_items' =>  'Search Translator', //Search
                'popular_items' => 'Popular Translators', //Popular
                'all_items' => 'All Translators', //List of all
                'name_field_description' => 'Name of the Translator of the novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the Translator', //Desc of the Description field
                'edit_item' => 'Edit Translator', //Edit
                'view_item' => 'View Translator', //View
                'update_item' => 'Update Translator', //Update
                'add_new_item' => 'Add New Translator', //Add New
                'new_item_name' => 'New Translator Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove translator', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No translator found', //Not Found Msg
                'no_terms' => 'No translators', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Translators', //Back to Translators List
                'item_link' => 'Translator Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the translator', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Translator', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'translator', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A translator is a person who translates from one language into another', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'translator', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Translator Term
            'default_term' => array(
                'name' => 'No Translator', //Name
                'slug' => 'no_translator', //Slug
                'description' => 'Default term for when no translator is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'translator',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Translator Taxonomy

        //Register Genre Taxonomy
        register_taxonomy('genre', ['novel'], array(
            
            //All Genre Labels
            'labels' => array(
                'name' => 'Genre', //General Name
                'singular_name' => 'Genre', //Singular Taxonomy Name
                'search_items' =>  'Search Genre', //Search
                'all_items' => 'All Genres', //List of all
                'parent_item' => 'Parent Genre', //Parent
                'parent_item_colon' => 'Parent Genre: ', //Parent with colon
                'name_field_description' => 'Name of the Genre', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'parent_field_description' => 'Assign a parent genre if its a sub-genre.', //Desc for the Parent field
                'desc_field_description' => 'A short informational description of the genre', //Desc of the Description field
                'edit_item' => 'Edit Genre', //Edit
                'view_item' => 'View Genre', //View
                'update_item' => 'Update Genre', //Update
                'add_new_item' => 'Add New Genre', //Add New
                'new_item_name' => 'New Genre Name', //New Item Name
                'not_found' => 'No genres found', //Not Found Msg
                'no_terms' => 'No genres', //Post and Media tables
                'filter_by_item' => 'FIlter by Genre', //Filter msg
                'most_used' => 'Most Used Genre', //Most Used Msg
                'back_to_items' => 'Back to Genres', //Back to Genres List
                'item_link' => 'Genre Link', //Genre Link in Block Editor
                'item_link_description' => 'A link to a genre', //Desc for Genre Link in Block Editor
                'menu_name' => 'Genre', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'genre', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A category of literary composition characterized by a particular style, form, or content', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'genre', //Query name for the wp_query
            'hierarchical' => true, //Hierarchy

            //Default Genre Term
            'default_term' => array(
                'name' => 'No Genre', //Name
                'slug' => 'no_genre', //Slug
                'description' => 'Default term for when no genre is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'genre',
                'with_front' => false, //Hide the base slug that is genre
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Genre Taxonomy
    }

    public function save_post_function($post_id) { //Default Tag

        $tags = get_terms('post_tag'); //Get all the tags
        
        if(empty($tags)) //If there are no tags already assigned
            wp_set_post_tags( $post_id, 'No Tag', true ); //Assign the default tag
        else if( count($tags)>1){ //If there are tags assigned
            foreach ($tags as $tag) { //Loop through all the tag terms
                //if category is the default, then remove it
                if ($tag->name == "No Tag") { //IF there is NO Tag with other tags
                    wp_remove_object_terms($post_id, 'No Tag', 'post_tag'); //Remove the Default Tag
                }
            }
        }
    }
}//End of Class
?>