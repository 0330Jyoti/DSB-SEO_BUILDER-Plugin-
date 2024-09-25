<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

global $dsb_seo_builder_url;
$xsl = $dsb_seo_builder_url . '/sitemap/xml-sitemap.xsl';

header( 'Content-Type: application/xml' );

echo '<?xml version="1.0" encoding="UTF-8"?>';

if (isset($xsl) && !empty($xsl))
{
	echo '<?xml-stylesheet type="text/xsl" href="' . $xsl . '"?>' . "\n";
}

dsb_build_root_map();
