<?php
/**
 * Template for static pages. Shortcodes in page content render here.
 *
 * @package GridBot_Command_Center
 */

get_header();
?>
<main id="main-content" class="site-main page-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('page-article'); ?>>
            <?php if (!is_front_page()) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </header>
            <?php endif; ?>
            <div class="page-content entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_footer();
