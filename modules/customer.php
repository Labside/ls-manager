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
        }
        
        /**
         * Customer Infos Render
         * @global type $ls_manager
         * @global type $post 
         */
        public function customer_infos_render(){
            global $ls_manager, $post;
            
            // Get Post Meta Key 
            $customer_attribut_owner        = get_post_meta($post->ID, 'customer-attribut-owner', true);
            $customer_attribut_address      = get_post_meta($post->ID, 'customer-attribut-address', true);
            $customer_attribut_zip_code     = get_post_meta($post->ID, 'customer-attribut-zip-code', true);
            $customer_attribut_city         = get_post_meta($post->ID, 'customer-attribut-city', true);
            $customer_attribut_country         = get_post_meta($post->ID, 'customer-attribut-country', true);
            $customer_attribut_phone        = get_post_meta($post->ID, 'customer-attribut-phone', true);
            $customer_attribut_phone2       = get_post_meta($post->ID, 'customer-attribut-phone2', true);
            $customer_attribut_phone3       = get_post_meta($post->ID, 'customer-attribut-phone3', true);
            $customer_attribut_fax          = get_post_meta($post->ID, 'customer-attribut-fax', true);
            $customer_attribut_email        = get_post_meta($post->ID, 'customer-attribut-email', true);
            $customer_attribut_email2       = get_post_meta($post->ID, 'customer-attribut-email2', true);
            $customer_attribut_website      = get_post_meta($post->ID, 'customer-attribut-website', true);
            $customer_attribut_latitude     = get_post_meta($post->ID, 'customer-attribut-latitude', true);
            $customer_attribut_longitude    = get_post_meta($post->ID, 'customer-attribut-longitude', true);
            
            // Use nonce for verification
            echo '<input type="hidden" name="customer_attributs_metabox_nonce" value="'. wp_create_nonce('customer_attributs_metabox'). '" />';

            // Owner
            echo '<label style="width:25%;display:block;float:left;">'.__('Owner', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-owner" id="customer-attribut-owner" value="'.$customer_attribut_owner.'" style="width:70%;" /><br />';
            
            // Adresse
            echo '<label style="width:25%;display:block;float:left;">'.__('Address', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-address" id="customer-attribut-address" value="'.$customer_attribut_address.'" style="width:70%;" /><br />';
            
            // Zip Code
            echo '<label style="width:25%;display:block;float:left;">'.__('Zip Code', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-zip-code" id="customer-attribut-zip-code" value="'.$customer_attribut_zip_code.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('City', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-city" id="customer-attribut-city" value="'.$customer_attribut_city.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('Country', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-country" id="customer-attribut-country" value="'.$customer_attribut_country.'" style="width:70%;" /><br />';

            // Telephone
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-phone" id="customer-attribut-phone" value="'.$customer_attribut_phone.'" style="width:70%;" /><br />';

            // Telephone 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="customer-attribut-phone2" id="customer-attribut-phone2" value="'.$customer_attribut_phone2.'" style="width:70%;" /><br />';

            // Telephone 3
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 3</label>';
            echo '<input type="text" name="customer-attribut-phone3" id="customer-attribut-phone3" value="'.$customer_attribut_phone3.'" style="width:70%;" /><br />';

            // Fax
            echo '<label style="width:25%;display:block;float:left;">'.__('Fax', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-fax" id="customer-attribut-fax" value="'.$customer_attribut_fax.'" style="width:70%;" /><br />';

            // Email
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-email" id="customer-attribut-email" value="'.$customer_attribut_email.'" style="width:70%;" /><br />';

            // Email 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="customer-attribut-email2" id="customer-attribut-email2" value="'.$customer_attribut_email2.'" style="width:70%;" /><br />';

            // Website
            echo '<label style="width:25%;display:block;float:left;">'.__('Website', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-website" id="customer-attribut-website" value="'.$customer_attribut_website.'" style="width:70%;" /><br />';
            
            // Latitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Latitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-latitude" id="customer-attribut-latitude" value="'.$customer_attribut_latitude.'" style="width:70%;" /><br />';

            // Longitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Longitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="customer-attribut-longitude" id="customer-attribut-longitude" value="'.$customer_attribut_longitude.'" style="width:70%;" /><br />';
            echo '<em><a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">Cliquez ici</a> pour obtenir obtenir les coordonnées</em><br />';
        }
        
        /**
         * Customer Infos Save Postdata
         * @param type $post_id
         * @return type 
         */
        public function customer_infos_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['customer_attributs_metabox_nonce'], 'customer_attributs_metabox'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Partner Attribut : Owner
            $customer_attribut_owner = sanitize_text_field($_POST['customer-attribut-owner']);
            if (!empty($customer_attribut_owner)) update_post_meta ($post_id, 'customer-attribut-owner', $customer_attribut_owner);
            else update_post_meta ($post_id, 'customer-attribut-owner', '');
            
            // Update Customer Attribut : Address
            $customer_attribut_address = sanitize_text_field($_POST['customer-attribut-address']);
            if (!empty($customer_attribut_address)) update_post_meta ($post_id, 'customer-attribut-address', $customer_attribut_address);
            else update_post_meta ($post_id, 'customer-attribut-address', '');
            
            // Update Customer Attribut : Zip Code
            $customer_attribut_zip_code = sanitize_text_field($_POST['customer-attribut-zip-code']);
            if (!empty($customer_attribut_zip_code)) update_post_meta ($post_id, 'customer-attribut-zip-code', $customer_attribut_zip_code);
            else update_post_meta ($post_id, 'customer-attribut-zip-code', '');
            
            // Update Customer Attribut : City
            $customer_attribut_city = sanitize_text_field($_POST['customer-attribut-city']);
            if (!empty($customer_attribut_city)) update_post_meta ($post_id, 'customer-attribut-city', $customer_attribut_city);
            else update_post_meta ($post_id, 'customer-attribut-city', '');
            
            // Update Customer Attribut : Country
            $customer_attribut_country = sanitize_text_field($_POST['customer-attribut-country']);
            if (!empty($customer_attribut_country)) update_post_meta ($post_id, 'customer-attribut-country', $customer_attribut_country);
            else update_post_meta ($post_id, 'customer-attribut-country', '');
            
            // Update Customer Attribut : Phone
            $customer_attribut_phone = sanitize_text_field($_POST['customer-attribut-phone']);
            if (!empty($customer_attribut_phone)) update_post_meta ($post_id, 'customer-attribut-phone', $customer_attribut_phone);
            else update_post_meta ($post_id, 'customer-attribut-phone', '');
            
            // Update Customer Attribut : Phone 2
            $customer_attribut_phone2 = sanitize_text_field($_POST['customer-attribut-phone2']);
            if (!empty($customer_attribut_phone2)) update_post_meta ($post_id, 'customer-attribut-phone2', $customer_attribut_phone2);
            else update_post_meta ($post_id, 'customer-attribut-phone2', '');
            
            // Update Customer Attribut : Phone 3
            $customer_attribut_phone3 = sanitize_text_field($_POST['customer-attribut-phone3']);
            if (!empty($customer_attribut_phone3)) update_post_meta ($post_id, 'customer-attribut-phone3', $customer_attribut_phone3);
            else update_post_meta ($post_id, 'customer-attribut-phone3', '');
            
            // Update Customer Attribut : Fax
            $customer_attribut_fax = sanitize_text_field($_POST['customer-attribut-fax']);
            if (!empty($customer_attribut_fax)) update_post_meta ($post_id, 'customer-attribut-fax', $customer_attribut_fax);
            else update_post_meta ($post_id, 'customer-attribut-fax', '');
            
            // Update Customer Attribut : Email
            $customer_attribut_email = sanitize_text_field($_POST['customer-attribut-email']);
            if (!empty($customer_attribut_email)) update_post_meta ($post_id, 'customer-attribut-email', $customer_attribut_email);
            else update_post_meta ($post_id, 'customer-attribut-email', '');
            
            // Update Customer Attribut : Email 2
            $customer_attribut_email2 = sanitize_text_field($_POST['customer-attribut-email2']);
            if (!empty($customer_attribut_email2)) update_post_meta ($post_id, 'customer-attribut-email2', $customer_attribut_email2);
            else update_post_meta ($post_id, 'customer-attribut-email2', '');
            
            // Update Customer Attribut : Website
            $customer_attribut_website = sanitize_text_field($_POST['customer-attribut-website']);
            if (!empty($customer_attribut_website)) update_post_meta ($post_id, 'customer-attribut-website', $customer_attribut_website);
            else update_post_meta ($post_id, 'customer-attribut-website', '');
            
            // Update Customer Attribut : Latitude
            $customer_attribut_latitude = sanitize_text_field($_POST['customer-attribut-latitude']);
            if (!empty($customer_attribut_latitude)) update_post_meta ($post_id, 'customer-attribut-latitude', $customer_attribut_latitude);
            else update_post_meta ($post_id, 'customer-attribut-latitude', '');
            
            // Update Customer Attribut : Longitude
            $customer_attribut_longitude = sanitize_text_field($_POST['customer-attribut-longitude']);
            if (!empty($customer_attribut_longitude)) update_post_meta ($post_id, 'customer-attribut-longitude', $customer_attribut_longitude);
            else update_post_meta ($post_id, 'customer-attribut-longitude', '');
        }
    }
}

