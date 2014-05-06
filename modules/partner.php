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
            
            // Partner Infos MetaBox + Save Data
            add_meta_box('partner-infos-box', 'Informations détaillées', array(&$this,'partner_attributs_render'), 'partner', 'normal', 'high');
            add_action('save_post', array(&$this,'partner_attributs_save_postdata'));
            
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
         * Render Meta Box : Partner Attributs
         */
        public function partner_attributs_render(){
            
            global $ls_manager, $post;
            
            // Get Post Meta Key 
            $partner_attribut_owner        = get_post_meta($post->ID, 'partner-attribut-owner', true);
            $partner_attribut_address      = get_post_meta($post->ID, 'partner-attribut-address', true);
            $partner_attribut_zip_code     = get_post_meta($post->ID, 'partner-attribut-zip-code', true);
            $partner_attribut_city         = get_post_meta($post->ID, 'partner-attribut-city', true);
            $partner_attribut_phone        = get_post_meta($post->ID, 'partner-attribut-phone', true);
            $partner_attribut_phone2       = get_post_meta($post->ID, 'partner-attribut-phone2', true);
            $partner_attribut_phone3       = get_post_meta($post->ID, 'partner-attribut-phone3', true);
            $partner_attribut_fax          = get_post_meta($post->ID, 'partner-attribut-fax', true);
            $partner_attribut_email        = get_post_meta($post->ID, 'partner-attribut-email', true);
            $partner_attribut_email2       = get_post_meta($post->ID, 'partner-attribut-email2', true);
            $partner_attribut_website      = get_post_meta($post->ID, 'partner-attribut-website', true);
            $partner_attribut_latitude     = get_post_meta($post->ID, 'partner-attribut-latitude', true);
            $partner_attribut_longitude    = get_post_meta($post->ID, 'partner-attribut-longitude', true);
            
            // Use nonce for verification
            echo wp_nonce_field('partner_attributs_metabox_submit','partner_attributs_metabox_nonce');

            // Owner
            echo '<label style="width:25%;display:block;float:left;">'.__('Owner', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-owner" id="partner-attribut-owner" value="'.$partner_attribut_owner.'" style="width:70%;" /><br />';
            
            // Adresse
            echo '<label style="width:25%;display:block;float:left;">'.__('Address', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-address" id="partner-attribut-address" value="'.$partner_attribut_address.'" style="width:70%;" /><br />';
            
            // Zip Code
            echo '<label style="width:25%;display:block;float:left;">'.__('Zip Code', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-zip-code" id="partner-attribut-zip-code" value="'.$partner_attribut_zip_code.'" style="width:70%;" /><br />';
            
            // City
            echo '<label style="width:25%;display:block;float:left;">'.__('City', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-city" id="partner-attribut-city" value="'.$partner_attribut_city.'" style="width:70%;" /><br />';

            // Telephone
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-phone" id="partner-attribut-phone" value="'.$partner_attribut_phone.'" style="width:70%;" /><br />';

            // Telephone 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="partner-attribut-phone2" id="partner-attribut-phone2" value="'.$partner_attribut_phone2.'" style="width:70%;" /><br />';

            // Telephone 3
            echo '<label style="width:25%;display:block;float:left;">'.__('Phone', $ls_manager->ls_manager_domain).' 3</label>';
            echo '<input type="text" name="partner-attribut-phone3" id="partner-attribut-phone3" value="'.$partner_attribut_phone3.'" style="width:70%;" /><br />';

            // Fax
            echo '<label style="width:25%;display:block;float:left;">'.__('Fax', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-fax" id="partner-attribut-fax" value="'.$partner_attribut_fax.'" style="width:70%;" /><br />';

            // Email
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-email" id="partner-attribut-email" value="'.$partner_attribut_email.'" style="width:70%;" /><br />';

            // Email 2
            echo '<label style="width:25%;display:block;float:left;">'.__('Email', $ls_manager->ls_manager_domain).' 2</label>';
            echo '<input type="text" name="partner-attribut-email2" id="partner-attribut-email2" value="'.$partner_attribut_email2.'" style="width:70%;" /><br />';

            // Website
            echo '<label style="width:25%;display:block;float:left;">'.__('Website', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-website" id="partner-attribut-website" value="'.$partner_attribut_website.'" style="width:70%;" /><br />';
            
            // Latitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Latitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-latitude" id="partner-attribut-latitude" value="'.$partner_attribut_latitude.'" style="width:70%;" /><br />';

            // Longitude
            echo '<label style="width:25%;display:block;float:left;">'.__('Longitude', $ls_manager->ls_manager_domain).'</label>';
            echo '<input type="text" name="partner-attribut-longitude" id="partner-attribut-longitude" value="'.$partner_attribut_longitude.'" style="width:70%;" /><br />';
            echo '<em><a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">Cliquez ici</a> pour obtenir obtenir les coordonnées</em><br />';
        }
        
        /**
         * Save PostData : Partner Attributs
         * @param type $post_id
         */
        public function partner_attributs_save_postdata($post_id){
            
            // Check Nonce
            if (empty($_POST) || !check_admin_referer('partner_attributs_metabox_submit', 'partner_attributs_metabox_nonce'))
                return $post_id;

            // Check Autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            
            // Update Partner Attribut : Owner
            $partner_attribut_owner = sanitize_text_field($_POST['partner-attribut-owner']);
            if (!empty($partner_attribut_owner)) update_post_meta ($post_id, 'partner-attribut-owner', $partner_attribut_owner);
            else update_post_meta ($post_id, 'partner-attribut-owner', '');
            
            // Update Partner Attribut : Address
            $partner_attribut_address = sanitize_text_field($_POST['partner-attribut-address']);
            if (!empty($partner_attribut_address)) update_post_meta ($post_id, 'partner-attribut-address', $partner_attribut_address);
            else update_post_meta ($post_id, 'partner-attribut-address', '');
            
            // Update Partner Attribut : Zip Code
            $partner_attribut_zip_code = sanitize_text_field($_POST['partner-attribut-zip-code']);
            if (!empty($partner_attribut_zip_code)) update_post_meta ($post_id, 'partner-attribut-zip-code', $partner_attribut_zip_code);
            else update_post_meta ($post_id, 'partner-attribut-zip-code', '');
            
            // Update Partner Attribut : City
            $partner_attribut_city = sanitize_text_field($_POST['partner-attribut-city']);
            if (!empty($partner_attribut_city)) update_post_meta ($post_id, 'partner-attribut-city', $partner_attribut_city);
            else update_post_meta ($post_id, 'partner-attribut-city', '');
            
            // Update Partner Attribut : Phone
            $partner_attribut_phone = sanitize_text_field($_POST['partner-attribut-phone']);
            if (!empty($partner_attribut_phone)) update_post_meta ($post_id, 'partner-attribut-phone', $partner_attribut_phone);
            else update_post_meta ($post_id, 'partner-attribut-phone', '');
            
            // Update Partner Attribut : Phone 2
            $partner_attribut_phone2 = sanitize_text_field($_POST['partner-attribut-phone2']);
            if (!empty($partner_attribut_phone2)) update_post_meta ($post_id, 'partner-attribut-phone2', $partner_attribut_phone2);
            else update_post_meta ($post_id, 'partner-attribut-phone2', '');
            
            // Update Partner Attribut : Phone 3
            $partner_attribut_phone3 = sanitize_text_field($_POST['partner-attribut-phone3']);
            if (!empty($partner_attribut_phone3)) update_post_meta ($post_id, 'partner-attribut-phone3', $partner_attribut_phone3);
            else update_post_meta ($post_id, 'partner-attribut-phone3', '');
            
            // Update Partner Attribut : Fax
            $partner_attribut_fax = sanitize_text_field($_POST['partner-attribut-fax']);
            if (!empty($partner_attribut_fax)) update_post_meta ($post_id, 'partner-attribut-fax', $partner_attribut_fax);
            else update_post_meta ($post_id, 'partner-attribut-fax', '');
            
            // Update Partner Attribut : Email
            $partner_attribut_email = sanitize_text_field($_POST['partner-attribut-email']);
            if (!empty($partner_attribut_email)) update_post_meta ($post_id, 'partner-attribut-email', $partner_attribut_email);
            else update_post_meta ($post_id, 'partner-attribut-email', '');
            
            // Update Partner Attribut : Email 2
            $partner_attribut_email2 = sanitize_text_field($_POST['partner-attribut-email2']);
            if (!empty($partner_attribut_email2)) update_post_meta ($post_id, 'partner-attribut-email2', $partner_attribut_email2);
            else update_post_meta ($post_id, 'partner-attribut-email2', '');
            
            // Update Partner Attribut : Website
            $partner_attribut_website = sanitize_text_field($_POST['partner-attribut-website']);
            if (!empty($partner_attribut_website)) update_post_meta ($post_id, 'partner-attribut-website', $partner_attribut_website);
            else update_post_meta ($post_id, 'partner-attribut-website', '');
            
            // Update Partner Attribut : Latitude
            $partner_attribut_latitude = sanitize_text_field($_POST['partner-attribut-latitude']);
            if (!empty($partner_attribut_latitude)) update_post_meta ($post_id, 'partner-attribut-latitude', $partner_attribut_latitude);
            else update_post_meta ($post_id, 'partner-attribut-latitude', '');
            
            // Update Partner Attribut : Longitude
            $partner_attribut_longitude = sanitize_text_field($_POST['partner-attribut-longitude']);
            if (!empty($partner_attribut_longitude)) update_post_meta ($post_id, 'partner-attribut-longitude', $partner_attribut_longitude);
            else update_post_meta ($post_id, 'partner-attribut-longitude', '');
        }
    
        /**
        * Partner Add Columns
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
        * Partner Custom Column Render
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
                    $partner_attribut_owner        = get_post_meta($post_id, 'partner-attribut-owner', true);
                    $partner_attribut_address      = get_post_meta($post_id, 'partner-attribut-address', true);
                    $partner_attribut_zip_code     = get_post_meta($post_id, 'partner-attribut-zip-code', true);
                    $partner_attribut_city         = get_post_meta($post_id, 'partner-attribut-city', true);
                    $partner_attribut_phone        = get_post_meta($post_id, 'partner-attribut-phone', true);
                    $partner_attribut_email        = get_post_meta($post_id, 'partner-attribut-email', true);
                    // Display Partenaire Summary
                    if (!empty($partner_attribut_owner))
                        echo $partner_attribut_owner . '<br />';
                    if (!empty($partner_attribut_address) && !empty($partner_attribut_zip_code) && !empty($partner_attribut_city))
                        echo $partner_attribut_address . '<br />' . $partner_attribut_zip_code . ' ' . $partner_attribut_city . '<br />';
                    if (!empty($partner_attribut_phone))
                        echo $partner_attribut_phone . '<br />';
                    if (!empty($partner_attribut_email))
                        echo $partner_attribut_email . '<br />';
                break;
            }
        }
    }
}

