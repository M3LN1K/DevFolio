<?php get_header(); ?>

<main class="container">
    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('project-detail'); ?>>

            <!-- Заголовок -->
            <header class="project-header">
                <h1><?php the_title(); ?></h1>
                <div class="meta-data">
                    <span>Дата: <?php echo get_the_date(); ?></span>
                    <span>Рубрика: <?php echo strip_tags(get_the_term_list( $id, 'category', '' , ', ' )); ?></span>
                </div>
            </header>

            <!-- Миниатюра (Featured Image) -->
            <?php if ( has_post_thumbnail() ) : ?>
                <figure class="project-hero-image">
                    <?php the_post_thumbnail( 'large' ); ?>
                </figure>
            <?php endif; ?>

            <!-- Контент проекта -->
            <section class="project-content">
                <?php the_content(); ?>
            </section>

            <!-- Дополнительные поля (если нужны, например, ссылка на GitHub) -->
            <!-- Лучше использовать Advanced Custom Fields (ACF), но можно и через meta_box вручную -->
            <?php
            $github_link = get_post_meta( get_the_ID(), '_github_url', true );
            if ( ! empty( $github_link ) ) {
                echo '<p><strong>GitHub:</strong> <a href="' . esc_url($github_link) . '" target="_blank">' . esc_html($github_link) . '</a></p>';
            }
            ?>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
