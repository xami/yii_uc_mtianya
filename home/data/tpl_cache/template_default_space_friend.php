<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template/default/space_friend|template/default/header|template/default/space_menu|template/default/space_list|template/default/footer', '1348305838', 'template/default/space_friend');?><?php $_TPL['titles'] = array('好友'); ?>
<?php if(empty($_SGLOBAL['inajax'])) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?=$_SC['charset']?>" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<title><?php if($_TPL['titles']) { ?><?php if(is_array($_TPL['titles'])) { foreach($_TPL['titles'] as $value) { ?><?php if($value) { ?><?=$value?> - <?php } ?><?php } } ?><?php } ?><?php if($_SN[$space['uid']]) { ?><?=$_SN[$space['uid']]?> - <?php } ?><?=$_SCONFIG['sitename']?> - Powered by UCenter Home</title>
<script language="javascript" type="text/javascript" src="source/script_cookie.js"></script>
<script language="javascript" type="text/javascript" src="source/script_common.js"></script>
<script language="javascript" type="text/javascript" src="source/script_menu.js"></script>
<script language="javascript" type="text/javascript" src="source/script_ajax.js"></script>
<script language="javascript" type="text/javascript" src="source/script_face.js"></script>
<script language="javascript" type="text/javascript" src="source/script_manage.js"></script>
<style type="text/css">
@import url(template/default/style.css);
<?php if($_TPL['css']) { ?>
@import url(template/default/<?=$_TPL['css']?>.css);
<?php } ?>
<?php if(!empty($_SGLOBAL['space_theme'])) { ?>
@import url(theme/<?=$_SGLOBAL['space_theme']?>/style.css);
<?php } elseif($_SCONFIG['template'] != 'default') { ?>
@import url(template/<?=$_SCONFIG['template']?>/style.css);
<?php } ?>
<?php if(!empty($_SGLOBAL['space_css'])) { ?>
<?=$_SGLOBAL['space_css']?>
<?php } ?>
</style>
<link rel="shortcut icon" href="image/favicon.ico" />
<link rel="edituri" type="application/rsd+xml" title="rsd" href="xmlrpc.php?rsd=<?=$space['uid']?>" />
</head>
<body>

<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div id="header">
<?php if($_SGLOBAL['ad']['header']) { ?><div id="ad_header"><?php adshow('header'); ?></div><?php } ?>
<div class="headerwarp">
<h1 class="logo"><a href="index.php"><img src="template/<?=$_SCONFIG['template']?>/image/logo.gif" alt="<?=$_SCONFIG['sitename']?>" /></a></h1>
<ul class="menu">
<?php if($_SGLOBAL['supe_uid']) { ?>
<li><a href="space.php?do=home">首页</a></li>
<li><a href="space.php">个人主页</a></li>
<li><a href="space.php?do=friend">好友</a></li>
<li><a href="network.php">随便看看</a></li>
<?php } else { ?>
<li><a href="index.php">首页</a></li>
<?php } ?>

<?php if($_SGLOBAL['appmenu']) { ?>
<?php if($_SGLOBAL['appmenus']) { ?>
<li class="dropmenu" id="ucappmenu" onclick="showMenu(this.id)">
<a href="javascript:;">站内导航</a>
</li>
<?php } else { ?>
<li><a target="_blank" href="<?=$_SGLOBAL['appmenu']['url']?>" title="<?=$_SGLOBAL['appmenu']['name']?>"><?=$_SGLOBAL['appmenu']['name']?></a></li>
<?php } ?>
<?php } ?>

<?php if($_SGLOBAL['supe_uid']) { ?>
<li><a href="space.php?do=pm<?php if(!empty($_SGLOBAL['member']['newpm'])) { ?>&filter=newpm<?php } ?>">消息<?php if(!empty($_SGLOBAL['member']['newpm'])) { ?>(新)<?php } ?></a></li>
<?php if($_SGLOBAL['member']['allnotenum']) { ?><li class="notify" id="membernotemenu" onmouseover="showMenu(this.id)"><a href="space.php?do=notice"><?=$_SGLOBAL['member']['allnotenum']?>个提醒</a></li><?php } ?>
<?php } else { ?>
<li><a href="help.php">帮助</a></li>
<?php } ?>
</ul>

