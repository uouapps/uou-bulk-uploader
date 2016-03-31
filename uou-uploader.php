<?php
/**
 * Plugin Name: UOU Bulk Uploader
 * Plugin URI:  http://uou.ch
 * Description: Bulk Company and industry uploader for Globo WP theme.
 * Author:      UOUApps
 * Author URI:  http://uouapps.com
 * Version:     1.0.0
 * Text Domain: globo
 * Domain Path: /languages/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

if ( ! class_exists( 'GloboExIm' ) ) :


Class GloboExIm {
	public $version = '1.0.0';

    protected static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function __construct(){
        define( 'GEI_VERSION', $this->version );
    	define( 'GEI_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'GEI_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'GEI_FILE', __FILE__ );

        define( 'GEI_CSS_PATH', GEI_URL.'/assets/css/' );
        define( 'GEI_JS_PATH', GEI_URL.'/assets/js/' );

        // activation hook
        register_activation_hook( __FILE__, array($this, 'install' ));

        
        require_once( GEI_DIR . '/includes/class-csv-export.php' );

        require_once( GEI_DIR . '/includes/class-gei-admin-ajax.php' );
        
		// require_once( GEI_DIR . '/includes/shortcodes.php' );

        add_action('admin_enqueue_scripts', array($this, 'gei_admin_load_scripts'), 99 );

        // This tells WordPress to call the function named "setup_theme_admin_menus"
        // when it's time to create the menu pages.
        add_action( 'admin_menu', array( $this, 'globo_exim_setup_admin_menus' ) );


        // load plugin text domain
        add_action( 'init', array( $this, 'globo_exim_widget_textdomain' ) );

    }

    public function gei_admin_load_scripts() {

        wp_enqueue_style( 'gei-screen', GEI_CSS_PATH . 'screen.css', array(), GEI_VERSION, 'all' );

        wp_register_script( 'gei-bootstrap-admin', GEI_JS_PATH . 'bootstrap.admin.js', array('jquery'), false, true );
        wp_enqueue_script( 'gei-bootstrap-admin' );

        wp_register_script( 'gei-admin', GEI_JS_PATH . 'app.js', array('jquery'), false, true );
        wp_enqueue_script( 'gei-admin' );

        

        wp_register_script( 'gei-jquery-knob', GEI_JS_PATH . 'jquery.knob.js', array('jquery'), false, true );
        wp_enqueue_script( 'gei-jquery-knob' );

        wp_enqueue_script( 'jquery-ui-widget' );

        wp_register_script( 'gei-jquery-iframe-transport', GEI_JS_PATH . 'jquery.iframe-transport.js', array('jquery'), false, true );
        wp_enqueue_script( 'gei-jquery-iframe-transport' );

        wp_register_script( 'gei-jquery-fileupload', GEI_JS_PATH . 'jquery.fileupload.js', array('jquery'), false, true );
        wp_enqueue_script( 'gei-jquery-fileupload' );

        wp_register_script( 'gei-script', GEI_JS_PATH . 'script.js', array('jquery'), false, true );
        wp_enqueue_script( 'gei-script' );

        wp_localize_script('gei-admin', 'gei', array(
        
            'nonce_globo_exim_do_ajax'     => wp_create_nonce( 'globo-exim-do-ajax' ),
            'ajaxurl' => admin_url( 'admin-ajax.php')

        ) );

        wp_localize_script('gei-script', 'gei', array(
        
            'nonce_globo_exim_do_ajax'     => wp_create_nonce( 'upload_import_file' ),
            'ajaxurl' => admin_url( 'admin-ajax.php')

        ) );
    }

    public function install() {
        
        $upload_dir = wp_upload_dir();
        $dir = $upload_dir['basedir'] .'/globoexim/';
        @mkdir($dir);
        
    }

    /**
     * Loads the Widget's text domain for localization and translation.
     */
    public function globo_exim_widget_textdomain() {
        
        load_plugin_textdomain( 'globo', false, plugin_dir_path( __FILE__ ) . 'languages/' );
    } // end widget_textdomain

    public function globo_exim_setup_admin_menus() {

        add_menu_page( 'UOU Bulk Uploader Settings', 'UOU Bulk Uploader', 'manage_options', 
            'uou_uploader', array( $this, 'uou_uploader_page' ) );

        add_submenu_page( 'uou_uploader', 'Company Import', 'Company Import', 'manage_options', 'uou_uploader_company_import', array( $this, 'uou_uploader_company_import' ) );
        add_submenu_page( 'uou_uploader', 'Company Export', 'Company Export', 'manage_options', 'uou_uploader_company_export', array( $this, 'uou_uploader_company_export' ) );
        add_submenu_page( 'uou_uploader', 'Industry Import', 'Industry Import', 'manage_options', 'uou_uploader_industry_import', array( $this, 'uou_uploader_industry_import' ) );
        add_submenu_page( 'uou_uploader', 'Industry Export', 'Industry Export', 'manage_options', 'uou_uploader_industry_export', array( $this, 'uou_uploader_industry_export' ) );

    }

    public function uou_uploader_page() {
    
        require_once( GEI_DIR . '/templates/exim-page-html.php' );
        
    }

    function uou_uploader_industry_import() {

        require_once( GEI_DIR . '/templates/exim-industry-import-html.php' );

    }

    function uou_uploader_company_import() {

        require_once( GEI_DIR . '/templates/exim-company-import-html.php' );

    }

    function uou_uploader_company_export() {
        require_once( GEI_DIR . '/templates/exim-company-export-html.php' );
    }

    function uou_uploader_industry_export() {
        require_once( GEI_DIR . '/templates/exim-industry-export-html.php' );   
    }
    
}

endif;



function GEI() {
    return GloboExIm::instance();
}

// Global for backwards compatibility.
$GLOBALS['globoexim'] = GEI();