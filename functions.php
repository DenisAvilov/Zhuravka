<?php
/**
 * zhuravka functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zhuravka
 */

if ( ! function_exists( 'zhuravka_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function zhuravka_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on zhuravka, use a find and replace
		 * to change 'zhuravka' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'zhuravka', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( [
			'menu-1' => esc_html__( 'Primary', 'zhuravka' ),
			'main-menu' => 'меню на главной',
		]	
	    );

		

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'zhuravka_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;


add_action( 'after_setup_theme', 'zhuravka_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function zhuravka_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'zhuravka_content_width', 640 );
}

add_action( 'after_setup_theme', 'zhuravka_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function zhuravka_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'zhuravka' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'zhuravka' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Icons', 'zhuravka' ),
		'id'            => 'icons-1',
		'description'   => esc_html__( 'Add widgets here.', 'zhuravka' ),
		'before_widget' => '<nav class="soc">',
		'after_widget'  => '</nav>',		
		
	) );
}
add_action( 'widgets_init', 'zhuravka_widgets_init' );


function register_mypost_types(){
	register_post_type('zhuravka-header',[
   
		'labels' => [
	'name'               => '____', // основное название для типа записи
	'singular_name'      => '____', // название для одной записи этого типа
	'add_new'            => 'Добавить ____', // для добавления новой записи
	'add_new_item'       => 'Добавление ____', // заголовка у вновь создаваемой записи в админ-панели.
	'edit_item'          => 'Редактирование ____', // для редактирования типа записи
	'new_item'           => 'Новое ____', // текст новой записи
	'view_item'          => 'Смотреть ____', // для просмотра записи этого типа.
	'search_items'       => 'Искать ____', // для поиска по этим типам записи
	'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
	'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
	'parent_item_colon'  => '', // для родителей (у древовидных типов)
	'menu_name'          => 'Шапка главной', // название меню
],
	'description'         => '',
	'public'              => true,
	// 'publicly_queryable'  => null, // зависит от public
	// 'exclude_from_search' => null, // зависит от public
	// 'show_ui'             => null, // зависит от public
	// 'show_in_nav_menus'   => null, // зависит от public
	'show_in_menu'        => true, // показывать ли в меню адмнки
	// 'show_in_admin_bar'   => null, // зависит от show_in_menu
	'show_in_rest'        => null, // добавить в REST API. C WP 4.7
	'rest_base'           => null, // $post_type. C WP 4.7
	'menu_position'       => null,
	'menu_icon'           => null, 
	//'capability_type'   => 'post',
	//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
	//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
	'hierarchical'        => false,
	'supports'            => [ 'title', 'thumbnail','title','editor','excerpt','post-formats' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
	'taxonomies'          => [],
	'has_archive'         => false,
]) ; 
 register_post_type('zhuravka-content',[
   
	'labels' => [
'name'               => '____', // основное название для типа записи
'singular_name'      => '____', // название для одной записи этого типа
'add_new'            => 'Добавить слайд', // для добавления новой записи
'add_new_item'       => 'Добавление слайда', // заголовка у вновь создаваемой записи в админ-панели.
'edit_item'          => 'Редактирование слайда', // для редактирования типа записи
'new_item'           => 'Новой слайд', // текст новой записи
'view_item'          => 'Смотреть ____', // для просмотра записи этого типа.
'search_items'       => 'Искать ____', // для поиска по этим типам записи
'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
'parent_item_colon'  => '', // для родителей (у древовидных типов)
'menu_name'          => 'Слайдер топ', // название меню
],
'description'         => '',
'public'              => true,
// 'publicly_queryable'  => null, // зависит от public
// 'exclude_from_search' => null, // зависит от public
// 'show_ui'             => null, // зависит от public
// 'show_in_nav_menus'   => null, // зависит от public
'show_in_menu'        => true, // показывать ли в меню адмнки
// 'show_in_admin_bar'   => null, // зависит от show_in_menu
'show_in_rest'        => null, // добавить в REST API. C WP 4.7
'rest_base'           => null, // $post_type. C WP 4.7
'menu_position'       => null,
'menu_icon'           => null, 
//'capability_type'   => 'post',
//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
'hierarchical'        => false,
'supports'            => [ 'title', 'thumbnail','title','editor','excerpt','post-formats' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
'taxonomies'          => [],
'has_archive'         => false,
]);  
register_post_type('card-product',[
   
	'labels' => [
'name'               => '____', // основное название для типа записи
'singular_name'      => '____', // название для одной записи этого типа
'add_new'            => 'Добавить вид деятельности', // для добавления новой записи
'add_new_item'       => 'Добавление вида деятельности', // заголовка у вновь создаваемой записи в админ-панели.
'edit_item'          => 'Редактирование вид деятельности', // для редактирования типа записи
'new_item'           => 'Новой вид деятельности', // текст новой записи
'view_item'          => 'Смотреть ____', // для просмотра записи этого типа.
'search_items'       => 'Искать ____', // для поиска по этим типам записи
'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
'parent_item_colon'  => '', // для родителей (у древовидных типов)
'menu_name'          => 'Улуги', // название меню
],
'items_list'               => '',
'description'         => '',
'public'              => true,
// 'publicly_queryable'  => null, // зависит от public
'exclude_from_search' => false, // зависит от public
// 'show_ui'             => null, // зависит от public
// 'show_in_nav_menus'   => null, // зависит от public
'show_in_menu'        => true, // показывать ли в меню адмнки
// 'show_in_admin_bar'   => null, // зависит от show_in_menu
'show_in_rest'        => null, // добавить в REST API. C WP 4.7
'rest_base'           => null, // $post_type. C WP 4.7
'menu_position'       => null,
'menu_icon'           => null, 
//'capability_type'   => 'post',
//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
'hierarchical'        => false,
'supports'            => [ 'title', 'thumbnail','title','editor','excerpt','post-formats' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
'taxonomies'          => [],
'has_archive'         => false,
]) ; 
register_post_type('slider-job',[
   
	'labels' => [
'name'               => 'slider-job', // основное название для типа записи
'singular_name'      => 'sliderJob', // название для одной записи этого типа
'add_new'            => 'Добавить slide', // для добавления новой записи
'add_new_item'       => 'Добавление slide', // заголовка у вновь создаваемой записи в админ-панели.
'edit_item'          => 'Редактирование slide', // для редактирования типа записи
'new_item'           => 'Новой slide', // текст новой записи
'view_item'          => 'Смотреть ____', // для просмотра записи этого типа.
'search_items'       => 'Искать ____', // для поиска по этим типам записи
'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
'parent_item_colon'  => '', // для родителей (у древовидных типов)
'menu_name'          => 'Слайдер работ', // название меню
],
'items_list'               => '',
'description'         => '',
'public'              => true,
// 'publicly_queryable'  => null, // зависит от public
'exclude_from_search' => false, // зависит от public
// 'show_ui'             => null, // зависит от public
// 'show_in_nav_menus'   => null, // зависит от public
'show_in_menu'        => true, // показывать ли в меню адмнки
// 'show_in_admin_bar'   => null, // зависит от show_in_menu
'show_in_rest'        => null, // добавить в REST API. C WP 4.7
'rest_base'           => null, // $post_type. C WP 4.7
'menu_position'       => null,
'menu_icon'           => null, 
//'capability_type'   => 'post',
//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
'hierarchical'        => false,
'supports'            => [ 'title', 'thumbnail','title','editor','excerpt','post-formats' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
'taxonomies'          => [],
'has_archive'         => false,
]) ; 
register_post_type('s-reviews',[
   
	'labels' => [
'name'               => 's-reviews', // основное название для типа записи
'singular_name'      => 'sReviews', // название для одной записи этого типа
'add_new'            => 'Добавить отзыв', // для добавления новой записи
'add_new_item'       => 'Добавление отзыва', // заголовка у вновь создаваемой записи в админ-панели.
'edit_item'          => 'Редактирование отзыва', // для редактирования типа записи
'new_item'           => 'Новой отзыв', // текст новой записи
'view_item'          => 'Смотреть ____', // для просмотра записи этого типа.
'search_items'       => 'Искать ____', // для поиска по этим типам записи
'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
'parent_item_colon'  => '', // для родителей (у древовидных типов)
'menu_name'          => 'Отзывы', // название меню
],
'items_list'               => '',
'description'         => '',
'public'              => true,
// 'publicly_queryable'  => null, // зависит от public
'exclude_from_search' => false, // зависит от public
// 'show_ui'             => null, // зависит от public
// 'show_in_nav_menus'   => null, // зависит от public
'show_in_menu'        => true, // показывать ли в меню адмнки
// 'show_in_admin_bar'   => null, // зависит от show_in_menu
'show_in_rest'        => null, // добавить в REST API. C WP 4.7
'rest_base'           => null, // $post_type. C WP 4.7
'menu_position'       => null,
'menu_icon'           => null, 
//'capability_type'   => 'post',
//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
'hierarchical'        => false,
'supports'            => [ 'title', 'thumbnail','title','editor','excerpt','post-formats' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
'taxonomies'          => [],
'has_archive'         => false,
]) ; 

}

function show_zhuravka_header(){
	$args = [
		'orderby'     => 'date',
		'order'       => 'ASC',
		'post_type'   => 'zhuravka-header',  
	 ];
	
	 $posts  = get_posts( $args ); 
	
	//var_dump($posts);
	 return $posts;

}

function show_zhuravka_slider_top(){
   
	$args = [
		'numberposts' => 15,
		'post_content'=> '',
		'post_title'  => '',
		'orderby'     => 'date',
		'order'       => 'ASC',
		'post_type'   => 'zhuravka-content'
	];
	 
	$posts = get_posts( $args );

	return $posts;
}
function show_zhuravka_card_product(){
   
	$args = [
		'numberposts' => 4,
		'post_content'=> '',
		'post_title'  => '',
		'orderby'     => 'date', 
		'order'       => 'ASC',
		'post_type'   => 'card-product'
	];
	 
	$posts = get_posts( $args );

	return $posts;
}
function show_zhuravka_slider_job(){
   
	$args = [
		
		'post_content'=> '',
		'post_title'  => '',
		'orderby'     => 'date', 
		'order'       => 'ASC',
		'post_type'   => 'slider-job'
	];
	 
	$posts = get_posts( $args );

	return $posts;
}
function show_zhuravka_slider_reviews(){
   
	$args = [
		
		'post_content'=> '',
		'post_title'  => '',
		'orderby'     => 'date', 
		'order'       => 'ASC',
		'post_type'   => 's-reviews'
	];
	 
	$posts = get_posts( $args );

	return $posts;
}
add_action( 'init', 'register_mypost_types' ); //  гемтрация хука для вывода полей на главную из админки
/**
 * Enqueue scripts and styles.
 * <script src="libs/jquery.mmenu/jquery.mmenu.all.js" type="text/javascript"></script>

 */
function zhuravka_scripts() {	
	wp_enqueue_style( 'zhuravka-style', get_stylesheet_uri() );

	wp_enqueue_script( 'zhuravka-jquery', get_template_directory_uri() . '/js/jQuery.js', array(), '001', true );
	
	wp_enqueue_script( 'zhuravka-fotorama', get_template_directory_uri() . '/layouts/fotorama/fotorama.js', array(), '003', true );
	wp_enqueue_script( 'zhuravka-selectize', get_template_directory_uri() . '/layouts/selectize/dist/js/standalone/selectize.js', array(), '004', true );
	wp_enqueue_script( 'zhuravka-popup', get_template_directory_uri() . '/layouts/Popup/dist/jquery.magnific-popup.min.js', array(), '005', true );
	wp_enqueue_script( 'zhuravka-mmenu', get_template_directory_uri() . '/layouts/jquery.mmenu/jquery.mmenu.all.js', array(), '006', true );
    wp_enqueue_script( 'zhuravka-OwlCarousel', get_template_directory_uri() . '/layouts/OwlCarousel/dist/owl.carousel.min.js', array(), '007', true );
	
	wp_enqueue_script( 'zhuravka-scripts', get_template_directory_uri() . '/js/scripts.js', array(), '010', true );

	wp_enqueue_script( 'zhuravka-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'zhuravka-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'zhuravka_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