<div class="nav_account">
<?php if($_SGLOBAL['supe_uid']) { ?>
<a href="space.php?uid=<?=$_SGLOBAL['supe_uid']?>" class="login_thumb"><?php echo avatar($_SGLOBAL[supe_uid]); ?></a>
<a href="space.php?uid=<?=$_SGLOBAL['supe_uid']?>" class="loginName"><?=$_SN[$_SGLOBAL['supe_uid']]?></a>
<?php if($_SGLOBAL['member']['credit']) { ?>
<a href="cp.php?ac=credit" style="font-size:11px;padding:0 0 0 5px;"><img src="image/credit.gif"><?=$_SGLOBAL['member']['credit']?></a>
<?php } ?>
<br />
<?php if(empty($_SCONFIG['closeinvite'])) { ?>
<a href="cp.php?ac=invite">邀请</a> 
<?php } ?>
<a href="cp.php?ac=task">任务</a> 
<a href="cp.php?ac=magic">道具</a>
<a href="cp.php">设置</a> 
<a href="cp.php?ac=common&op=logout&uhash=<?=$_SGLOBAL['uhash']?>">退出</a>
<?php } else { ?>
<a href="do.php?ac=<?=$_SCONFIG['register_action']?>" class="login_thumb"><?php echo avatar($_SGLOBAL[supe_uid]); ?></a>
欢迎您<br>
<a href="do.php?ac=<?=$_SCONFIG['login_action']?>">登录</a> | 
<a href="do.php?ac=<?=$_SCONFIG['register_action']?>">注册</a>
<?php } ?>
</div>
</div>
</div>

<div id="wrap">

<?php if(empty($_TPL['nosidebar'])) { ?>
<div id="main">
<div id="app_sidebar">
<?php if($_SGLOBAL['supe_uid']) { ?>
<ul class="app_list" id="default_userapp">
<li><img src="image/app/doing.gif"><a href="space.php?do=doing">记录</a></li>
<li><img src="image/app/album.gif"><a href="space.php?do=album">相册</a><em><a href="cp.php?ac=upload" class="gray">上传</a></em></li>
<li><img src="image/app/blog.gif"><a href="space.php?do=blog">日志</a><em><a href="cp.php?ac=blog" class="gray">发表</a></em></li>
<li><img src="image/app/poll.gif"/><a href="space.php?do=poll">投票</a><em><a href="cp.php?ac=poll" class="gray">发起</a></em></li>
<li><img src="image/app/mtag.gif"><a href="space.php?do=mtag">群组</a><em><a href="cp.php?ac=thread" class="gray">话题</a></em></li>
<li><img src="image/app/event.gif"/><a href="space.php?do=event">活动</a><em><a href="cp.php?ac=event" class="gray">发起</a></em></li>
<li><img src="image/app/share.gif"><a href="space.php?do=share">分享</a></li>
<li><img src="image/app/topic.gif"><a href="space.php?do=topic">热闹</a></li>
</ul>

<ul class="app_list topline" id="my_defaultapp">
<?php if($_SCONFIG['my_status']) { ?>
<?php if(is_array($_SGLOBAL['userapp'])) { foreach($_SGLOBAL['userapp'] as $value) { ?>
<li><img src="http://appicon.manyou.com/icons/<?=$value['appid']?>"><a href="userapp.php?id=<?=$value['appid']?>"><?=$value['appname']?></a></li>
<?php } } ?>
<?php } ?>
</ul>

<?php if($_SCONFIG['my_status']) { ?>
<ul class="app_list topline" id="my_userapp">
<?php if(is_array($_SGLOBAL['my_menu'])) { foreach($_SGLOBAL['my_menu'] as $value) { ?>
<li id="userapp_li_<?=$value['appid']?>"><img src="http://appicon.manyou.com/icons/<?=$value['appid']?>"><a href="userapp.php?id=<?=$value['appid']?>" title="<?=$value['appname']?>"><?=$value['appname']?></a></li>
<?php } } ?>
</ul>
<?php } ?>

<?php if($_SGLOBAL['my_menu_more']) { ?>
<p class="app_more"><a href="javascript:;" id="a_app_more" onclick="userapp_open();" class="off">展开</a></p>
<?php } ?>

<?php if($_SCONFIG['my_status']) { ?>
<div class="app_m">
<ul>
<li><img src="image/app_add.gif"><a href="cp.php?ac=userapp&my_suffix=%2Fapp%2Flist" class="addApp">添加应用</a></li>
<li><img src="image/app_set.gif"><a href="cp.php?ac=userapp&op=menu" class="myApp">管理应用</a></li>
</ul>
</div>
<?php } ?>

<?php } else { ?>
<div class="bar_text">
<form id="loginform" name="loginform" action="do.php?ac=<?=$_SCONFIG['login_action']?>&ref" method="post">
<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
<p class="title">登录站点</p>
<p>用户名</p>
<p><input type="text" name="username" id="username" class="t_input" size="15" value="" /></p>
<p>密码</p>
<p><input type="password" name="password" id="password" class="t_input" size="15" value="" /></p>
<p><input type="checkbox" id="cookietime" name="cookietime" value="315360000" checked /><label for="cookietime">记住我</label></p>
<p>
<input type="submit" id="loginsubmit" name="loginsubmit" value="登录" class="submit" />
<input type="button" name="regbutton" value="注册" class="button" onclick="urlto('do.php?ac=<?=$_SCONFIG['register_action']?>');">
</p>
</form>
</div>
<?php } ?>
</div>

<div id="mainarea">

<?php if($_SGLOBAL['ad']['contenttop']) { ?><div id="ad_contenttop"><?php adshow('contenttop'); ?></div><?php } ?>
<?php } ?>

