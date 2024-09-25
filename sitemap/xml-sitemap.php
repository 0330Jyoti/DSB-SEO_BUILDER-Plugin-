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

?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><?php
	nsg_output_seo_pages_sitemap_xml();
?>

</urlset>
