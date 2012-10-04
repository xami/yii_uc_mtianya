<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_theme.php 12880 2009-07-24 07:20:24Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$op = empty($_GET['op'])?'':$_GET['op'];
$dir = empty($_GET['dir'])?'':preg_replace("/[^0-9a-z]/item", '', $_GET['dir']);
$allowcss = checkperm('allowcss');

if(submitcheck('csssubmit')) {
	
	checksecurity($_POST['css']);
	
	$css = $allowcss?getstr($_POST['css'], 5000, 1, 1):'';
	$nocss = empty($_POST['nocss'])?0:1;
	updatetable('spacefield', array('theme'=>'', 'css'=>$css, 'nocss'=>$nocss), array('uid'=>$_SGLOBAL['supe_uid']));
	
	showmessage('do_success', 'cp.php?ac=theme&op=diy&view=ok', 0);

} elseif (submitcheck('timeoffsetsubmit')) {
	
	updatetable('spacefield', array('timeoffset'=>$_POST['timeoffset']), array('uid'=>$_SGLOBAL['supe_uid']));
	showmessage('do_success', 'cp.php?ac=theme');
}

//ȷ���ļ��Ƿ����
if($dir && $dir != 'uchomedefault') {
	$cssfile = S_ROOT.'./theme/'.$dir.'/style.css';
	if(!file_exists($cssfile)) {
		showmessage('theme_does_not_exist');
	}
}

if ($op == 'use') {
	//����
	if($dir == 'uchomedefault') {
		$setarr = array('theme'=>'', 'css'=>'');
	} else {
		$setarr = array('theme'=>$dir, 'css'=>'');
	}
	updatetable('spacefield', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
	showmessage('do_success', 'space.php', 0);
	
} elseif ($op == 'diy') {
	//�Զ���
} else {
	
	//ģ���б�
	$themes = array(
		array('dir'=>'uchomedefault', 'name'=>cplang('the_default_style'), 'pic'=>'image/theme_default.jpg')
	);
	$themes[] = array('dir'=>'uchomediy', 'name'=>cplang('the_diy_style'), 'pic'=>'image/theme_diy.jpg');

	//��ȡ���ط��Ŀ¼
	$themedirs = sreaddir(S_ROOT.'./theme');
	foreach ($themedirs as $key => $dirname) {
		//��ʽ�ļ���ͼƬ�����
		$now_dir = S_ROOT.'./theme/'.$dirname;
		if(file_exists($now_dir.'/style.css') && file_exists($now_dir.'/preview.jpg')) {
			$themes[] = array(
				'dir' => $dirname,
				'name' => getcssname($dirname)
			);
		}
	}
	
	//ʱ��
	$toselect = array($space['timeoffset'] => ' selected');
}

$actives = array('theme'=>' class="active"');

include_once template("cp_theme");

//��ȡϵͳ�����
function getcssname($dirname) {
	$css = sreadfile(S_ROOT.'./theme/'.$dirname.'/style.css');
	if($css) {
		preg_match("/\[name\](.+?)\[\/name\]/item", $css, $mathes);
		if(!empty($mathes[1])) $name = shtmlspecialchars($mathes[1]);
	} else {
		$name = 'No name';
	}
	return $name;
}

function checksecurity($str) {
	
	//ִ��һϵ�еĹ�����֤�Ƿ�Ϸ���CSS
	$filter = array(
		'/\/\*[\n\r]*(.+?)[\n\r]*\*\//is',
		'/[^a-z0-9]+/item',
	);
	$str = preg_replace($filter, '', $str);
	if(preg_match("/(expression|import|script)/item", $str)) {
		showmessage('css_contains_elements_of_insecurity');
	}
	return true;
}
?>