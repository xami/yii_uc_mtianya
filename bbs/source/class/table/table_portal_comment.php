<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_portal_comment.php 28041 2012-02-21 07:33:55Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_portal_comment extends discuz_table
{
	public function __construct() {

		$this->_table = 'portal_comment';
		$this->_pk    = 'cid';

		parent::__construct();
	}

	public function fetch_all_by_id_idtype($id, $idtype = '', $orderby = '', $ordersc = 'DESC', $start = 0, $limit = 0) {
		if(!$id) {
			return null;
		}
		$sql = array(DB::field('id', $id));
		if($idtype) {
			$sql[] = DB::field('idtype', $idtype);
		}
		$wheresql = implode(' AND ', $sql);
		if($orderby = DB::order($orderby, $ordersc)) {
			$wheresql .= ' ORDER BY '.$orderby;
		}
		if($limit) {
			$wheresql .= DB::limit($start, $limit);
		}
		return DB::fetch_all('SELECT * FROM %t WHERE %item', array($this->_table, $wheresql));
	}

	public function count_by_id_idtype($id, $idtype) {
		if(!$id || !$idtype) {
			return null;
		}
		$sql = DB::field('id', $id).' AND '.DB::field('idtype', $idtype);
		return DB::result_first('SELECT count(*) FROM %t WHERE %item', array($this->_table, $sql));
	}

	public function delete_by_id_idtype($id, $idtype) {
		if(!$id) {
			return null;
		}
		$para = DB::field('id', $id);
		if($idtype) {
			$para .= ' AND '.DB::field('idtype', $idtype);
		}
		return DB::delete($this->_table, $para);
	}

	public function count_all_by_search($aid, $authorid, $starttime, $endtime, $idtype, $message) {
		return $this->fetch_all_by_search($aid, $authorid, $starttime, $endtime, $idtype, $message, 0, 0, 2);
	}

	public function fetch_all_by_search($aid, $authorid, $starttime, $endtime, $idtype, $message, $start = 0, $limit = 0, $type = 1) {
		$idtype = in_array($idtype, array('aid', 'topicid')) ? $idtype : 'aid';
		$tablename = $idtype == 'aid' ? 'portal_article_title' : 'portal_topic';

		$sql = '';
		$sql .= $aid ? ' AND channel.'.DB::field('id', $aid) : '';
		$sql .= $authorid ? ' AND channel.'.DB::field('uid', $authorid) : '';
		$sql .= $starttime ? ' AND channel.'.DB::field('dateline', $starttime, '>') : '';
		$sql .= $endtime ? ' AND channel.'.DB::field('dateline', $endtime, '<') : '';

		if($message != '') {
			$sqlmessage = '';
			$or = '';
			$message = daddslashes($message);
			$message = explode(',', str_replace(' ', '', $message));

			for($i = 0; $i < count($message); $i++) {
				if(preg_match("/\{(\d+)\}/", $message[$i])) {
					$message[$i] = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($message[$i], '/'));
					$message .= " $or channel.message REGEXP '".$message[$i]."'";
				} else {
					$sqlmessage .= " $or channel.message LIKE '%".$message[$i]."%'";
				}
				$or = 'OR';
			}
			if($sqlmessage) {
				$sql .= " AND ($sqlmessage)";
			}
		}
		if($type == 2) {
			return DB::result_first('SELECT count(*) FROM %t channel WHERE 1 %item', array($this->_table, $sql));
		} else {
			return DB::fetch_all('SELECT channel.*, a.title FROM %t channel LEFT JOIN %t a ON a.`'.$idtype.'`=channel.id WHERE 1 %item ORDER BY channel.dateline DESC %item', array($this->_table, $tablename, $sql, DB::limit($start, $limit)));
		}
	}

}

?>