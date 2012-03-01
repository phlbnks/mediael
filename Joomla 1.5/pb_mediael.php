<?php
/**
* @Copyright Copyright (C) 2011 Phil Banks
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );
$mainframe->registerEvent( 'onPrepareContent', 'pluginPbMediaEl' );

function pluginPbMediaEl(&$row, &$params) {
	
	$plugin =& JPluginHelper::getPlugin('content', 'pb_mediael');
  	$pluginParams = new JParameter( $plugin->params );

	if (!isset($GLOBALS['plg_pb_mediael'])) {
		$GLOBALS['plg_pb_mediael'] = 1;
	}
	
	$hits = preg_match_all('#{pb_mediael\s*(.*?)}#s', $row->text, $matches);
	
	if (!empty($hits)) {
		$document =& JFactory::getDocument();
		
		
		// Check if mediaelsentials stylesheets are loaded
		$styleSheets = array_keys($document->_styleSheets);
		
		$foundmediaelStyles = false;
		for ($i = 0; $i<count($styleSheets); $i++) {
			if (stripos($styleSheets[$i], 'mediaelementplayer.min.css') !== false) {
				$foundmediaelStyles = true;
			}
		}
		if (!$foundmediaelStyles) {
			$document->addStyleSheet(JURI::base().'plugins/content/pb_mediael/mediaelementplayer.min.css');
		}
		
		
		// Check if mediaelsentials script is loaded
		$scripts = array_keys($document->_scripts);
		$foundJqueryScripts = false;
		for ($i = 0; $i<count($scripts); $i++) {
			if (stripos($scripts[$i], 'jquery') !== false) {
				$foundJqueryScripts = true;
			}
		}
		if (!$foundJqueryScripts) {
			$document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js');
		}
		$foundMediaelScripts = false;
		for ($i = 0; $i<count($scripts); $i++) {
			if (stripos($scripts[$i], 'mediaelement-and-player.min.js') !== false) {
				$foundMediaelScripts = true;
			}
		}
		if (!$foundMediaelScripts) {
			$document->addScript(JURI::base().'plugins/content/pb_mediael/mediaelement-and-player.min.js');
		}
		$foundMediaelJScripts = false;
		for ($i = 0; $i<count($scripts); $i++) {
			if (stripos($scripts[$i], 'mediaelementplayer') !== false) {
				$foundMediaeljsScripts = true;
			}
		}
		
		$document->addScriptDeclaration('
		var $j = jQuery.noConflict();
		$j(document).ready(function() {
			'$download = $pluginParams->get('download'); if ($download == "false" || $download == "0") {'$j(".PbMediaEl").hide();'}'
			$j("video,audio").mediaelementplayer({
				startVolume: 			'.$pluginParams->get('defaultVolume', '0.85').',
				enableAutosize:			true,
			});
		});');
		
		// Parse shortcodes in content
			for ($i=$GLOBALS['plg_pb_mediael']; $i<$GLOBALS['plg_pb_mediael']+$hits; $i++) {
				for ($j=0; $j<$hits; $j++) {
					$videoParams = $matches[1][$j];
					$videoParamsList = contentPbMediaEl_getParams($videoParams, $pluginParams);
					$html = contentPbMediaEl_createHTML($i+$j, $pluginParams, $videoParamsList);
					$pattern = str_replace('[', '\[', $matches[0][$j]);
					$pattern = str_replace(']', '\]', $pattern);
					$pattern = str_replace('/', '\/', $pattern);
		    		$row->text = preg_replace('/'.$pattern.'/', $html, $row->text, 1);
				}
			}
		
		
		// Count instances
		$GLOBALS['plg_pb_mediael'] += $hits;
		
	} else {
		return false;
	}
	
	return true;	
	
}

function contentPbMediaEl_getParams($videoParams, $pluginParams) {
	
	$videoParamsList['media']				= $pluginParams->get('media');
	$videoParamsList['width'] 				= $pluginParams->get('width');
	$videoParamsList['height'] 				= $pluginParams->get('height');
	$videoParamsList['autoplay']			= $pluginParams->get('autoplay');
	$videoParamsList['preload']				= $pluginParams->get('preload');
	$videoParamsList['loop']				= $pluginParams->get('loop');
	$videoParamsList['audio_m4a']			= $pluginParams->get('audio_m4a');
	$videoParamsList['audio_mp3']			= $pluginParams->get('audio_mp3');
	$videoParamsList['audio_mpeg']			= $pluginParams->get('audio_mpeg');
	$videoParamsList['video_mp4']			= $pluginParams->get('video_mp4');
	$videoParamsList['video_m4v']			= $pluginParams->get('video_m4v');
	$videoParamsList['video_mov']			= $pluginParams->get('video_mov');
	$videoParamsList['video_webm']			= $pluginParams->get('video_webm');
	$videoParamsList['video_ogg'] 			= $pluginParams->get('video_ogg');
	$videoParamsList['image'] 				= $pluginParams->get('image');
	$videoParamsList['image_visibility'] 	= $pluginParams->get('image_visibility');
	$videoParamsList['flash'] 				= $pluginParams->get('flash');
	
	$items = explode(' ', $videoParams);
	
	foreach ($items as $item) {
		if ($item != '') {
			$item	= explode('=', $item);
			$name 	= $item[0];
			$value	= strtr($item[1], array('['=>'', ']'=>''));
			$videoParamsList[$name] = $value;
		}
	}
	
	return $videoParamsList;
}

function contentPbMediaEl_createHTML($id, &$pluginParams, &$videoParamsList) {
	
	$media				= $videoParamsList['media'];
	$width 				= $videoParamsList['width'];
	$height 			= $videoParamsList['height'];
	$autoplay			= $videoParamsList['autoplay'];
	$preload			= $videoParamsList['preload'];
	$loop				= $videoParamsList['loop'];
	$audio_m4a			= $videoParamsList['audio_m4a'];
	$audio_mp3			= $videoParamsList['audio_mp3'];
	$audio_mpeg			= $videoParamsList['audio_mpeg'];
	$video_mp4			= $videoParamsList['video_mp4'];
	$video_m4v			= $videoParamsList['video_m4v'];
	$video_mov			= $videoParamsList['video_mov'];
	$video_webm			= $videoParamsList['video_webm'];
	$video_ogg			= $videoParamsList['video_ogg'];
	$flash				= $videoParamsList['flash'];
	$image 				= $videoParamsList['image'];
	$download 			= $videoParamsList['download'];
	$image_visibility	= $videoParamsList['image_visibility'];
	$wmode				= $pluginParams->get('wmode', 'default');
	$uri_flash			= '';
	$uri_image			= '';
	
	// Add URI for local flash video
	if (stripos($flash, 'http://') === false) {
		$uri_flash = JURI::base();		
	}
	
	// Add URI for local flash image
	if (stripos($image, 'http://') === false) {
		$uri_image = JURI::base();		
	}
	
	// Preload works for both HTML and Flash
	if ($preload == "true" || $preload == "1") {
		$preload_html 	= ' preload="auto"';
		$preload_flash	= '"autoBuffering":true';
	} else {
		$preload_html 	= ' preload="none"';
		$preload_flash	= '"autoBuffering":false';
	}
	
	// Autoplay works for both HTML and Flash
	if ($autoplay == "true" || $autoplay == "1") {
		$autoplay_html 	= ' autoplay="autoplay"';
		$autoplay_flash	= '"autoPlay":true';
	} else {
		$autoplay_html 	= '';
		$autoplay_flash	= '"autoPlay":false';
	}
	
	// Actually loop works only for HTML
	if ($loop == "true" || $loop == "1") {
		$loop_html		= ' loop="loop"';
	} else {
		$loop_html 		= '';
	}
	
	// Poster image
	if ($image_visibility == "true" || $image_visibility == "1") {
		$poster_html = ' poster="'.$image;
	} else {
		$poster_html = '';
	}
	
	// HTML output
	$html = '<'.$media.' width="'.$width.'" height="'.$height.'" controls="controls"'.$autoplay_html.$preload_html.$loop_html.$poster_html.'">';
	
	if ($audio_m4a != "") {
		$html .= '<source src="'.$audio_m4a.'" type="audio/m4a" />';
	}
	if ($audio_mp3 != "") {
		$html .= '<source src="'.$audio_mp3.'" type="audio/mp3" />';
	}
	if ($audio_mpeg != "") {
		$html .= '<source src="'.$audio_mpeg.'" type="audio/mpeg" />';
	}
	
	if ($video_mp4 != "") {
		$html .= '<source src="'.$video_mp4.'" type="video/mp4" />';
	}
	if ($video_m4v != "") {
		$html .= '<source src="'.$video_m4v.'" type="video/m4v" />';
	}
	if ($video_mov != "") {
		$html .= '<source src="'.$video_mov.'" type="video/mov" />';
	}
	if ($video_webm != "") {
		$html .= '<source src="'.$video_webm.'" type="video/webm" />';
	}
	if ($video_ogg != "") {
		$html .= '<source src="'.$video_ogg.'" type="video/ogg" />';
	}
	
	if ($flash != "") {
		$html .= '<object width="'.$width.'" height="'.$height.'" type="application/x-shockwave-flash" data="'.JURI::base().'plugins/content/pb_mediael/flashmediaelement.swf">
        		<param name="movie" value="'.JURI::base().'plugins/content/pb_mediael/flashmediaelement.swf" />';
		if ($wmode != 'default') {
			$html .= '<param name="wmode" value="'.$wmode.'" />';			
		}
	
 		$html .= '<param name="flashvars" value="controls=true&poster='.$uri_image.$image.'&file='.$uri_flash.$flash.'" />';

		if ($image_visibility == "true" || $image_visibility == "1") {
			$html .= '<img src="'.$image.'" width="'.$width.'" height="'.$height.'" alt="Poster Image" title="No video playback capabilities." />';
		}

      	$html .= '</object>';
	}
	
	$html .= '</'.$media.'>';
	
	$html .='<p class="PbMediaEl"><strong>If you cannot see the media above - download here: </strong>';
	
	if ($audio_m4a != "") {
		$html .= '<a href="'.$audio_m4a.'">M4A</a> ';
	}
	if ($audio_mp3 != "") {
		$html .= '<a href="'.$audio_mp3.'">MP3</a> ';
	}
	if ($audio_mpeg != "") {
		$html .= '<a href="'.$audio_mpeg.'">MPEG</a> ';
	}
	
	if ($video_mp4 != "") {
		$html .= '<a href="'.$video_mp4.'">MP4</a> ';
	}
	if ($video_m4v != "") {
		$html .= '<a href="'.$video_m4v.'">M4V</a> ';
	}
	if ($video_mov != "") {
		$html .= '<a href="'.$video_mov.'">MOV</a> ';
	}
	if ($video_webm != "") {
		$html .= '<a href="'.$video_webm.'">WebM</a> ';
	}
	if ($video_ogg != "") {
		$html .= '<a href="'.$video_ogg.'">Ogg</a><br>';
	}

	$html .= '</p>';
			

	return $html;
	
}