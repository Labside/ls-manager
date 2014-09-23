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
            
            // Partner Custom Style
            add_action('admin_head', array(&$this,'quote_admin_head'));
        }
        
        /**
         * Quote Hook Admin Init 
         */
        public function admin_quote_init(){
                        
            // Quote Builder Metabox + Save Data
            add_meta_box('quote-builder-box', 'Détails du devis', array(&$this,'quote_builder_render'), 'quote', 'normal', 'high');
            add_action('save_post', array(&$this,'quote_builder_save_postdata'));
            // Ajax Methods 
            add_action('wp_ajax_quote_builder_add_item', array(&$this,'quote_builder_add_item'));// Quote Builder Add Item
            add_action('wp_ajax_quote_builder_remove_item', array(&$this,'quote_builder_remove_item'));// Quote Builder Delete Item
            // Quote To Invoice Metabox + Ajax Call
            add_meta_box('quote-to-invoice-box', 'Facturation', array(&$this,'quote_to_invoice_render'), 'quote', 'side', 'low');
            add_action('wp_ajax_quote_to_invoice_process', array(&$this,'quote_to_invoice_process'));
        }
        
        /**
         * Quote Admin Styles 
         */
        public function quote_admin_head(){
            echo '<style type="text/css">';
            echo 'fieldset.quote-builder{margin:0 auto 15px auto;padding:10px; border:1px dotted #BBB;}';
            echo 'fieldset.quote-builder h4 {float:left;width:25%;diplay:inline-block;margin-top:0;}';
            echo 'tr.line-last{border-top:1px solid #444;}';
            echo '</style>';
        }
        
        /**
         *
         * @global type $ls_manager
         * @global type $post 
         */
        public function quote_builder_render(){
            global $ls_manager, $post;
            
            // Use nonce for verification            
            echo '<input type="hidden" name="quote_builder_metabox_nonce" value="'. wp_create_nonce('quote_builder_metabox'). '" />';
                    
            // Enterprise
            echo '<fieldset class="quote-builder">';
                echo '<h4>Expéditeur :</h4>';
                // Choose An Enterprise
                $current_enterprise_id = get_post_meta($post->ID, 'quote-enterprise-id', true);
                $args = array('post_type' => 'enterprise', 'posts_per_page' => '-1' , 'order' => 'ASC', 'orderby' => 'title');
                $enterprise_list = get_posts($args);
                echo '<select id="quote-enterprise-id" name="quote-enterprise-id" style="width:70%">';
                echo '  <option value="0"> --- </option>';
                if ($enterprise_list)
                    foreach($enterprise_list as $enterprise_item)
                        echo '<option value="'.$enterprise_item->ID.'" '.(isset($current_enterprise_id) && $current_enterprise_id == $enterprise_item->ID ? 'selected="selected"' : '').'>'.$enterprise_item->post_title.'</option>';
                echo '</select>';
            echo '</fieldset>';
            
            // Destinataire
            $current_dest_id = get_post_meta($post->ID, 'quote-dest-id', true);
            echo '<fieldset class="quote-builder">';
                echo '<h4>Destinataire :</h4>';
                $a_list_post_type = array('partner' => 'Partners', 'prospect' => 'Prospect', 'customer' => 'Customer');
                echo '<select id="quote-dest-id" name="quote-dest-id" style="width:70%" >';
                echo '  <option value="0"> --- </option>';
                foreach($a_list_post_type as $key => $value){
                    echo '  <option value="" style="font-weight:bold;font-style:italic;">'.$value.'</option>';
                    // Get Posts Group By Post Type
                    $args = array('post_type' => $key, 'posts_per_page' => '-1' , 'order' => 'ASC', 'orderby' => 'title');
                    $post_list = get_posts($args);
                    if ($post_list)
                        foreach($post_list as $post_item)
                            echo '  <option value="'.$post_item->ID.'" '.(isset($current_dest_id) && $current_dest_id == $post_item->ID ? 'selected="selected"' : '').'>&nbsp;&nbsp;&nbsp;&nbsp;'.$post_item->post_title.'</option>';
                }
                echo '</select>';
            echo '</fieldset>';
            
            echo '<fieldset class="quote-builder">';
                echo '<h4>Objet du devis :</h4>';
                // Quote Main Title
                $quote_main_title = get_post_meta($post->ID, 'quote-main-title', true);
                echo '<input type="text" id="quote-main-title" name="quote-main-title" style="width:70%" value="'.$quote_main_title.'" /><br />';
            echo '</fieldset>';
            
            // Get Post Meta Quote Items
            $quote_items = get_post_meta($post->ID, 'quote-items'); // Unserialize
            
            // Display Item Fields
            echo '<fieldset class="quote-builder">';
                // Quote Table Results
                echo '<fieldset class="quote-builder">';
                    echo '<h4>Eléments existants :</h4><br class="clear" />';
                    echo '<table id="quote-item-table" class="widefat">';
                    echo ' <thead>';
                    echo '    <tr>';
                    echo '        <th style="width:30%">Titre</th>';
                    echo '        <th style="width:30%">Description</th>';       
                    echo '        <th style="width:10%">Jours</th>';
                    echo '        <th style="width:10%">Prix unitaire</th>';
                    echo '        <th style="width:10%">Total</th>';
                    echo '        <th style="width:10%">Actions</th>';
                    echo '    </tr>';
                    echo ' </thead>';

                    // Add Stored Items In The Table
                    echo '<tbody>';
                    if (empty($quote_items)){
                        echo '  <tr class="line">';
                        echo '    <td colspan="6" align="center">No Data</td>';
                        echo '  </tr>';
                    }else{
                        $count = 0;
                        $total_amount = 0;
                        
                        foreach($quote_items[0] as $quote_item){
                            echo '  <tr class="line">';
                            echo '    <td>'.$quote_item[0].'</td>';
                            echo '    <td>'.$quote_item[1].'</td>';
                            echo '    <td>'.$quote_item[2].'</td>';
                            echo '    <td style="text-align:right;">'.$quote_item[3].' €</td>';
                            echo '    <td style="text-align:right;">'.((float)$quote_item[2]*(float)$quote_item[3]).' €</td>';
                            $nonce = wp_create_nonce('quote_builder_remove_item');
                            $link = admin_url('admin-ajax.php?action=quote_builder_remove_item&post_id='.$post->ID.'&nonce='.$nonce);
                            echo '  <td><a class="remove-quote-item" href="'.$link.'" data-line="'.$count.'" data-post-id="'.$post->ID.'" data-nonce="'.$nonce.'" >
                                    <img src="'.  plugins_url('/ls-manager/img/icon-delete.png').'" title="Supprimer la ligne" alt="Supprimer la ligne" />
                                    </a><span class="spinner" style="display:none;float:left;"></span></td>';
                            echo '  </tr>';
                            $count ++;
                            $total_amount += ((float)$quote_item[2]*(float)$quote_item[3]);
                        }
                    }
                    echo ' </tbody>';
                    echo ' <tfoot>';
                    echo '<tr class="line-last">';
                        echo '  <th colspan="3" style="text-align:right;"><strong>Total :</strong></th>';
                        echo '  <th colspan="2" style="text-align:right;">'.$total_amount.' €</th>';
                        echo '  <th>&nbsp;</th>';
                        echo '</tr>';
                    echo ' </tfoot>';
                 echo '</table>';
                
                 echo '<br />';
                 echo '<fieldset class="quote-builder">';
                    echo '<h4>Ajouter un élément :</h4><br class="clear" />';
                    // Quote Item Title
                    echo '<label for="quote-item-title" style="width:25%;display:block;float:left;margin-left:5%">Titre : </label>';
                    echo '<input type="text" id="quote-item-title" name="quote-item-title" style="width:60%" /><br />';
                    // Quote Item Description
                    echo '<label for="quote-item-description" style="width:25%;display:block;float:left;margin-left:5%">Description : </label>';
                    echo '<textarea id="quote-item-description" name="quote-item-description" style="width:60%"></textarea><br />';
                    // Quote Item Quantity
                    echo '<label for="quote-item-quantity" style="width:25%;display:block;float:left;margin-left:5%">Quantité : </label>';
                    echo '<input type="text" id="quote-item-quantity" name="quote-item-quantity" style="width:60%" /><br />';
                    // Quote Item Unit Price
                    echo '<label for="quote-item-unit-price" style="width:25%;display:block;float:left;margin-left:5%">Prix unitaire : </label>';
                    echo '<input type="text" id="quote-item-unit-price" name="quote-item-unit-price" style="width:60%" /><br />';
                    // Spinner
                    echo '<span class="spinner" style="float:left;"></span>';
                    // Ajax Call Button
                    $nonce = wp_create_nonce('quote_builder_add_item');
                    $link  = admin_url('admin-ajax.php?action=quote_builder_add_item&nonce='.$nonce);
                    echo '<input type="button" class="button-primary" id="add-quote-item" href="'.$link.'" data-post-id="'.$post->ID.'" data-nonce="'.$nonce.'" value="Ajouter un élément au devis" />';
                echo '</fieldset>';

                
            echo '</fieldset>';
            
            // Action Button 
            if (isset($post->ID) && is_numeric($post->ID)) {
                // Spinner
                echo '<span class="spinner" style="float:left;"></span>';
                // Button
                echo '<input type="button" id="create-pdf-quote" class="button-primary" value="Generer le devis" />';
            }
            if (file_exists(LS_MANAGER_PLUGIN_DIR_PATH.'pdf/quote-'.$post->ID.'.pdf')){
                echo '<a class="button-secondary" href="'.LS_MANAGER_PLUGIN_DIR_URL.'pdf/quote-'.$post->ID.'.pdf" target="_blank">Voir le devis</a>';
                echo '<a class="button-secondary" href="#"><img src="'.  plugins_url('/ls-manager/img/icon-delete.png').'" title="Supprimer le devis" alt="Supprimer le devis" /></a>';
            }
        }
        
        /**
         * 
         * @param type $post_id 
         */
        public function quote_builder_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['quote_builder_metabox_nonce'], 'quote_builder_metabox'))
                return $post_id;
            
            // Update Quote Attribut : Enterprise Id
            $quote_enterprise_id = (int)$_POST['quote-enterprise-id'];
            if (!empty($quote_enterprise_id)) update_post_meta ($post_id, 'quote-enterprise-id', $quote_enterprise_id);
            else update_post_meta ($post_id, 'quote-enterprise-id', '');
            
            // Update Quote Attribut : Dest Id
            $quote_dest_id = (int)$_POST['quote-dest-id'];
            if (!empty($quote_dest_id)) update_post_meta ($post_id, 'quote-dest-id', $quote_dest_id);
            else update_post_meta ($post_id, 'quote-dest-id', '');
            
            // Update Quote Attribut : Main Title
            $quote_main_title = (string)$_POST['quote-main-title'];
            if (!empty($quote_main_title)) update_post_meta ($post_id, 'quote-main-title', $quote_main_title);
            else update_post_meta ($post_id, 'quote-main-title', '');
        }
        
        /**
         * Ajax Quote Builder : Add Item
         */
        public function quote_builder_add_item(){
            // Verify Nonce
            if ( !wp_verify_nonce( $_REQUEST['nonce'], 'quote_builder_add_item')) exit();
            
            // Get Posted Values
            $post_id                = $_REQUEST['post_id'];
            $quote_item_title       = $_REQUEST['quote_item_title'];
            $quote_item_description = $_REQUEST['quote_item_description'];
            $quote_item_quantity    = $_REQUEST['quote_item_quantity'];
            $quote_item_unit_price  = $_REQUEST['quote_item_unit_price'];
            
            // Optimize Title & Description
            $quote_item_title = str_replace("\'", "'",  $quote_item_title);
            $quote_item_description = str_replace("\'", "'",  $quote_item_description);
            
            // Get Existing Collections Of Items
            $quote_items = get_post_meta($post_id, 'quote-items', true);
            // Add New Item To Collection
            $quote_items[] = array($quote_item_title, $quote_item_description, $quote_item_quantity, $quote_item_unit_price);
            // Update Items Collection
            update_post_meta($post_id,'quote-items', $quote_items);
            
            $quote_builder_row_render = '';
            $count = 0;
            $total_amount = 0;
            
            // Build HTML Render
            foreach($quote_items as $item){
                $quote_builder_row_render .= '<tr class="line">';
                $quote_builder_row_render .= '  <td>'.$item[0].' </td>';
                $quote_builder_row_render .= '  <td>'.$item[1].' </td>';
                $quote_builder_row_render .= '  <td>'.$item[2].' </td>';
                $quote_builder_row_render .= '  <td style="text-align:right;">'.$item[3].' €</td>';
                $quote_builder_row_render .= '  <td style="text-align:right;">'.((float)$item[2]*(float)$item[3]).' €</td>';
                $nonce = wp_create_nonce('quote_builder_remove_item');
                $link = admin_url('admin-ajax.php?action=quote_builder_add_item&post_id='.$post_id.'&nonce='.$nonce);
                $quote_builder_row_render .= '  <td><a class="remove-quote-item" href="'.$link.'" data-line="'.$count.'" data-post-id="'.$post_id.'" data-nonce="'.$nonce.'" >
                        <img src="'.  plugins_url('/ls-manager/img/icon-delete.png').'" title="Supprimer la ligne" alt="Supprimer la ligne" />
                        </a><span class="spinner" style="display:none;float:left;"></span></td>';
                $quote_builder_row_render .= '</tr>';
                $count++;
                $total_amount += ((float)$item[2]*(float)$item[3]);
            }
            $quote_builder_footer_render .= '<tr class="line-last">';
            $quote_builder_footer_render .= '  <th colspan="3" style="text-align:right;"><strong>Total :</strong></th>';
            $quote_builder_footer_render .= '  <th colspan="2" style="text-align:right;">'.$total_amount.' €</th>';
            $quote_builder_footer_render .= '  <th>&nbsp;</th>';
            $quote_builder_footer_render .= '</tr>';
            $response = json_encode( array( 'success' => true, 'rows' => $quote_builder_row_render, 'footer' => $quote_builder_footer_render ) );
            // Output Response & Exit !
            header( "Content-Type: application/json" );
            echo $response;
            exit();
        }
        
        /**
         * Ajax Quote Builder : Remove Item
         */
        public function quote_builder_remove_item(){
            
            // Verify Nonce
            if ( !wp_verify_nonce( $_REQUEST['nonce'], 'quote_builder_remove_item')) exit();
            
            // Get Posted Values
            $post_id = $_REQUEST["post_id"];
            $line    = $_REQUEST['line'];
            
            // Get Existing Collections Of Items
            $quote_items = get_post_meta($post_id, 'quote-items', true);
            
            if (is_array($quote_items) && sizeof($quote_items) <= 1 ){
                delete_post_meta($post_id, 'quote-items');
                $quote_items = '';
            }elseif (is_array($quote_items) && sizeof($quote_items) > 1){
                unset($quote_items[$line]);
                $quote_items = array_values($quote_items); // Re-order array 
                update_post_meta($post_id,'quote-items', $quote_items);
            }
            
            // Get Existing Collections Of Items
            $quote_items = get_post_meta($post_id, 'quote-items', true);
            
            $quote_builder_row_render = '';
            
            if (!empty($quote_items) && sizeof($quote_items) > 0){
                $count = 0;
                $total_amount = 0;
                foreach($quote_items as $item){
                    $quote_builder_row_render .= '<tr class="line">';
                    $quote_builder_row_render .= '  <td>'.$item[0].' </td>';
                    $quote_builder_row_render .= '  <td>'.$item[1].' </td>';
                    $quote_builder_row_render .= '  <td>'.$item[2].' </td>';
                    $quote_builder_row_render .= '  <td style="text-align:right;">'.$item[3].' €</td>';
                    $quote_builder_row_render .= '  <td style="text-align:right;">'.((float)$item[2]*(float)$item[3]).' €</td>';
                    $nonce = wp_create_nonce('quote_builder_remove_item');
                    $link = admin_url('admin-ajax.php?action=quote_builder_add_item&post_id='.$post_id.'&nonce='.$nonce);
                    $quote_builder_row_render .= '  <td><a class="remove-quote-item" href="'.$link.'" data-line="'.$count.'" data-post-id="'.$post_id.'" data-nonce="'.$nonce.'" >
                            <img src="'.  plugins_url('/ls-manager/img/icon-delete.png').'" title="Supprimer la ligne" alt="Supprimer la ligne" />
                            </a><span class="spinner" style="display:none;float:left;"></span></td>';
                    $quote_builder_row_render .= '</tr>';
                    $count++;
                    $total_amount += ((float)$item[2]*(float)$item[3]);
                }
                $quote_builder_footer_render .= '<tr class="line-last">';
                $quote_builder_footer_render .= '  <th colspan="3" style="text-align:right;"><strong>Total :</strong></th>';
                $quote_builder_footer_render .= '  <th colspan="2" style="text-align:right;">'.$total_amount.' €</th>';
                $quote_builder_footer_render .= '  <th>&nbsp;</th>';
                $quote_builder_footer_render .= '</tr>';
            }else{
                $quote_builder_row_render = '<td clospan="6">No data</td>';
            }
            // Json Encode Response
            $response = json_encode( array( 'success' => true, 'rows' => $quote_builder_row_render, 'footer' => $quote_builder_footer_render ) );
            // Output Response & Exit !
            header( "Content-Type: application/json" );
            echo $response;
            exit();
        }
    
        /**
         * Quote To Invoice : Render Metabox 
         */
        public function quote_to_invoice_render(){
            global $ls_manager, $post;
            
            // Spinner
            echo '<span class="spinner" style="float:left;"></span>';
            // Ajax Call Button
            $nonce = wp_create_nonce('quote_to_invoice');
            $link  = admin_url('admin-ajax.php?action=quote_to_invoice_process&nonce='.$nonce);
            echo '<input type="button" class="button-secondary" id="quote-to-invoice-button" href="'.$link.'" data-post-id="'.$post->ID.'" data-nonce="'.$nonce.'" value="Créer la facture" />';
        }
        
        /**
         * Quote To Invoice : Save Postdata
         */
        public function quote_to_invoice_process(){
            // Verify Nonce
            if ( !wp_verify_nonce( $_REQUEST['nonce'], 'quote_to_invoice')) exit();
            
            // Get Posted Values
            $post_id = $_REQUEST["post_id"];
            
            // Get Quote Meta Values
            $current_enterprise_id = get_post_meta($post_id, 'quote-enterprise-id', true);
            $current_dest_id       = get_post_meta($post_id, 'quote-dest-id', true);
            $quote_main_title      = get_post_meta($post_id, 'quote-main-title', true);
            $quote_items           = get_post_meta($post_id, 'quote-items', true); 
                
            // Create New Post Type Invoice Base On Main Title Field
            $args = array(
                'post_title'     => get_the_title($post_id),
                'post_status'    => 'draft',
                'post_type'      => 'invoice',
            );
            $invoice_post_id = wp_insert_post($args);

            if ($invoice_post_id != 0 ){
                // Create New Post Meta Values
                update_post_meta($invoice_post_id, 'invoice-enterprise-id', $current_enterprise_id);
                update_post_meta($invoice_post_id, 'invoice-dest-id', $current_dest_id);
                update_post_meta($invoice_post_id, 'invoice-main-title', $quote_main_title);
                update_post_meta($invoice_post_id, 'invoice-items', $quote_items);
            }
                                 
            // Json Encode Response
            $response = json_encode( array( 'success' => true) );
            // Output Response & Exit !
            header( "Content-Type: application/json" );
            echo $response;
            exit();
        }
    }
}
