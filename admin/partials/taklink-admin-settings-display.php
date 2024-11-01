<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://takl.ink
 * @since      1.0.0
 *
 * @package    TakLink
 * @subpackage TakLink/admin/partials
 */
?>

<div class="wrap">
    <div id="icon-themes" class="icon32"></div>  
    <?php settings_errors(); ?>  
    <form method="POST" action="options.php">  
        <?php 
            settings_fields( 'plugin_name_general_settings' );
            do_settings_sections( 'plugin_name_general_settings' ); 
        ?>             
        <?php submit_button(); ?>  
    </form> 
</div>