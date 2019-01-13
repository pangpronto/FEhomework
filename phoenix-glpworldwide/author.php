<?php
/**
 * The main template file
 * Display author detail (show on the blog post)
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Phoenix
 */
?>
<div class="row author-blog">
    <div class="col-md-12">
        <div class="well well-author">
            <div class="anthor-image">
                <?php echo get_avatar( get_the_author_meta('user_email') , 125 )?>
            </div>
            <div class="author-quote">
                <h3><strong><?php echo get_the_author_meta('user_firstname'); ?> <?php echo get_the_author_meta('user_lastname'); ?></strong></h3>
                <h4><?php echo get_the_author_meta('position'); ?></h3>
                <p><?php echo get_the_author_meta('user_description'); ?></p>
            </div>
        </div>
    </div>
</div>