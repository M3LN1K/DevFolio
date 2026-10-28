<?php get_header(); ?>

<div class="portfolio-grid container">

    <h1>Мои Проекты</h1>

    <?php
    // Настраиваем запрос для вывода всех проектов
    $args = array(
        'post_type'      => 'project',
        'posts_per_page' => -1, // Или число для пагинации, например 9
        'orderby'        => 'date',
        'order'          => 'DESC'
    );
    $the_query = new WP_Query( $args );
    ?>

    <?php if ( $the_query->have_posts() ) : ?>

        <ul class="projects-list">
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

                <li class="project-card">
                    <!-- Ссылка на детальную страницу -->
                    <a href="<?php the_permalink(); ?>">

                        <!-- Изображение -->
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="card-image">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                            </div>
                        <?php else : ?>
                            <div class="card-placeholder">Нет изображения</div>
                        <?php endif; ?>

                        <!-- Информация о проекте -->
                        <div class="card-info">
                            <h3><?php the_title(); ?></h3>

                            <!-- Краткое описание (Excerpt) -->
                            <p><?php the_excerpt(); ?></p>

                            <!-- Теги технологий (можно выводить через таксономию или кастомные поля) -->
                            <div class="tech-tags">
                                <?php
                                // Пример вывода тегов как меток технологий
                                $tags = get_the_terms( get_the_ID(), 'tag' );
                                if ( $tags && ! is_wp_error( $tags ) ) {
                                    foreach ( $tags as $tag ) {
                                        echo '<span class="tag">' . esc_html( $tag->name ) . '</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </a>
                </li>

            <?php endwhile; ?>
        </ul>

        <!-- Пагинация, если posts_per_page ограничен -->
        <div class="pagination">
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '← Назад',
                'next_text' => 'Вперёд →',
            ) );
            ?>
        </div>

    <?php else : ?>
        <p>Проектов пока нет.</p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>

</div>

<?php get_footer(); ?>
