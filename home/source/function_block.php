<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: function_block.php 8150 2008-07-21 08:17:22Z zhengqingpeng $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//����ģ��
function block_batch($param) {
	global $_SGLOBAL, $_SBLOCK, $_SCONFIG;
	
	$cachekey = smd5($param);
	$paramarr = parseparameter($param);

	if(empty($_SCONFIG['allowcache'])) {
		$paramarr['cachetime'] = 0;//�رջ���
	} else {
		$paramarr['cachetime'] = intval($paramarr['cachetime']);
	}
	
	if(!empty($paramarr['perpage'])) {
		//��ҳ
		$_GET['page'] = empty($_GET['page'])?1:intval($_GET['page']);
		if($_GET['page'] < 1) $_GET['page'] = 1;
		if($_GET['page'] > 1 && $paramarr['cachetime']) {
			$cachekey = smd5($param.$_GET['page']);//key�ı�
		}
	}
	//��ȡ����
	if($paramarr['cachetime']) {
		$caches = block_get($cachekey);
	} else {
		$caches = array();
	}

	if(!empty($caches['mtime']) && $_SGLOBAL['timestamp']-$caches['mtime'] <= $paramarr['cachetime']) {
		//ʹ�û���
		$_SBLOCK[$paramarr['cachename']] = $caches['values'];
		$_SBLOCK[$paramarr['cachename'].'_multipage'] = $caches['multi'];
		
	} else {
		//��ѯ���
		$blockarr = array();
		$results = getparamsql($paramarr);
		if($results['count']) {
			$query = $_SGLOBAL['db']->query($results['sql']);
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$blockarr[] = $value;
			}
		}
		$_SBLOCK[$paramarr['cachename']] = $blockarr;
		$_SBLOCK[$paramarr['cachename'].'_multipage'] = $results['multi'];
		
		//���»���
		if($paramarr['cachetime']) {
			$blockarr['multipage'] = $results['multi'];//�����ҳ
			block_set($cachekey, $blockarr);
		}
	}
}

//��ȡģ�黺��
function block_get($cachekey) {
	global $_SGLOBAL, $_SCONFIG;

	$caches = array('mtime'=>0);
	if($_SCONFIG['cachemode'] == 'file') {
		$cachefile = S_ROOT.'./data/block_cache/'.getcachedirname($cachekey, '/').$cachekey.'.data';
		if(file_exists($cachefile)) {
			if(@$fp = fopen($cachefile, 'r')) {
				$data = fread($fp,filesize($cachefile));
				fclose($fp);
			}
			@$blockarr = unserialize($data);
			if(isset($blockarr['multipage'])) {
				$caches['multi'] = $blockarr['multipage'];
				unset( $blockarr['multipage']);
			} else {
				$caches['multi'] = '';
			}
			$caches['values'] = $blockarr;
			@$caches['mtime'] = filemtime($cachefile);
		}
	} else {
		$thetable = tname('cache'.getcachedirname($cachekey));
		if($query = $_SGLOBAL['db']->query("SELECT * FROM $thetable WHERE cachekey = '$cachekey'", 'SILENT')) {
			if($result = $_SGLOBAL['db']->fetch_array($query)) {
				@$blockarr = unserialize($result['value']);
				if(isset($blockarr['multipage'])) {
					$caches['multi'] = $blockarr['multipage'];
					unset( $blockarr['multipage']);
				} else {
					$caches['multi'] = '';
				}
				$caches['values'] = $blockarr;
				@$caches['mtime'] = $result['mtime'];
			}
		} else {
			//�����ֱ�
			$basetable = tname('cache');
			$query = $_SGLOBAL['db']->query("SHOW CREATE TABLE $basetable");
			$creattable = $_SGLOBAL['db']->fetch_array($query);
			$sql = str_replace($basetable, $thetable, $creattable['Create Table']);
			$_SGLOBAL['db']->query($sql, 'SILENT');//�����ֱ�
		}
	}
	
	return $caches;
}

//����ģ��
function block_set($cachekey, $blockarr) {
	global $_SGLOBAL, $_SCONFIG;
	
	$blockvalue = serialize($blockarr);
	
	if($_SCONFIG['cachemode'] == 'file') {
		//�ı��洢
		$dircheck = false;
		$cachedir = S_ROOT.'./data/block_cache/';
		if(!is_dir($cachedir)) @mkdir($cachedir);
		$cachedir .= getcachedirname($cachekey, '/');
		if(!is_dir($cachedir)) {
			if(@mkdir($cachedir)) {
				$dircheck = true;
			}
		} else {
			$dircheck = true;
		}
		if($dircheck) {
			$cachefile = $cachedir.$cachekey.'.data';
			if(@$fp = fopen($cachefile, 'w')) {
				fwrite($fp, $blockvalue);
				fclose($fp);
			}
		}
	} else {
		$thetable = tname('cache'.getcachedirname($cachekey));
		$_SGLOBAL['db']->query("REPLACE INTO $thetable (cachekey, value, mtime) VALUES ('$cachekey', '".addslashes($blockvalue)."', '$_SGLOBAL[timestamp]')");
	}
}

