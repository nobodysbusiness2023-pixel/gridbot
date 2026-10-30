<?php
/**
 * Template for single posts and custom post types.
 *
 * @package GridBot_Command_Center
 */

get_header();
?>
<main id="main-content" class="site-main single-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-article card-3d'); ?>>
            <header class="page-header">
                <h1 class="page-title"><?php the_title(); ?></h1>
                <p class="entry-meta"><?php echo esc_html(get_the_date()); ?></p>
            </header>
            <?php if (has_post_thumbnail()) : ?>
                <div class="entry-thumbnail"><?php the_post_thumbnail('large'); ?></div>
            <?php endif; ?>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_footer();
