<?php
if(!defined('IN_UCHOME')) exit('Access Denied');
$_SGLOBAL['app']=Array
	(
	1 => Array
		(
		'name' => 'Yii_Uc',
		'url' => 'http://www.mtianya.com/api/uc.php',
		'type' => 'DISCUZX',
		'open' => 1,
		'icon' => 'discuzx'
		),
	2 => Array
		(
		'name' => 'Yii_BBS',
		'url' => 'http://bbs.mtianya.com',
		'type' => 'DISCUZX',
		'open' => 1,
		'icon' => 'discuzx'
		),
	3 => Array
		(
		'name' => '个人家园',
		'url' => 'http://home.mtianya.com',
		'type' => 'UCHOME',
		'open' => '0',
		'icon' => 'uchome'
		)
	)
?>