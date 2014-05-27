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
            
            // Create Custom Taxonomy
            $this->create_taxonomies();
            
            // Create Custom Post Types
            $this->create_post_types();
            
            // Add Taxonomy Filter : Types
            add_action( 'restrict_manage_posts', array(&$this, 'partner_taxonomy_filter_dropdownlist') );
            
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
                'partner', 
                array(  'labels' => $labels,
                        'description'         => __('Partners listing',$ls_manager->ls_manager_domain),
                        'public'              => true, 
                        'publicly_queryable'  => true,
                        'show_ui'             => true, 
                        'exclude_from_search' => false, 
                        'menu_position'       => 25, 
                        'query_var'           => true,
                        'supports'            => array( 'title', 'editor', 'thumbnail' ), 
                        'taxonomies'          => array('type'),
                        'rewrite'             => true, 
                        'capability_type'     => 'post', 
                        'hierachical'         => false,
                        'menu_icon'           => $ls_manager_globals['plugin_url'] .'/img/icon-partner.png',
                )
            );
            
            // Post Type : Project
            /*$labels = array(
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
                'project', 
                array(  'labels' => $labels,
                        'description' => __('Projects listing',$ls_manager->ls_manager_domain), 
                        'public' => true, 
                        'publicly_queryable' => true,
                        'show_ui' => true, 
                        'exclude_from_search' => false, 
                        'menu_position' => 26, 
                        'query_var' => true,
                        'supports' => array( 'title', 'author', 'editor', 'excerpt', 'thumbnail' ), 
                        'taxonomies' => array('category'),
                        'rewrite' => true, 
                        'capability_type' => 'post', 
                        'hierachical' => false,
                        'menu_icon' => $ls_manager_globals['plugin_url'] .'/img/icon-project.png',
                )
            );*/
            
            // Post Type : Prospect
            $labels = array(
                'name'               => __('Prospects',$ls_manager->ls_manager_domain),
                'singular_name'      => __('Prospect',$ls_manager->ls_manager_domain), 
                'add_new'            => __('New prospect',$ls_manager->ls_manager_domain),
                'add_new_item'       => __('Add a new prospect',$ls_manager->ls_manager_domain), 
                'edit'               => __('Edit',$ls_manager->ls_manager_domain), 
                'edit_item'          => __('Edit prospect',$ls_manager->ls_manager_domain),
                'new_item'           => __('New prospect',$ls_manager->ls_manager_domain), 
                'view'               => __('Prospects',$ls_manager->ls_manager_domain), 
                'view_item'          => __('View prospect',$ls_manager->ls_manager_domain),
                'search_items'       => __('Search prospect',$ls_manager->ls_manager_domain),
                'not_found'          => __('No prospect found',$ls_manager->ls_manager_domain), 
                'not_found_in_trash' => __('No prospect found in trash',$ls_manager->ls_manager_domain),
                'parent'             => __('Parent',$ls_manager->ls_manager_domain)
            );
            
            register_post_type( 
                'prospect', 
                array(  'labels' => $labels,
                        'description' => __('Prospects listing',$ls_manager->ls_manager_domain), 
                        'public' => true, 
                        'publicly_queryable' => true,
                        'show_ui' => true, 
                        'exclude_from_search' => false, 
                        'menu_position' => 26, 
                        'query_var' => true,
                        'supports' => array( 'title', 'editor', 'thumbnail' ), 
                        'taxonomies' => array(),
                        'rewrite' => true, 
                        'capability_type' => 'post', 
                        'hierachical' => false,
                        'menu_icon' => $ls_manager_globals['plugin_url'] .'/img/icon-prospect.png',
                )
            );
            
            // Post Type : Customer
            $labels = array(
                'name'               => __('Customers',$ls_manager->ls_manager_domain),
                'singular_name'      => __('Customer',$ls_manager->ls_manager_domain), 
                'add_new'            => __('New customer',$ls_manager->ls_manager_domain),
                'add_new_item'       => __('Add a new customer',$ls_manager->ls_manager_domain), 
                'edit'               => __('Edit',$ls_manager->ls_manager_domain), 
                'edit_item'          => __('Edit customer',$ls_manager->ls_manager_domain),
                'new_item'           => __('New customer',$ls_manager->ls_manager_domain), 
                'view'               => __('Customers',$ls_manager->ls_manager_domain), 
                'view_item'          => __('View customer',$ls_manager->ls_manager_domain),
                'search_items'       => __('Search customer',$ls_manager->ls_manager_domain),
                'not_found'          => __('No customer found',$ls_manager->ls_manager_domain), 
                'not_found_in_trash' => __('No customer found in trash',$ls_manager->ls_manager_domain),
                'parent'             => __('Parent',$ls_manager->ls_manager_domain)
            );
            
            register_post_type( 
                'customer', 
                array(  'labels' => $labels,
                        'description' => __('Customers listing',$ls_manager->ls_manager_domain), 
                        'public' => true, 
                        'publicly_queryable' => true,
                        'show_ui' => true, 
                        'exclude_from_search' => false, 
                        'menu_position' => 25, 
                        'query_var' => true,
                        'supports' => array( 'title', 'editor', 'thumbnail' ), 
                        'taxonomies' => array(),
                        'rewrite' => true, 
                        'capability_type' => 'post', 
                        'hierachical' => false,
                        'menu_icon' => $ls_manager_globals['plugin_url'] .'/img/icon-customer.png',
                )
            );
            
        }
    
        /**
         * Create Specifics Custom Taxonomies
         * @global type $ls_manager 
         */
        private function create_taxonomies(){
            
            global $ls_manager;
            
            // Add Taxonomy "Type", hierarchical (like categories)
            $labels = array(
                    'name'              => __('Types',$ls_manager->ls_manager_domain),
                    'singular_name'     => __('Type',$ls_manager->ls_manager_domain),
                    'search_items'      => __('Find a type',$ls_manager->ls_manager_domain),
                    'all_items'         => __('All types',$ls_manager->ls_manager_domain),
                    'parent_item'       => __('Parent type',$ls_manager->ls_manager_domain),
                    'parent_item_colon' => __('Parent types :',$ls_manager->ls_manager_domain),
                    'edit_item'         => __('Edit type',$ls_manager->ls_manager_domain),
                    'update_item'       => __('Update type',$ls_manager->ls_manager_domain),
                    'add_new_item'      => __('Add new type',$ls_manager->ls_manager_domain),
                    'new_item_name'     => __('New type',$ls_manager->ls_manager_domain),
                    'menu_name'         => __('Types',$ls_manager->ls_manager_domain),
            );

            $args = array(
                    'hierarchical'      => true,
                    'labels'            => $labels,
                    'show_ui'           => true,
                    'show_admin_column' => true,
                    'query_var'         => true,
                    'rewrite'           => array( 'slug' => 'partners', 'with_front' => false ),
            );
            register_taxonomy( 'type', array( 'partner' ), $args );
        }
    
        /**
         * Custom Taxonomy "Type" Filters Dropdown List ("Partners" Page List)
         */
        public function partner_taxonomy_filter_dropdownlist(){
            
            global $wp_query, $ls_manager;
            
            $type_slug_walker = new Type_Slug_Walker;
            $screen = get_current_screen();
            
            if ( $screen->post_type == 'partner' ) {
                wp_dropdown_categories( array(
                    'show_option_all' => __('See all types',$ls_manager->ls_manager_domain),
                    'taxonomy' => 'type',
                    'name' => 'type',
                    'orderby' => 'name',
                    'selected' => ( isset( $wp_query->query['type'] ) ? $wp_query->query['type'] : '' ),
                    'hierarchical' => true,
                    'depth' => 0,
                    'show_count' => true,
                    'hide_empty' => false,
                    'walker' => $type_slug_walker
                ) );
            }
        }
    }

    /**
    * Override Walker Category DropDown : Use Slug As Value In Options 
    */
    class Type_Slug_Walker extends Walker_CategoryDropdown {   
        /**
        * Override start_el native function
        */
        function start_el( &$output, $category, $depth, $args, $id = 0 ) {

            $pad = str_repeat('&nbsp;', $depth * 3);
            $output .= "\t<option class=\"level-$depth\" value=\"".$category->slug."\"";
            if ( $category->term_id == $args['selected'] )
                $output .= ' selected="selected"';
            $output .= '>';
            $output .= $pad.$category->name;
            if ( $args['show_count'] )
                $output .= '&nbsp;&nbsp;('. $category->count .')';
            $output .= "</option>\n";
        }
    }
}