//�ַ����
function parseparameter($param) {
	$paramarr = array();
	$sarr = explode('/', $param);
	if(empty($sarr)) return $paramarr;
	for($i=0; $i<count($sarr); $i=$i+2) {
		if(!empty($sarr[$i+1])) $paramarr[$sarr[$i]] = str_replace(array('/', '\\'), '', rawurldecode($sarr[$i+1]));
	}
	return $paramarr;
}

//��ȡ���������
function getcachedirname($cachekey, $ext='') {
	global $_SCONFIG;
	return empty($_SCONFIG['cachegrade'])?'':substr($cachekey, 0, $_SCONFIG['cachegrade']).$ext;
}

//MD5����ȡ������
function smd5($str) {
	return substr(md5($str), 8, 16);
}

//��ȡ����sql
function getcountsql($sqlstring, $rule, $tablename, $where) {
	preg_match("/$rule/item", $sqlstring, $mathes);
	if(empty($mathes)) {
		$countsql = '';
	} else {
		if($where < 0) $mathes[$where] = '1';//����������
		$countsql = "SELECT COUNT(*) FROM {$mathes[$tablename]} WHERE {$mathes[$where]}";
	}
	return $countsql;
}

//��ȡ�����Ͳ�ѯ���
function getparamsql($paramarr) {
	global $_SGLOBAL;
	
	$paramarr['sql'] = preg_replace("/\[(\d+)\]/e", 'mksqltime(\'\\1\')', $paramarr['sql']);

	$sqlstring ='SELECT'.preg_replace("/^(select)/item", '', str_replace(';', '', trim($paramarr['sql'])));
	if(empty($paramarr['perpage'])) {
		return array('count'=>1, 'sql'=>$sqlstring, 'multi'=>'');
	}
	
	$listcount = 0;
	$countsql = '';
	if(empty($countsql)) {
		$countsql = getcountsql($sqlstring, 'SELECT\s(.+?)\sFROM\s(.+?)\sWHERE\s(.+?)\sORDER', 2, 3);
	}
	if(empty($countsql)) {
		$countsql = getcountsql($sqlstring, 'SELECT\s(.+?)\sFROM\s(.+?)\sWHERE\s(.+?)\sLIMIT', 2, 3);
	}
	if(empty($countsql)) {
		$countsql = getcountsql($sqlstring, 'SELECT\s(.+?)\sFROM\s(.+?)\sWHERE\s(.+?)$', 2, 3);
	}
	if(empty($countsql)) {
		$countsql = getcountsql($sqlstring, 'SELECT\s(.+?)\sFROM\s(.+?)\sORDER', 2, -1);
	}
	if(empty($countsql)) {
		$countsql = getcountsql($sqlstring, 'SELECT\s(.+?)\sFROM\s(.+?)\sLIMIT', 2, -1);
	}
	if(empty($countsql)) {
		$countsql = getcountsql($sqlstring, 'SELECT\s(.+?)\sFROM\s(.+?)$', 2, -1);
	}
	if(!empty($countsql)) {
		$query = $_SGLOBAL['db']->query($countsql);
		$listcount = $_SGLOBAL['db']->result($query, 0);
		if($listcount) {
			//ҳ��
			$start = ($_GET['page']-1)*$paramarr['perpage'];
			//ҳ��url
			$urlplus = array();
			foreach ($_GET as $key => $value) {
				if($key != 'page') $urlplus[] = rawurlencode($key).'='.rawurlencode($value);
			}
			$mpurl = $_SERVER['PHP_SELF'].(empty($urlplus)?'':'?'.implode('&', $urlplus));
			
			//�ж�ҳ���Ƿ񳬳���Χ
			if($start >= $listcount) {
				showmessage('page_number_is_beyond', $mpurl, 0);
			}
			//��ҳ����
			$multi = multi($listcount, $paramarr['perpage'], $_GET['page'], $mpurl);
			//SQL��
			$sqlstring = preg_replace("/ LIMIT(.+?)$/is", '', $sqlstring);
			$sqlstring .= ' LIMIT '.$start.','.$paramarr['perpage'];
		}
	}

	return array('count'=>$listcount, 'sql'=>$sqlstring, 'multi'=>$multi);
}

function mksqltime($time) {
	global $_SGLOBAL;

	return $_SGLOBAL['timestamp']-$time;
}

?>