<?php } ?>


<?php if(!empty($_SGLOBAL['inajax'])) { ?>
<div id="space_friend">
<h3 class="feed_header">
<a href="cp.php?ac=friend&op=search" class="r_option" target="_blank">寻找好友</a>
好友(共 <?=$count?> 个)
</h3><br>

<?php if($list) { ?>
<div id="friend_ul">
<ul class="line_list">
<?php if(is_array($list)) { foreach($list as $key => $value) { ?>
<li>
<table width="100%">
<tr>
<td width="70">
<div class="avatar48"><a href="space.php?uid=<?=$value['uid']?>"><?php echo avatar($value[uid],small); ?></a></div>
</td>
<td>
<div class="thumbTitle"><p<?php if($ols[$value['uid']]) { ?> class="online_icon_p"<?php } ?>><a href="space.php?uid=<?=$value['uid']?>"<?php g_color($value[groupid]); ?>><?=$_SN[$value['uid']]?></a> <?php g_icon($value[groupid]); ?></p></div>

<?php if($value['note']) { ?><div><?=$value['note']?></div><?php } ?>

<?php if($ols[$value['uid']]) { ?><div class="gray"><?php echo sgmdate('H:item',$ols[$value[uid]],1); ?></div><?php } ?>
<div class="setti">

<?php if(!$value['isfriend']) { ?>
<a href="cp.php?ac=friend&op=add&uid=<?=$value['uid']?>" id="a_friend_<?=$key?>" onclick="ajaxmenu(event, this.id, 1)">加为好友</a>
<?php } ?>
</div>
</td></tr></table>
</li>
<?php } } ?>
</ul>
</div>
<div class="page"><?=$multi?></div>

<?php } else { ?>
<div class="c_form">
没有相关用户列表。
</div>
<?php } ?>
</div><br />

<?php } else { ?>

<?php if($space['self']) { ?>
<div class="searchbar floatright">
<?php if($_GET['view']=='me') { ?>
<form method="get" action="space.php">
<input type="hidden" name="do" value="friend">
<input name="searchkey" value="" size="15" class="t_input" type="text">
<input name="searchsubmit" value="找好友" class="submit" type="submit">
<input type="hidden" name="searchmode" value="1" />
</form>
<?php } else { ?>
<form method="get" action="cp.php">
<input type="hidden" name="ac" value="friend" />
<input type="hidden" name="op" value="search" />
<input name="searchkey" value="" size="15" class="t_input" type="text">
<input name="searchsubmit" value="找人" class="submit" type="submit">
<input type="hidden" name="searchmode" value="1" />
</form>
<?php } ?>
</div>
<h2 class="title"><img src="image/icon/friend.gif" />好友</h2>
<div class="tabs_header">
<ul class="tabs">
<li<?=$actives['me']?>><a href="space.php?uid=<?=$space['uid']?>&do=friend"><span>好友列表</span></a></li>
<li><a href="cp.php?ac=friend&op=search"><span>查找好友</span></a></li>
<li><a href="cp.php?ac=friend&op=find"><span>可能认识的人</span></a></li>
<?php if(empty($_SCONFIG['closeinvite'])) { ?>
<li><a href="cp.php?ac=invite"><span>邀请好友</span></a></li>
<?php } ?>
<li><a href="cp.php?ac=friend&op=request"><span>好友请求</span></a></li>
<li><a href="space.php?do=top"><span>排行榜</span></a></li>
</ul>
</div>
<div id="content" style="width: 640px;">

<div class="c_mgs"><div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
<?php if($_GET['view']=='blacklist') { ?>
加入到黑名单的用户，将会从您的好友列表中删除。同时，对方将不能进行与您相关的打招呼、踩日志、加好友、评论、留言、短消息等互动行为。
<?php } elseif($_GET['view']=='me') { ?>

当前共有 <?=$space['friendnum']?> 个好友。


<?php if($maxfriendnum) { ?>
(最多可以添加 <?=$maxfriendnum?> 个好友)
<p>
<?php if($_SGLOBAL['magic']['friendnum']) { ?>
<img src="image/magic/friendnum.small.gif" class="magicicon" />
<a id="a_magic_friendnum" href="magic.php?mid=friendnum" onclick="ajaxmenu(event, this.id, 1)">我要扩容好友数</a>
(您可以购买道具“<?=$_SGLOBAL['magic']['friendnum']?>”来扩容，让自己可以添加更多的好友。)
<?php } ?>
</p>
<?php } ?>

<p style="padding-top:10px;">
好友列表按照好友热度高低排序<br>
好友热度是系统根据您与好友之间交互的动作自动累计的一个参考值，数值越大，表示您与这位好友互动越频繁。
</p>
<?php } elseif($_GET['view']=='online') { ?>
<?php if($_GET['type'] == 'friend') { ?>
这些好友当前在线，赶快去拜访一下吧
<?php } elseif($_GET['type']=='near') { ?>
通过系统匹配，这些朋友就在您附近，您可能认识他们
<?php } else { ?>
显示当前全部在线的用户
<?php } ?>
<?php } elseif($_GET['view']=='visitor') { ?>
他们拜访过您，回访一下吧
<?php } elseif($_GET['view']=='trace') { ?>
您曾经拜访过的用户列表
<?php } ?>
</div></div></div></div></div>

<?php if($_GET['view']=='blacklist') { ?>
<div class="h_status">
<h2>添加新黑名单用户</h2>
<form method="post" name="blackform" action="cp.php?ac=friend&op=blacklist&start=<?=$_GET['start']?>">
用户名：<input type="text" name="username" value="" size="15" class="t_input">
<input type="submit" name="blacklistsubmit_btn" id="moodsubmit_btn" value="添加" class="submit">
<input type="hidden" name="blacklistsubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
</form>
</div>
<?php } ?>

<?php if($list) { ?>
<div class="thumb_list" id="friend_ul">
<ul>
<?php if(is_array($list)) { foreach($list as $key => $value) { ?>
<li id="friend_<?=$value['uid']?>_li">
<?php if($value['username'] == '') { ?>
<div class="avatar48"><img src="image/magic/hidden.gif" alt="匿名" /></div>
<div class="thumbTitle"><p>匿名</p></div>
<?php } else { ?>
<div class="avatar48"><a href="space.php?uid=<?=$value['uid']?>"><?php echo avatar($value[uid],small); ?></a></div>
<div class="thumbTitle">
<p<?php if($ols[$value['uid']]) { ?> class="online_icon_p"<?php } ?>>
<a href="space.php?uid=<?=$value['uid']?>"<?php g_color($value[groupid]); ?>><?=$_SN[$value['uid']]?></a> 
<?php g_icon($value[groupid]); ?>
<?php if($value['videostatus']) { ?>
<img src="image/videophoto.gif" align="absmiddle">
<?php } ?>
</p></div>
<?php if($value['note']) { ?><div><?=$value['note']?></div><?php } ?>
<?php } ?>

<?php if($_GET['view']=='blacklist') { ?>
<div class="gray"><a href="cp.php?ac=friend&op=blacklist&subop=delete&uid=<?=$value['uid']?>&start=<?=$_GET['start']?>">黑名单除名</a></div>
<?php } elseif($_GET['view']=='visitor' || $_GET['view'] == 'trace') { ?>
<div class="gray"><?php echo sgmdate('n月j号',$value[dateline],1); ?></div>
<?php } elseif($_GET['view']=='online') { ?>
<div class="gray"><?php echo sgmdate('H:item',$ols[$value[uid]],1); ?></div>
<?php } else { ?>
<?php if($ols[$value['uid']]) { ?><div class="gray"><?php echo sgmdate('H:item',$ols[$value[uid]],1); ?></div><?php } ?>
<div class="gray">
<?php if($value['num']) { ?><a href="cp.php?ac=friend&op=changenum&uid=<?=$value['uid']?>" id="friendnum_<?=$value['uid']?>" onclick="ajaxmenu(event, this.id)">热度(<span id="spannum_<?=$value['uid']?>"><?=$value['num']?></span>)</a><span class="pipe">|</span><?php } ?>
<?php if(!$value['isfriend']) { ?>
<a href="cp.php?ac=friend&op=add&uid=<?=$value['uid']?>" id="a_friend_<?=$key?>" onclick="ajaxmenu(event, this.id, 1)">加为好友</a>
<?php } else { ?>
<a href="cp.php?ac=friend&op=changegroup&uid=<?=$value['uid']?>" id="friend_group_<?=$value['uid']?>" onclick="ajaxmenu(event, this.id)">分组</a><span class="pipe">|</span>
<a href="cp.php?ac=friend&op=ignore&uid=<?=$value['uid']?>" id="a_ignore_<?=$key?>" onclick="ajaxmenu(event, this.id)">删除</a>
<?php } ?>
</div>
<?php } ?>
</li>
<?php } } ?>
</ul>
</div>
<div class="page"><?=$multi?></div>

<?php } else { ?>
<div class="c_form">
没有相关用户列表。
</div>
<?php } ?>

</div>

<div id="sidebar" style="width: 150px;">
<?php if($_SCONFIG['my_status']) { ?>
<!-- 同步好友至Manyou 开始 -->
<script type="text/javascript">
function my_sync_tip(msg, close_time) {;
var my_tip = document.getElementById("my_tip");
if (!my_tip) {
my_tip = document.createElement("div");
document.getElementsByTagName("body")[0].appendChild(my_tip);
my_tip.id = "my_tip";
}
my_tip.style.display = 'block';
my_tip.innerHTML = '<div class="popupmenu_centerbox" style="position: absolute; top: 200px; right: 500px; padding: 20px; width: 300px; height: 15px; z-index:9999;">' + msg + '</div>';
if (close_time) {
setTimeout("document.getElementById('my_tip').style.display = 'none';", close_time);
}
}
function my_sync_friend() {
my_sync_tip('正在同步好友信息...', 0);
var my_scri = document.createElement("script");
document.getElementsByTagName("head")[0].appendChild(my_scri);
my_scri.charset = "UTF-8";
my_scri.src = "http://uchome.manyou.com/user/syncFriends?sId=<?=$_SCONFIG['my_siteid']?>&uUchId=<?=$space['uid']?>&ts=<?=$_SGLOBAL['timestamp']?>&key=<?php echo md5($_SCONFIG[my_siteid] . $_SCONFIG[my_sitekey] . $space[uid] . $_SGLOBAL[timestamp]); ?>";
}
</script>

<div class="c_mgs"><div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
<p>在游戏中找不到自己的好友？请点击下面的的按钮，将好友信息同步到里面。</p>
<p style="text-align: center;padding: 20px 0 0;"> <a href="#" onclick="my_sync_friend(); return false;" title="将好友关系同步至Manyou平台，以便在应用里看到他们"><img alt="刷新好友信息" src="image/syncfriend.gif"/></a> </p>
</div></div></div></div></div>
<!-- 同步好友至Manyou 结束 -->
<?php } ?>

<div class="cat">
<h3>
好友菜单
</h3>
<ul>
<li<?=$a_actives['me']?>><a href="space.php?uid=<?=$space['uid']?>&do=friend">全部好友列表</a></li>
<li<?=$a_actives['onlinefriend']?>><a href="space.php?uid=<?=$space['uid']?>&do=friend&view=online&type=friend">当前在线的好友</a></li>
<li<?=$a_actives['onlinenear']?>><a href="space.php?uid=<?=$space['uid']?>&do=friend&view=online&type=near">就在我附近的朋友</a></li>
<li<?=$a_actives['visitor']?>><a href="space.php?uid=<?=$space['uid']?>&do=friend&view=visitor">我的访客</a></li>
<li<?=$a_actives['trace']?>><a href="space.php?uid=<?=$space['uid']?>&do=friend&view=trace">我的足迹</a></li>
<li<?=$a_actives['blacklist']?>><a href="space.php?uid=<?=$space['uid']?>&do=friend&view=blacklist">我的黑名单</a></li>
</ul>
</div>

<?php if($groups) { ?>
<div class="cat">
<h3>
<span class="r_option"><a href="cp.php?ac=friend&op=group">批量分组</a></span>
好友分组
</h3>
<ul class="post_list line_list">
<li><a href="space.php?do=friend&group=-1">全部好友</a></li>
<?php if(is_array($groups)) { foreach($groups as $key => $value) { ?>
<li<?=$groupselect[$key]?>>
<a href="cp.php?ac=friend&op=groupignore&group=<?=$key?>" id="c_ignore_<?=$key?>" onclick="ajaxmenu(event, this.id)" title="屏蔽用户组动态" class="c_delete">屏蔽</a>
<?php if($key) { ?>
<a href="cp.php?ac=friend&op=groupname&group=<?=$key?>" id="c_edit_<?=$key?>" onclick="ajaxmenu(event, this.id, 1)" title="编辑用户组名" class="c_edit">编辑</a>
<?php } ?>
<?php if(isset($space['privacy']['filter_gid'][$key])) { ?><span class="gray">[屏蔽]</span><?php } ?> <a href="space.php?do=friend&group=<?=$key?>"><span id="friend_groupname_<?=$key?>"><?=$value?></span></a>
</li>
<?php } } ?>
</ul>
</div>
<?php } ?>

</div>


<?php } else { ?>
<?php $_TPL['spacetitle'] = "好友";
	$_TPL['spacemenus'][] = "<a href=\"space.php?uid=$space[uid]&do=friend&view=me\">TA的好友列表</a>"; ?>
<div class="c_header a_header">
<div class="avatar48"><a href="space.php?uid=<?=$space['uid']?>"><?php echo avatar($space[uid],small); ?></a></div>
<?php if($_SGLOBAL['refer']) { ?>
<a class="r_option" href="<?=$_SGLOBAL['refer']?>">&laquo; 返回上一页</a>
<?php } ?>
<p style="font-size:14px"><?=$_SN[$space['uid']]?>的<?=$_TPL['spacetitle']?></p>
<a href="space.php?uid=<?=$space['uid']?>" class="spacelink"><?=$_SN[$space['uid']]?>的主页</a>
<?php if($_TPL['spacemenus']) { ?>
<?php if(is_array($_TPL['spacemenus'])) { foreach($_TPL['spacemenus'] as $value) { ?> <span class="pipe">&raquo;</span> <?=$value?><?php } } ?>
<?php } ?>
</div>

<div class="h_status">共有 <?=$space['friendnum']?> 个好友</div>
<div class="space_list">
<?php if($list) { ?>
<?php if(is_array($list)) { foreach($list as $key => $value) { ?>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="65"><div class="avatar48"><a href="space.php?uid=<?=$value['uid']?>"><?php echo avatar($value[uid],small); ?></a></div></td>
<td>
<h2>
<?php if($ols[$value['uid']]) { ?><img src="image/online_icon.gif" align="absmiddle"> <?php } ?>
<a href="space.php?uid=<?=$value['uid']?>" title="<?=$_SN[$value['uid']]?>"<?php g_color($value[groupid]); ?>><?=$_SN[$value['uid']]?></a>
<?php if($value['username'] && $_SN[$value['uid']]!=$value['username']) { ?><span class="gray">(<?=$value['username']?>)</span><?php } ?>
<?php g_icon($value[groupid]); ?>
<?php if($value['videostatus']) { ?>
<img src="image/videophoto.gif" align="absmiddle">
<?php } ?>
</h2>
<?php if($value['sex']==2) { ?><p>美女</p><?php } elseif($value['sex']==1) { ?><p>帅哥</p><?php } ?></p>
<p>
<?php if($_GET['view']=='show') { ?>竞价<?php } ?>积分：<?=$value['credit']?> / <?php if($value['experience']) { ?>经验：<?=$value['experience']?> / <?php } ?>人气：<?=$value['viewnum']?> / 好友：<?=$value['friendnum']?></p>
<?php if($value['note']) { ?><?=$value['note']?><?php } ?>
</td>
<td width="100">
<ul class="line_list">
<li><a href="space.php?uid=<?=$value['uid']?>">去串个门</a></li>
<li><a href="cp.php?ac=poke&op=send&uid=<?=$value['uid']?>" id="a_poke_<?=$key?>" onclick="ajaxmenu(event, this.id, 1)" title="打招呼">打个招呼</a></li>
<?php if(isset($value['isfriend']) && !$value['isfriend']) { ?><li><a href="cp.php?ac=friend&op=add&uid=<?=$value['uid']?>" id="a_friend_<?=$key?>" onclick="ajaxmenu(event, this.id, 1)" title="加好友">加为好友</a></li><?php } ?>	
</ul>
</td>
</tr>
</table>
<?php } } ?>
<div class="page"><?=$multi?></div>
<?php } else { ?>
<div class="c_form">没有相关成员。</div>
<?php } ?>
</div>



<?php } ?>

<?php } ?>

