<?php

if (!class_exists('LS_Manager_Project')) {
    
    /**
     * LS_Manager_Project - Object Class 
     */
    class LS_Manager_Project{
        
        /** 
         * Construct the plugin object 
         */ 
        public function __construct() {
            // Add Render & Save Hooks
            add_action( 'admin_init', array(&$this,'admin_project_init') );            
        }
        
        public function admin_project_init(){
            // Project Infos MetaBox + Save Data
            add_meta_box('project-infos-box', 'Informations détaillées', array(&$this,'project_infos_render'), 'project', 'normal', 'high');
            add_action('save_post', array(&$this,'project_infos_save_postdata'));
        }
        
        public function project_infos_render(){
            echo 'ICI';
        }
        
        public function project_infos_save_postdata($post_id){
            return $post_id;            
        }
    }
}

