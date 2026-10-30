<?php
/**
 * The main template file for GridBot Command Center.
 * Front page: the built-in landing page. Everything else: a post listing.
 *
 * @package GridBot_Command_Center
 */

get_header();

if (is_front_page() || is_home() && !have_posts()) {
    get_template_part('template-parts/landing-page');
} else {
    ?>
    <main id="main-content" class="site-main archive-main">
        <header class="page-header">
            <h1 class="page-title"><?php
                if (is_home()) { echo esc_html(get_bloginfo('name')); }
                else { the_archive_title(); }
            ?></h1>
        </header>
        <?php if (have_posts()) : ?>
            <div class="archive-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('card-3d archive-card'); ?>>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-summary"><?php the_excerpt(); ?></div>
                </article>
            <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('Nothing published yet.', 'gridbot-command-center'); ?></p>
        <?php endif; ?>
    </main>
    <?php
}

get_footer();
