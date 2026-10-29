<?php get_header(); ?>

    <main class="project-single-page">
        <?php while ( have_posts() ) : the_post();

            // Получаем все наши метаполя
            $client      = get_post_meta( get_the_ID(), '_project_client', true );
            $role        = get_post_meta( get_the_ID(), '_project_role', true );
            $duration    = get_post_meta( get_the_ID(), '_project_duration', true );
            $challenge   = get_post_meta( get_the_ID(), '_project_challenge', true );
            $solution    = get_post_meta( get_the_ID(), '_project_solution', true );
            $github_url  = get_post_meta( get_the_ID(), '_github_url', true );
            $demo_url    = get_post_meta( get_the_ID(), '_demo_url', true );

            // Технологии
            $technologies = get_the_terms( get_the_ID(), 'technology' );
            ?>

            <!-- HERO SECTION -->
            <section class="ps-hero">
                <div class="container">
                    <h1 class="ps-title"><?php the_title(); ?></h1>

                    <p class="ps-subtitle">
                        <?php
                        $raw_excerpt = get_the_excerpt();
                        if ( ! empty( $raw_excerpt ) ) {
                            $clean_text = wp_strip_all_tags( $raw_excerpt );
                            $clean_text = preg_replace( '/\s+/', ' ', $clean_text );
                            echo esc_html( trim( $clean_text ) );
                        } else {
                            echo esc_html( strip_tags( wp_trim_words( get_the_content(), 30, '...' ) ) );
                        }
                        ?>
                    </p>

                    <!-- Кнопки действий -->
                    <div class="ps-actions">
                        <?php if(!empty($demo_url)): ?>
                            <a href="<?php echo esc_url($demo_url); ?>" target="_blank" class="btn btn-primary">Посмотреть Live ↗</a>
                        <?php endif; ?>

                        <?php if(!empty($github_url)): ?>
                            <a href="<?php echo esc_url($github_url); ?>" target="_blank" class="btn btn-secondary">Исходный код GitHub</a>
                        <?php endif; ?>

                        <a href="<?php echo home_url('/projects/'); ?>" class="btn btn-back">
                            ← Все проекты
                        </a>
                    </div>
                </div>

            </section>

            <!-- MAIN CONTENT GRID -->
            <div class="container ps-content-grid">

                <!-- LEFT COLUMN: Описание и Кейс -->
                <article class="ps-main-col">

                    <!-- Блок Задача/Решение -->
                    <?php if($challenge || $solution): ?>
                        <section class="ps-case-study">
                            <h2>Обзор проекта</h2>
                            <div class="case-cards">
                                <?php if($challenge): ?>
                                    <div class="case-card challenge">
                                        <div class="icon">🎯</div>
                                        <h3>Цели:</h3>
                                        <p><?php echo nl2br(esc_html($challenge)); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if($solution): ?>
                                    <div class="case-card solution">
                                        <div class="icon">💡</div>
                                        <h3>Решения:</h3>
                                        <p><?php echo nl2br(esc_html($solution)); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <!-- Основной текст (контент поста) -->
                    <section class="ps-description">
                        <h2>Подробности разработки</h2>
                        <div class="rich-text">
                            <?php the_content(); ?>
                        </div>
                    </section>

                    <!-- Галерея мокапов -->
                    <?php
                    $attachments = get_posts(array(
                            'post_type'      => 'attachment',
                            'posts_per_page' => -1,
                            'post_parent'    => get_the_ID(),
                            'mime_type'      => 'image',
                            'exclude'        => get_post_thumbnail_id()
                    ));
                    if (!empty($attachments)) : ?>
                        <section class="ps-gallery">
                            <h2>Интерфейс и Скриншоты</h2>
                            <div class="gallery-grid">
                                <?php foreach ($attachments as $att) :
                                    // Получаем массив [url, width, height]
                                    $img_data = wp_get_attachment_image_src($att->ID, 'large');
                                    if ($img_data) :
                                        $src = $img_data[0];
                                        $alt = get_post_meta($att->ID, '_wp_attachment_image_alt', true);
                                        if (!$alt) $alt = get_the_title(); // Фолбэк альтернативного текста
                                        ?>
                                        <figure class="gallery-item">
                                            <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy">
                                        </figure>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                </article>

                <!-- RIGHT SIDEBAR: Техстек и инфо -->
                <aside class="ps-sidebar">
                    <div class="sidebar-widget tech-stack-widget">
                        <h3>Технологии</h3>
                        <ul class="tech-list">
                            <?php if ($technologies && !is_wp_error($technologies)) : ?>
                                <?php foreach ($technologies as $term) : ?>
                                    <li><span class="tech-badge"><?php echo esc_html($term->name); ?></span></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="no-data">Нет данных о технологиях</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="sidebar-widget info-widget">
                        <h3>Информация</h3>
                        <dl class="info-dl">
                            <?php if($client): ?>
                                <dt>Клиент</dt>
                                <dd><?php echo esc_html($client); ?></dd>
                            <?php endif; ?>

                            <?php if($role): ?>
                                <dt>Моя роль</dt>
                                <dd><?php echo esc_html($role); ?></dd>
                            <?php endif; ?>

                            <?php if($duration): ?>
                                <dt>Продолжительность</dt>
                                <dd><?php echo esc_html($duration); ?></dd>
                            <?php endif; ?>

                            <dt>Дата публикации</dt>
                            <dd><?php echo get_the_date(); ?></dd>
                        </dl>
                    </div>
                </aside>

            </div>

            <!-- FOOTER CTA -->
            <section class="ps-footer-cta container">
                <h2>Понравился проект?</h2>
                <p>Давайте обсудим, как я могу помочь с вашим следующим продуктом.</p>
                <a href="#contact" class="btn btn-primary">Написать мне</a>
            </section>

        <?php endwhile; ?>
    </main>

<?php get_footer(); ?>