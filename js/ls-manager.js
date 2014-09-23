jQuery(document).ready(function(){
    //alert('DOM Ready !');
    if (!window.location.origin) // Fix Origin Propertie On IE - The Bad Browser            
        window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
        var hostname = window.location.origin;
        var plugin_url = hostname + '/wp-content/plugins/ls-manager/';
            
            
    // Ajax Quote PDF Click
    jQuery('#create-pdf-quote').on('click', function(event) {
        event.preventDefault();
        var post_id = jQuery('#post_ID').val();
        var enterprise_id = jQuery('#quote-enterprise-id option:selected').val();
        var dest_id = jQuery('#quote-dest-id option:selected').val();
        var main_title = jQuery('#quote-main-title').val();
        
        // Ajax Request
        jQuery.ajax({type: "POST", url: plugin_url + 'ajax/create-pdf-quote.php', 
            data: 'post_id='+post_id
                 +'&enterprise_id='+enterprise_id
                 +'&dest_id='+dest_id
                 +'&main_title='+main_title,
            success: function(msg) { 
                alert('Test PDF');
            }
        });
    });

    // Ajax Add Quote Item
    jQuery('#add-quote-item').on('click', function(event) {
        event.preventDefault();
        var nonce                  = jQuery('#add-quote-item').data('nonce');
        var post_id                = jQuery('#post_ID').val();
        var quote_item_title       = jQuery('#quote-item-title').val();
        var quote_item_description = jQuery('#quote-item-description').val();
        var quote_item_quantity    = jQuery('#quote-item-quantity').val();
        var quote_item_unit_price  = jQuery('#quote-item-unit-price').val();
        // Ajax Request
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : lsAjax.ajaxurl,
            data : {
                action: "quote_builder_add_item", 
                post_id: post_id, 
                quote_item_title: quote_item_title,
                quote_item_description: quote_item_description, 
                quote_item_quantity: quote_item_quantity , 
                quote_item_unit_price: quote_item_unit_price ,
                nonce: nonce},
            beforeSend: function() { jQuery('#add-quote-item').prev('span.spinner').css('display','block'); },
            success: function(response) {
                if(response.success == true) {
                    jQuery('#quote-item-table tbody').html('');
                    jQuery('#quote-item-table tfoot').html('');
                    rows = response.rows.replace('\"', '"');
                    jQuery("#quote-item-table tbody").html(rows.toString());
                    footer = response.footer.replace('\"', '"');
                    jQuery("#quote-item-table tfoot").html(footer.toString());
                    // Flush Inputs 
                    jQuery('#quote-item-title').val('');
                    jQuery('#quote-item-description').val('');
                    jQuery('#quote-item-quantity').val('');
                    jQuery('#quote-item-unit-price').val('');
                }
            },
            complete: function( jqXHR, textStatus ){ jQuery('#add-quote-item').prev('span.spinner').css('display','none'); }
        });
    });
    
    // Ajax Remove Quote Item
    jQuery('.remove-quote-item').live('click', function(event) {
        event.preventDefault();
        var $this  = jQuery(this);
        post_id    = jQuery(this).attr("data-post-id");
        nonce      = jQuery(this).attr("data-nonce");
        line       = jQuery(this).attr("data-line");

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : lsAjax.ajaxurl,
            data : {
                action: "quote_builder_remove_item", 
                post_id : post_id, 
                nonce: nonce, 
                line : line },                
            beforeSend: function() { jQuery($this).prev('span.spinner').css('display','inline-block'); },
            success: function(response) {
                if(response.success == true) {
                    jQuery('#quote-item-table tbody').html('');
                    jQuery('#quote-item-table tfoot').html('');
                    rows = response.rows.replace('\"', '"');
                    jQuery('#quote-item-table tbody').html(rows.toString());
                    footer = response.footer.replace('\"', '"');
                    jQuery("#quote-item-table tfoot").html(footer.toString());
                }
            },
            complete: function( jqXHR, textStatus ){ jQuery($this).next('span.spinner').css('display','none'); }
        });
    });
    
    // Ajax Quote To Invoice Process
    jQuery('#quote-to-invoice-button').on('click', function(event) {
        event.preventDefault();
        var $this  = jQuery(this);
        post_id    = $this.attr("data-post-id");
        nonce      = $this.attr("data-nonce");
        
        // Ajax Request
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : lsAjax.ajaxurl,
            data : { action: "quote_to_invoice_process", post_id: post_id, nonce: nonce},
            beforeSend: function() { jQuery('#quote-to-invoice-button').prev('span.spinner').css('display','block'); },
            success: function(response) {
                if(response.success == true) {alert('OK');}
            },
            complete: function( jqXHR, textStatus ){ jQuery('#quote-to-invoice-button').prev('span.spinner').css('display','none'); }
        });
    });
    
    // Ajax Invoice PDF Click
    jQuery('#create-pdf-invoice').on('click', function(event) {
        event.preventDefault();
        var post_id = jQuery('#post_ID').val();
        var enterprise_id = jQuery('#invoice-enterprise-id option:selected').val();
        var dest_id = jQuery('#invoice-dest-id option:selected').val();
        var main_title = jQuery('#invoice-main-title').val();
        
        // Ajax Request
        jQuery.ajax({type: "POST", url: plugin_url + 'ajax/create-pdf-invoice.php', 
            data: 'post_id='+post_id
                 +'&enterprise_id='+enterprise_id
                 +'&dest_id='+dest_id
                 +'&main_title='+main_title,
            success: function(msg) { 
                alert('Test PDF');
            }
        });
    });
    
    // Ajax Add Invoice Item
    jQuery('#add-invoice-item').on('click', function(event) {
        event.preventDefault();
        var nonce                  = jQuery('#add-invoice-item').data('nonce');
        var post_id                = jQuery('#post_ID').val();
        var invoice_item_title       = jQuery('#invoice-item-title').val();
        var invoice_item_description = jQuery('#invoice-item-description').val();
        var invoice_item_quantity    = jQuery('#invoice-item-quantity').val();
        var invoice_item_unit_price  = jQuery('#invoice-item-unit-price').val();
        // Ajax Request
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : lsAjax.ajaxurl,
            data : {
                action: "invoice_builder_add_item", 
                post_id: post_id, 
                invoice_item_title: invoice_item_title,
                invoice_item_description: invoice_item_description, 
                invoice_item_quantity: invoice_item_quantity , 
                invoice_item_unit_price: invoice_item_unit_price ,
                nonce: nonce},
            beforeSend: function() { jQuery('#add-invoice-item').prev('span.spinner').css('display','block'); },
            success: function(response) {
                if(response.success == true) {
                    jQuery('#invoice-item-table tbody').html('');
                    jQuery('#invoice-item-table tfoot').html('');
                    rows = response.rows.replace('\"', '"');
                    jQuery("#invoice-item-table tbody").html(rows.toString());
                    footer = response.footer.replace('\"', '"');
                    jQuery("#invoice-item-table tfoot").html(footer.toString());
                    // Flush Inputs 
                    jQuery('#invoice-item-title').val('');
                    jQuery('#invoice-item-description').val('');
                    jQuery('#invoice-item-quantity').val('');
                    jQuery('#invoice-item-unit-price').val('');
                }
            },
            complete: function( jqXHR, textStatus ){ jQuery('#add-invoice-item').prev('span.spinner').css('display','none'); }
        });
    });
    
    // Ajax Remove invoice Item
    jQuery('.remove-invoice-item').live('click', function(event) {
        event.preventDefault();
        var $this  = jQuery(this);
        post_id    = jQuery(this).attr("data-post-id");
        nonce      = jQuery(this).attr("data-nonce");
        line       = jQuery(this).attr("data-line");

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : lsAjax.ajaxurl,
            data : {
                action: "invoice_builder_remove_item", 
                post_id : post_id, 
                nonce: nonce, 
                line : line },                
            beforeSend: function() { jQuery($this).prev('span.spinner').css('display','inline-block'); },
            success: function(response) {
                if(response.success == true) {
                    jQuery('#invoice-item-table tbody').html('');
                    jQuery('#invoice-item-table tfoot').html('');
                    rows = response.rows.replace('\"', '"');
                    jQuery('#invoice-item-table tbody').html(rows.toString());
                    footer = response.footer.replace('\"', '"');
                    jQuery("#invoice-item-table tfoot").html(footer.toString());
                }
            },
            complete: function( jqXHR, textStatus ){ jQuery($this).next('span.spinner').css('display','none'); }
        });
    });
});
