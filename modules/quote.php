<?php

if (!class_exists('LS_Manager_Quote')) {
    
    /**
     * LS_Manager_Quote - Object Class 
     */
    class LS_Manager_Quote{
        
        /** 
         * Construct the plugin object 
         */ 
        public function __construct() {
            // Add Render & Save Hooks
            add_action( 'admin_init', array(&$this,'admin_quote_init') );            
        }
        
        /**
         * Quote Hook Admin Init 
         */
        public function admin_quote_init(){
                        
            // Quote Builder Metabox + Save Data
            add_meta_box('quote-builder-box', 'Détails du devis', array(&$this,'quote_builder_render'), 'quote', 'normal', 'high');
            add_action('save_post', array(&$this,'quote_builder_save_postdata'));
            
        }
        
        public function quote_builder_render(){
            global $ls_manager, $post;
            
            // Choose A Partner, A Prospect or A Client
            $a_list_post_type = array('partner' => 'Partners', 'prospect' => 'Prospect', 'customer' => 'Customer');
            foreach($a_list_post_type as $key => $value){
                $args = array('post_type' => $key, 'posts_per_page' => '-1' , 'order' => 'ASC', 'orderby' => 'title');
                $post_list = get_posts($args);
                echo '<input type="radio" name="quote-type" id="quote-type-'.$key.'" value="'.$key.'" />';
                echo '<label for="'.$key.'-association">'.$value.'</label>';
                echo '<select id="'.$key.'-association" name="'.$key.'-association">';
                echo '  <option value="0"> --- </option>';
                if ($post_list) {
                    foreach($post_list as $post){
                        echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
                    }
                }
                echo '</select><br />';
            }
            
            $field_count = 0;
            
            // Display 1st Item Fields
            
            // Quote Item Title
            echo '<label for="quote-item-title-'.$field_count.'">Titre : </label>';
            echo '<input type="text" id="quote-item-title-'.$field_count.'" name="quote-item-title-'.$field_count.'" /><br />';
            // Quote Item Description
            echo '<label for="quote-item-description-'.$field_count.'">Description : </label>';
            echo '<textarea id="quote-item-description-'.$field_count.'" name="quote-item-description-'.$field_count.'"></textarea><br />';
            // Quote Item Time
            echo '<label for="quote-item-time-'.$field_count.'">Temps  : </label>';
            echo '<input type="text" id="quote-item-time-'.$field_count.'" name="quote-item-time-'.$field_count.'" /> <em>(jours)</em><br />';
            
            echo '<input type="button" id="add-quote-item" class="button-primary" value="Ajouter" />';
            
        }
        
        public function quote_builder_save_postdata($post_id){
            
        }
    }
}
