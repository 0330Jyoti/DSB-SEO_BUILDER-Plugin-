<?php

/**
 * Plugin Name:             DynamicSEO Builder
 * Plugin URI:              https://profiles.wordpress.org/iqbal1486/
 * Description:             Generate SEO landingpages based on different search terms and locations.
 * Version:                 1.0.0
 * Author:                  Wpsrintplan
 * Author URI:               https://profiles.wordpress.org/iqbal1486/
 * Text Domain:             dsb_seo_builder
 * Domain Path:             /languages
 * 
 * Copyright:               © 2024 wpsprintplan.com
 * License:                 GNU General Public License v3.0
 * License URI:             https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') or die();

define('DSB_PLUGIN_VERSION', '2.6.0');

add_action('init', 'dsb_plugin_init', -999999);
function dsb_plugin_init(){
    load_plugin_textdomain('dsb_seo_builder', false, 'dsb-seo-builder/languages');
}


register_activation_hook( __FILE__, 'dsb_activate' );
function dsb_activate(){ 
 
    dsb_register_cpt_dsb_seo_page();

    dsb_create_seo_gen_example_page();

    flush_rewrite_rules(); 
}
 
register_deactivation_hook( __FILE__, 'dsb_deactivate' );
function dsb_deactivate(){

    unregister_post_type( 'dsb_seo_page' );

 
    flush_rewrite_rules();
}

function dsb_get_plugin_dir(){
    $dsb_seo_generator_dir = plugin_dir_path(__FILE__);
    return $dsb_seo_generator_dir;
}

function dsb_get_plugin_url(){
    $dsb_seo_generator_url = plugins_url('dsb-seo-builder');
    return $dsb_seo_generator_url;
}

function dsb_get_plugin_basename(){
    $dsb_seo_generator_basename = plugin_basename(__FILE__);
    return $dsb_seo_generator_basename;
}

$dsb_seo_generator_dir = dsb_get_plugin_dir();
$dsb_seo_generator_url = dsb_get_plugin_url();

require_once "{$dsb_seo_generator_dir}includes/field-filters.php";

require_once "{$dsb_seo_generator_dir}includes/admin.php";
require_once "{$dsb_seo_generator_dir}includes/content-filters.php";
require_once "{$dsb_seo_generator_dir}includes/yoast-filters.php";
require_once "{$dsb_seo_generator_dir}includes/rankmath-filters.php";
require_once "{$dsb_seo_generator_dir}includes/custom-post-type.php";
require_once "{$dsb_seo_generator_dir}includes/functions.php";
require_once "{$dsb_seo_generator_dir}includes/url-rewrites.php";

require_once "{$dsb_seo_generator_dir}includes/class.dsb-config.php";
require_once "{$dsb_seo_generator_dir}includes/class.dsb-meta-block.php";
require_once "{$dsb_seo_generator_dir}includes/class.dsb-meta-block-fields.php";
require_once "{$dsb_seo_generator_dir}includes/class.dsb-spintax.php";

require_once "{$dsb_seo_generator_dir}includes/class.dsb.php";
require_once "{$dsb_seo_generator_dir}includes/class.dsb-settings.php";
require_once "{$dsb_seo_generator_dir}includes/class.dsb-documentation.php";
require_once "{$dsb_seo_generator_dir}includes/dsb-meta-boxes.php";
