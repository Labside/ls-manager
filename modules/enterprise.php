<?php

if (!class_exists('LS_Manager_Enterprise')) {
    
    /**
     * LS_Manager_Enterprise - Object Class 
     */
    class LS_Manager_Enterprise{
        
        /** 
         * Construct the plugin object 
         */ 
        public function __construct() {
            // Add Render & Save Hooks
            add_action( 'admin_init', array(&$this,'admin_enterprise_init') );            
        }
        
        /**
         * Enterprise Hook Admin Init 
         */
        public function admin_enterprise_init(){
            // Enterprise Properties MetaBox + Save Data
            add_meta_box('enterprise-properties-box', 'Informations détaillées', array(&$this,'enterprise_properties_render'), 'enterprise', 'normal', 'high');
            add_action('save_post', array(&$this,'enterprise_properties_save_postdata'));
            
            // Enterprise Prices Metabox + Save Data
            add_meta_box('enterprise-prices-box', 'Prix', array(&$this,'enterprise_prices_render'), 'enterprise', 'normal', 'high');
            add_action('save_post', array(&$this,'enterprise_prices_save_postdata'));
            
            // Enterprise Custom Columns In Post Type List
            add_filter( 'manage_edit-enterprise_columns', array(&$this,'enterprise_add_columns') ) ;
            add_action( 'manage_enterprise_posts_custom_column', array(&$this,'enterprise_add_custom_columns_render'), 10, 2);
        }
        
        /**
         * Enterprise Properties Render
         * @global type $ls_manager
         * @global type $post 
         */
        public function enterprise_properties_render(){
            global $ls_manager, $post;
            
            // Get Post Meta Key 
            $enterprise_property_owner        = get_post_meta($post->ID, 'property-owner', true);
            $enterprise_property_address      = get_post_meta($post->ID, 'property-address', true);
            $enterprise_property_zip_code     = get_post_meta($post->ID, 'property-zip-code', true);
            $enterprise_property_city         = get_post_meta($post->ID, 'property-city', true);
            $enterprise_property_country      = get_post_meta($post->ID, 'property-country', true);
            $enterprise_property_phone        = get_post_meta($post->ID, 'property-phone', true);
            $enterprise_property_phone2       = get_post_meta($post->ID, 'property-phone2', true);
            $enterprise_property_phone3       = get_post_meta($post->ID, 'property-phone3', true);
            $enterprise_property_fax          = get_post_meta($post->ID, 'property-fax', true);
            $enterprise_property_email        = get_post_meta($post->ID, 'property-email', true);
            $enterprise_property_email2       = get_post_meta($post->ID, 'property-email2', true);
            $enterprise_property_website      = get_post_meta($post->ID, 'property-website', true);
            $enterprise_property_latitude     = get_post_meta($post->ID, 'property-latitude', true);
            $enterprise_property_longitude    = get_post_meta($post->ID, 'property-longitude', true);
            
            // Use nonce for verification
            echo '<input type="hidden" name="enterprise_properties_metabox_nonce" value="'. wp_create_nonce('enterprise_properties_metabox'). '" />';

            // Owner
            echo '<label style="width:25%;display:block;float:left;">'.__('Owner', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-owner" id="property-owner" value="'.$enterprise_property_owner.'" style="width:70%;" /><br />';
            
            // Adresse
            echo '<label style="width:25%;display:block;float:left;">'.__('Address', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-address" id="property-address" value="'.$enterprise_property_address.'" style="width:70%;" /><br />';
            
            // Zip Code
            echo '<label style="width:25%;display:block;float:left;">'.__('Zip Code', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-zip-code" id="property-zip-code" value="'.$enterprise_property_zip_code.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('City', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-city" id="property-city" value="'.$enterprise_property_city.'" style="width:70%;" /><br />';
            
            // Country
            echo '<label style="width:25%;display:block;float:left;">'.__('Country', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-country" id="property-country" value="'.$enterprise_property_country.'" style="width:70%;" /><br />';

            // Telephone
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-phone" id="property-phone" value="'.$enterprise_property_phone.'" style="width:70%;" /><br />';

            // Telephone 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="property-phone2" id="property-phone2" value="'.$enterprise_property_phone2.'" style="width:70%;" /><br />';

            // Telephone 3
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 3</label>';
            echo '<input type="text" name="property-phone3" id="property-phone3" value="'.$enterprise_property_phone3.'" style="width:70%;" /><br />';

            // Fax
            echo '<label style="width:25%;display:block;float:left;">'.__('Fax', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-fax" id="property-fax" value="'.$enterprise_property_fax.'" style="width:70%;" /><br />';

            // Email
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-email" id="property-email" value="'.$enterprise_property_email.'" style="width:70%;" /><br />';

            // Email 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="property-email2" id="property-email2" value="'.$enterprise_property_email2.'" style="width:70%;" /><br />';

            // Website
            echo '<label style="width:25%;display:block;float:left;">'.__('Website', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-website" id="property-website" value="'.$enterprise_property_website.'" style="width:70%;" /><br />';
            
            // Latitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Latitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-latitude" id="property-latitude" value="'.$enterprise_property_latitude.'" style="width:70%;" /><br />';

            // Longitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Longitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-longitude" id="enterprise-attribut-longitude" value="'.$enterprise_property_longitude.'" style="width:70%;" /><br />';
            echo '<em><a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">Cliquez ici</a> pour obtenir obtenir les coordonnées</em><br />';
            
        }
        
        /**
         * Enterprise Properties Save Postdata
         * @param type $post_id
         * @return type 
         */
        public function enterprise_properties_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['enterprise_properties_metabox_nonce'], 'enterprise_properties_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Enterprise Property : Owner
            $enterprise_property_owner = sanitize_text_field($_POST['property-owner']);
            if (!empty($enterprise_property_owner)) update_post_meta ($post_id, 'property-owner', $enterprise_property_owner);
            else update_post_meta ($post_id, 'property-owner', '');
            
            // Update Enterprise Property : Address
            $enterprise_property_address = sanitize_text_field($_POST['property-address']);
            if (!empty($enterprise_property_address)) update_post_meta ($post_id, 'property-address', $enterprise_property_address);
            else update_post_meta ($post_id, 'property-address', '');
            
            // Update Enterprise Property : Zip Code
            $enterprise_property_zip_code = sanitize_text_field($_POST['property-zip-code']);
            if (!empty($enterprise_property_zip_code)) update_post_meta ($post_id, 'property-zip-code', $enterprise_property_zip_code);
            else update_post_meta ($post_id, 'property-zip-code', '');
            
            // Update Enterprise Property : City
            $enterprise_property_city = sanitize_text_field($_POST['property-city']);
            if (!empty($enterprise_property_city)) update_post_meta ($post_id, 'property-city', $enterprise_property_city);
            else update_post_meta ($post_id, 'property-city', '');
            
            // Update Enterprise Property : Country
            $enterprise_property_country = sanitize_text_field($_POST['property-country']);
            if (!empty($enterprise_property_country)) update_post_meta ($post_id, 'property-country', $enterprise_property_country);
            else update_post_meta ($post_id, 'property-country', '');
            
            // Update Enterprise Property : Phone
            $enterprise_property_phone = sanitize_text_field($_POST['property-phone']);
            if (!empty($enterprise_property_phone)) update_post_meta ($post_id, 'property-phone', $enterprise_property_phone);
            else update_post_meta ($post_id, 'property-phone', '');
            
            // Update Enterprise Property : Phone 2
            $enterprise_property_phone2 = sanitize_text_field($_POST['property-phone2']);
            if (!empty($enterprise_property_phone2)) update_post_meta ($post_id, 'property-phone2', $enterprise_property_phone2);
            else update_post_meta ($post_id, 'property-phone2', '');
            
            // Update Enterprise Property : Phone 3
            $enterprise_property_phone3 = sanitize_text_field($_POST['property-phone3']);
            if (!empty($enterprise_property_phone3)) update_post_meta ($post_id, 'property-phone3', $enterprise_property_phone3);
            else update_post_meta ($post_id, 'property-phone3', '');
            
            // Update Enterprise Property : Fax
            $enterprise_property_fax = sanitize_text_field($_POST['property-fax']);
            if (!empty($enterprise_property_fax)) update_post_meta ($post_id, 'property-fax', $enterprise_property_fax);
            else update_post_meta ($post_id, 'property-fax', '');
            
            // Update Enterprise Property : Email
            $enterprise_property_email = sanitize_text_field($_POST['property-email']);
            if (!empty($enterprise_property_email)) update_post_meta ($post_id, 'property-email', $enterprise_property_email);
            else update_post_meta ($post_id, 'property-email', '');
            
            // Update Enterprise Property : Email 2
            $enterprise_property_email2 = sanitize_text_field($_POST['property-email2']);
            if (!empty($enterprise_property_email2)) update_post_meta ($post_id, 'property-email2', $enterprise_property_email2);
            else update_post_meta ($post_id, 'property-email2', '');
            
            // Update Enterprise Property : Website
            $enterprise_property_website = sanitize_text_field($_POST['property-website']);
            if (!empty($enterprise_property_website)) update_post_meta ($post_id, 'property-website', $enterprise_property_website);
            else update_post_meta ($post_id, 'property-website', '');
            
            // Update Enterprise Property : Latitude
            $enterprise_property_latitude = sanitize_text_field($_POST['property-latitude']);
            if (!empty($enterprise_property_latitude)) update_post_meta ($post_id, 'property-latitude', $enterprise_property_latitude);
            else update_post_meta ($post_id, 'property-latitude', '');
            
            // Update Enterprise Property : Longitude
            $enterprise_property_longitude = sanitize_text_field($_POST['property-longitude']);
            if (!empty($enterprise_property_longitude)) update_post_meta ($post_id, 'property-longitude', $enterprise_property_longitude);
            else update_post_meta ($post_id, 'property-longitude', '');          
        }
    
        /**
        * Enterprise Add Columns
        * @param type $columns
        */
        public function enterprise_add_columns($columns){
            global $ls_manager;
            $custom_columns = array();
            foreach($columns as $key => $title) {
                if ($key=='title') {
                    $custom_columns['thumbnail']       = 'Logo';
                    $custom_columns[$key]              = $title;
                    $custom_columns['enterprise']      = __('Summary', $ls_manager->ls_manager_domain);
                }
                elseif ($key=='date'){
                    unset($custom_columns[$key]);
                }
                else $custom_columns[$key] = $title;
            }
            return $custom_columns;
        }
        
        /**
        * Enterprise Custom Column Render
        * @param type $column_name
        * @param type $post_id 
        */
        public function enterprise_add_custom_columns_render($column_name, $post_id){
            switch ($column_name) {
                case 'thumbnail' :    
                    // Display Thumbnail
                    $prospect_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'medium');
                    if (!empty($prospect_thumbnail))
                        echo '<img src="'.$prospect_thumbnail[0].'" alt="" />';
                break;
                case 'enterprise' :    
                    // Get Prospect Summary
                    $enterprise_property_owner        = get_post_meta($post_id, 'property-owner', true);
                    $enterprise_property_address      = get_post_meta($post_id, 'property-address', true);
                    $enterprise_property_zip_code     = get_post_meta($post_id, 'property-zip-code', true);
                    $enterprise_property_city         = get_post_meta($post_id, 'property-city', true);
                    $enterprise_property_country      = get_post_meta($post_id, 'property-country', true);
                    $enterprise_property_phone        = get_post_meta($post_id, 'property-phone', true);
                    $enterprise_property_email        = get_post_meta($post_id, 'property-email', true);
                    // Display Prospect Summary
                    if (!empty($enterprise_property_owner))
                        echo $enterprise_property_owner . '<br />';
                    if (!empty($enterprise_property_address) && !empty($enterprise_property_zip_code) && !empty($enterprise_property_city))
                        echo $enterprise_property_address . '<br />' . $enterprise_property_zip_code . ' ' . $enterprise_property_city . '<br />';
                    if (!empty($enterprise_property_country))
                        echo $enterprise_property_country . '<br />';
                    if (!empty($enterprise_property_phone))
                        echo $enterprise_property_phone . '<br />';
                    if (!empty($enterprise_property_email))
                        echo $enterprise_property_email . '<br />';
                break;
            }
        }
    
        /**
         * Enterprise Prices Render 
         */
        public function enterprise_prices_render(){
            global $ls_manager, $post;
            
            $enterprise_hours_per_day  = get_post_meta($post->ID, 'hours-per-day', true);
            $enterprise_price_per_hour = get_post_meta($post->ID, 'price-per-hour', true);
            $enterprise_price_tax      = get_post_meta($post->ID, 'price-tax', true);
            
            // Use nonce for verification
            echo '<input type="hidden" name="enterprise_prices_metabox_nonce" value="'. wp_create_nonce('enterprise_prices_metabox'). '" />';
            
            // Hours per day
            echo '<label style="width:25%;display:block;float:left;">'.__('Hours per day', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="hours-per-day" id="hours-per-day" value="'.$enterprise_hours_per_day.'" style="width:70%;" /><br />';
            
            // Price per hours
            echo '<label style="width:25%;display:block;float:left;">'.__('Price per hours', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="price-per-hour" id="price-per-hour" value="'.$enterprise_price_per_hour.'" style="width:70%;" /><br />';
            
            // Price tax
            echo '<label style="width:25%;display:block;float:left;">'.__('Price tax', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="price-tax" id="price-tax" value="'.$enterprise_price_tax.'" style="width:70%;" /><br />';
        }
        
        public function enterprise_prices_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['enterprise_prices_metabox_nonce'], 'enterprise_prices_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Enterprise Price : Hours per day
            $enterprise_hours_per_day = sanitize_text_field($_POST['hours-per-day']);
            if (!empty($enterprise_hours_per_day)) update_post_meta ($post_id, 'hours-per-day', $enterprise_hours_per_day);
            else update_post_meta ($post_id, 'hours-per-day', '');
            
            // Update Enterprise Price : Hours per day
            $enterprise_price_per_hour = sanitize_text_field($_POST['price-per-hour']);
            if (!empty($enterprise_price_per_hour)) update_post_meta ($post_id, 'price-per-hour', $enterprise_price_per_hour);
            else update_post_meta ($post_id, 'price-per-hour', '');
            
            // Update Enterprise Price : Price Tax
            $enterprise_price_tax = sanitize_text_field($_POST['price-tax']);
            if (!empty($enterprise_price_tax)) update_post_meta ($post_id, 'price-tax', $enterprise_price_tax);
            else update_post_meta ($post_id, 'price-tax', '');
        }
    
        
    }
}

