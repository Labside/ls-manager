<?php
if (!class_exists('LS_Manager_PDF')){
    
    class LS_Manager_PDF extends TCPDF{
        
            private $data;
        
            function __construct( $data, $orientation, $unit, $format ) {
                parent::__construct( $orientation, $unit, $format, true, 'UTF-8', false );

                $this->data = $data;

                # Set the page margins
                $this->SetMargins( 50, 20, 50, true );
                $this->SetAutoPageBreak( true, 20 );

                # Set document meta-information
                $this->SetCreator( PDF_CREATOR );
                $this->SetAuthor( 'Labside' );

                //set image scale factor
                $this->setImageScale(PDF_IMAGE_SCALE_RATIO); 

                //set some language-dependent strings
                global $l;
                $this->setLanguageArray($l);
            }
            
            # Page header and footer code.
            public function Header() {
                    global $webcolor;
                    
                    // Get Enterprise Infos
                    $enterprise_id                    = $this->data['enterprise_id'];
                    $enterprise_property_owner        = get_post_meta($enterprise_id, 'property-owner', true);
                    $enterprise_property_job          = get_post_meta($enterprise_id, 'property-job', true);
                    $enterprise_property_address      = get_post_meta($enterprise_id, 'property-address', true);
                    $enterprise_property_zip_code     = get_post_meta($enterprise_id, 'property-zip-code', true);
                    $enterprise_property_city         = get_post_meta($enterprise_id, 'property-city', true);
                    $enterprise_property_country      = get_post_meta($enterprise_id, 'property-country', true);
                    $enterprise_property_phone        = get_post_meta($enterprise_id, 'property-phone', true);
                    $enterprise_property_email        = get_post_meta($enterprise_id, 'property-email', true);
                    $enterprise_property_website      = get_post_meta($enterprise_id, 'property-website', true);
                    $enterprise_property_legal_number = get_post_meta($enterprise_id, 'property-legal-number', true);
                    
                    $enterprise_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($enterprise_id),'medium');
                    if (!empty($enterprise_thumbnail))
                        $img_enterprise = $enterprise_thumbnail[0];
                    
                    // Get Dest Infos (Partner / Customer / Prospect)
                    $dest_id                      = $this->data['dest_id'];
                    $dest                       = get_post($dest_id);
                    $dest_property_owner        = get_post_meta($dest_id, 'property-owner', true);
                    $dest_property_address      = get_post_meta($dest_id, 'property-address', true);
                    $dest_property_zip_code     = get_post_meta($dest_id, 'property-zip-code', true);
                    $dest_property_city         = get_post_meta($dest_id, 'property-city', true);
                    $dest_property_country      = get_post_meta($dest_id, 'property-country', true);                    
                    
		    $this->Image($img_enterprise, 50, 10, 150, 75, '', $enterprise_property_website, 'T');
                    
                    // Todo : Use file_get_content + str_replace for tag sample
                    $html_header = file_get_contents(LS_MANAGER_PLUGIN_DIR_PATH . '/html/quote/header.html');
                    if ($html_header !== FALSE){
                        
                        // Enterprise Fields
                        $html_header = str_replace('[enterprise_property_owner]', $enterprise_property_owner, $html_header);
                        $html_header = str_replace('[enterprise_property_job]', $enterprise_property_job, $html_header);
                        $html_header = str_replace('[enterprise_property_address]', $enterprise_property_address, $html_header);
                        $html_header = str_replace('[enterprise_property_zip_code]', $enterprise_property_zip_code, $html_header);
                        $html_header = str_replace('[enterprise_property_city]', $enterprise_property_city, $html_header);
                        $html_header = str_replace('[enterprise_property_country]', $enterprise_property_country, $html_header);
                        $html_header = str_replace('[enterprise_property_phone]', $enterprise_property_phone, $html_header);
                        $html_header = str_replace('[enterprise_property_email]', $enterprise_property_email, $html_header);
                        $html_header = str_replace('[enterprise_property_legal_number]', $enterprise_property_legal_number, $html_header);
                        
                        // Destinataire Fields
                        $html_header = str_replace('[dest_property_enterprise]', $dest->post_title, $html_header);
                        $html_header = str_replace('[dest_property_owner]', $dest_property_owner, $html_header);
                        $html_header = str_replace('[dest_property_address]', $dest_property_address, $html_header);
                        $html_header = str_replace('[dest_property_zip_code]', $dest_property_zip_code, $html_header);
                        $html_header = str_replace('[dest_property_city]', $dest_property_city, $html_header);
                        $html_header = str_replace('[dest_property_country]', $dest_property_country, $html_header);
                        
                        // Current Date
                        $html_header = str_replace('[current_date]', date('d/m/Y'), $html_header);
                        
                    }else $html_header = 'Error'; 
                    
                    $this->Cell(0, 75, '', 0, 1);
                    $this->WriteHTML($html_header, false,0 ,true,0);
                    $this->SetLineStyle( array( 'width' => 1, 'color' => array( $webcolor['black'] ) ) );
                    $this->Line(50, 200, $this->getPageWidth() - 50, 200 );
            }

            public function Footer() {
                    global $webcolor;

                    $this->SetLineStyle( array( 'width' => 1, 'color' => array( $webcolor['black'] ) ) );
                    $this->Line( 50, $this->getPageHeight() - 50, $this->getPageWidth() - 50, $this->getPageHeight() - 50 );
                    $this->SetFont( 'helvetica', '', 9 );
                    $this->SetY( -50, true );
                    $this->Cell( 50, -40, 'Dispensé d’immatriculation au Registre du Commerce et des Sociétés et au Répertoire des Métiers.' );
            }

            # Create Quote Prossess
            public function CreateQuote() {
                    $this->AddPage();
                    $this->SetFont( 'helvetica', '', 9 );
                    $this->SetY( 180, true );
                    
                    // Generate Quote Number
                    $quote_unique_id = '00'.date('Ymd');
                    
                    // Get Quote Items And Build HTML Table Body
                    $post_id = $this->data['post_id'];
                    $items   = get_post_meta($post_id, 'quote-items');
                    $tbody   = '';
                    $total_price = 0;
                    foreach( $items[0] as $item ) {
                        $tbody .= '<tr>';
                        $tbody .= ' <td><strong>'.$item[0].'</strong><br />'.$item[1].'</td>';
                        $tbody .= ' <td align="right" valign="center">'.number_format($item[2], 2, ',', ' ').' </td>';
                        $tbody .= ' <td align="right" valign="center">'.number_format($item[3], 2, ',', ' ').' €</td>';
                        $tbody .= ' <td align="right" valign="center    ">'.number_format(((float)$item[2]*(float)$item[3]), 2, ',', ' ').' €</td>';
                        $tbody .= '</tr>';
                        
                        $total_price += ((float)$item[2]*(float)$item[3]);
                    }
                    $this->SetFont( '', '' );
                    
                    $html_table = file_get_contents(LS_MANAGER_PLUGIN_DIR_PATH . '/html/quote/content.html');
                    if ($html_table !== FALSE){
                        // 
                        $html_table = str_replace('[main_title]', $this->data['main_title'], $html_table);
                        $html_table = str_replace('[quote_unique_id]', $quote_unique_id, $html_table);
                        $html_table = str_replace('[tbody]', $tbody, $html_table);
                        $html_table = str_replace('[total_price]', number_format($total_price, 2, ',', ' '), $html_table);
                        $html_table = str_replace('[accompte]', number_format(0.3*$total_price, 2, ',', ' '), $html_table);
                        
                    }else $html_table = 'Error';
                    
                    $this->Cell(0, 15, '', 0, 1);
                    $this->WriteHTML($html_table, false,0 ,true,0);
            }
    
            # Create Invoice Prossess
            public function CreateInvoice(){
                $this->AddPage();
                $this->SetFont( 'helvetica', '', 9 );
                $this->SetY( 180, true );

                // Generate Quote Number
                $invoice_unique_id = '00'.date('Ymd');

                // Get Quote Items And Build HTML Table Body
                $post_id = $this->data['post_id'];
                $items   = get_post_meta($post_id, 'invoice-items');
                $tbody   = '';
                $total_price = 0;
                foreach( $items[0] as $item ) {
                    $tbody .= '<tr>';
                    $tbody .= ' <td><strong>'.$item[0].'</strong><br />'.$item[1].'</td>';
                    $tbody .= ' <td align="right" valign="center">'.number_format($item[2], 2, ',', ' ').' </td>';
                    $tbody .= ' <td align="right" valign="center">'.number_format($item[3], 2, ',', ' ').' €</td>';
                    $tbody .= ' <td align="right" valign="center    ">'.number_format(((float)$item[2]*(float)$item[3]), 2, ',', ' ').' €</td>';
                    $tbody .= '</tr>';

                    $total_price += ((float)$item[2]*(float)$item[3]);
                }
                $this->SetFont( '', '' );

                $html_table = file_get_contents(LS_MANAGER_PLUGIN_DIR_PATH . '/html/invoice/content.html');
                if ($html_table !== FALSE){
                    // 
                    $html_table = str_replace('[main_title]', $this->data['main_title'], $html_table);
                    $html_table = str_replace('[invoice_unique_id]', $invoice_unique_id, $html_table);
                    $html_table = str_replace('[tbody]', $tbody, $html_table);
                    $html_table = str_replace('[total_price]', number_format($total_price, 2, ',', ' '), $html_table);
                    $html_table = str_replace('[accompte]', number_format(0.3*$total_price, 2, ',', ' '), $html_table);

                }else $html_table = 'Error';
                /*$html_table .= '<table border="0" cellspacing="0" cellpadding="0" width="100%" style="margin:0;padding:0;font-size:14px;">
                                <tr>
                                    <td width="5%"></td>
                                    <td width="75%" align="left">
                                        <h4>Objet de la facture :</h4>
                                        <h5>'. $this->data['main_title'] .'</h5>
                                    </td>
                                    <td width="15%" align="right">
                                        <h4>N&deg; de facture</h4>
                                        <h5>'.$quote_unique_id.'</h5>
                                    </td>
                                    <td width="5%"></td>
                                </tr>
                                <tr>
                                    <td colspan="4" height="20" ></td>
                                </tr>
                                </table>';


                $html_table .= '<table border="0" cellspacing="0" cellpadding="0" width="100%" style="margin:0;padding:0;font-size:11px;">
                                    <tr>
                                        <td width="5%"></td>
                                        <td width="90%">
                                            <table border="1" cellspacing="0" cellpadding="4" width="100%" style="margin:0;padding:0;font-size:9px;">
                                                <thead>
                                                    <tr>
                                                        <th width="55%" align="center">Détails de la prestation</th>
                                                        <th width="15%" align="center">Quantité</th>
                                                        <th width="15%" align="center">Prix unitaire HT</th>
                                                        <th width="15%" align="center">Prix total HT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>'.$tbody.'</tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" width="85%" ></td>
                                                        <td width="15%" ></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" width="70%" >T.V.A non applicable en regard de l’article 293B du C.G.I</td>
                                                        <td width="15%">Net à payer</td>
                                                        <td width="15%" align="right" valign="center">'.number_format($total_price, 2, ',', ' ').' €</td>
                                                    </tr>
                                                    <!--<tr>
                                                        <td colspan="2" width="70%" >Acompte de 30% réglé au démarrage de la prestation</td>
                                                        <td width="15%">Accompte</td>
                                                        <td width="15%" align="right" valign="center">- '.number_format(0.3*$total_price, 2, ',', ' ').' €</td>
                                                    </tr>-->
                                                </tfoot>
                                            </table>
                                        </td>
                                        <td width="5%"></td>
                                    </tr>
                                </table>
                                <p>&nbsp;</p>
                                <p>Règlement :
                                    <ul>
                                        <li>Par chèque à l\'ordre de "Hervé THOMAS"</li>
                                        <li>Par virment sur le compte suivant : 
                                            <ul>
                                                <li>IBAN : FR76 1670 6050 6650 8694 5200 562</li>
                                                <li>BIC  : AGRIFRPP887</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </p>
                                <p>Paiement à 30 jours dès reception de la facture.</p>
                                <p>Pénalité de retard 1,5 fois le taux en vigeur</p>';*/
                $this->Cell(0, 15, '', 0, 1);
                $this->WriteHTML($html_table, false,0 ,true,0);
            }
    }
}
