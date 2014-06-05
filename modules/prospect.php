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
        
        /**
         * Prospect : Hook Admin Init 
         */
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
            
            // Test PDF
            add_meta_box('prospect-pdf-box', 'PDF', array($this,'prospect_pdf_render'), 'prospect', 'side', 'high');
            add_action('save_post', array($this,'prospect_pdf_save_postdata'));
            
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
            $prospect_property_owner        = get_post_meta($post->ID, 'property-owner', true);
            $prospect_property_address      = get_post_meta($post->ID, 'property-address', true);
            $prospect_property_zip_code     = get_post_meta($post->ID, 'property-zip-code', true);
            $prospect_property_city         = get_post_meta($post->ID, 'property-city', true);
            $prospect_property_country      = get_post_meta($post->ID, 'property-country', true);
            $prospect_property_phone        = get_post_meta($post->ID, 'property-phone', true);
            $prospect_property_phone2       = get_post_meta($post->ID, 'property-phone2', true);
            $prospect_property_phone3       = get_post_meta($post->ID, 'property-phone3', true);
            $prospect_property_fax          = get_post_meta($post->ID, 'property-fax', true);
            $prospect_property_email        = get_post_meta($post->ID, 'property-email', true);
            $prospect_property_email2       = get_post_meta($post->ID, 'property-email2', true);
            $prospect_property_website      = get_post_meta($post->ID, 'property-website', true);
            $prospect_property_latitude     = get_post_meta($post->ID, 'property-latitude', true);
            $prospect_property_longitude    = get_post_meta($post->ID, 'property-longitude', true);
            
            // Use nonce for verification
            echo '<input type="hidden" name="prospect_properties_metabox_nonce" value="'. wp_create_nonce('prospect_properties_metabox'). '" />';

            // Owner
            echo '<label style="width:25%;display:block;float:left;">'.__('Owner', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-owner" id="property-owner" value="'.$prospect_property_owner.'" style="width:70%;" /><br />';
            
            // Adresse
            echo '<label style="width:25%;display:block;float:left;">'.__('Address', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-address" id="property-address" value="'.$prospect_property_address.'" style="width:70%;" /><br />';
            
            // Zip Code
            echo '<label style="width:25%;display:block;float:left;">'.__('Zip Code', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-zip-code" id="property-zip-code" value="'.$prospect_property_zip_code.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('City', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-city" id="property-city" value="'.$prospect_property_city.'" style="width:70%;" /><br />';
            
            // Country
            echo '<label style="width:25%;display:block;float:left;">'.__('Country', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-country" id="property-country" value="'.$prospect_property_country.'" style="width:70%;" /><br />';

            // Telephone
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-phone" id="property-phone" value="'.$prospect_property_phone.'" style="width:70%;" /><br />';

            // Telephone 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="property-phone2" id="property-phone2" value="'.$prospect_property_phone2.'" style="width:70%;" /><br />';

            // Telephone 3
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 3</label>';
            echo '<input type="text" name="property-phone3" id="property-phone3" value="'.$prospect_property_phone3.'" style="width:70%;" /><br />';

            // Fax
            echo '<label style="width:25%;display:block;float:left;">'.__('Fax', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-fax" id="property-fax" value="'.$prospect_property_fax.'" style="width:70%;" /><br />';

            // Email
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-email" id="property-email" value="'.$prospect_property_email.'" style="width:70%;" /><br />';

            // Email 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="property-email2" id="property-email2" value="'.$prospect_property_email2.'" style="width:70%;" /><br />';

            // Website
            echo '<label style="width:25%;display:block;float:left;">'.__('Website', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-website" id="property-website" value="'.$prospect_property_website.'" style="width:70%;" /><br />';
            
            // Latitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Latitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-latitude" id="property-latitude" value="'.$prospect_property_latitude.'" style="width:70%;" /><br />';

            // Longitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Longitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-longitude" id="prospect-attribut-longitude" value="'.$prospect_property_longitude.'" style="width:70%;" /><br />';
            echo '<em><a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">Cliquez ici</a> pour obtenir obtenir les coordonnées</em><br />';
            
        }
        
        /**
         * Prospect Infos Save Post Data
         * @param type $post_id
         * @return type 
         */
        public function prospect_infos_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['prospect_properties_metabox_nonce'], 'prospect_properties_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Partner Attribut : Owner
            $prospect_property_owner = sanitize_text_field($_POST['property-owner']);
            if (!empty($prospect_property_owner)) update_post_meta ($post_id, 'property-owner', $prospect_property_owner);
            else update_post_meta ($post_id, 'property-owner', '');
            
            // Update Prospect Attribut : Address
            $prospect_property_address = sanitize_text_field($_POST['property-address']);
            if (!empty($prospect_property_address)) update_post_meta ($post_id, 'property-address', $prospect_property_address);
            else update_post_meta ($post_id, 'property-address', '');
            
            // Update Prospect Attribut : Zip Code
            $prospect_property_zip_code = sanitize_text_field($_POST['property-zip-code']);
            if (!empty($prospect_property_zip_code)) update_post_meta ($post_id, 'property-zip-code', $prospect_property_zip_code);
            else update_post_meta ($post_id, 'property-zip-code', '');
            
            // Update Prospect Attribut : City
            $prospect_property_city = sanitize_text_field($_POST['property-city']);
            if (!empty($prospect_property_city)) update_post_meta ($post_id, 'property-city', $prospect_property_city);
            else update_post_meta ($post_id, 'property-city', '');
            
            // Update Prospect Attribut : Country
            $prospect_property_country = sanitize_text_field($_POST['property-country']);
            if (!empty($prospect_property_country)) update_post_meta ($post_id, 'property-country', $prospect_property_country);
            else update_post_meta ($post_id, 'property-country', '');
            
            // Update Prospect Attribut : Phone
            $prospect_property_phone = sanitize_text_field($_POST['property-phone']);
            if (!empty($prospect_property_phone)) update_post_meta ($post_id, 'property-phone', $prospect_property_phone);
            else update_post_meta ($post_id, 'property-phone', '');
            
            // Update Prospect Attribut : Phone 2
            $prospect_property_phone2 = sanitize_text_field($_POST['property-phone2']);
            if (!empty($prospect_property_phone2)) update_post_meta ($post_id, 'property-phone2', $prospect_property_phone2);
            else update_post_meta ($post_id, 'property-phone2', '');
            
            // Update Prospect Attribut : Phone 3
            $prospect_property_phone3 = sanitize_text_field($_POST['property-phone3']);
            if (!empty($prospect_property_phone3)) update_post_meta ($post_id, 'property-phone3', $prospect_property_phone3);
            else update_post_meta ($post_id, 'property-phone3', '');
            
            // Update Prospect Attribut : Fax
            $prospect_property_fax = sanitize_text_field($_POST['property-fax']);
            if (!empty($prospect_property_fax)) update_post_meta ($post_id, 'property-fax', $prospect_property_fax);
            else update_post_meta ($post_id, 'property-fax', '');
            
            // Update Prospect Attribut : Email
            $prospect_property_email = sanitize_text_field($_POST['property-email']);
            if (!empty($prospect_property_email)) update_post_meta ($post_id, 'property-email', $prospect_property_email);
            else update_post_meta ($post_id, 'property-email', '');
            
            // Update Prospect Attribut : Email 2
            $prospect_property_email2 = sanitize_text_field($_POST['property-email2']);
            if (!empty($prospect_property_email2)) update_post_meta ($post_id, 'property-email2', $prospect_property_email2);
            else update_post_meta ($post_id, 'property-email2', '');
            
            // Update Prospect Attribut : Website
            $prospect_property_website = sanitize_text_field($_POST['property-website']);
            if (!empty($prospect_property_website)) update_post_meta ($post_id, 'property-website', $prospect_property_website);
            else update_post_meta ($post_id, 'property-website', '');
            
            // Update Prospect Attribut : Latitude
            $prospect_property_latitude = sanitize_text_field($_POST['property-latitude']);
            if (!empty($prospect_property_latitude)) update_post_meta ($post_id, 'property-latitude', $prospect_property_latitude);
            else update_post_meta ($post_id, 'property-latitude', '');
            
            // Update Prospect Attribut : Longitude
            $prospect_property_longitude = sanitize_text_field($_POST['property-longitude']);
            if (!empty($prospect_property_longitude)) update_post_meta ($post_id, 'property-longitude', $prospect_property_longitude);
            else update_post_meta ($post_id, 'property-longitude', '');
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
         * Transform Prospect To Customer Render
         * @global type $ls_manager
         * @global type $post 
         */
        public function prospect_to_customer_render(){
            
            global $ls_manager, $post;
            
            // Get Prospect Post Meta
            $prosect_to_customer = get_post_meta($post->ID, 'prospect-to-customer', true);
                        
            // Use nonce for verification
            echo '<input type="hidden" name="prospect_to_customer_metabox_nonce" value="'. wp_create_nonce('prospect_to_customer_metabox'). '" />';
            
            // Display Fields
            echo '<label style="width:100%;display:block;float:left;">'.__('Transform prospect into customer ?', $ls_manager->ls_manager_domain).'</label><br />';
            echo '<input type="radio" name="prospect-to-customer" id="prospect-to-customer-no" value="no" '.(empty($prosect_to_customer) || $prosect_to_customer == 'no' ? 'checked="checked"' : '').' />';
            echo '<label for="prospect-to-customer-no" style="margin-right:10px">'.__('No', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="radio" name="prospect-to-customer" id="prospect-to-customer-yes" value="yes" '.($prosect_to_customer == 'yes' ? 'checked="checked"' : '').' />';
            echo '<label for="prospect-to-customer-yes" style="margin-right:10px">'.__('Yes', $ls_manager->ls_manager_domain).'</label>';
            
        }
        
        /**
         * Prospect To Customer Save Postdata
         * @param type $post_id
         * @return type 
         */
        public function prospect_to_customer_save_postdata($post_id){
            
            // Check Nonce
            if (!wp_verify_nonce($_POST['prospect_to_customer_metabox_nonce'], 'prospect_to_customer_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Get Prospect To Customer Post Value
            $prospect_to_customer = sanitize_text_field($_POST['prospect-to-customer']);
            if (!empty($prospect_to_customer)) update_post_meta ($post_id, 'prospect-to-customer', $prospect_to_customer);
            else update_post_meta ($post_id, 'prospect-to-customer', '');
            
            // Convert Prospect To Customer ?
            if ($prospect_to_customer == 'yes'){ 
                // Change Post Type
                set_post_type($post_id, 'customer');
            }
        }
    
        public function prospect_pdf_render(){
            echo '<a href="#" id="create-pdf">'.Test.'</a>';
        }
        
        public function prospect_pdf_save_postdata(){
                       
        }
        
        /**
        * Prospect Add Columns
        * @param type $columns
        */
        public function prospect_add_columns($columns){
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
        public function prospect_add_custom_columns_render($column_name, $post_id){
            switch ($column_name) {
                case 'thumbnail' :    
                    // Display Thumbnail
                    $prospect_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'medium');
                    if (!empty($prospect_thumbnail))
                        echo '<img src="'.$prospect_thumbnail[0].'" alt="" />';
                break;
                case 'prospect' :    
                    // Get Prospect Summary
                    $prospect_property_owner        = get_post_meta($post_id, 'property-owner', true);
                    $prospect_property_address      = get_post_meta($post_id, 'property-address', true);
                    $prospect_property_zip_code     = get_post_meta($post_id, 'property-zip-code', true);
                    $prospect_property_city         = get_post_meta($post_id, 'property-city', true);
                    $prospect_property_country      = get_post_meta($post_id, 'property-country', true);
                    $prospect_property_phone        = get_post_meta($post_id, 'property-phone', true);
                    $prospect_property_email        = get_post_meta($post_id, 'property-email', true);
                    // Display Prospect Summary
                    if (!empty($prospect_property_owner))
                        echo $prospect_property_owner . '<br />';
                    if (!empty($prospect_property_address) && !empty($prospect_property_zip_code) && !empty($prospect_property_city))
                        echo $prospect_property_address . '<br />' . $prospect_property_zip_code . ' ' . $prospect_property_city . '<br />';
                    if (!empty($prospect_property_country))
                        echo $prospect_property_country . '<br />';
                    if (!empty($prospect_property_phone))
                        echo $prospect_property_phone . '<br />';
                    if (!empty($prospect_property_email))
                        echo $prospect_property_email . '<br />';
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

