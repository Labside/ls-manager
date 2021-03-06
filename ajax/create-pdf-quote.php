<?php
require('../../../../wp-load.php');

if (isset($_POST['post_id'])){
    
    // Get Posted Parameters
    $post_id       = (int)$_POST['post_id'];
    $enterprise_id = (int)$_POST['enterprise_id'];
    $dest_id       = (int)$_POST['dest_id'];
    $main_title    = (string)$_POST['main_title'];
    
    // Build Array Of Informations
    $data = array();
    $data['enterprise_id'] = $enterprise_id;
    $data['post_id']       = $post_id;
    $data['dest_id']       = $dest_id;
    $data['main_title']    = $main_title;

    // Create a new PDF document.
    $pdf = new LS_Manager_PDF( $data, 'P', 'pt', 'LETTER' );

    // Generate the quote.
    $pdf->CreateQuote();
    
    // Output the PDF document.
    $pdf->Output( LS_MANAGER_PLUGIN_DIR_PATH.'pdf/quote-'.$post_id.'.pdf', 'FD' );
}

?>