<?php if(empty($_SGLOBAL['inajax'])) { ?>
<?php if(empty($_TPL['nosidebar'])) { ?>
<?php if($_SGLOBAL['ad']['contentbottom']) { ?><br style="line-height:0;clear:both;"/><div id="ad_contentbottom"><?php adshow('contentbottom'); ?></div><?php } ?>
</div>

<!--/mainarea-->
<div id="bottom"></div>
</div>
<!--/main-->
<?php } ?>

<div id="footer">
<?php if($_TPL['templates']) { ?>
<div class="chostlp" title="切换风格"><img id="chostlp" src="<?=$_TPL['default_template']['icon']?>" onmouseover="showMenu(this.id)" alt="<?=$_TPL['default_template']['name']?>" /></div>
<ul id="chostlp_menu" class="chostlp_drop" style="display: none">
<?php if(is_array($_TPL['templates'])) { foreach($_TPL['templates'] as $value) { ?>
<li><a href="cp.php?ac=common&op=changetpl&name=<?=$value['name']?>" title="<?=$value['name']?>"><img src="<?=$value['icon']?>" alt="<?=$value['name']?>" /></a></li>
<?php } } ?>
</ul>
<?php } ?>

<p class="r_option">
<a href="javascript:;" onclick="window.scrollTo(0,0);" id="a_top" title="TOP"><img src="image/top.gif" alt="" style="padding: 5px 6px 6px;" /></a>
</p>

<?php if($_SGLOBAL['ad']['footer']) { ?>
<p style="padding:5px 0 10px 0;"><?php adshow('footer'); ?></p>
<?php } ?>

<?php if($_SCONFIG['close']) { ?>
<p style="color:blue;font-weight:bold;">
提醒：当前站点处于关闭状态
</p>
<?php } ?>
<p>
<?=$_SCONFIG['sitename']?> - 
<a href="mailto:<?=$_SCONFIG['adminemail']?>">联系我们</a>
<?php if($_SCONFIG['miibeian']) { ?> - <a  href="http://www.miibeian.gov.cn" target="_blank"><?=$_SCONFIG['miibeian']?></a><?php } ?>
</p>
<p>
Powered by <a  href="http://u.discuz.net" target="_blank"><strong>UCenter Home</strong></a> <span title="<?php echo X_RELEASE; ?>"><?php echo X_VER; ?></span>
<?php if(!empty($_SCONFIG['licensed'])) { ?><a  href="http://license.comsenz.com/?pid=7&host=<?=$_SERVER['HTTP_HOST']?>" target="_blank">Licensed</a><?php } ?>
&copy; 2001-2010 <a  href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>
</p>
<?php if($_SCONFIG['debuginfo']) { ?>
<p><?php echo debuginfo(); ?></p>
<?php } ?>
</div>
</div>
<!--/wrap-->

<?php if($_SGLOBAL['appmenu']) { ?>
<ul id="ucappmenu_menu" class="dropmenu_drop" style="display:none;">
<li><a href="<?=$_SGLOBAL['appmenu']['url']?>" title="<?=$_SGLOBAL['appmenu']['name']?>" target="_blank"><?=$_SGLOBAL['appmenu']['name']?></a></li>
<?php if(is_array($_SGLOBAL['appmenus'])) { foreach($_SGLOBAL['appmenus'] as $value) { ?>
<li><a href="<?=$value['url']?>" title="<?=$value['name']?>" target="_blank"><?=$value['name']?></a></li>
<?php } } ?>
</ul>
<?php } ?>

<?php if($_SGLOBAL['supe_uid']) { ?>
<ul id="membernotemenu_menu" class="dropmenu_drop" style="display:none;">
<?php $member = $_SGLOBAL['member']; ?>
<?php if($member['notenum']) { ?><li><img src="image/icon/notice.gif" width="16" alt="" /> <a href="space.php?do=notice"><strong><?=$member['notenum']?></strong> 个新通知</a></li><?php } ?>
<?php if($member['pokenum']) { ?><li><img src="image/icon/poke.gif" alt="" /> <a href="cp.php?ac=poke"><strong><?=$member['pokenum']?></strong> 个新招呼</a></li><?php } ?>
<?php if($member['addfriendnum']) { ?><li><img src="image/icon/friend.gif" alt="" /> <a href="cp.php?ac=friend&op=request"><strong><?=$member['addfriendnum']?></strong> 个好友请求</a></li><?php } ?>
<?php if($member['mtaginvitenum']) { ?><li><img src="image/icon/mtag.gif" alt="" /> <a href="cp.php?ac=mtag&op=mtaginvite"><strong><?=$member['mtaginvitenum']?></strong> 个群组邀请</a></li><?php } ?>
<?php if($member['eventinvitenum']) { ?><li><img src="image/icon/event.gif" alt="" /> <a href="cp.php?ac=event&op=eventinvite"><strong><?=$member['eventinvitenum']?></strong> 个活动邀请</a></li><?php } ?>
<?php if($member['myinvitenum']) { ?><li><img src="image/icon/userapp.gif" alt="" /> <a href="space.php?do=notice&view=userapp"><strong><?=$member['myinvitenum']?></strong> 个应用消息</a></li><?php } ?>
</ul>
<?php } ?>

<?php if($_SGLOBAL['supe_uid']) { ?>
<?php if(!isset($_SCOOKIE['checkpm'])) { ?>
<script language="javascript"  type="text/javascript" src="cp.php?ac=pm&op=checknewpm&rand=<?=$_SGLOBAL['timestamp']?>"></script>
<?php } ?>
<?php if(!isset($_SCOOKIE['synfriend'])) { ?>
<script language="javascript"  type="text/javascript" src="cp.php?ac=friend&op=syn&rand=<?=$_SGLOBAL['timestamp']?>"></script>
<?php } ?>
<?php } ?>
<?php if(!isset($_SCOOKIE['sendmail'])) { ?>
<script language="javascript"  type="text/javascript" src="do.php?ac=sendmail&rand=<?=$_SGLOBAL['timestamp']?>"></script>
<?php } ?>

<?php if($_SGLOBAL['ad']['couplet']) { ?>
<script language="javascript" type="text/javascript" src="source/script_couplet.js"></script>
<div id="uch_couplet" style="z-index: 10; position: absolute; display:none">
<div id="couplet_left" style="position: absolute; left: 2px; top: 60px; overflow: hidden;">
<div style="position: relative; top: 25px; margin:0.5em;" onMouseOver="this.style.cursor='hand'" onClick="closeBanner('uch_couplet');"><img src="image/advclose.gif"></div>
<?php adshow('couplet'); ?>
</div>
<div id="couplet_rigth" style="position: absolute; right: 2px; top: 60px; overflow: hidden;">
<div style="position: relative; top: 25px; margin:0.5em;" onMouseOver="this.style.cursor='hand'" onClick="closeBanner('uch_couplet');"><img src="image/advclose.gif"></div>
<?php adshow('couplet'); ?>
</div>
<script type="text/javascript">
lsfloatdiv('uch_couplet', 0, 0, '', 0).floatIt();
</script>
</div>
<?php } ?>
<?php if($_SCOOKIE['reward_log']) { ?>
<script type="text/javascript">
showreward();
</script>
<?php } ?>
</body>
</html>
<?php } ?>
<?php ob_out();?>