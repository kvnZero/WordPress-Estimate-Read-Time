<?php
/*
Plugin Name: Estimate Read Time
Plugin URI: http://abigeater.com
Description: 保存文章时提取中文并且计算阅读时长保存在meta
Version: 1.0
Author: abigeater
Author URI: http://abigeater.com
*/

add_filter( 'wp_insert_post_data', function ($data, $attr) {
	if ( 'post' !== $data['post_type'] ) {
		return $data;
	}

	$postContent = $attr['post_content'];
	$count = preg_match_all('/[\x{4e00}-\x{9fa5}]/u', $postContent, $match); //只获取中文
	$min = 0;
	if ($count !== false) {
		$min = round($count / 400, 2); //可调整到合适的每分钟频
	}
	update_post_meta($attr['ID'], 'ab_post_read_time_text', sprintf('%s min', $min));

	return $data;
}, 10, 2);

add_filter( 'astra_get_option_blog-single-meta',function($value){
	$value[] = 'ab_post_read_time_text';
	return $value;
});

add_filter( 'astra_meta_case_ab_post_read_time_text',function($output){
	$text = get_post_meta( get_the_ID(), 'ab_post_read_time_text', true );
	if ($text) {
		$output .= ' / <span style="color: black;">本篇阅读需要: '.$text.'</span>';
	}
	return $output;
});