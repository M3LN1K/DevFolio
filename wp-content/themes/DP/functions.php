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


function register_project_cpt() {
    $labels = array(
        'name'               => _x( 'Проекты', 'post type general name' ),
        'singular_name'      => _x( 'Проект', 'post type singular name' ),
        'add_new'            => _x( 'Добавить новый', 'project' ),
        'add_new_item'       => __( 'Добавить новый проект' ),
        'edit_item'          => __( 'Редактировать проект' ),
        'new_item'           => __( 'Новый проект' ),
        'view_item'          => __( 'Просмотреть проект' ),
        'search_items'       => __( 'Искать проекты' ),
        'not_found'          => __( 'Проекты не найдены' ),
        'not_found_in_trash' => __( 'Нет проектов в корзине' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Проекты'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'projects' ), // Чистые URL: site.com/projects/nazvanie-proekta
        'capability_type'    => 'post',
        'has_archive'        => true, // Включает архивную страницу /projects/
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
    );

    register_post_type( 'project', $args );
}
add_action( 'init', 'register_project_cpt' );

function register_technology_taxonomy() {
    $labels = array(
        'name'              => _x( 'Технологии', 'taxonomy general name' ),
        'singular_name'     => _x( 'Технология', 'taxonomy singular name' ),
        'search_items'      => __( 'Искать технологии' ),
        'all_items'         => __( 'Все технологии' ),
        'edit_item'         => __( 'Редактировать технологию' ),
        'update_item'       => __( 'Обновить технологию' ),
        'add_new_item'      => __( 'Добавить новую технологию' ),
        'new_item_name'     => __( 'Новое имя технологии' ),
        'menu_name'         => __( 'Технологии' ),
    );

    $args = array(
        'hierarchical'      => false, // Как теги (без древовидной структуры)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'technology' ),
    );

    // Привязываем таксономию к нашему типу записей 'project'
    register_taxonomy( 'technology', array( 'project' ), $args );
}
add_action( 'init', 'register_technology_taxonomy' );

/**
 * Регистрируем метабокс для проекта
 */
function add_project_metabox() {
    add_meta_box(
        'project_details_box',      // ID блока
        'Детали проекта',           // Заголовок блока
        'render_project_metabox',   // Функция, которая рисует HTML внутри блока
        'project',                  // Тип поста, к которому привязываем (наш CPT)
        'side',                     // Расположение: 'normal' (внизу), 'side' (справа), 'advanced'
        'default'                   // Приоритет
    );
}
add_action( 'add_meta_boxes', 'add_project_metabox' );

/**
 * Рисуем форму внутри метабокса
 */
function render_project_metabox( $post ) {
    // Получаем текущие значения, если они уже сохранены
    $github_url = get_post_meta( $post->ID, '_github_url', true );
    $demo_url   = get_post_meta( $post->ID, '_demo_url', true );

    // Не забываем про nonce для безопасности
    wp_nonce_field( 'save_project_meta', 'project_meta_nonce' );
    ?>
    <p>
        <label for="_github_url"><strong>Ссылка на GitHub:</strong></label><br/>
        <input type="url" id="_github_url" name="_github_url" value="<?php echo esc_attr($github_url); ?>" style="width:100%;" placeholder="https://github.com/user/repo" />
    </p>
    <p>
        <label for="_demo_url"><strong>Ссылка на Демо (Live):</strong></label><br/>
        <input type="url" id="_demo_url" name="_demo_url" value="<?php echo esc_attr($demo_url); ?>" style="width:100%;" placeholder="https://example.com" />
    </p>
    <?php
}

/**
 * Сохраняем данные при публикации/обновлении
 */
function save_project_meta( $post_id ) {
    // Проверка безопасности (Nonce)
    if ( ! isset( $_POST['project_meta_nonce'] ) || ! wp_verify_nonce( $_POST['project_meta_nonce'], 'save_project_meta' ) ) {
        return;
    }

    // Проверка прав пользователя
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Сохраняем GitHub URL
    if ( isset( $_POST['_github_url'] ) ) {
        update_post_meta( $post_id, '_github_url', sanitize_text_field( $_POST['_github_url'] ) );
    }

    // Сохраняем Demo URL
    if ( isset( $_POST['_demo_url'] ) ) {
        update_post_meta( $post_id, '_demo_url', sanitize_text_field( $_POST['_demo_url'] ) );
    }
}
add_action( 'save_post_project', 'save_project_meta' );