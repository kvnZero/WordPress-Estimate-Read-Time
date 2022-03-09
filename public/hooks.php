<?php

add_filter( 'wp_insert_post_data', function ($data, $attr) {
	if ( 'post' !== $data['post_type'] ) {
		return $data;
	}

	$postContent = $attr['post_content'];
	$count = preg_match_all('/[\x{4e00}-\x{9fa5}]/u', $postContent, $match); //只获取中文
	$min = 0;
	if ($count !== false) {
		$min = round($count / AB_Read_Time_Menu::get_setting_value('rate', 400), 2); //可调整到合适的每分钟频
	}
	update_post_meta($attr['ID'], 'ab_post_read_time_text', sprintf('%s min', $min)); //时间文本

	return $data;
}, 10, 2);

if (wp_get_theme()->get('Name') == 'Astra') { // 如果使用astra主题，你可以直接直接使用以下代码显示在文章标题下方较合适的地方。
	add_filter( 'astra_get_option_blog-single-meta',function($value){
		$value[] = 'ab_post_read_time_text';
		return $value;
	});
	
	add_filter( 'astra_meta_case_ab_post_read_time_text',function($output){
		$text = get_post_meta( get_the_ID(), 'ab_post_read_time_text', true );
		if ($text) {
			$output .= ' / <span style="color: black;">'.str_replace("{{time}}", $text, AB_Read_Time_Menu::get_setting_value('show_text_template')).'</span>';
		}
		return $output;
	});
}

// 如果需要其他地方使用，可以使用WordPress短代码的方式获取阅读时间文本
function ab_post_read_time_func($attrs) { 
	$post_id = $attrs['id'] ?? get_the_ID();
	if ($post_id) {
		return get_post_meta($post_id, 'ab_post_read_time_text', true);
	} else {
		return '';
	}
}

add_shortcode(AB_Read_Time_Menu::get_setting_value('short_code'), 'ab_post_read_time_func');