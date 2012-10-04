<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_blog.php 9233 2008-10-28 06:21:44Z liguode $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//Ȩ��
if(!checkperm('manageevent')) {
	cpmessage('no_authority_management_operation');
}

// ��ȡ�������Ϣ
if(!@include_once(S_ROOT.'./data/data_eventclass.php')) {
	include_once(S_ROOT.'./source/function_cache.php');
	eventclass_cache();
}

if(submitcheck('opsubmit')) {
	$grademap = array("verify"=>1, "delayverify"=>-1, "close"=>-2, "open"=>1, "recommend"=>2, "unrecommend"=>1);
	if($_POST['optype'] == 'delete') {
		include_once(S_ROOT.'./source/function_delete.php');
		if(!empty($_POST['ids']) && deleteevents($_POST['ids'])) {
			cpmessage('do_success', $_POST['mpurl']);
		} else {
			cpmessage('choose_to_delete_the_columns_event'); // ��ѡ��Ҫɾ��Ļ
		}
	} elseif(isset($grademap[$_POST['optype']])) {
		$grade = $grademap[$_POST['optype']];
		if(!empty($_POST['ids']) && verifyevents($_POST['ids'], $grade)) {
			cpmessage('do_success', $_POST['mpurl']);
		} else {
			cpmessage('choose_to_grade_the_columns_event'); // ��ѡ��Ҫ���õĻ״̬
		}
	} else {
		cpmessage('choice_batch_action');
	}
}

if(empty($_GET['op'])) {
	// ����
	
	$mpurl = 'admincp.php?ac=event';

	//��������
	$intkeys = array('eventid', 'uid', 'public', 'grade', "classid");
	$strkeys = array('province', 'city');
	$randkeys = array(array('intval','hot'));
	$likekeys = array('title');
	$results = getwheres($intkeys, $strkeys, $randkeys, $likekeys);
	$wherearr = $results['wherearr'];
	if($_GET['starttime']){
		$wherearr[] = "starttime >= " . sstrtotime($_GET['starttime']);
		$mpurl .= '&starttime=' . $_GET['starttime'];
	}
	if($_GET['endtime']){
		$wherearr[] = "starttime <= " . sstrtotime($_GET['endtime']);
		$mpurl .= '&endtime=' . $_GET['endtime'];
	}
	if($_GET['over']==1){
		$wherearr[] = "endtime < '$_SGLOBAL[timestamp]'";
		$mpurl .= '&over=1';
	} elseif($_GET['over']==='0') {
		$wherearr[] = "endtime >= '$_SGLOBAL[timestamp]'";
		$mpurl .= '&over=1';
	}

	$wheresql = empty($wherearr)?'1':implode(' AND ', $wherearr);
	$mpurl .= '&'.implode('&', $results['urls']);
	
	//����
	if(strlen($_GET['grade']) && $_GET['grade'] == 0) {
		$actives = array('grade0' => ' class="active"');
	} elseif ($_GET['grade'] == -1) {
		$actives = array('grade_1' => ' class="active"');
	} elseif ($_GET['grade'] == 1) {
		$actives = array('grade1' => ' class="active"');
	} elseif ($_GET['grade'] == -2) {
		$actives = array('grade_2' => ' class="active"');
	} elseif ($_GET['grade'] == 2) {
		$actives = array('grade2' => ' class="active"');
	} else {
		$actives = array('all' => ' class="active"');
	}

	//����
	$orders = getorders(array('dateline', 'starttime', 'membernum', 'hot'), 'eventid');
	$ordersql = $orders['sql'];
	if($orders['urls']) $mpurl .= '&'.implode('&', $orders['urls']);
	$orderby = array($_GET['orderby']=>' selected');
	$ordersc = array($_GET['ordersc']=>' selected');

	//��ʾ��ҳ
	$perpage = empty($_GET['perpage'])?0:intval($_GET['perpage']);
	if(!in_array($perpage, array(20,50,100, 1000))) $perpage = 20;
	$mpurl .= '&perpage='.$perpage;
	$perpages = array($perpage => ' selected');

	$page = empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	//��鿪ʼ��
	ckstart($start, $perpage);
	$managebatch = checkperm('managebatch');
	$allowbatch = true;
	$list = array();
	$multi = '';

	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('event')." WHERE $wheresql"), 0);
	if($count) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('event')." WHERE $wheresql $ordersql LIMIT $start,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$list[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid']) {
				$allowbatch = false;
			}
		}
		$multi = multi($count, $perpage, $page, $mpurl);
	}
}

