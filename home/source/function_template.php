<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: function_template.php 12678 2009-07-15 03:21:21Z xupeng $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$_SGLOBAL['item'] = 0;
$_SGLOBAL['block_search'] = $_SGLOBAL['block_replace'] = array();

function parse_template($tpl) {
	global $_SGLOBAL, $_SC, $_SCONFIG;

	//��ģ��
	$_SGLOBAL['sub_tpls'] = array($tpl);

	$tplfile = S_ROOT.'./'.$tpl.'.htm';
	$objfile = S_ROOT.'./data/tpl_cache/'.str_replace('/','_',$tpl).'.php';
	
	//read
	if(!file_exists($tplfile)) {
		$tplfile = str_replace('/'.$_SCONFIG['template'].'/', '/default/', $tplfile);
	}
	$template = sreadfile($tplfile);
	if(empty($template)) {
		exit("Template file : $tplfile Not found or have no access!");
	}

	//ģ��
	$template = preg_replace("/\<\!\-\-\{template\s+([a-z0-9_\/]+)\}\-\-\>/ie", "readtemplate('\\1')", $template);
	//������ҳ���еĴ���
	$template = preg_replace("/\<\!\-\-\{template\s+([a-z0-9_\/]+)\}\-\-\>/ie", "readtemplate('\\1')", $template);
	//����ģ�����
	$template = preg_replace("/\<\!\-\-\{block\/(.+?)\}\-\-\>/ie", "blocktags('\\1')", $template);
	//�������
	$template = preg_replace("/\<\!\-\-\{ad\/(.+?)\}\-\-\>/ie", "adtags('\\1')", $template);
	//ʱ�䴦��
	$template = preg_replace("/\<\!\-\-\{date\((.+?)\)\}\-\-\>/ie", "datetags('\\1')", $template);
	//ͷ����
	$template = preg_replace("/\<\!\-\-\{avatar\((.+?)\)\}\-\-\>/ie", "avatartags('\\1')", $template);
	//PHP����
	$template = preg_replace("/\<\!\-\-\{eval\s+(.+?)\s*\}\-\-\>/ies", "evaltags('\\1')", $template);

	//��ʼ����
	//����
	$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
	$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
	$template = preg_replace("/(\\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/s", "\\1['\\2']", $template);
	$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/$var_regexp/es", "addquote('<?=\\1?>')", $template);
	$template = preg_replace("/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "addquote('<?=\\1?>')", $template);
	//�߼�
	$template = preg_replace("/\{elseif\s+(.+?)\}/ies", "stripvtags('<?php } elseif(\\1) { ?>','')", $template);
	$template = preg_replace("/\{else\}/is", "<?php } else { ?>", $template);
	//ѭ��
	for($i = 0; $i < 6; $i++) {
		$template = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/ies", "stripvtags('<?php if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\\3<?php } } ?>')", $template);
		$template = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/ies", "stripvtags('<?php if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\\4<?php } } ?>')", $template);
		$template = preg_replace("/\{if\s+(.+?)\}(.+?)\{\/if\}/ies", "stripvtags('<?php if(\\1) { ?>','\\2<?php } ?>')", $template);
	}
	//����
	$template = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/s", "<?=\\1?>", $template);
	
	//�滻
	if(!empty($_SGLOBAL['block_search'])) {
		$template = str_replace($_SGLOBAL['block_search'], $_SGLOBAL['block_replace'], $template);
	}
	
	//����
	$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);
	
	//���Ӵ���
	$template = "<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('".implode('|', $_SGLOBAL['sub_tpls'])."', '$_SGLOBAL[timestamp]', '$tpl');?>$template<?php ob_out();?>";
	
	//write
	if(!swritefile($objfile, $template)) {
		exit("File: $objfile can not be write!");
	}
}

function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

function striptagquotes($expr) {
	$expr = preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr);
	$expr = str_replace("\\\"", "\"", preg_replace("/\[\'([a-zA-Z0-9_\-\.\x7f-\xff]+)\'\]/s", "[\\1]", $expr));
	return $expr;
}

function evaltags($php) {
	global $_SGLOBAL;

	$_SGLOBAL['item']++;
	$search = "<!--EVAL_TAG_{$_SGLOBAL['item']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['item']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['item']] = "<?php ".stripvtags($php)." ?>";
	
	return $search;
}

function blocktags($parameter) {
	global $_SGLOBAL;

	$_SGLOBAL['item']++;
	$search = "<!--BLOCK_TAG_{$_SGLOBAL['item']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['item']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['item']] = "<?php block(\"$parameter\"); ?>";
	return $search;
}

function adtags($pagetype) {
	global $_SGLOBAL;

	$_SGLOBAL['item']++;
	$search = "<!--AD_TAG_{$_SGLOBAL['item']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['item']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['item']] = "<?php adshow('$pagetype'); ?>";
	return $search;
}

function datetags($parameter) {
	global $_SGLOBAL;

	$_SGLOBAL['item']++;
	$search = "<!--DATE_TAG_{$_SGLOBAL['item']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['item']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['item']] = "<?php echo sgmdate($parameter); ?>";
	return $search;
}

function avatartags($parameter) {
	global $_SGLOBAL;

	$_SGLOBAL['item']++;
	$search = "<!--AVATAR_TAG_{$_SGLOBAL['item']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['item']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['item']] = "<?php echo avatar($parameter); ?>";
	return $search;
}

function stripvtags($expr, $statement='') {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}

function readtemplate($name) {
	global $_SGLOBAL, $_SCONFIG;
	
	$tpl = strexists($name,'/')?$name:"template/$_SCONFIG[template]/$name";
	$tplfile = S_ROOT.'./'.$tpl.'.htm';
	
	$_SGLOBAL['sub_tpls'][] = $tpl;
	
	if(!file_exists($tplfile)) {
		$tplfile = str_replace('/'.$_SCONFIG['template'].'/', '/default/', $tplfile);
	}
	$content = sreadfile($tplfile);
	return $content;
}

?>