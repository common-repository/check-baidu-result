<?php
/*

**************************************************************************

Plugin Name:  Check Baidu Result
Plugin URI:   http://www.arefly.com/check-baidu-result/
Description:  检查你的文章是否在百度搜索结果内
Version:      1.0.8
Author:       Arefly
Author URI:   http://www.arefly.com/
Text Domain:  check-baidu-result
Domain Path:  /lang/

**************************************************************************

	Copyright 2014  Arefly  (email : eflyjason@gmail.com)

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

**************************************************************************/

define("CHECK_BAIDU_RESULT_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("CHECK_BAIDU_RESULT_FULL_DIR", plugin_dir_path( __FILE__ ));
define("CHECK_BAIDU_RESULT_TEXT_DOMAIN", "check-baidu-result");

/* Plugin Localize */
function check_baidu_result_load_plugin_textdomain() {
	load_plugin_textdomain(CHECK_BAIDU_RESULT_TEXT_DOMAIN, false, dirname(plugin_basename( __FILE__ )).'/lang/');
}
add_action('plugins_loaded', 'check_baidu_result_load_plugin_textdomain');

include_once CHECK_BAIDU_RESULT_FULL_DIR."options.php";

/* Add Links to Plugins Management Page */
function check_baidu_result_action_links($links){
	$links[] = '<a href="'.get_admin_url(null, 'options-general.php?page='.CHECK_BAIDU_RESULT_TEXT_DOMAIN.'-options').'">'.__("Settings", CHECK_BAIDU_RESULT_TEXT_DOMAIN).'</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'check_baidu_result_action_links');

function check_baidu_result_check($url){
	$url = 'http://www.baidu.com/s?wd='.$url;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$rs = curl_exec($curl);
	curl_close($curl);
	if(strpos($rs, 'did not match any documents.')){
		return FALSE;
	}else{
		return TRUE;
	}  
}

function check_baidu_result($content){
	if(is_singular()){
		$get_name = get_option('check_baidu_result_get_name');
		if(!empty($get_name)){
			if(!isset($_GET[$get_name])){
				return $content;
			}
		}
		if(get_option('check_baidu_result_show_to') == "admin"){
			if(!is_super_admin()){
				return $content;
			}
		}
		if(check_baidu_result_check(get_permalink())){
			$content = '<p style="text-align: right; color: green;">'.__("This URL is in Baidu Search result.", CHECK_BAIDU_RESULT_TEXT_DOMAIN).'</p>'.$content; 
		}else{
			$content = '<p style="text-align: right; color: red;">'.__("This URL is not in Baidu Search result.", CHECK_BAIDU_RESULT_TEXT_DOMAIN).'</p>'.$content;  
		}
	}
	return $content;
}
add_filter('the_content', 'check_baidu_result');