//���û״̬����ˣ��Ƽ����ر�
function verifyevents($eventids, $grade){
	global $_SGLOBAL;

	$allowmanage = checkperm('manageevent');
	$managebatch = checkperm('managebatch');
	$opnum = 0;
	$eventarr = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("event")." WHERE eventid IN (".simplode($eventids).")");
	while($value=$_SGLOBAL['db']->fetch_array($query)){
	    if($allowmanage && !$managebatch) {
	    	$opnum++;
	    }
	}
	
	if(!$allowmanage || (!$managebatch && $opnum > 1)) return array();
	
	$grade = intval($grade);
	if(!in_array($grade, array(-2,-1,1,2))){
		cpmessage('bad_event_grade');// ����Ļ״̬
	}
	
	$newids = $events = $actions = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('event')." WHERE eventid IN (".simplode($eventids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if($grade == $value['grade']){
			continue;
		}
		$newids[] = $value['eventid'];
		$events[$value['eventid']] = $value;
		
		if($grade == -1){			
			$actions[$value['eventid']] = "unverify";//δͨ�����			
		} elseif($grade == 1) {			
			if($value['grade'] == -2){
				$actions[$value['eventid']] = "open";// ����
			} elseif($value['grade'] < 1) {
				$actions[$value['eventid']] = "verify";// ͨ�����				
			} elseif($value['grade'] == 2) {
				$actions[$value['eventid']] = "unrecommend"; // ȡ���Ƽ�		
			}
		} elseif($grade == 2) {
			//�Ƽ�������
			getreward('recommendevent', 1, $value['uid'], '', 0);
			$actions[$value['eventid']] = "recommend";//�Ƽ�
		} elseif($grade == -2){
			$actions[$value['eventid']] = "close";//�ر�
		}
	}
	
	if(empty($newids)) return array();
	
	@include_once(S_ROOT.'./data/data_eventclass.php');
	
	$noteids = $note_inserts = array();
	$feed_inserts = array();	
	foreach($newids as $id){
		$event = $events[$id];
		if($grade >= 1 && $events[$id]['grade'] < 1 && $events[$id]['grade'] >= -1 ){// feed:�����
			$poster = "";
			if(empty($event['poster'])){
				$poster = $_SGLOBAL['eventclass'][$event['classid']]['poster'];							
			} else {
				$poster = pic_get($event['poster'], $event['thumb'], $event['remote']);
			}
			$feedarr = array(
				'appid' => UC_APPID,
				'icon' => 'event',
				'uid' => $event['uid'],
				'username' => $event['username'],
				'dateline' => $_SGLOBAL['timestamp'],
				'title_template' => cplang('event_add'), 
				'title_data' => array('eventid'=>$id, 'title'=>$event['title']),
				'body_template' => cplang('event_feed_info'),
				'body_data' => array("eventid"=>$id, "title"=>$event['title'], "username"=>$event['username'], 'starttime'=>sgmdate('m-d H:item', $event['starttime']), 'endtime'=>sgmdate('m-d H:item', $event['endtime']), 'province'=>$event['province'], 'city'=>$event['city'], 'location'=>$event['location']),
				'body_general' => '',
				'image_1' => $poster,
				'image_1_link' => 'space.php?do=event&id='.$id,
				'image_2' => '',
				'image_2_link' => '',
				'image_3' => '',
				'image_3_link' => '',
				'image_4' => '',
				'image_4_link' => '',
				'target_ids' => '',
				'friend' => ''
			);
			$feedarr = sstripslashes($feedarr);//ȥ��ת��
			$feedarr['title_data'] = serialize(sstripslashes($feedarr['title_data']));//����ת��
			$feedarr['body_data'] = serialize(sstripslashes($feedarr['body_data']));//����ת��
			$feedarr['hash_template'] = md5($feedarr['title_template']."\t".$feedarr['body_template']);//ϲ��hash
			$feedarr['hash_data'] = md5($feedarr['title_template']."\t".$feedarr['title_data']."\t".$feedarr['body_template']."\t".$feedarr['body_data']);//�ϲ�hash
			$feedarr = saddslashes($feedarr);//����ת��
			$feed_inserts[] = "('$feedarr[appid]', 'event', '$feedarr[uid]', '$feedarr[username]', '$feedarr[dateline]', '0', '$feedarr[hash_template]', '$feedarr[hash_data]', '$feedarr[title_template]', '$feedarr[title_data]', '$feedarr[body_template]', '$feedarr[body_data]', '$feedarr[body_general]', '$feedarr[image_1]', '$feedarr[image_1_link]', '$feedarr[image_2]', '$feedarr[image_2_link]', '$feedarr[image_3]', '$feedarr[image_3_link]', '$feedarr[image_4]', '$feedarr[image_4_link]', '', '$id', 'eventid')";			
		}
		if($event['uid'] != $_SGLOBAL['supe_uid']){// �Լ��Ĳ�����֪ͨ			
			$noteids[] = $event[uid];
			$note_msg = cplang('event_set_'.$actions[$id], array("space.php?do=event&id=".$event['eventid'], $event['title']));
			$note_inserts[] = "('$event[uid]', 'system', '1', '0', '', '".addslashes($note_msg)."', '$_SGLOBAL[timestamp]')";
		}
	}
	
	unset($events);
	//�޸�״̬
	if($grade == 2){// ��Ҫͬʱ�޸��Ƽ�ʱ��
		$_SGLOBAL['db']->query("UPDATE ".tname("event")." SET grade='$grade', recommendtime='$_SGLOBAL[timestamp]' WHERE eventid IN (" . simplode($newids).")");
	} else {
		$_SGLOBAL['db']->query("UPDATE ".tname("event")." SET grade='$grade' WHERE eventid IN (" . simplode($newids).")");
	}
	//֪ͨ
	if($note_inserts){
		$_SGLOBAL['db']->query("INSERT INTO ".tname('notification')." (`uid`, `type`, `new`, `authorid`, `author`, `note`, `dateline`) VALUES ".implode(',', $note_inserts));
		$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET notenum=notenum+1 WHERE uid IN (".simplode($noteids).")");
	}
	//Feed
	if($feed_inserts){
		$_SGLOBAL['db']->query("INSERT INTO ".tname('feed')." (`appid` ,`icon` ,`uid` ,`username` ,`dateline` ,`friend` ,`hash_template` ,`hash_data` ,`title_template` ,`title_data` ,`body_template` ,`body_data` ,`body_general` ,`image_1` ,`image_1_link` ,`image_2` ,`image_2_link` ,`image_3` ,`image_3_link` ,`image_4` ,`image_4_link` ,`target_ids` ,`id` ,`idtype`) VALUES ".implode(',', $feed_inserts));
	}
	
	return $newids;
}


?>