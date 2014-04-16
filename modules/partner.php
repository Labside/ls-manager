<?php

if (!class_exists('LS_Manager_Partner')) {
    
    /**
     * LS_Manager_Partner - Object Class 
     */
    class LS_Manager_Partner{
        
        /** 
         * Construct the plugin object 
         */ 
        public function __construct() {
            // Add Render & Save Hooks
            add_action( 'admin_init', array(&$this,'admin_partner_init') );            
        }
        
        public function admin_partner_init(){
            // Partner Infos MetaBox + Save Data
            add_meta_box('partner-infos-box', 'Informations détaillées', array(&$this,'partner_infos_render'), 'partner', 'normal', 'high');
            add_action('save_post', array(&$this,'partner_infos_save_postdata'));
        }
        
        public function partner_infos_render(){
            
        }
        
        public function partner_infos_save_postdata($post_id){
            
        }
    }
}

