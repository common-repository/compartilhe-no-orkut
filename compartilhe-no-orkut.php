<?php
/*
Plugin Name: Compartilhe no Orkut
Plugin URI: http://pt.minichiello.name/2009/12/plugin-compartilhe-no-orkut-para-wordpress
Description: Exibe um botão para compartilhar o conteúdo no Orkut no final de cada post.
Version: 1.1
Author: Gerson Minichiello
Author URI: http://minichiello.name/
*/

/*  Copyright 2009 Gerson Minichiello (http://minichiello.name/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! defined('WP_CONTENT_URL')) {
	define('CNO_PATH', get_option('siteurl').'/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/');
} else {
	define('CNO_PATH', WP_CONTENT_URL.'/plugins/' . plugin_basename(dirname(__FILE__)) . '/');
}

function compartilhe_no_orkut_code($title, $content, $url)
{
	$content = trim(addslashes(trim(strip_tags(strip_shortcodes($content)))));
	if($content && strlen($content) > 90) {
		$content = substr($content, 0, 87) . '...';
	}
	$code = <<<EOT
<!-- Início Plugin Compartilhe no Orkut http://minichiello.name -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load('orkut.share', '1');
	google.setOnLoadCallback(function() {
		var params = {};
		params[google.orkut.share.Field.NETWORK] = 'orkut.com';
		params[google.orkut.share.Field.TITLE] = '{$title}';
		params[google.orkut.share.Field.CONTENT] = '{$content}';
		params[google.orkut.share.Field.DESTINATION_URL] = '{$url}';
		var connection = new google.orkut.share.Connection(params);
		document.getElementById('compartilhe-no-orkut').onclick = function(e) {
			connection.send('orkut.com', {});
		};
	}, true);
</script>
<!-- Fim Plugin Compartilhe no Orkut -->
EOT;
	return $code;
}

function compartilhe_no_orkut_head()
{
	global $post;
	if(is_single()) {
		echo compartilhe_no_orkut_code($post->post_title, $post->post_content, get_permalink($post->ID));
	}
}

function compartilhe_no_orkut_button()
{
	$img = CNO_PATH . 'compartilhe-no-orkut.gif';
	$button = "<div id=\"compartilhe-no-orkut\" align=\"center\" style=\"clear:both; padding:5px;\"><img src=\"" . $img . "\" alt=\"Compartilhe no Orkut!\" title=\"Compartilhe no Orkut!\" style=\"border:medium none;cursor:pointer;\"></div>";
	return $button;
}

function compartilhe_no_orkut($content)
{
	global $post;
	$button = '';
	if(is_single()) {
		$button = compartilhe_no_orkut_button();
	}
	return $content . $button;
}

add_action('wp_head', 'compartilhe_no_orkut_head');
add_filter('the_content', 'compartilhe_no_orkut');