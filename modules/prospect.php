<?php

if (!class_exists('LS_Manager_Prospect')) {
    
    /**
     * LS_Manager_Prospect - Object Class 
     */
    class LS_Manager_Prospect{
        
        public $a_list_prospect_status;
        
        /** 
         * Construct the plugin object 
         */ 
        public function __construct() {
            global $ls_manager;
            
            $this->a_list_prospect_status = array(
                'new'            => __('New prospect', $ls_manager->ls_manager_domain),
                'in-progress'    => __('In progress', $ls_manager->ls_manager_domain),
                'call-agan'      => __('Call again', $ls_manager->ls_manager_domain),
                'quote-sended'   => __('Quote sended', $ls_manager->ls_manager_domain),
                'futur-customer' => __('Futur customer', $ls_manager->ls_manager_domain),
            );
            
            // Add Render & Save Hooks
            add_action( 'admin_init', array(&$this,'admin_prospect_init') );    
            
        }
        
        public function admin_prospect_init(){
            // Prospect Infos MetaBox + Save Data
            add_meta_box('prospect-infos-box', 'Informations détaillées', array(&$this,'prospect_infos_render'), 'prospect', 'normal', 'high');
            add_action('save_post', array(&$this,'prospect_infos_save_postdata'));
            
            // Prospect State Metabox + Save Data
            add_meta_box('prospect-status-box', 'Status', array($this,'prospect_status_render'), 'prospect', 'side', 'high');
            add_action('save_post', array($this,'prospect_status_save_postdata'));
            
            // Prospect To Customer Metabox + Save Data
            add_meta_box('prospect-to-customer-box', 'Transformation', array($this,'prospect_to_customer_render'), 'prospect', 'side', 'high');
            add_action('save_post', array($this,'prospect_to_customer_save_postdata'));
            
            // Prospect Custom Columns In Post Type List
            add_filter( 'manage_edit-prospect_columns', array(&$this,'prospect_add_columns') ) ;
            add_action( 'manage_prospect_posts_custom_column', array(&$this,'prospect_add_custom_columns_render'), 10, 2);
        }
        
        /**
         * Prospect Infos Render 
         * @global type $ls_manager
         * @global type $post 
         */
        public function prospect_infos_render(){
            
            global $ls_manager, $post;
            
            // Get Post Meta Key 
            $prospect_attribut_owner        = get_post_meta($post->ID, 'prospect-attribut-owner', true);
            $prospect_attribut_address      = get_post_meta($post->ID, 'prospect-attribut-address', true);
            $prospect_attribut_zip_code     = get_post_meta($post->ID, 'prospect-attribut-zip-code', true);
            $prospect_attribut_city         = get_post_meta($post->ID, 'prospect-attribut-city', true);
            $prospect_attribut_country      = get_post_meta($post->ID, 'prospect-attribut-country', true);
            $prospect_attribut_phone        = get_post_meta($post->ID, 'prospect-attribut-phone', true);
            $prospect_attribut_phone2       = get_post_meta($post->ID, 'prospect-attribut-phone2', true);
            $prospect_attribut_phone3       = get_post_meta($post->ID, 'prospect-attribut-phone3', true);
            $prospect_attribut_fax          = get_post_meta($post->ID, 'prospect-attribut-fax', true);
            $prospect_attribut_email        = get_post_meta($post->ID, 'prospect-attribut-email', true);
            $prospect_attribut_email2       = get_post_meta($post->ID, 'prospect-attribut-email2', true);
            $prospect_attribut_website      = get_post_meta($post->ID, 'prospect-attribut-website', true);
            $prospect_attribut_latitude     = get_post_meta($post->ID, 'prospect-attribut-latitude', true);
            $prospect_attribut_longitude    = get_post_meta($post->ID, 'prospect-attribut-longitude', true);
            
            // Use nonce for verification
            echo '<input type="hidden" name="prospect_attributs_metabox_nonce" value="'. wp_create_nonce('prospect_attributs_metabox'). '" />';

            // Owner
            echo '<label style="width:25%;display:block;float:left;">'.__('Owner', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-owner" id="prospect-attribut-owner" value="'.$prospect_attribut_owner.'" style="width:70%;" /><br />';
            
            // Adresse
            echo '<label style="width:25%;display:block;float:left;">'.__('Address', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-address" id="prospect-attribut-address" value="'.$prospect_attribut_address.'" style="width:70%;" /><br />';
            
            // Zip Code
            echo '<label style="width:25%;display:block;float:left;">'.__('Zip Code', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-zip-code" id="prospect-attribut-zip-code" value="'.$prospect_attribut_zip_code.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('City', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-city" id="prospect-attribut-city" value="'.$prospect_attribut_city.'" style="width:70%;" /><br />';
            
            // Country
            echo '<label style="width:25%;display:block;float:left;">'.__('Country', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-country" id="prospect-attribut-country" value="'.$prospect_attribut_country.'" style="width:70%;" /><br />';

            // Telephone
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-phone" id="prospect-attribut-phone" value="'.$prospect_attribut_phone.'" style="width:70%;" /><br />';

            // Telephone 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="prospect-attribut-phone2" id="prospect-attribut-phone2" value="'.$prospect_attribut_phone2.'" style="width:70%;" /><br />';

            // Telephone 3
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 3</label>';
            echo '<input type="text" name="prospect-attribut-phone3" id="prospect-attribut-phone3" value="'.$prospect_attribut_phone3.'" style="width:70%;" /><br />';

            // Fax
            echo '<label style="width:25%;display:block;float:left;">'.__('Fax', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-fax" id="prospect-attribut-fax" value="'.$prospect_attribut_fax.'" style="width:70%;" /><br />';

            // Email
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-email" id="prospect-attribut-email" value="'.$prospect_attribut_email.'" style="width:70%;" /><br />';

            // Email 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="prospect-attribut-email2" id="prospect-attribut-email2" value="'.$prospect_attribut_email2.'" style="width:70%;" /><br />';

            // Website
            echo '<label style="width:25%;display:block;float:left;">'.__('Website', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-website" id="prospect-attribut-website" value="'.$prospect_attribut_website.'" style="width:70%;" /><br />';
            
            // Latitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Latitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-latitude" id="prospect-attribut-latitude" value="'.$prospect_attribut_latitude.'" style="width:70%;" /><br />';

            // Longitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Longitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="prospect-attribut-longitude" id="prospect-attribut-longitude" value="'.$prospect_attribut_longitude.'" style="width:70%;" /><br />';
            echo '<em><a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">Cliquez ici</a> pour obtenir obtenir les coordonnées</em><br />';
            
        }
        
        /**
         * Prospect Infos Save Post Data
         * @param type $post_id
         * @return type 
         */
        public function prospect_infos_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['prospect_attributs_metabox_nonce'], 'prospect_attributs_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Partner Attribut : Owner
            $prospect_attribut_owner = sanitize_text_field($_POST['prospect-attribut-owner']);
            if (!empty($prospect_attribut_owner)) update_post_meta ($post_id, 'prospect-attribut-owner', $prospect_attribut_owner);
            else update_post_meta ($post_id, 'prospect-attribut-owner', '');
            
            // Update Prospect Attribut : Address
            $prospect_attribut_address = sanitize_text_field($_POST['prospect-attribut-address']);
            if (!empty($prospect_attribut_address)) update_post_meta ($post_id, 'prospect-attribut-address', $prospect_attribut_address);
            else update_post_meta ($post_id, 'prospect-attribut-address', '');
            
            // Update Prospect Attribut : Zip Code
            $prospect_attribut_zip_code = sanitize_text_field($_POST['prospect-attribut-zip-code']);
            if (!empty($prospect_attribut_zip_code)) update_post_meta ($post_id, 'prospect-attribut-zip-code', $prospect_attribut_zip_code);
            else update_post_meta ($post_id, 'prospect-attribut-zip-code', '');
            
            // Update Prospect Attribut : City
            $prospect_attribut_city = sanitize_text_field($_POST['prospect-attribut-city']);
            if (!empty($prospect_attribut_city)) update_post_meta ($post_id, 'prospect-attribut-city', $prospect_attribut_city);
            else update_post_meta ($post_id, 'prospect-attribut-city', '');
            
            // Update Prospect Attribut : Country
            $prospect_attribut_country = sanitize_text_field($_POST['prospect-attribut-country']);
            if (!empty($prospect_attribut_country)) update_post_meta ($post_id, 'prospect-attribut-country', $prospect_attribut_country);
            else update_post_meta ($post_id, 'prospect-attribut-country', '');
            
            // Update Prospect Attribut : Phone
            $prospect_attribut_phone = sanitize_text_field($_POST['prospect-attribut-phone']);
            if (!empty($prospect_attribut_phone)) update_post_meta ($post_id, 'prospect-attribut-phone', $prospect_attribut_phone);
            else update_post_meta ($post_id, 'prospect-attribut-phone', '');
            
            // Update Prospect Attribut : Phone 2
            $prospect_attribut_phone2 = sanitize_text_field($_POST['prospect-attribut-phone2']);
            if (!empty($prospect_attribut_phone2)) update_post_meta ($post_id, 'prospect-attribut-phone2', $prospect_attribut_phone2);
            else update_post_meta ($post_id, 'prospect-attribut-phone2', '');
            
            // Update Prospect Attribut : Phone 3
            $prospect_attribut_phone3 = sanitize_text_field($_POST['prospect-attribut-phone3']);
            if (!empty($prospect_attribut_phone3)) update_post_meta ($post_id, 'prospect-attribut-phone3', $prospect_attribut_phone3);
            else update_post_meta ($post_id, 'prospect-attribut-phone3', '');
            
            // Update Prospect Attribut : Fax
            $prospect_attribut_fax = sanitize_text_field($_POST['prospect-attribut-fax']);
            if (!empty($prospect_attribut_fax)) update_post_meta ($post_id, 'prospect-attribut-fax', $prospect_attribut_fax);
            else update_post_meta ($post_id, 'prospect-attribut-fax', '');
            
            // Update Prospect Attribut : Email
            $prospect_attribut_email = sanitize_text_field($_POST['prospect-attribut-email']);
            if (!empty($prospect_attribut_email)) update_post_meta ($post_id, 'prospect-attribut-email', $prospect_attribut_email);
            else update_post_meta ($post_id, 'prospect-attribut-email', '');
            
            // Update Prospect Attribut : Email 2
            $prospect_attribut_email2 = sanitize_text_field($_POST['prospect-attribut-email2']);
            if (!empty($prospect_attribut_email2)) update_post_meta ($post_id, 'prospect-attribut-email2', $prospect_attribut_email2);
            else update_post_meta ($post_id, 'prospect-attribut-email2', '');
            
            // Update Prospect Attribut : Website
            $prospect_attribut_website = sanitize_text_field($_POST['prospect-attribut-website']);
            if (!empty($prospect_attribut_website)) update_post_meta ($post_id, 'prospect-attribut-website', $prospect_attribut_website);
            else update_post_meta ($post_id, 'prospect-attribut-website', '');
            
            // Update Prospect Attribut : Latitude
            $prospect_attribut_latitude = sanitize_text_field($_POST['prospect-attribut-latitude']);
            if (!empty($prospect_attribut_latitude)) update_post_meta ($post_id, 'prospect-attribut-latitude', $prospect_attribut_latitude);
            else update_post_meta ($post_id, 'prospect-attribut-latitude', '');
            
            // Update Prospect Attribut : Longitude
            $prospect_attribut_longitude = sanitize_text_field($_POST['prospect-attribut-longitude']);
            if (!empty($prospect_attribut_longitude)) update_post_meta ($post_id, 'prospect-attribut-longitude', $prospect_attribut_longitude);
            else update_post_meta ($post_id, 'prospect-attribut-longitude', '');
        }
    
        /**
         * Prospect Status Render
         * @global type $ls_manager
         * @global type $post 
         */
        public function prospect_status_render(){
            global $ls_manager, $post;
            
            // Get Status Post Meta
            $prospect_status = get_post_meta($post->ID, 'prospect-status', true);
            
            // Use nonce for verification
            echo '<input type="hidden" name="prospect_status_metabox_nonce" value="'. wp_create_nonce('prospect_status_metabox'). '" />';
            
            // Prospect Select List
            echo '<label style="width:100%;display:block;float:left;">'.__('Prospect Status', $ls_manager->ls_manager_domain).'</label>';
            echo '<select name="prospect-status" id="">';
            foreach($this->a_list_prospect_status as $key => $value){
                echo '<option value="'.$key.'" '.(($key == $prospect_status) ? 'selected="selected"' : '').'>'.$value.'</option>';
            }
            echo '<select>';
        }
        
        /**
         * Prospect Status Save Postdata
         * @param type $post_id
         * @return type 
         */
        public function prospect_status_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['prospect_status_metabox_nonce'], 'prospect_status_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Prospect Status
            $prospect_status = sanitize_text_field($_POST['prospect-status']);
            if (!empty($prospect_status)) update_post_meta ($post_id, 'prospect-status', $prospect_status);
            else update_post_meta ($post_id, 'prospect-status', '');
        }
        
        /**
         * Transform Prospect To Customer
         * @global type $ls_manager
         * @global type $post 
         */
        public function prospect_to_customer_render(){
            
            global $ls_manager, $post;
            
            // Get Prospect Post Meta
            $prosect_to_customer = get_post_meta($post->ID, 'prospect-to-customer', true);
            
            // Use nonce for verification
            //wp_nonce_field('prospect_to_customer_metabox_submit','prospect_to_customer_metabox_nonce');
            
            // Transform Prospect To Customer
            echo '<label style="width:100%;display:block;float:left;">'.__('Transform prospect into customer ?', $ls_manager->ls_manager_domain).'</label><br />';
            echo '<input type="radio" name="prospect-to-customer" id="prospect-to-customer-no" value="no" '.(empty($prosect_to_customer) || $prosect_to_customer == 'no' ? 'checked="checked"' : '').' />';
            echo '<label for="prospect-to-customer-no" style="margin-right:10px">'.__('No', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="radio" name="prospect-to-customer" id="prospect-to-customer-yes" value="yes" '.($prosect_to_customer == 'yes' ? 'checked="checked"' : '').' />';
            echo '<label for="prospect-to-customer-yes" style="margin-right:10px">'.__('Yes', $ls_manager->ls_manager_domain).'</label>';
            
        }
        
        /**
         *
         * @param type $post_id
         * @return type 
         */
        public function prospect_to_customer_save_postdata($post_id){
            
            // Check Nonce
            //if (empty($_POST) || !check_admin_referer('prospect_to_customer_metabox_submit', 'prospect_to_customer_metabox_nonce'))
            //    return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Get Prospect To Customer Post Value
            $prospect_to_customer = sanitize_text_field($_POST['prospect-to-customer']);
            if (!empty($prospect_to_customer)) update_post_meta ($post_id, 'prospect-to-customer', $prospect_to_customer);
            else update_post_meta ($post_id, 'prospect-to-customer', '');
            
            // Convert Prospect To Customer ?
            if ($prospect_to_customer == 'yes'){ 
                
                // Create Customer Post Meta & Delete Prospect Post Meta 
                
                $prospect_attribut_owner        = get_post_meta($post_id, 'prospect-attribut-owner', true);
                update_post_meta($post_id, 'customer-attribut-owner', $prospect_attribut_owner);
                delete_post_meta($post_id, 'prospect-attribut-owner');
                $prospect_attribut_address      = get_post_meta($post_id, 'prospect-attribut-address', true);
                update_post_meta($post_id, 'customer-attribut-address', $prospect_attribut_address);
                delete_post_meta($post_id, 'prospect-attribut-address');
                $prospect_attribut_zip_code     = get_post_meta($post_id, 'prospect-attribut-zip-code', true);
                update_post_meta($post_id, 'customer-attribut-zip-code', $prospect_attribut_zip_code);
                delete_post_meta($post_id, 'prospect-attribut-zip-code');
                $prospect_attribut_city         = get_post_meta($post_id, 'prospect-attribut-city', true);
                update_post_meta($post_id, 'customer-attribut-city', $prospect_attribut_city);
                delete_post_meta($post_id, 'prospect-attribut-city');
                $prospect_attribut_phone        = get_post_meta($post_id, 'prospect-attribut-phone', true);
                update_post_meta($post_id, 'customer-attribut-phone', $prospect_attribut_phone);
                delete_post_meta($post_id, 'prospect-attribut-phone');
                $prospect_attribut_phone2       = get_post_meta($post_id, 'prospect-attribut-phone2', true);
                update_post_meta($post_id, 'customer-attribut-phone2', $prospect_attribut_phone2);
                delete_post_meta($post_id, 'prospect-attribut-phone2');
                $prospect_attribut_phone3       = get_post_meta($post_id, 'prospect-attribut-phone3', true);
                update_post_meta($post_id, 'customer-attribut-phone3', $prospect_attribut_phone3);
                delete_post_meta($post_id, 'prospect-attribut-phone3');
                $prospect_attribut_fax          = get_post_meta($post_id, 'prospect-attribut-fax', true);
                update_post_meta($post_id, 'customer-attribut-fax', $prospect_attribut_fax);
                delete_post_meta($post_id, 'prospect-attribut-fax');
                $prospect_attribut_email        = get_post_meta($post_id, 'prospect-attribut-email', true);
                update_post_meta($post_id, 'customer-attribut-email', $prospect_attribut_email);
                delete_post_meta($post_id, 'prospect-attribut-email');
                $prospect_attribut_email2       = get_post_meta($post_id, 'prospect-attribut-email2', true);
                update_post_meta($post_id, 'customer-attribut-email2', $prospect_attribut_email2);
                delete_post_meta($post_id, 'prospect-attribut-email2');
                $prospect_attribut_website      = get_post_meta($post_id, 'prospect-attribut-website', true);
                update_post_meta($post_id, 'customer-attribut-website', $prospect_attribut_website);
                delete_post_meta($post_id, 'prospect-attribut-website');
                $prospect_attribut_latitude     = get_post_meta($post_id, 'prospect-attribut-latitude', true);
                update_post_meta($post_id, 'customer-attribut-latitude', $prospect_attribut_latitude);
                delete_post_meta($post_id, 'prospect-attribut-latitude');
                $prospect_attribut_longitude    = get_post_meta($post_id, 'prospect-attribut-longitude', true); 
                update_post_meta($post_id, 'customer-attribut-longitude', $prospect_attribut_longitude);
                delete_post_meta($post_id, 'prospect-attribut-longitude');
                
                // Change Post Type
                set_post_type($post_id, 'customer');
            }
        }
    
        /**
        * Prospect Add Columns
        * @param type $columns
        */
        function prospect_add_columns($columns){
            global $ls_manager;
            $custom_columns = array();
            foreach($columns as $key => $title) {
                if ($key=='title') {
                    $custom_columns['thumbnail']       = 'Logo';
                    $custom_columns[$key]              = $title;
                    $custom_columns['prospect']        = __('Summary', $ls_manager->ls_manager_domain);
                    $custom_columns['prospect_status'] = __('Prospect Status', $ls_manager->ls_manager_domain);
                }
                elseif ($key=='date'){
                    unset($custom_columns[$key]);
                }
                else $custom_columns[$key] = $title;
            }
            return $custom_columns;
        }
        
        /**
        * Prospect Custom Column Render
        * @param type $column_name
        * @param type $post_id 
        */
        function prospect_add_custom_columns_render($column_name, $post_id){
            switch ($column_name) {
                case 'thumbnail' :    
                    // Display Thumbnail
                    $prospect_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'medium');
                    if (!empty($prospect_thumbnail))
                        echo '<img src="'.$prospect_thumbnail[0].'" alt="" />';
                break;
                case 'prospect' :    
                    // Get Prospect Summary
                    $prospect_attribut_owner        = get_post_meta($post_id, 'prospect-attribut-owner', true);
                    $prospect_attribut_address      = get_post_meta($post_id, 'prospect-attribut-address', true);
                    $prospect_attribut_zip_code     = get_post_meta($post_id, 'prospect-attribut-zip-code', true);
                    $prospect_attribut_city         = get_post_meta($post_id, 'prospect-attribut-city', true);
                    $prospect_attribut_country      = get_post_meta($post_id, 'prospect-attribut-country', true);
                    $prospect_attribut_phone        = get_post_meta($post_id, 'prospect-attribut-phone', true);
                    $prospect_attribut_email        = get_post_meta($post_id, 'prospect-attribut-email', true);
                    // Display Prospect Summary
                    if (!empty($prospect_attribut_owner))
                        echo $prospect_attribut_owner . '<br />';
                    if (!empty($prospect_attribut_address) && !empty($prospect_attribut_zip_code) && !empty($prospect_attribut_city))
                        echo $prospect_attribut_address . '<br />' . $prospect_attribut_zip_code . ' ' . $prospect_attribut_city . '<br />';
                    if (!empty($prospect_attribut_country))
                        echo $prospect_attribut_country . '<br />';
                    if (!empty($prospect_attribut_phone))
                        echo $prospect_attribut_phone . '<br />';
                    if (!empty($prospect_attribut_email))
                        echo $prospect_attribut_email . '<br />';
                break;
                case 'prospect_status' :
                    // Get Prospect Status (Custom Meta Value)
                    $prospect_status = get_post_meta($post_id, 'prospect-status', true);
                    // Display Prospect Status
                    if (!empty($prospect_status))
                        echo $this->a_list_prospect_status[$prospect_status] . '<br />';
                break;
            }
        }
    }
}

