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
        
        /**
         * Hook Admin Init 
         */
        public function admin_partner_init(){
            
            // Partner Properties MetaBox + Save Data
            add_meta_box('partner-infos-box', 'Informations détaillées', array(&$this,'partner_properties_render'), 'partner', 'normal', 'high');
            add_action('save_post', array(&$this,'partner_properties_save_postdata'));
            
            // Enterprise Association MetaBox + Save Data
            add_meta_box('enterprise-association-box', 'Entreprise associée', array(&$this,'enterprise_association_render'), 'partner', 'side', 'high');
            add_action('save_post', array(&$this,'enterprise_association_save_postdata'));
            
            // Partner Custom Columns In Post Type List
            add_filter( 'manage_edit-partner_columns', array(&$this,'partner_add_columns') ) ;
            add_action( 'manage_partner_posts_custom_column', array(&$this,'partner_add_custom_columns_render'), 10, 2);
            
            // Partner Custom Style
            add_action('admin_head', array(&$this,'partner_admin_head'));
        }
                
        /**
         *  Display Partner Custom Style
         *  TODO : Optimize => Put in specific css file
         */
        function partner_admin_head() {
            echo '<style type="text/css">';
            echo '#posts-filter .tablenav select[name=m]{display:none;}';
            echo '#thumbnail { width:15%;}';
            echo '.column-thumbnail img { max-width:100%;}';
            echo '@media all and (max-width: 768px) {';
            echo '#thumbnail { width:20%;}';
            echo '.column-summary{display:block;}';
            echo '.column-title{width:30%;}';
            echo '}';
            echo '@media all and (min-width: 320px) and (max-width: 360px) {';
            echo '.column-thumbnail {display:none;}';
            echo '.column-summary{display:block;}';
            echo '.column-title{width:40%;}';
            echo '}';
            echo '</style>';
        }
        
        /**
         * Render Meta Box : Partner Properties
         */
        public function partner_properties_render(){
            
            global $ls_manager, $post;
            
            // Get Post Meta Key 
            $partner_property_owner        = get_post_meta($post->ID, 'property-owner', true);
            $partner_property_address      = get_post_meta($post->ID, 'property-address', true);
            $partner_property_zip_code     = get_post_meta($post->ID, 'property-zip-code', true);
            $partner_property_city         = get_post_meta($post->ID, 'property-city', true);
            $partner_property_country      = get_post_meta($post->ID, 'property-country', true);
            $partner_property_phone        = get_post_meta($post->ID, 'property-phone', true);
            $partner_property_phone2       = get_post_meta($post->ID, 'property-phone2', true);
            $partner_property_phone3       = get_post_meta($post->ID, 'property-phone3', true);
            $partner_property_fax          = get_post_meta($post->ID, 'property-fax', true);
            $partner_property_email        = get_post_meta($post->ID, 'property-email', true);
            $partner_property_email2       = get_post_meta($post->ID, 'property-email2', true);
            $partner_property_website      = get_post_meta($post->ID, 'property-website', true);
            $partner_property_latitude     = get_post_meta($post->ID, 'property-latitude', true);
            $partner_property_longitude    = get_post_meta($post->ID, 'property-longitude', true);
            
            // Use nonce for verification            
            echo '<input type="hidden" name="partner_properties_metabox_nonce" value="'. wp_create_nonce('partner_properties_metabox'). '" />';

            // Owner
            echo '<label style="width:25%;display:block;float:left;">'.__('Owner', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-owner" id="property-owner" value="'.$partner_property_owner.'" style="width:70%;" /><br />';
            
            // Adresse
            echo '<label style="width:25%;display:block;float:left;">'.__('Address', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-address" id="property-address" value="'.$partner_property_address.'" style="width:70%;" /><br />';
            
            // Zip Code
            echo '<label style="width:25%;display:block;float:left;">'.__('Zip Code', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-zip-code" id="property-zip-code" value="'.$partner_property_zip_code.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('City', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-city" id="property-city" value="'.$partner_property_city.'" style="width:70%;" /><br />';
            
            // Country
            echo '<label style="width:25%;display:block;float:left;">'.__('Country', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-country" id="property-city" value="'.$partner_property_country.'" style="width:70%;" /><br />';

            // Telephone
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-phone" id="property-phone" value="'.$partner_property_phone.'" style="width:70%;" /><br />';

            // Telephone 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="property-phone2" id="property-phone2" value="'.$partner_property_phone2.'" style="width:70%;" /><br />';

            // Telephone 3
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 3</label>';
            echo '<input type="text" name="property-phone3" id="property-phone3" value="'.$partner_property_phone3.'" style="width:70%;" /><br />';

            // Fax
            echo '<label style="width:25%;display:block;float:left;">'.__('Fax', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-fax" id="property-fax" value="'.$partner_property_fax.'" style="width:70%;" /><br />';

            // Email
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-email" id="property-email" value="'.$partner_property_email.'" style="width:70%;" /><br />';

            // Email 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="property-email2" id="property-email2" value="'.$partner_property_email2.'" style="width:70%;" /><br />';

            // Website
            echo '<label style="width:25%;display:block;float:left;">'.__('Website', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-website" id="property-website" value="'.$partner_property_website.'" style="width:70%;" /><br />';
            
            // Latitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Latitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-latitude" id="property-latitude" value="'.$partner_property_latitude.'" style="width:70%;" /><br />';

            // Longitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Longitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="property-longitude" id="property-longitude" value="'.$partner_property_longitude.'" style="width:70%;" /><br />';
            echo '<em><a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">Cliquez ici</a> pour obtenir obtenir les coordonnées</em><br />';
        }
        
        /**
         * Save PostData : Partner Properties
         * @param type $post_id
         */
        public function partner_properties_save_postdata($post_id){
            
            // Check Nonce
            if (!wp_verify_nonce($_POST['partner_properties_metabox_nonce'], 'partner_properties_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Partner Attribut : Owner
            $partner_property_owner = sanitize_text_field($_POST['property-owner']);
            if (!empty($partner_property_owner)) update_post_meta ($post_id, 'property-owner', $partner_property_owner);
            else update_post_meta ($post_id, 'property-owner', '');
            
            // Update Partner Attribut : Address
            $partner_property_address = sanitize_text_field($_POST['property-address']);
            if (!empty($partner_property_address)) update_post_meta ($post_id, 'property-address', $partner_property_address);
            else update_post_meta ($post_id, 'property-address', '');
            
            // Update Partner Attribut : Zip Code
            $partner_property_zip_code = sanitize_text_field($_POST['property-zip-code']);
            if (!empty($partner_property_zip_code)) update_post_meta ($post_id, 'property-zip-code', $partner_property_zip_code);
            else update_post_meta ($post_id, 'property-zip-code', '');
            
            // Update Partner Attribut : City
            $partner_property_city = sanitize_text_field($_POST['property-city']);
            if (!empty($partner_property_city)) update_post_meta ($post_id, 'property-city', $partner_property_city);
            else update_post_meta ($post_id, 'property-city', '');
            
            // Update Partner Attribut : Country
            $partner_property_country = sanitize_text_field($_POST['property-country']);
            if (!empty($partner_property_country)) update_post_meta ($post_id, 'property-country', $partner_property_country);
            else update_post_meta ($post_id, 'property-country', '');
            
            // Update Partner Attribut : Phone
            $partner_property_phone = sanitize_text_field($_POST['property-phone']);
            if (!empty($partner_property_phone)) update_post_meta ($post_id, 'property-phone', $partner_property_phone);
            else update_post_meta ($post_id, 'property-phone', '');
            
            // Update Partner Attribut : Phone 2
            $partner_property_phone2 = sanitize_text_field($_POST['property-phone2']);
            if (!empty($partner_property_phone2)) update_post_meta ($post_id, 'property-phone2', $partner_property_phone2);
            else update_post_meta ($post_id, 'property-phone2', '');
            
            // Update Partner Attribut : Phone 3
            $partner_property_phone3 = sanitize_text_field($_POST['property-phone3']);
            if (!empty($partner_property_phone3)) update_post_meta ($post_id, 'property-phone3', $partner_property_phone3);
            else update_post_meta ($post_id, 'property-phone3', '');
            
            // Update Partner Attribut : Fax
            $partner_property_fax = sanitize_text_field($_POST['property-fax']);
            if (!empty($partner_property_fax)) update_post_meta ($post_id, 'property-fax', $partner_property_fax);
            else update_post_meta ($post_id, 'property-fax', '');
            
            // Update Partner Attribut : Email
            $partner_property_email = sanitize_text_field($_POST['property-email']);
            if (!empty($partner_property_email)) update_post_meta ($post_id, 'property-email', $partner_property_email);
            else update_post_meta ($post_id, 'property-email', '');
            
            // Update Partner Attribut : Email 2
            $partner_property_email2 = sanitize_text_field($_POST['property-email2']);
            if (!empty($partner_property_email2)) update_post_meta ($post_id, 'property-email2', $partner_property_email2);
            else update_post_meta ($post_id, 'property-email2', '');
            
            // Update Partner Attribut : Website
            $partner_property_website = sanitize_text_field($_POST['property-website']);
            if (!empty($partner_property_website)) update_post_meta ($post_id, 'property-website', $partner_property_website);
            else update_post_meta ($post_id, 'property-website', '');
            
            // Update Partner Attribut : Latitude
            $partner_property_latitude = sanitize_text_field($_POST['property-latitude']);
            if (!empty($partner_property_latitude)) update_post_meta ($post_id, 'property-latitude', $partner_property_latitude);
            else update_post_meta ($post_id, 'property-latitude', '');
            
            // Update Partner Attribut : Longitude
            $partner_property_longitude = sanitize_text_field($_POST['property-longitude']);
            if (!empty($partner_property_longitude)) update_post_meta ($post_id, 'property-longitude', $partner_property_longitude);
            else update_post_meta ($post_id, 'property-longitude', '');
        }
        
        /**
         * Render Meta Box : Associate Partner To Existing Enterprise 
         * @global type $ls_manager
         * @global type $post 
         */
        public function enterprise_association_render(){
            global $ls_manager, $post;
            
            // Get Enterprise Association Post Meta
            $enterprise_association_id = get_post_meta($post->ID, 'enterprise-association-id', true);
            
            // Use nonce for verification            
            echo '<input type="hidden" name="enterprise_association_metabox_nonce" value="'. wp_create_nonce('enterprise_association_metabox'). '" />';
            
            // Display Dropdown List
            echo '<select id="enterprise-association-id" name="enterprise-association-id" >';
            echo '  <option value=""> -- </option>';
            // Get List Of Enterprise
            $args = array('post_type' => 'enterprise', 'posts_per_page' => '-1', 'post_status' => 'publish', 'orderby' => 'title');
            $enterprise_collection = get_posts($args);
            if (sizeof($enterprise_collection) > 0){
                foreach($enterprise_collection as $enterprise){
                    $selected = $enterprise->ID == $enterprise_association_id ? 'selected="selected"' : '';
                    echo '  <option value="'.$enterprise->ID.'" '.$selected.'>'.$enterprise->post_title.'</option>';
                }
            }
            echo '</select>';
            
        }
        
        /**
         * Save Postdata : Associate Partner To Existing Enterprise 
         * @param type $post_id
         * @return type 
         */
        public function enterprise_association_save_postdata($post_id){
            
            // Check Nonce
            if (!wp_verify_nonce($_POST['enterprise_association_metabox_nonce'], 'enterprise_association_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Post Meta Value
            $enterprise_association_id = sanitize_text_field($_POST['enterprise-association-id']);
            if (!empty($enterprise_association_id)) update_post_meta($post_id, 'enterprise-association-id',$enterprise_association_id);
            else update_post_meta($post_id, 'enterprise-association-id', '');
        }
        
        /**
        * Partner Posts List : Insert Custom Columns
        * @param type $columns
        */
        function partner_add_columns($columns){
            global $ls_manager;
            $custom_columns = array();
            foreach($columns as $key => $title) {
                if ($key=='title') {
                    $custom_columns['thumbnail']  = 'Logo';
                    $custom_columns[$key]         = $title;
                    $custom_columns['partner']    = __('Summary', $ls_manager->ls_manager_domain);
                }
                elseif ($key=='date'){
                    unset($custom_columns[$key]);
                }
                else $custom_columns[$key] = $title;
            }
            return $custom_columns;
        }
        
        /**
        * Partner Posts List : Custom Columns Render
        * @param type $column_name
        * @param type $post_id 
        */
        function partner_add_custom_columns_render($column_name, $post_id){
            switch ($column_name) {
                case 'thumbnail' :    
                    // Display Thumbnail
                    $partner_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'medium');
                    if (!empty($partner_thumbnail))
                        echo '<img src="'.$partner_thumbnail[0].'" alt="" />';
                break;
                case 'partner' :    
                    // Get Partenaire Summary
                    $partner_property_owner        = get_post_meta($post_id, 'property-owner', true);
                    $partner_property_address      = get_post_meta($post_id, 'property-address', true);
                    $partner_property_zip_code     = get_post_meta($post_id, 'property-zip-code', true);
                    $partner_property_city         = get_post_meta($post_id, 'property-city', true);
                    $partner_property_country      = get_post_meta($post_id, 'property-country', true);
                    $partner_property_phone        = get_post_meta($post_id, 'property-phone', true);
                    $partner_property_email        = get_post_meta($post_id, 'property-email', true);
                    // Display Partenaire Summary
                    if (!empty($partner_property_owner))
                        echo $partner_property_owner . '<br />';
                    if (!empty($partner_property_address) && !empty($partner_property_zip_code) && !empty($partner_property_city))
                        echo $partner_property_address . '<br />' . $partner_property_zip_code . ' ' . $partner_property_city . '<br />';
                    if (!empty($partner_property_country))
                        echo $partner_property_country . '<br />';
                    if (!empty($partner_property_phone))
                        echo $partner_property_phone . '<br />';
                    if (!empty($partner_property_email))
                        echo $partner_property_email . '<br />';
                break;
            }
        }
    }
}

