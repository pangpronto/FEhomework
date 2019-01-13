<?php if ( has_post_format( 'quote' ) ) { ?>
    <article class="content format-quote">
<?php } else { ?>
    <article class="content" itemtype="http://schema.org/BlogPosting" itemscope="">
<?php } ?>
    <h1><span itemprop="name"><?php echo get_the_title(); ?></span></h1>
    <meta content="<?php the_time('Y-m-j') ?>" itemprop="datePublished">
    <footer>
    <?php
        //Get child theme's name
        $themename = get_option( 'stylesheet' );
        $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
        $site_options = get_option($themename);

        $post_meta_date = $site_options['post_meta_date'];
        $post_meta_author = $site_options['post_meta_author'];
        $post_meta_categories = $site_options['post_meta_categories'];
        $post_meta_tags = $site_options['post_meta_tags'];

        if ( $post_meta_date ) {
            echo '<span class="date"><i class="fa fa-calendar"></i> ';
            the_time( 'F jS, Y' );
            echo '</span> ';
        }

        if ( $post_meta_author ) {
            echo '<span class="user"><div class="anthor-image">';
            echo get_avatar( get_the_author_meta('user_email') , 32 );
            echo '</div><span itemprop="author">';
            the_author_posts_link();
            echo '</span></span> ';
        }

        if ( $post_meta_categories ) {
            echo '<span class="category"><i class="fa fa-file"></i> <span itemprop="genre">';
            the_category( ', ' );
            echo '</span></span> ';
        }

        if ( $post_meta_tags ) {
            the_tags( '<span class="tag"><i class="fa fa-tag"></i> <span itemprop="keywords">', '</span>, <span itemprop="keywords">', '</span></span>' );
        }

    ?>
    </footer>
    <?php
    $arg_defaults = array(
        'width'              => 870,
        'height'             => 272,
        'crop'               => true,
        'crop_from_position' => 'center,center',
        'resize'             => true,
        'cache'              => true,
        'default'            => null,
        'jpeg_quality'       => 70,
        'resize_animations'  => false,
        'return'             => 'url',
        'background_fill'    => null
        );
    $syndicate_image = get_post_custom_values('syndicates_image_1', get_the_ID());
    global $post;
    $post_date = new DateTime($post->post_date);
    $current_date = new DateTime('now');
    $interval = date_diff($current_date, $post_date);
    if($interval->y <= 2) {
        if(has_post_thumbnail()) {
            $image_src  = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full');
            echo '<figure class="clearfix">';
            echo '<img src="' . wpthumb( $image_src[0], $arg_defaults ) . '" class="img-responsive wp-post-image alignnone" alt="' . get_the_title() . '">';
            echo '</figure>';
        }
        else if($syndicate_image[0] != NULL) {
            echo '<figure class="clearfix">';
            echo '<img src="' . wpthumb( $syndicate_image[0], $arg_defaults ) . '" class="img-responsive wp-post-image alignnone" alt="' . get_the_title() . '">';
            echo '</figure>';
        }
    }
    else {
        $blog_engine_custom_field = get_post_meta($post->ID, 'icon', true);
        if ($blog_engine_custom_field == 'http://www.techadvisory.org/favicon.ico') {
            add_filter('the_content', 'remove_img');
        }
    }
    ?>
    <div itemprop="articleBody">
        <?php the_content();  ?>
    </div>
</article>
<hr>

<div class="share-block"></div>

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

<div class="single-post-ad-block"></div>

           
<?php 
$orig_post = $post;
    global $post;
    $tags = wp_get_post_tags($post->ID);
    if ($tags) {
        $tag_ids = array();
        foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
        $args=array(
            'tag__in' => $tag_ids,
            'post__not_in' => array($post->ID),
            'posts_per_page'=>4, // Number of related posts that will be shown.
            'caller_get_posts'=>1
        );
    $my_query = new wp_query( $args );
    if( $my_query->have_posts() ) {

        echo '<h3 class="font-15 uppercase">You may also like</h3><div class="related row">';

    while( $my_query->have_posts() ) {
        $my_query->the_post(); ?>
        <div class="col-md-6">
        <a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>">
        <article style="background: url('<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full')[0]?>') no-repeat scroll center center /cover;">
            <div class="related-title"><h3><?php the_title(); ?></h3></div>
        </article></a>
        </div>

    <?php }
    echo '</div>';
    }


}
$post = $orig_post;
wp_reset_query();    


