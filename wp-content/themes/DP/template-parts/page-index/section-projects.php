<!-- Projects Section -->
<section id="projects">
    <div class="section-header fade-in">
        <h2>Мои проекты</h2>
        <div class="section-line"></div>
        <p>Вот некоторые из моих лучших работ</p>
    </div>

    <div class="projects-grid">
        <?php
        // Запрос к базе данных: получаем все посты типа 'project'
        $args = array(
                'post_type'      => 'project',
                'posts_per_page' => -1, // Выводим все проекты. Если нужно ограничить, поставьте число, например 6
                'orderby'        => 'date',
                'order'          => 'DESC'
        );

        $loop = new WP_Query( $args );

        if ( $loop->have_posts() ) :
            while ( $loop->have_posts() ) : $loop->the_post(); ?>

                <!-- Карточка проекта -->
                <div class="project-card fade-in">

                    <!-- Блок изображения/фона -->
                    <div class="project-image" style="background: linear-gradient(135deg, #7c3aed, #ec4899);">
                        <?php
                        // Проверяем, есть ли миниатюра у записи
                        if ( has_post_thumbnail() ) {
                            // Выводим изображение с классом project-thumb-img, как в вашем примере
                            the_post_thumbnail( 'medium_large', array( 'class' => 'project-thumb-img' ) );
                        } else {
                            // Если картинки нет, оставляем пустой div или заглушку
                            echo '<!-- Нет изображения -->';
                        }
                        ?>
                    </div>

                    <!-- Контент карточки -->
                    <div class="project-content">
                        <!-- Заголовок проекта -->
                        <h3><?php the_title(); ?></h3>

                        <!-- Описание (берем краткое описание Excerpt или обрезанный контент) -->
                        <p>
                            <?php
                            // Используем excerpt, если заполнено, иначе берем начало контента
                            if ( has_excerpt() ) {
                                the_excerpt();
                            } else {
                                echo wp_trim_words( get_the_content(), 20, '...' );
                            }
                            ?>
                        </p>

                        <!-- Теги технологий -->
                        <div class="project-tags">
                            <?php
                            // Получаем таксономию 'technology', которую мы регистрировали ранее
                            // Если вы еще не создали таксономию, временно используйте стандартные теги post_tag
                            $terms = get_the_terms( get_the_ID(), 'technology' );

                            // Fallback: если таксономии technology нет, пробуем обычные теги
                            if ( ! $terms || is_wp_error( $terms ) ) {
                                $terms = get_the_terms( get_the_ID(), 'post_tag' );
                            }

                            if ( $terms && ! is_wp_error( $terms ) ) :
                                foreach ( $terms as $term ) :
                                    // Определяем класс span в зависимости от названия термина (для AI тегов можно добавить логику позже)
                                    $tag_class = 'tag';

                                    // Пример логики для стиля __ai, если название содержит "AI"
                                    if ( strpos( strtolower($term->name), 'ai' ) !== false ) {
                                        $tag_class = 'tag__ai';
                                    }
                                    ?>
                                    <span class="<?php echo esc_attr($tag_class); ?>"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach;
                            endif; ?>
                        </div>

                        <!-- ССЫЛКИ (GitHub + Подробнее) -->
                        <div class="project-links">

                            <!-- 1. Кнопка "GitHub" -->
                            <?php
                            $github_url = get_post_meta( get_the_ID(), '_github_url', true );
                            if ( ! empty( $github_url ) ) : ?>
                                <a href="<?php echo esc_url($github_url); ?>" target="_blank" rel="noopener noreferrer" class="project-link github-btn">
                                    GitHub →
                                </a>
                            <?php endif; ?>

                            <!-- 2. Кнопка "Демо" (если нужна отдельная кнопка для live-сайта) -->
                            <?php
                            $demo_url = get_post_meta( get_the_ID(), '_demo_url', true );
                            if ( ! empty( $demo_url ) ) : ?>
                                <a href="<?php echo esc_url($demo_url); ?>" target="_blank" rel="noopener noreferrer" class="project-link demo-btn">
                                    Демо →
                                </a>
                            <?php endif; ?>

                            <!-- 3. Кнопка "Подробнее" (всегда видна, ведет на single-project.php) -->
                            <a href="<?php the_permalink(); ?>" class="project-link detail-btn">
                                Подробнее →
                            </a>
                        </div>
                    </div>
                </div>

            <?php endwhile;

            // Важно сбросить данные запроса после цикла
            wp_reset_postdata();

        else : ?>
            <p>Проекты пока не добавлены.</p>
        <?php endif; ?>
    </div>
</section>