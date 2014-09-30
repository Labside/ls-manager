<?php

if (!class_exists('LS_Manager_Customer')) {
    
    /**
     * LS_Manager_Customer - Object Class 
     */
    class LS_Manager_Customer{
        
        /** 
         * Construct the plugin object 
         */ 
        public function __construct() {
            // Add Render & Save Hooks
            add_action( 'admin_init', array(&$this,'admin_customer_init') );            
        }
        
        /**
         * Customer Admin Init Hooks 
         */
        public function admin_customer_init(){
            // Customer Infos MetaBox + Save Data
            add_meta_box('customer-infos-box', 'Informations détaillées', array(&$this,'customer_infos_render'), 'customer', 'normal', 'high');
            add_action('save_post', array(&$this,'customer_infos_save_postdata'));
            
            // Customer Custom Columns In Post Type List
            add_filter( 'manage_edit-customer_columns', array(&$this,'customer_add_columns') ) ;
            add_action( 'manage_customer_posts_custom_column', array(&$this,'customer_add_custom_columns_render'), 10, 2);
        }
        
        /**
         * Customer Infos Render
         * @global type $ls_manager
         * @global type $post 
         */
        public function customer_infos_render(){
            global $ls_manager, $post;
            
            // Get Post Meta Key 
            $customer_property_owner        = get_post_meta($post->ID, 'property-owner', true);
            $customer_property_address      = get_post_meta($post->ID, 'property-address', true);
            $customer_property_zip_code     = get_post_meta($post->ID, 'property-zip-code', true);
            $customer_property_city         = get_post_meta($post->ID, 'property-city', true);
            $customer_property_country      = get_post_meta($post->ID, 'property-country', true);
            $customer_property_phone        = get_post_meta($post->ID, 'property-phone', true);
            $customer_property_phone2       = get_post_meta($post->ID, 'property-phone2', true);
            $customer_property_phone3       = get_post_meta($post->ID, 'property-phone3', true);
            $customer_property_fax          = get_post_meta($post->ID, 'property-fax', true);
            $customer_property_email        = get_post_meta($post->ID, 'property-email', true);
            $customer_property_email2       = get_post_meta($post->ID, 'property-email2', true);
            $customer_property_website      = get_post_meta($post->ID, 'property-website', true);
            $customer_property_latitude     = get_post_meta($post->ID, 'property-latitude', true);
            $customer_property_longitude    = get_post_meta($post->ID, 'property-longitude', true);
            
            // Use nonce for verification
            echo '<input type="hidden" name="customer_properties_metabox_nonce" value="'. wp_create_nonce('customer_properties_metabox'). '" />';

            // Owner
            echo '<label style="width:25%;display:block;float:left;">'.__('Owner', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-owner" id="property-owner" value="'.$customer_property_owner.'" style="width:70%;" /><br />';
            
            // Adresse
            echo '<label style="width:25%;display:block;float:left;">'.__('Address', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-address" id="property-address" value="'.$customer_property_address.'" style="width:70%;" /><br />';
            
            // Zip Code
            echo '<label style="width:25%;display:block;float:left;">'.__('Zip Code', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-zip-code" id="property-zip-code" value="'.$customer_property_zip_code.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('City', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-city" id="property-city" value="'.$customer_property_city.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('Country', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-country" id="property-country" value="'.$customer_property_country.'" style="width:70%;" /><br />';

            // Telephone
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-phone" id="property-phone" value="'.$customer_property_phone.'" style="width:70%;" /><br />';

            // Telephone 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="property-phone2" id="property-phone2" value="'.$customer_property_phone2.'" style="width:70%;" /><br />';

            // Telephone 3
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 3</label>';
            echo '<input type="text" name="property-phone3" id="property-phone3" value="'.$customer_property_phone3.'" style="width:70%;" /><br />';

            // Fax
            echo '<label style="width:25%;display:block;float:left;">'.__('Fax', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-fax" id="property-fax" value="'.$customer_property_fax.'" style="width:70%;" /><br />';

            // Email
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-email" id="property-email" value="'.$customer_property_email.'" style="width:70%;" /><br />';

            // Email 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="property-email2" id="property-email2" value="'.$customer_property_email2.'" style="width:70%;" /><br />';

            // Website
            echo '<label style="width:25%;display:block;float:left;">'.__('Website', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-website" id="property-website" value="'.$customer_property_website.'" style="width:70%;" /><br />';
            
            // Latitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Latitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-latitude" id="property-latitude" value="'.$customer_property_latitude.'" style="width:70%;" /><br />';

            // Longitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Longitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-longitude" id="property-longitude" value="'.$customer_property_longitude.'" style="width:70%;" /><br />';
            echo '<em><a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">Cliquez ici</a> pour obtenir obtenir les coordonnées</em><br />';
        }
        
        /**
         * Customer Infos Save Postdata
         * @param type $post_id
         * @return type 
         */
        public function customer_infos_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['customer_properties_metabox_nonce'], 'customer_properties_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Partner Attribut : Owner
            $customer_property_owner = sanitize_text_field($_POST['property-owner']);
            if (!empty($customer_property_owner)) update_post_meta ($post_id, 'property-owner', $customer_property_owner);
            else update_post_meta ($post_id, 'property-owner', '');
            
            // Update Customer Attribut : Address
            $customer_property_address = sanitize_text_field($_POST['property-address']);
            if (!empty($customer_property_address)) update_post_meta ($post_id, 'property-address', $customer_property_address);
            else update_post_meta ($post_id, 'property-address', '');
            
            // Update Customer Attribut : Zip Code
            $customer_property_zip_code = sanitize_text_field($_POST['property-zip-code']);
            if (!empty($customer_property_zip_code)) update_post_meta ($post_id, 'property-zip-code', $customer_property_zip_code);
            else update_post_meta ($post_id, 'property-zip-code', '');
            
            // Update Customer Attribut : City
            $customer_property_city = sanitize_text_field($_POST['property-city']);
            if (!empty($customer_property_city)) update_post_meta ($post_id, 'property-city', $customer_property_city);
            else update_post_meta ($post_id, 'property-city', '');
            
            // Update Customer Attribut : Country
            $customer_property_country = sanitize_text_field($_POST['property-country']);
            if (!empty($customer_property_country)) update_post_meta ($post_id, 'property-country', $customer_property_country);
            else update_post_meta ($post_id, 'property-country', '');
            
            // Update Customer Attribut : Phone
            $customer_property_phone = sanitize_text_field($_POST['property-phone']);
            if (!empty($customer_property_phone)) update_post_meta ($post_id, 'property-phone', $customer_property_phone);
            else update_post_meta ($post_id, 'property-phone', '');
            
            // Update Customer Attribut : Phone 2
            $customer_property_phone2 = sanitize_text_field($_POST['property-phone2']);
            if (!empty($customer_property_phone2)) update_post_meta ($post_id, 'property-phone2', $customer_property_phone2);
            else update_post_meta ($post_id, 'property-phone2', '');
            
            // Update Customer Attribut : Phone 3
            $customer_property_phone3 = sanitize_text_field($_POST['property-phone3']);
            if (!empty($customer_property_phone3)) update_post_meta ($post_id, 'property-phone3', $customer_property_phone3);
            else update_post_meta ($post_id, 'property-phone3', '');
            
            // Update Customer Attribut : Fax
            $customer_property_fax = sanitize_text_field($_POST['property-fax']);
            if (!empty($customer_property_fax)) update_post_meta ($post_id, 'property-fax', $customer_property_fax);
            else update_post_meta ($post_id, 'property-fax', '');
            
            // Update Customer Attribut : Email
            $customer_property_email = sanitize_text_field($_POST['property-email']);
            if (!empty($customer_property_email)) update_post_meta ($post_id, 'property-email', $customer_property_email);
            else update_post_meta ($post_id, 'property-email', '');
            
            // Update Customer Attribut : Email 2
            $customer_property_email2 = sanitize_text_field($_POST['property-email2']);
            if (!empty($customer_property_email2)) update_post_meta ($post_id, 'property-email2', $customer_property_email2);
            else update_post_meta ($post_id, 'property-email2', '');
            
            // Update Customer Attribut : Website
            $customer_property_website = sanitize_text_field($_POST['property-website']);
            if (!empty($customer_property_website)) update_post_meta ($post_id, 'property-website', $customer_property_website);
            else update_post_meta ($post_id, 'property-website', '');
            
            // Update Customer Attribut : Latitude
            $customer_property_latitude = sanitize_text_field($_POST['property-latitude']);
            if (!empty($customer_property_latitude)) update_post_meta ($post_id, 'property-latitude', $customer_property_latitude);
            else update_post_meta ($post_id, 'property-latitude', '');
            
            // Update Customer Attribut : Longitude
            $customer_property_longitude = sanitize_text_field($_POST['property-longitude']);
            if (!empty($customer_property_longitude)) update_post_meta ($post_id, 'property-longitude', $customer_property_longitude);
            else update_post_meta ($post_id, 'property-longitude', '');
        }
        
        /**
        * Customer Posts List : Insert Custom Columns
        * @param type $columns
        */
        function customer_add_columns($columns){
            global $ls_manager;
            $custom_columns = array();
            foreach($columns as $key => $title) {
                if ($key=='title') {
                    $custom_columns['thumbnail']  = 'Logo';
                    $custom_columns[$key]         = $title;
                    $custom_columns['customer']    = __('Summary', $ls_manager->ls_manager_domain);
                }
                elseif ($key=='date'){
                    unset($custom_columns[$key]);
                }
                else $custom_columns[$key] = $title;
            }
            return $custom_columns;
        }
        
        /**
        * Customer Posts List : Custom Columns Render
        * @param type $column_name
        * @param type $post_id 
        */
        function customer_add_custom_columns_render($column_name, $post_id){
            switch ($column_name) {
                case 'thumbnail' :    
                    // Display Thumbnail
                    $customer_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'medium');
                    if (!empty($customer_thumbnail))
                        echo '<img src="'.$customer_thumbnail[0].'" alt="" />';
                break;
                case 'customer' :    
                    // Get Partenaire Summary
                    $customer_property_owner        = get_post_meta($post_id, 'property-owner', true);
                    $customer_property_address      = get_post_meta($post_id, 'property-address', true);
                    $customer_property_zip_code     = get_post_meta($post_id, 'property-zip-code', true);
                    $customer_property_city         = get_post_meta($post_id, 'property-city', true);
                    $customer_property_country      = get_post_meta($post_id, 'property-country', true);
                    $customer_property_phone        = get_post_meta($post_id, 'property-phone', true);
                    $customer_property_email        = get_post_meta($post_id, 'property-email', true);
                    // Display Partenaire Summary
                    if (!empty($customer_property_owner))
                        echo $customer_property_owner . '<br />';
                    if (!empty($customer_property_address) && !empty($customer_property_zip_code) && !empty($customer_property_city))
                        echo $customer_property_address . '<br />' . $customer_property_zip_code . ' ' . $customer_property_city . '<br />';
                    if (!empty($customer_property_country))
                        echo $customer_property_country . '<br />';
                    if (!empty($customer_property_phone))
                        echo $customer_property_phone . '<br />';
                    if (!empty($customer_property_email))
                        echo $customer_property_email . '<br />';
                break;
            }
        }
    }
}

