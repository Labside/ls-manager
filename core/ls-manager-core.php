<?php
if (!class_exists('LS_Manager')) {
  
    /**
     *	Labside Manager - Main class
     */
    class LS_Manager {
        
        
        static $ls_manager_version    = '0.1';         // Version
        var $ls_manager_domain        = 'lsmanager';   // Domain
        var $options_group            = 'ls_manager_'; // Prefix to all options
        
	public $ls_manager_settings   = null;
        public $ls_manager_post_types = null;
        public $ls_manager_enterprise = null;
        public $ls_manager_partner    = null;
        public $ls_manager_project    = null;
        public $ls_manager_prospect   = null;
        public $ls_manager_customer   = null;
        
        /** 
         * Construct the plugin main object 
         */ 
        public function __construct($plugin_file) {
            
            global $ls_manager_globals;
            
            // Global Properties
            $ls_manager_globals = array(
                'plugin_file'     => $plugin_file,
                'plugin_dir'      => plugin_dir_path( $plugin_file ), //the path of the plugin directory
		'plugin_url'      => plugin_dir_url( $plugin_file ), //the URL of the plugin directory
                'plugin_dir_name' => dirname( plugin_basename( $plugin_file ) ),
            );
            
            // Install and Uninstall Hooks 
            register_activation_hook($ls_manager_globals['plugin_file'], array(&$this, 'activate')); 
            register_deactivation_hook($ls_manager_globals['plugin_file'], array(&$this, 'deactivate'));
                        
            // Load Loacalization File
            load_plugin_textdomain($this->ls_manager_domain, false,  $ls_manager_globals['plugin_dir_name'] . '/languages');
            
            // Main Hooks
            add_action('init', array(&$this, 'init'));
            add_action('admin_init', array(&$this, 'admin_init'));
            
        } // END public function __construct 
        
        public function init(){
            
            // Load Post Types Builder
            $this->ls_manager_post_types = new LS_Manager_Post_Types();
            
            // Load Enterprise Post Type
            $this->ls_manager_enterprise = new LS_Manager_Enterprise();
            
            // Load Partner Post Type
            $this->ls_manager_partner    = new LS_Manager_Partner();
            
            // Load Project Post Type
            //$this->ls_manager_project    = new LS_Manager_Project();
            
            // Load Prospect Post Type
            $this->ls_manager_prospect    = new LS_Manager_Prospect();
            
            // Load Customer Post Type
            $this->ls_manager_customer    = new LS_Manager_Customer();
            
            // Load Js Files
            add_action('admin_enqueue_scripts',array(&$this,'admin_js'));
            
            
        } // END public function init()
        
        public function admin_init(){
            
            // Hide Core Update
            add_action( 'admin_notices', array(&$this, 'hide_update_notice_to_all_but_admin_users'), 1 );
            
            // Add Body Class
            add_filter('admin_body_class', array(&$this, 'add_custom_admin_body_class'));
            
            // Add Support Of Upload File On Edit Forms
            add_action('post_edit_form_tag', array(&$this,'update_edit_form'));
                        
        } // END public function admin_init()
        
        public function admin_js() {
            global $ls_manager_globals;
            wp_register_script('ls-manager-js', $ls_manager_globals['plugin_url'] . 'js/ls-manager.js', array('jquery'), self::$ls_manager_version, false );
            wp_enqueue_script('ls-manager-js');
            return;
        }
        
        public function add_custom_admin_body_class( $classes ) {
            //if ($this->current_user_role == 'commercial' || $this->current_user_role == 'externe')
                //$classes .= ' profile-'.$this->current_user_role;
            //$intl_text = __('Hello',$this->ls_manager_domain);
            //$classes .= ' LSManager-'.$intl_text;
            return $classes;
        }
        
        public function hide_update_notice_to_all_but_admin_users() {
            if (!current_user_can('manage_options'))
                remove_action( 'admin_notices', 'update_nag', 3 );
        }
        
        /*
         * Support Of Upload On Edit Post
         */
        public function update_edit_form() {  
            echo ' enctype="multipart/form-data"';  
        } // end update_edit_form 
        
        /** 
         * Activate plugin 
         */ 
        public static function activate() { 
        // do not generate any output here
        } // END public static function activate 
        
        /** 
         * Deactivate plugin 
         */ 
        public static function deactivate() { 
        // do not generate any output here    
        } // END public static function deactivate
    }
}
