<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp.php 12352 2009-06-11 06:59:06Z liguode $
*/

//ͨ���ļ�
include_once('./common.php');
include_once(S_ROOT.'./source/function_cp.php');
include_once(S_ROOT.'./source/function_magic.php');

$mid = empty($_GET['mid'])?'':trim($_GET['mid']);
$op = empty($_GET['op'])?'use':$_GET['op'];
$id = empty($_GET['id'])?0:intval($_GET['id']);
$idtype = empty($_GET['idtype'])?'':trim($_GET['idtype']);

//Ȩ���ж�
if(empty($_SGLOBAL['supe_uid'])) {
	showmessage('to_login', 'do.php?ac='.$_SCONFIG['login_action']);
}

//վ��ر�
checkclose();

//MID���
if(empty($mid)) {
	showmessage('unknown_magic');
}

//��ȡ�ռ���Ϣ
$space = getspace($_SGLOBAL['supe_uid']);
if(empty($space)) {
	showmessage('space_does_not_exist');
}

//��õ���
$magic = magic_get($mid);

//�Ƿ�ӵ�иõ���
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("usermagic")." WHERE uid='$_SGLOBAL[supe_uid]' AND mid='$mid'");
$usermagic = $_SGLOBAL['db']->fetch_array($query);
if(empty($usermagic['count'])) {
	$op = 'buy';
}

//�ύ����
$frombuy = false;
if(submitcheck('buysubmit')) {
	//��õ�����Ϣ
	$results = magic_buy_get($magic);
	extract($results);

	//�������
	magic_buy_post($magic, $magicstore, $coupon);

	$op = 'use';
	$frombuy = true;//����ǹ��������ʹ��
	$usermagic['count'] += $_POST['buynum'];
}

//�������
if($op == 'buy') {

	//��õ�����Ϣ
	$results = magic_buy_get($magic);
	extract($results);

	//ĳЩ������Ҫ���ݵĸ�����Ϣ
	$extra = '';
	if($mid == 'doodle') {
		$extra = "&showid=$_GET[showid]&target=$_GET[target]&from=$_GET[from]";
	}

	include_once template('cp_magic');
	exit();

}

//�����ʹ�������ڵ�ʹ�ô���
if($magic['useperoid'] > 0) {
	$time = $_SGLOBAL['timestamp'] - $magic['useperoid'];
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('magicuselog')." WHERE uid='$_SGLOBAL[supe_uid]' AND mid='$mid' AND dateline > '$time'"), 0);
	if($count >= $magic['usecount']) {
		//ȡ����������ʹ�õ�һ����¼�������´�ʹ��ʱ��
		$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('magicuselog')." WHERE uid='$_SGLOBAL[supe_uid]' AND mid='$mid' AND dateline > '$time' ORDER BY dateline LIMIT 1");
		$value = $_SGLOBAL['db']->fetch_array($query);
		$nexttime = sgmdate('m-d H:item:s', $value['dateline'] + $magic['useperoid']);
		showmessage('magic_usecount_limit', '', '', array($nexttime));
	}
}

include_once(S_ROOT.'./source/magic_'.$mid.'.php');
include_once template('magic_'.$mid);

?>