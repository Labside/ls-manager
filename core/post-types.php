<?php

if (!class_exists('LS_Manager_Post_Types')) {
    
    /**
     * LS_Manager_Post_Types - Object Class 
     */
    class LS_Manager_Post_Types{
        
        /** 
         * Construct the plugin object 
         */ 
        public function __construct() {
            
            // Create Custom Post Types
            $this->create_post_types();
            
        }
        
        /**
         * Create Specifics Custom Post Types
         * @global type $ls_manager
         * @global type $ls_manager_globals 
         */
        private function create_post_types(){
            
            global $ls_manager, $ls_manager_globals;
            
            // Post Type : Partenaire
            $labels = array(
                'name'               => __('Partners',$ls_manager->ls_manager_domain),
                'singular_name'      => __('Partner',$ls_manager->ls_manager_domain), 
                'add_new'            => __('New partner',$ls_manager->ls_manager_domain),
                'add_new_item'       => __('Add a new partner',$ls_manager->ls_manager_domain), 
                'edit'               => __('Edit',$ls_manager->ls_manager_domain), 
                'edit_item'          => __('Edit partner',$ls_manager->ls_manager_domain),
                'new_item'           => __('New partner',$ls_manager->ls_manager_domain), 
                'view'               => __('Partner',$ls_manager->ls_manager_domain), 
                'view_item'          => __('View partner',$ls_manager->ls_manager_domain),
                'search_items'       => __('Search partner',$ls_manager->ls_manager_domain),
                'not_found'          => __('No partner found',$ls_manager->ls_manager_domain), 
                'not_found_in_trash' => __('No partner found in trash',$ls_manager->ls_manager_domain),
                'parent'             => __('Parent',$ls_manager->ls_manager_domain)
            );
            
            register_post_type( 
                'project', 
                array(  'labels' => $labels,
                        'description'         => __('Partners listing',$ls_manager->ls_manager_domain),
                        'public'              => true, 
                        'publicly_queryable'  => true,
                        'show_ui'             => true, 
                        'exclude_from_search' => false, 
                        'menu_position'       => 25, 
                        'query_var'           => true,
                        'supports'            => array( 'title', 'editor', 'thumbnail' ), 
                        'taxonomies'          => array('category'),
                        'rewrite'             => true, 
                        'capability_type'     => 'post', 
                        'hierachical'         => false,
                        'menu_icon'           => $ls_manager_globals['plugin_url'] .'/img/icon-partner.png',
                )
            );
            
            // Post Type : Project
            $labels = array(
                'name'               => __('Projects',$ls_manager->ls_manager_domain),
                'singular_name'      => __('Project',$ls_manager->ls_manager_domain), 
                'add_new'            => __('New project',$ls_manager->ls_manager_domain),
                'add_new_item'       => __('Add a new project',$ls_manager->ls_manager_domain), 
                'edit'               => __('Edit',$ls_manager->ls_manager_domain), 
                'edit_item'          => __('Edit project',$ls_manager->ls_manager_domain),
                'new_item'           => __('New project',$ls_manager->ls_manager_domain), 
                'view'               => __('Projects',$ls_manager->ls_manager_domain), 
                'view_item'          => __('View project',$ls_manager->ls_manager_domain),
                'search_items'       => __('Search project',$ls_manager->ls_manager_domain),
                'not_found'          => __('No project found',$ls_manager->ls_manager_domain), 
                'not_found_in_trash' => __('No project found in trash',$ls_manager->ls_manager_domain),
                'parent'             => __('Parent',$ls_manager->ls_manager_domain)
            );
            
            register_post_type( 
                'partner', 
                array(  'labels' => $labels,
                        'description' => __('Projects listing',$ls_manager->ls_manager_domain), 
                        'public' => true, 
                        'publicly_queryable' => true,
                        'show_ui' => true, 
                        'exclude_from_search' => false, 
                        'menu_position' => 25, 
                        'query_var' => true,
                        'supports' => array( 'title', 'author', 'editor', 'excerpt', 'thumbnail' ), 
                        'taxonomies' => array('category'),
                        'rewrite' => true, 
                        'capability_type' => 'post', 
                        'hierachical' => false,
                        'menu_icon' => $ls_manager_globals['plugin_url'] .'/img/icon-project.png',
                )
            );
            
        }
    }
}
