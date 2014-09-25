<?php

if (!class_exists('LS_Manager_Invoice')) {
    
    /**
     * LS_Manager_Quote - Object Class 
     */
    class LS_Manager_Invoice{
        
        /** 
         * Construct the plugin object 
         */ 
        public function __construct() {
            // Add Render & Save Hooks
            add_action( 'admin_init', array(&$this,'admin_invoice_init') );      
            
            // Partner Custom Style
            add_action('admin_head', array(&$this,'invoice_admin_head'));
        }
        
        /**
         * Invoice Hook Admin Init 
         */
        public function admin_invoice_init(){
                        
            // Invoice Builder Metabox + Save Data
            add_meta_box('invoice-builder-box', 'Détails de la facture', array(&$this,'invoice_builder_render'), 'invoice', 'normal', 'high');
            add_action('save_post', array(&$this,'invoice_builder_save_postdata'));
            // Ajax Methods 
            add_action('wp_ajax_invoice_builder_add_item', array(&$this,'invoice_builder_add_item'));// Invoice Builder Add Item
            add_action('wp_ajax_invoice_builder_remove_item', array(&$this,'invoice_builder_remove_item'));// Invoice Builder Delete Item
        }
        
        /**
         * Invoice Admin Styles 
         */
        public function invoice_admin_head(){
            echo '<style type="text/css">';
            echo 'fieldset.invoice-builder{margin:0 auto 15px auto;padding:10px; border:1px dotted #BBB;}';
            echo 'fieldset.invoice-builder h4 {float:left;width:25%;diplay:inline-block;margin-top:0;}';
            echo '</style>';
        }
        
        /**
         *
         * @global type $ls_manager
         * @global type $post 
         */
        public function invoice_builder_render(){
            global $ls_manager, $post;
            
            // Use nonce for verification            
            echo '<input type="hidden" name="invoice_builder_metabox_nonce" value="'. wp_create_nonce('invoice_builder_metabox'). '" />';
                    
            // Enterprise
            echo '<fieldset class="invoice-builder">';
                echo '<h4>Expéditeur :</h4>';
                // Choose An Enterprise
                $current_enterprise_id = get_post_meta($post->ID, 'invoice-enterprise-id', true);
                $args = array('post_type' => 'enterprise', 'posts_per_page' => '-1' , 'order' => 'ASC', 'orderby' => 'title');
                $enterprise_list = get_posts($args);
                echo '<select id="invoice-enterprise-id" name="invoice-enterprise-id" style="width:70%">';
                echo '  <option value="0"> --- </option>';
                if ($enterprise_list)
                    foreach($enterprise_list as $enterprise_item)
                        echo '<option value="'.$enterprise_item->ID.'" '.(isset($current_enterprise_id) && $current_enterprise_id == $enterprise_item->ID ? 'selected="selected"' : '').'>'.$enterprise_item->post_title.'</option>';
                echo '</select>';
            echo '</fieldset>';
            
            // Destinataire
            $current_dest_id = get_post_meta($post->ID, 'invoice-dest-id', true);
            echo '<fieldset class="invoice-builder">';
                echo '<h4>Destinataire :</h4>';
                $a_list_post_type = array('partner' => 'Partners', 'prospect' => 'Prospect', 'customer' => 'Customer');
                echo '<select id="invoice-dest-id" name="invoice-dest-id" style="width:70%" >';
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
            
            echo '<fieldset class="invoice-builder">';
                echo '<h4>Objet de la facture :</h4>';
                // invoice Main Title
                $invoice_main_title = get_post_meta($post->ID, 'invoice-main-title', true);
                echo '<input type="text" id="invoice-main-title" name="invoice-main-title" style="width:70%" value="'.$invoice_main_title.'" /><br />';
            echo '</fieldset>';
            
            // Get Post Meta invoice Items
            $invoice_items = get_post_meta($post->ID, 'invoice-items'); // Unserialize
            
            // Display Item Fields
            echo '<fieldset class="invoice-builder">';
                // invoice Table Results
                echo '<fieldset class="invoice-builder">';
                    echo '<h4>Eléments existants :</h4><br class="clear" />';
                    echo '<table id="invoice-item-table" class="widefat">';
                    echo ' <thead>';
                    echo '    <tr>';
                    echo '        <th style="width:30%">Titre</th>';
                    echo '        <th style="width:30%">Description</th>';       
                    echo '        <th style="width:10%">Jours</th>';
                    echo '        <th style="width:10%">Prix unitaire</th>';
                    echo '        <th style="width:10%;text-align:right;">Total</th>';
                    echo '        <th style="width:10%;text-align:right;">Actions</th>';
                    echo '    </tr>';
                    echo ' </thead>';

                    // Add Stored Items In The Table
                    echo '<tbody>';
                    if (empty($invoice_items)){
                        echo '  <tr class="line">';
                        echo '    <td colspan="6" align="center">No Data</td>';
                        echo '  </tr>';
                    }else{
                        $count = 0;
                        $total_amount = 0;
                        
                        foreach($invoice_items[0] as $invoice_item){
                            echo '  <tr class="line">';
                            echo '    <td>'.$invoice_item[0].'</td>';
                            echo '    <td>'.$invoice_item[1].'</td>';
                            echo '    <td>'.$invoice_item[2].'</td>';
                            echo '    <td style="text-align:right;">'.$invoice_item[3].' €</td>';
                            echo '    <td style="text-align:right;">'.((float)$invoice_item[2]*(float)$invoice_item[3]).' €</td>';
                            $nonce = wp_create_nonce('invoice_builder_remove_item');
                            $link = admin_url('admin-ajax.php?action=invoice_builder_remove_item&post_id='.$post->ID.'&nonce='.$nonce);
                            echo '  <td style="text-align:right;"><a class="remove-invoice-item" href="'.$link.'" data-line="'.$count.'" data-post-id="'.$post->ID.'" data-nonce="'.$nonce.'" >
                                    <img src="'.  plugins_url('/ls-manager/img/icon-delete.png').'" title="Supprimer la ligne" alt="Supprimer la ligne" />
                                    </a><span class="spinner" style="display:none;float:left;"></span></td>';
                            echo '  </tr>';
                            $count ++;
                            $total_amount += ((float)$invoice_item[2]*(float)$invoice_item[3]);
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
                 echo '<fieldset class="invoice-builder">';
                    echo '<h4>Ajouter un élément :</h4><br class="clear" />';
                    // invoice Item Title
                    echo '<label for="invoice-item-title" style="width:25%;display:block;float:left;margin-left:5%">Titre : </label>';
                    echo '<input type="text" id="invoice-item-title" name="invoice-item-title" style="width:60%" /><br />';
                    // invoice Item Description
                    echo '<label for="invoice-item-description" style="width:25%;display:block;float:left;margin-left:5%">Description : </label>';
                    echo '<textarea id="invoice-item-description" name="invoice-item-description" style="width:60%"></textarea><br />';
                    // invoice Item Quantity
                    echo '<label for="invoice-item-quantity" style="width:25%;display:block;float:left;margin-left:5%">Quantité : </label>';
                    echo '<input type="text" id="invoice-item-quantity" name="invoice-item-quantity" style="width:60%" /><br />';
                    // invoice Item Unit Price
                    echo '<label for="invoice-item-unit-price" style="width:25%;display:block;float:left;margin-left:5%">Prix unitaire : </label>';
                    echo '<input type="text" id="invoice-item-unit-price" name="invoice-item-unit-price" style="width:60%" /><br />';
                    // Spinner
                    echo '<span class="spinner" style="float:left;"></span>';
                    // Ajax Call Button
                    $nonce = wp_create_nonce('invoice_builder_add_item');
                    $link  = admin_url('admin-ajax.php?action=invoice_builder_add_item&nonce='.$nonce);
                    echo '<input type="button" class="button-primary" id="add-invoice-item" href="'.$link.'" data-post-id="'.$post->ID.'" data-nonce="'.$nonce.'" value="Ajouter un élément à la facture" />';
                echo '</fieldset>';

                
            echo '</fieldset>';
            
            // Action Button 
            if (isset($post->ID) && is_numeric($post->ID)) {
                // Spinner
                echo '<span class="spinner" style="float:left;"></span>';
                // Button
                echo '<input type="button" id="create-pdf-invoice" class="button-primary" value="Generer la facture" />';
            }
            if (file_exists(LS_MANAGER_PLUGIN_DIR_PATH.'pdf/invoice-'.$post->ID.'.pdf')){
                echo '<a class="button-secondary" href="'.LS_MANAGER_PLUGIN_DIR_URL.'pdf/invoice-'.$post->ID.'.pdf" target="_blank">Voir le devis</a>';
                echo '<a class="button-secondary" href="#"><img src="'.  plugins_url('/ls-manager/img/icon-delete.png').'" title="Supprimer le devis" alt="Supprimer le devis" /></a>';
            }
        }
        
        /**
         * 
         * @param type $post_id 
         */
        public function invoice_builder_save_postdata($post_id){
            // Check Nonce
            if (!wp_verify_nonce($_POST['invoice_builder_metabox_nonce'], 'invoice_builder_metabox'))
                return $post_id;
            
            // Update invoice Attribut : Enterprise Id
            $invoice_enterprise_id = (int)$_POST['invoice-enterprise-id'];
            if (!empty($invoice_enterprise_id)) update_post_meta ($post_id, 'invoice-enterprise-id', $invoice_enterprise_id);
            else update_post_meta ($post_id, 'invoice-enterprise-id', '');
            
            // Update invoice Attribut : Dest Id
            $invoice_dest_id = (int)$_POST['invoice-dest-id'];
            if (!empty($invoice_dest_id)) update_post_meta ($post_id, 'invoice-dest-id', $invoice_dest_id);
            else update_post_meta ($post_id, 'invoice-dest-id', '');
            
            // Update invoice Attribut : Main Title
            $invoice_main_title = (string)$_POST['invoice-main-title'];
            if (!empty($invoice_main_title)) update_post_meta ($post_id, 'invoice-main-title', $invoice_main_title);
            else update_post_meta ($post_id, 'invoice-main-title', '');
        }

    
        /**
         * Ajax invoice Builder : Add Item
         */
        public function invoice_builder_add_item(){
            // Verify Nonce
            if ( !wp_verify_nonce( $_REQUEST['nonce'], 'invoice_builder_add_item')) exit();
            
            // Get Posted Values
            $post_id                = $_REQUEST['post_id'];
            $invoice_item_title       = $_REQUEST['invoice_item_title'];
            $invoice_item_description = $_REQUEST['invoice_item_description'];
            $invoice_item_quantity    = $_REQUEST['invoice_item_quantity'];
            $invoice_item_unit_price  = $_REQUEST['invoice_item_unit_price'];
            
            // Optimize Title & Description
            $invoice_item_title = str_replace("\'", "'",  $invoice_item_title);
            $invoice_item_description = str_replace("\'", "'",  $invoice_item_description);
            
            // Get Existing Collections Of Items
            $invoice_items = get_post_meta($post_id, 'invoice-items', true);
            // Add New Item To Collection
            $invoice_items[] = array($invoice_item_title, $invoice_item_description, $invoice_item_quantity, $invoice_item_unit_price);
            // Update Items Collection
            update_post_meta($post_id,'invoice-items', $invoice_items);
            
            $invoice_builder_row_render = '';
            $invoice_builder_footer_render = '';
            $count = 0;
            $total_amount = 0;
            
            // Build HTML Render
            foreach($invoice_items as $item){
                $invoice_builder_row_render .= '<tr class="line">';
                $invoice_builder_row_render .= '  <td>'.$item[0].' </td>';
                $invoice_builder_row_render .= '  <td>'.$item[1].' </td>';
                $invoice_builder_row_render .= '  <td>'.$item[2].' </td>';
                $invoice_builder_row_render .= '  <td style="text-align:right;">'.$item[3].' €</td>';
                $invoice_builder_row_render .= '  <td style="text-align:right;">'.((float)$item[2]*(float)$item[3]).' €</td>';
                $nonce = wp_create_nonce('invoice_builder_remove_item');
                $link = admin_url('admin-ajax.php?action=invoice_builder_add_item&post_id='.$post_id.'&nonce='.$nonce);
                $invoice_builder_row_render .= '  <td style="text-align:right;"><a class="remove-invoice-item" href="'.$link.'" data-line="'.$count.'" data-post-id="'.$post_id.'" data-nonce="'.$nonce.'" >
                        <img src="'.  plugins_url('/ls-manager/img/icon-delete.png').'" title="Supprimer la ligne" alt="Supprimer la ligne" />
                        </a><span class="spinner" style="display:none;float:left;"></span></td>';
                $invoice_builder_row_render .= '</tr>';
                $count++;
                $total_amount += ((float)$item[2]*(float)$item[3]);
            }
            $invoice_builder_footer_render .= '<tr class="line-last">';
            $invoice_builder_footer_render .= '  <th colspan="3" style="text-align:right;"><strong>Total :</strong></th>';
            $invoice_builder_footer_render .= '  <th colspan="2" style="text-align:right;">'.$total_amount.' €</th>';
            $invoice_builder_footer_render .= '  <th>&nbsp;</th>';
            $invoice_builder_footer_render .= '</tr>';
            $response = json_encode( array( 'success' => true, 'rows' => $invoice_builder_row_render, 'footer' => $invoice_builder_footer_render ) );
            // Output Response & Exit !
            header( "Content-Type: application/json" );
            echo $response;
            exit();
        }
        
        /**
         * Ajax invoice Builder : Remove Item
         */
        public function invoice_builder_remove_item(){
            
            // Verify Nonce
            if ( !wp_verify_nonce( $_REQUEST['nonce'], 'invoice_builder_remove_item')) exit();
            
            // Get Posted Values
            $post_id = $_REQUEST["post_id"];
            $line    = $_REQUEST['line'];
            
            // Get Existing Collections Of Items
            $invoice_items = get_post_meta($post_id, 'invoice-items', true);
            
            if (is_array($invoice_items) && sizeof($invoice_items) <= 1 ){
                delete_post_meta($post_id, 'invoice-items');
                $invoice_items = '';
            }elseif (is_array($invoice_items) && sizeof($invoice_items) > 1){
                unset($invoice_items[$line]);
                $invoice_items = array_values($invoice_items); // Re-order array 
                update_post_meta($post_id,'invoice-items', $invoice_items);
            }
            
            // Get Existing Collections Of Items
            $invoice_items = get_post_meta($post_id, 'invoice-items', true);
            
            $invoice_builder_row_render = '';
            $invoice_builder_footer_render = '';
            
            if (!empty($invoice_items) && sizeof($invoice_items) > 0){
                $count = 0;
                $total_amount = 0;
                foreach($invoice_items as $item){
                    $invoice_builder_row_render .= '<tr class="line">';
                    $invoice_builder_row_render .= '  <td>'.$item[0].' </td>';
                    $invoice_builder_row_render .= '  <td>'.$item[1].' </td>';
                    $invoice_builder_row_render .= '  <td>'.$item[2].' </td>';
                    $invoice_builder_row_render .= '  <td style="text-align:right;">'.$item[3].' €</td>';
                    $invoice_builder_row_render .= '  <td style="text-align:right;">'.((float)$item[2]*(float)$item[3]).' €</td>';
                    $nonce = wp_create_nonce('invoice_builder_remove_item');
                    $link = admin_url('admin-ajax.php?action=invoice_builder_add_item&post_id='.$post_id.'&nonce='.$nonce);
                    $invoice_builder_row_render .= '  <td style="text-align:right;"><a class="remove-invoice-item" href="'.$link.'" data-line="'.$count.'" data-post-id="'.$post_id.'" data-nonce="'.$nonce.'" >
                            <img src="'.  plugins_url('/ls-manager/img/icon-delete.png').'" title="Supprimer la ligne" alt="Supprimer la ligne" />
                            </a><span class="spinner" style="display:none;float:left;"></span></td>';
                    $invoice_builder_row_render .= '</tr>';
                    $count++;
                    $total_amount += ((float)$item[2]*(float)$item[3]);
                }
                $invoice_builder_footer_render .= '<tr class="line-last">';
                $invoice_builder_footer_render .= '  <th colspan="3" style="text-align:right;"><strong>Total :</strong></th>';
                $invoice_builder_footer_render .= '  <th colspan="2" style="text-align:right;">'.$total_amount.' €</th>';
                $invoice_builder_footer_render .= '  <th>&nbsp;</th>';
                $invoice_builder_footer_render .= '</tr>';
            }else{
                $invoice_builder_row_render = '<td clospan="6">No data</td>';
            }
            // Json Encode Response
            $response = json_encode( array( 'success' => true, 'rows' => $invoice_builder_row_render, 'footer' => $invoice_builder_footer_render ) );
            // Output Response & Exit !
            header( "Content-Type: application/json" );
            echo $response;
            exit();
        }
    }
}
?>
