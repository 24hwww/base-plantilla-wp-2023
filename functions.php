<?php
/**
 * _s functions and definitions
 *
 * @package lc
 */

class Syf_Theme{
	private static $instance = null;

	public static function get_instance(){
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    function __construct() {

    	add_filter('show_admin_bar', '__return_false');
    	add_filter('the_generator', function(){return '';});

    	add_action('after_setup_theme', array($this, 'theme_t_setup' ));
    	add_action('init', array($this, 'remove_or_disable_actions_wp'));
    	add_action('init', array($this, 'stop_loading_wp_embed_and_jquery'));
    	add_action('wp_enqueue_scripts', array($this, 'move_scripts_from_head_to_footer'));
    	add_action('wp_default_scripts', array($this, 'dequeue_jquery_migrate_func'));

    	add_action('wp_enqueue_scripts', array($this, 'theme_t_assets_func'));
    	add_filter('script_loader_tag',  array($this, 'add_id_to_script_func'), 10, 3 );

    	add_action('wp_print_styles', function(){
            wp_style_add_data('woocommerce-inline','after','');
        });
        add_filter( 'body_class', function($classes){
			remove_action( 'wp_footer', 'wc_no_js' );
			$classes = array_diff($classes, array('woocommerce-no-js'));
			return array_values($classes);
		},10, 1);

    	add_action( 'wp_head', array($this, 'insertar_meta_etiquetas_func'),1);
      	add_action( 'wp_head', array($this, 'insertar_inc_manifest_link_func'));

		add_action( 'wp_head', array($this, 'insertar_google_analytics_func'));

      	add_action('template_redirect', array($this, 'remove_woocommerce_styles_scripts_func'), 999 );

		add_filter( 'wp_headers', array($this, 'custom_security_headers_func') );

		/****/
		add_filter('wp_nav_menu_items', 'do_shortcode');
		add_shortcode('show_cart_count', array($this, 'show_cart_count_func'));
		add_filter('nav_menu_item_title', function ($title, $item, $args, $depth) {
			return do_shortcode($title);
		}, 10, 4);
		add_filter( 'the_title', function( $title, $item_id ) {
			if ( 'nav_menu_item' === get_post_type( $item_id ) ) {
				return do_shortcode( $title );
			}
			return $title;
		}, 10, 2 );
    }

    public function theme_t_setup() {
		load_theme_textdomain( 'cy', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'title-tag' );
		register_nav_menus( array(
			'primary' => __( 'Primario', 'lc' ),
			'links-social' => __( 'Socials', 'lc' ),
		    'links-home' => __( 'Enlaces Home', 'lc' ),
			'footer-links' => __( 'Footer Links', 'lc' )
			));
	}
	public function remove_or_disable_actions_wp() {
	  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	  remove_action( 'wp_print_styles', 'print_emoji_styles' );
	  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	  remove_action( 'admin_print_styles', 'print_emoji_styles' );
	  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	  remove_filter( 'wp_robots', 'wp_robots_max_image_preview_large' );
	  add_filter( 'tiny_mce_plugins', function($plugins){
	  	  if ( is_array( $plugins ) ) {return array_diff( $plugins, array( 'wpemoji' ) );}else{return array();}
	  });
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'rsd_link');
		remove_action( 'wp_head', 'wlwmanifest_link');
		remove_action( 'wp_head', 'wp_shortlink_wp_head');
		remove_action('wp_head', 'rest_output_link_wp_head', 10);
		remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
		remove_action('template_redirect', 'rest_output_link_header', 11, 0);
	  remove_action( 'wp_head', 'wp_resource_hints', 2 );
	  remove_action( 'wp_head', 'feed_links_extra', 3 );
	  remove_action( 'wp_head', 'feed_links', 2 );
	  remove_action( 'wp_head', 'index_rel_link' );
	  remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
	  remove_action( 'wp_head', 'rest_output_link_wp_head');
	  remove_action( 'wp_head', 'wc_gallery_noscript');
	}
	public function move_scripts_from_head_to_footer(){
	    remove_action( 'wp_head', 'wp_print_scripts' );
	    remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
	    remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );

	    add_action( 'wp_footer', 'wp_print_scripts', 5);
	    add_action( 'wp_footer', 'wp_enqueue_scripts', 5);
	    add_action( 'wp_footer', 'wp_print_head_scripts', 5);

