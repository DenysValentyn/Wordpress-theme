<?php
/**
 * Novel template
 * 
 * @package LNarchive
*/
get_header(); //Get the Header function

$the_post = get_post();
$the_post_id = get_the_ID(); //Get the Post ID
$the_post_title = get_the_title(); //Get the Title
$the_post_type = get_post_type( $the_post_id ); //Get the Post Type
$max_posts = get_option('posts_per_page'); //Get the max posts value
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div id="<?php echo esc_attr($the_post_id);?>" class="row main-row"> <!-- Main Row -->
        <div class="novel-wrap col-lg-9"> <!-- Novel Content Div -->
        <?php               
            if( have_posts(  ) ) {  //If there are posts
            while( have_posts(  )) : the_post(); //Loop through the posts
                
                //Title
                printf(
                    '<h1 class="page-title">%1$s</h1>', //HTML
                    wp_kses_post( $the_post_title), //Get the Title
                );
                ?>
                    <div class="info-section"> <!-- Novel Info Div -->
                        <div class="row novel-row">
                            <div class="novel-cover-div col-lg-4 col-md-4 cold-sm-12">
                                <div class="row"> <!-- Novel Cover Row -->
                                    <div class="col"> <!-- Novel Cover Col -->
                                        <?php 
                                            if (has_post_thumbnail( $the_post_id )) { //If the novel has a thumbnail
                                                the_post_custom_thumbnail(
                                                    $the_post_id, //The novel ID
                                                    'novel-cover', //Name of the size
                                                    [
                                                        'class' => 'novel-cover', //Class attachment for css
                                                        'alt'  => esc_html($the_post_title), //Attach the title as the default alt for the img
                                                    ]
                                                );
                                            }
                                        ?>
                                    </div>

                                    <table class="novel-info-table col-lg-12 col-md-12 cold-sm-12 col-6"> <!-- Novel Taxonomies Col -->
                                        <?php
                                            //List of all Iterms to display
                                            $taxs = array('novel_status', 'language', 'publisher', 'format','writer', 'illustrator', 'translator');

                                            foreach( $taxs as $tax) { //Loop through all items
                                                $terms = get_the_terms($the_post_id, $tax); //Get all the Terms

                                                if( !empty($terms)) { //If there are no terms
                                                ?>
                                                <tr>
                                                    <th><?php echo esc_attr(get_taxonomy_labels(get_taxonomy($tax))->name)?><th> <!-- Display the Name Label -->
                                                    <td>
                                                        <?php                   
                                                            foreach( $terms as $key => $article_term) { //Loops through all article terms
                                                                ?>
                                                                    <a href="<?php echo esc_attr(get_term_link($article_term, $tax))?>"><?php echo esc_html($article_term->name)?></a> <!-- Entry -->
                                                                    <br> <!-- New Line for next entry -->
                                                                <?php
                                                            }                     
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                }
                                            }  
                                        ?>
                                        <tr>
                                            <th>Volumes</th>
                                            <th></th>
                                            <td id="volume-no">0</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="novel-info col"> <!-- Novel Info Col -->
                                <h2>Description</h2><?php //Desc Title
                                the_excerpt(); //Get the excerpt

                                $alt_names = get_post_meta( $the_post_id, 'alternate_names_value', true ); //Get the Alt Name field
                                $alt_names_array = explode( ",", $alt_names ); //Separate the multiple names using the comma

                                if( !empty( $alt_names_array[0] )){ //If there are alternate names
                                ?>
                                    <h4>Alternate Names</h4> <!-- Title -->
                                    <p>
                                        <?php
                                            foreach( $alt_names_array as $alt) { //Loop through all the names
                                                ?>
                                                    <?php echo $alt;//Print the name?>
                                                    <br> <!--Break the Line -->
                                                <?php
                                            }
                                        ?>
                                    </p>
                                    <?php
                                }                           

                                $genre_terms = get_the_terms($the_post_id, 'genre'); //Get the genres
                                $tag_terms = get_the_terms($the_post_id, 'post_tag'); //Get the tags

                                if( !empty( $genre_terms )) { //If there are genres assigned
                                    ?><h3>Genre</h3><?php //Genre Title
                                    taxonomy_button_list( $genre_terms , 'genre'); //List the Genre Taxonomy
                                }

                                if( !empty( $tag_terms ) && $tag_terms[0]->name != 'None'){ //Perform static check that the only tag is not None and there are tags
                                    ?><h3>Tag</h3><?php //Tag Title
                                    taxonomy_button_list( $tag_terms, 'post-tag'); //List the Tag Taxonomy
                                }

                                get_template_part('template-parts/edit-btn'); //Get the Edit Button Template
                                ?>
                            </div>
                        </div>
                    </div>                       
                <?php

                    $vol_args = array(  //Arguments for the Loop
                        'post_type' => 'volume', //Post Type
                        'posts_per_page' => $max_posts, //Posts on one page
                        'orderby' => 'date', //Order by date
                        'order' => 'ASC', //ASC or DEC
                        'meta_key' => 'series_value', //Meta Key
                        'meta_value' => $the_post_id, //Meta value
                    );                       

                    $vquery = new WP_Query($vol_args); //Volumes List Query

                    if($vquery->have_posts()) { //If there are any volumes
                        ?>
                            <div class="volumes-section">
                                <h2>Volumes</h2> <!-- Volumes Section Heading -->
                                <?php novel_list( $vquery, 'volume'); //Print Novel List ?>
                            </div>
                        <?php
                    }

                    wp_reset_postdata(); //Reset the $POST data

                    $universe_novels = array_merge( get_post_siblings( $the_post_id ), get_post_ancestors( $the_post_id ), get_post_children( $the_post_id ) ); //Get the novels in the same Universe

                    if( !empty($universe_novels) ) {
                        $uquery_args = array( //Arguments for the Loop
                            'post_type' => $the_post_type, //Post Types
                            'posts_per_page' => -1, //Posts on one page
                            'orderby' => 'rand', //Order by date
                            'post__not_in'   => array( $the_post_id ), //Exclude the current post
                            'post__in' => $universe_novels, //The queried posts should be present in the Universe Novels List
                        );

                        $uquery = new WP_Query( $uquery_args ); //Children List Query

                        if($uquery->have_posts()) { //If there are any children
                            ?>
                                <div class="child-section">
                                    <h2>Novels from same Universe</h2> <!-- Child Novels Section Heading -->
                                    <?php novel_list( $uquery, 'child' ); //Print Novel List?>
                                </div>
                            <?php
                        }

                        wp_reset_postdata(); //Reset the $POST data
                    }

                    $rtags = array(); //Initialize an empty array to store all the term ids of the tags

                    foreach( $tag_terms as $tag ){ //Loop through all the post tags
                        array_push( $rtags, $tag->term_id ); //Push the tag into the array
                    }

                    $related_args = array(  //Arguments for the Loop
                        'post_type' => $the_post_type, //Post Type
                        'posts_per_page' => $max_posts, //Posts on one page
                        'orderby' => 'rand', //Order by date
                        'tag__in' => $rtags, //Tag
                        'post__not_in'   => array_merge( $universe_novels ,array($the_post_id) ), //Exclude the post itself
                    );

                    $rquery = new WP_Query($related_args); //Related Posts Query

                    if($rquery->have_posts()) { //If there are any related posts
                        ?>
                            <div class="related-section">
                                <h2>Recommendations</h2> <!-- Related Section Heading -->
                                <?php novel_list( $rquery, 'child' ); //Print Novel List?>
                            </div>
                        <?php
                    }

                    wp_reset_postdata(); //Reset the $POST data

                    $args = array(  //Arguments for the Loop
                        'post_type' => 'post', //Post Type
                        'posts_per_page' => 3, //Posts on one page
                        'meta_key' => 'series_value', //Meta Key
                        'meta_value' => $the_post_id, //Meta value
                    );

                    $loop = new WP_Query( $args ); //Create the Loop

                    if($loop->have_posts()) { //If there is post
                        ?>
                            <div class="posts-section">
                                <div class="row"> <!-- Novel News Articles Div -->
                                    <h2>Related Articles</h2>
                                    <?php post_list( $loop, 'novel-articles' ); //Post List?>
                                </div>
                            </div>
                        <?php
                    }
        
                    wp_reset_postdata(); //Reset the $POST data
                    ?>
                        <h2 class="d-flex justify-content-center review-title">Reviews</h2> <!--Review Section Heading-->
                    <?php
                        comments_template('/reviews.php'); //Get the Comments Template
            endwhile;
            }
        ?>
        </div>
        <div class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-novel'); //Get the Sidebar?>
        </div>
    </div>
</main>

<?php get_footer();?> <!-- Get the Footer -->