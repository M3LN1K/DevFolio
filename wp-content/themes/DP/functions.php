<?php
// Поддержка функций темы
function my_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    // Регистрация меню
    register_nav_menus( array(
        'primary' => esc_html__( 'Главное меню', 'my-custom-theme' ),
    ) );
}
add_action( 'after_setup_theme', 'my_theme_setup' );

// Правильное подключение CSS и JS
function my_theme_enqueue_scripts() {
    // Подключаем основной style.css
    wp_enqueue_style(
        'my-theme-style',
        get_stylesheet_uri(),
        array(),
        '1.0'
    );

    // CSS файлы в папке assets/css
    // wp_enqueue_style( 'my-theme-custom', get_template_directory_uri() . '/assets/css/custom.css', array(), '1.0' );

    // JS файлы в папке assets/js
    wp_enqueue_script(
        'my-theme-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        '1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_scripts' );
