<?php
/**
 *  Plugin Name: Freelance Manager
 *  Plugin URI: http://labside.fr
 *  Description: Simple CRM For Freelancer
 *  Version: 0.1
 *  Author: <a href="http://labside.fr" target="_blank">Hervé THOMAS - Labside</a>
 */

/*  Copyright 2014  HERVE THOMAS  (email : herve.thomas@labside.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Define constants */
define( 'LS_MANAGER_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );
define( 'LS_MANAGER_PLUGIN_DIR_URL',  plugin_dir_url(__FILE__) );

// Load Libs Dependencies
require_once( LS_MANAGER_PLUGIN_DIR_PATH . 'libs/tcpdf/tcpdf.php' );

include( LS_MANAGER_PLUGIN_DIR_PATH . 'core/ls-manager-core.php');
include( LS_MANAGER_PLUGIN_DIR_PATH . 'core/ls-manager-pdf.php');
require( LS_MANAGER_PLUGIN_DIR_PATH . 'core/ls-manager-cpt.php' );
require( LS_MANAGER_PLUGIN_DIR_PATH . 'modules/enterprise.php' );
require( LS_MANAGER_PLUGIN_DIR_PATH . 'modules/partner.php' );
require( LS_MANAGER_PLUGIN_DIR_PATH . 'modules/project.php' );
require( LS_MANAGER_PLUGIN_DIR_PATH . 'modules/prospect.php' );
require( LS_MANAGER_PLUGIN_DIR_PATH . 'modules/customer.php' );
require( LS_MANAGER_PLUGIN_DIR_PATH . 'modules/quote.php' );
require( LS_MANAGER_PLUGIN_DIR_PATH . 'modules/invoice.php' );



/**
 *  Labside Manager Instance 
 */
if (class_exists('LS_Manager')) {
    
	global $ls_manager;
        
        // Instanciation 
	$ls_manager = new LS_Manager(__FILE__);
        
}
