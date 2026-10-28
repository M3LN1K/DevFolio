<?php
/*
Template Name: Portfolio Template
*/
get_header(); ?>



    <main class="site_content">
        <?php get_template_part('template-parts/page-index/section', 'hero') ?>
        <?php get_template_part('template-parts/page-index/section', 'stats') ?>
        <?php get_template_part('template-parts/page-index/section', 'projects') ?>
        <?php get_template_part('template-parts/page-index/section', 'skills') ?>
        <?php get_template_part('template-parts/page-index/section', 'experience') ?>
        <?php get_template_part('template-parts/page-index/section', 'contact') ?>
    </main>
<?php get_footer(); ?>