				wp_dequeue_style( 'wp-block-library' );
	    	wp_dequeue_style( 'wp-block-library-theme' );
	    	wp_dequeue_style( 'wc-block-style' );
	    	wp_dequeue_style( 'global-styles' );
				wp_dequeue_style( 'wc-blocks-style' );
	}
	public function stop_loading_wp_embed_and_jquery(){
		if (!is_admin()) {wp_deregister_script('wp-embed');}
	}
	public function dequeue_jquery_migrate_func($scripts){
	    if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
	        $scripts->registered['jquery']->deps = array_diff(
	            $scripts->registered['jquery']->deps,
	            [ 'jquery-migrate' ]
	        );
	    }
			if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {$script = $scripts->registered['jquery'];
			if ( $script->deps ) { $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );}}
	}
	public function theme_t_assets_func(){

		/* ESTILO PLANTILLA */
		wp_enqueue_style( 'style-theme', get_stylesheet_uri(), array(), time() );

	  	/* BOOTSTRAP */
		wp_enqueue_style( 'theme', get_template_directory_uri().'/inc/css/theme.css', array(), time() );
		wp_enqueue_script('bootstrap-js', get_template_directory_uri().'/inc/js/bootstrap.min.js', array('jquery') );

		wp_enqueue_script('twreplace-js', get_template_directory_uri().'/inc/js/twreplace.min.js', array('jquery') );


	  	wp_enqueue_style( 'fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' );

		wp_enqueue_style( 'magnific-popup-css', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css' );
		wp_enqueue_script('magnific-popup-js', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array('jquery') );


	  	wp_enqueue_style( 'swiper-css', 'https://unpkg.com/swiper@8/swiper-bundle.min.css' );
	  	wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper@8/swiper-bundle.min.js', array('jquery') );


  		wp_enqueue_script('theme-js', get_template_directory_uri().'/inc/js/theme.js', array('jquery') );

	}
	public function add_id_to_script_func($tag, $handle, $src){
	 if ( 'ionicons-esm' === $handle ) {
	    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
	  }
	  return $tag;
	}

	public function insertar_meta_etiquetas_func(){
		ob_start();
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="">
		<meta property="og:type" content="website" />
		<meta name="robots" content="index, follow" />
		<?php
		$metas = ob_get_contents();
		ob_end_clean();
		echo preg_replace('/[\x00-\x1F\xFF]/','',$metas);
	}

	public function insertar_inc_manifest_link_func() {
		ob_start();
		$favicon = get_template_directory_uri(). '/inc/favicon/';
		?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $favicon; ?>apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $favicon; ?>favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="192x192" href="<?php echo $favicon; ?>android-chrome-192x192.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favicon; ?>favicon-16x16.png">
		<link rel="manifest" href="<?php echo $favicon; ?>site.webmanifest">
		<link rel="mask-icon" href="<?php echo $favicon; ?>safari-pinned-tab.svg" color="#f4e00c">
		<meta name="msapplication-TileColor" content="#19191b">
		<meta name="theme-color" content="#19191b">
		<link rel="manifest" href="<?php echo $favicon; ?>/manifest.json">
		<?php
		$metas = ob_get_contents();
		ob_end_clean();
		#echo preg_replace('/[\x00-\x1F\xFF]/','',$metas);
	}

	public function insertar_google_analytics_func() {
		$codigo_ga = 'UA-62454044-38';
		ob_start();
		?>
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $codigo_ga; ?>"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', '<?php echo $codigo_ga; ?>');
		</script>
		<?php
		$ga = ob_get_contents();
		ob_end_clean();
		echo preg_replace('/[\x00-\x1F\xFF]/','',$ga);
	}

	public function remove_woocommerce_styles_scripts_func(){
        remove_action('wp_enqueue_scripts', [WC_Frontend_Scripts::class, 'load_scripts']);
        remove_action('wp_print_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
        remove_action('wp_print_footer_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
	}

	public function custom_security_headers_func($headers){
	$headers['X-XSS-Protection'] = '1; mode=block';
	  $headers['X-Content-Type-Options'] = 'nosniff';
	  $headers['X-Content-Security-Policy'] = 'default-src \'self\'; script-src \'self\';';

	  return $headers;
	}

	public function is_request_ajax(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    	return true;
  	}
		return false;
	}

	public function show_cart_count_func(){

	}

}
$GLOBALS['syftheme'] = Syf_Theme::get_instance();
