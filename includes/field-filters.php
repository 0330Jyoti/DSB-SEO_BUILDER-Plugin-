<?php

function dsb_acf_load_field_information($html, $field){

	if ($field->get_id() === 'dsb-explanation')
	{
		global $post;

		$dsb                		= DSB_Seo_Builder::get_instance();

		$post_id            		= (int)get_the_ID();
		$post_name          		= $post->post_name;
		$post_status				= $post->post_status;
		$urls               		= $dsb->dsb_get_seo_page_urls($post_name, $post_id, 20);
		$search_terms       		= $dsb->dsb_get_search_terms($post_id);
		$locations          		= $dsb->dsb_get_locations($post_id);
		$search_term_placeholder 	= $dsb->get_search_term_single_placeholder();
		$location_placeholder 		= $dsb->get_location_single_placeholder();

		if (is_array($search_terms) && is_array($locations))
		{
			$num_urls           = count($search_terms) * count($locations);
			$nums_urls_cap      = $num_urls > 20 ? __("The first 20 URLs are listed below.") : "";
		}

		if (is_array($urls) && count($urls) > 0)
		{
			$explanation 		= sprintf(
								__("A unique URL is generated for each combination of %s and %s.", 'dsb_seo_builder'),
								$search_term_placeholder,
								$location_placeholder
							);

			$num_generated		= sprintf(__('%s URLs generated.', 'dsb_seo_builder'), number_format($num_urls, 0, ",", "."));

			$html = "<div class='dsb-label'><p>{$explanation}</p>";
			
			$html .= "<p>{$num_generated} {$nums_urls_cap}</p></div><br>";

            $html .= "<div style='overflow-y: scroll; max-height: 100px;'>";

            $href = false;
            if ($post_status !== 'publish')
            {
                $href = get_home_url() . "?post_type=dsb_seo_page&p={$post_id}&preview=true";
            }

            $index = 0;
            foreach ($urls as $url)
            {
                $style = 'display: block; padding: 8px;';
                if ($index % 2 == 0)
                {
                    $style .= ' background-color: #f0f0f0;';
                }
                if ($post_status === 'publish')
                {
                    $href = $url;
                }

                $url    = urldecode($url);

                $html .= "<a href='{$href}' target='_blank' style='{$style}'>{$url}</a>";

                $index++;
            }

			$html .= "</div>";
		}
		else
		{
			$no_urls = __("Add Search terms and Locations.");
			$html = "<div class='dsb-label'><p><span class='dashicons-before dashicons-no' style='color: #ff0000;'></span> {$no_urls}</p></div>";
		}
	}
	else if ($field->get_id() === 'dsb-sitemap')
	{
		$sitemap_url = dsb_get_sitemap_url();

		$html = __('Add this sitemap to the Google Search Console Sitemaps:', 'dsb_seo_builder');
		
		$html .= sprintf(
			'<br><br><a href="%s" target="_blank">seo_builder_sitemap_index.xml</a>',
			$sitemap_url
		);			
	}

	else if ($field->get_id() === 'dsb-documentation')
	{
        $dsb_seo_builder_dir = dsb_get_plugin_dir();

        ob_start();
            require_once "{$dsb_seo_builder_dir}assets/documentation.php";
		    $html = ob_get_contents();
        ob_end_clean();
	}
	
	else if ($field->get_id() === 'dsb-url-structure')
	{
		global $post;

		$dsb = DSB_Seo_Builder::get_instance();
		$site_url			= get_home_url();
		$seo_page_base		= urldecode('/' . $post->post_name);
		$slug_placeholder	= $dsb->dsb_get_slug_placeholder($post->ID);

        

		$html = "<p class='dsb-url-structure'><span class='dsb-url-structure-site-url'>{$site_url}</span><label class='dsb-url-structure-seo-page-base' for='dsb-seo-page-base'>{$seo_page_base}</label>/<label class='dsb-url-structure-base-slug' for='dsb-slug-placeholder'>{$slug_placeholder}</span></p>";
	}
	else if ($field->get_id() === 'dsb-placeholders-warning')
	{
		$dsb        = DSB_Seo_Builder::get_instance();
		$pages      = $dsb->dsb_get_seo_pages('any');

		if ($pages)
		{
			$html = sprintf("<span class='error'>%s</span>",
				__('When you change these placeholders, do not forget to update the placeholders on your SEO Pages', 'dsb_seo_builder')
			);
		}
	}

    return $html;
}
add_filter('dsb-show-field-html', 'dsb_acf_load_field_information', 10, 2);


function dsb_meta_block_field_sanitize_value($value, $field){
	if ($field->get_id() === 'dsb-slug-placeholder')
	{
		$dsb						= DSB_Seo_Builder::get_instance();

		$search_term_placeholder 	= $dsb->get_search_term_single_placeholder();
		$location_placeholder 		= $dsb->get_location_single_placeholder();
		
		$has_both_placeholders		= $dsb->has_any_placeholder($value, true);

		if ($has_both_placeholders)
		{
			$value = str_replace($search_term_placeholder, '___aa1234aa___', $value);
			$value = str_replace($location_placeholder, '___bb1234bb___', $value);
		}

		$value = sanitize_title($value);

		if ($has_both_placeholders)
		{
			$value = str_replace('___aa1234aa___', $search_term_placeholder, $value);
			$value = str_replace('___bb1234bb___', $location_placeholder, $value);
		}
		else
		{
			if (!dsb_is_empty($value))
			{
				$value .= "-" . $field->args['default'];
			}
		}
	}
	else if ($field->get_id() === 'dsb-search-terms')
	{
		$dsb    = DSB_Seo_Builder::get_instance();
		$value	= dsb_limit_max_lines($value, $dsb->dsb_get_max_search_terms());
		$value	= strip_tags($value);
	}
	else if ($field->get_id() === 'dsb-locations')
	{
		$dsb    = DSB_Seo_Builder::get_instance();
		$value	= dsb_limit_max_lines($value, $dsb->dsb_get_max_locations());
		$value	= strip_tags($value);
	}
	

    return $value;
}
add_filter('dsb-meta-block-field-sanitize-value', 'dsb_meta_block_field_sanitize_value', 10, 2);


function dsb_update_post_name($value, $post_id){	
	remove_action('dsb-save-field-dsb-seo-page-base', 'dsb_update_post_name');

	if ((int)$post_id > 0)
	{
		$post	= get_post($post_id);

		

		if (empty($value))
		{
			$value 	= sanitize_title($post->post_title);
		}

		$value = wp_unique_post_slug($value, $post_id, $post->post_status, $post->post_type, $post->post_parent);

		if ($post->post_name !== $value)
		{
			$args	= array(
				'ID'		=> $post_id,
				'post_name'	=> $value
			);
		
			wp_update_post($args);
		}
	}

	update_option( 'dsb-flush-rewrite-rules', 1 );
}
add_action('dsb-save-field-dsb-seo-page-base', 'dsb_update_post_name', 10, 2);


function dsb_post_updated($post_ID, $post_after, $post_before){
	remove_action("post_updated", "dsb_post_updated");

	if ($post_after->post_type === 'dsb_seo_page' && $post_before->post_name !== $post_after->post_name)
	{
		update_option( 'dsb-flush-rewrite-rules', 1 );
	}
}
add_action("post_updated", "dsb_post_updated", 10, 3);


function dsb_get_value_seo_page_base($value, $post_id){
	$post = get_post($post_id);
    return urldecode($post->post_name);
}
add_filter('dsb-get-value-dsb-seo-page-base', 'dsb_get_value_seo_page_base', 10, 2);
