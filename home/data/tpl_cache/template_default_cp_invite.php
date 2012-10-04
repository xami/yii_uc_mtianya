<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template/default/cp_invite|template/default/header|template/default/footer', '1348305850', 'template/default/cp_invite');?><?php if(empty($_SGLOBAL['inajax'])) { ?>
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

<?php if($_GET['op'] == 'resend') { ?>

<h1>重发邮件</h1>
<a href="javascript:hideMenu();" class="float_del" title="关闭">关闭</a>
<div class="popupmenu_inner" id="__resendform_<?=$id?>">
<form method="post" id="resendform_<?=$id?>" name="resendform_<?=$id?>" action="cp.php?ac=invite&op=resend&id=<?=$id?>">
<p>确定重新发送邀请邮件吗？</p>
<p class="btn_line">
<input type="hidden" name="refer" value="<?=$_SGLOBAL['refer']?>">
<?php if($_SGLOBAL['inajax']) { ?>
<input type="hidden" name="resendsubmit" value="true" />
<button name="resendsubmit_btn" type="button" class="submit" value="true" onclick="ajaxpost('resendform_<?=$id?>', 'resend_mail', 2000)">重发</button>
<?php } else { ?>
<button name="resendsubmit" type="submit" class="submit" value="true">确定</button>
<?php } ?>
</p>

<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
</form>
</div>

<?php } elseif($_GET['op'] == 'delete') { ?>

<h1>删除记录</h1>
<a href="javascript:hideMenu();" class="float_del" title="关闭">关闭</a>
<div class="popupmenu_inner" id="__deleteform_<?=$id?>">
<form method="post" id="deleteform_<?=$id?>" name="deleteform_<?=$id?>" action="cp.php?ac=invite&op=delete&id=<?=$id?>">
<p>确定要删除该邀请记录吗？</p>
<p>删除该邀请记录后,您的好友将不能通过<br/>原邀请记录链接注册成为您的好友!</p>
<p class="btn_line">
<input type="hidden" name="refer" value="<?=$_SGLOBAL['refer']?>">
<?php if($_SGLOBAL['inajax']) { ?>
<input type="hidden" name="deletesubmit" value="true" />
<button name="deletesubmit_btn" type="button" class="submit" value="true" onclick="ajaxpost('deleteform_<?=$id?>', 'resend_mail', 2000)">删除</button>
<?php } else { ?>
<button name="deletesubmit" type="submit" class="submit" value="true">删除</button>
<?php } ?>
</p>

<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
</form>
</div>

<?php } else { ?>

<h2 class="title"><img src="image/icon/friend.gif">好友</h2>
<div class="tabs_header">
<ul class="tabs">
<li><a href="space.php?uid=<?=$space['uid']?>&do=friend"><span>好友列表</span></a></li>
<li><a href="cp.php?ac=friend&op=search"><span>查找好友</span></a></li>
<li><a href="cp.php?ac=friend&op=find"><span>可能认识的人</span></a></li>
<li class="active"><a href="cp.php?ac=invite"><span>邀请好友</span></a></li>
<li><a href="cp.php?ac=friend&op=request"><span>好友请求</span></a></li>
<li><a href="space.php?do=top"><span>排行榜</span></a></li>
</ul>
</div>

<div id="content">	
<form method="post" action="cp.php?ac=invite&appid=<?=$appid?>&ref" class="c_form">
<?php if($get_invite) { ?>
<div class="c_mgs"><div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
每邀请成功一个好友，就可获得 <strong><?=$get_invite?></strong> 个奖励积分。赶快行动吧！
</div></div></div></div></div>
<?php } ?>
<table cellspacing="0" cellpadding="0" class="formtable" width="100%">
<caption>
<h2><?php if($appid) { ?>邀请我的好友一起玩<?=$appinfo['appname']?><?php } else { ?>我的好友邀请链接<?php } ?></h2>
<p>
<div class="composer_header">
<?php if($appid) { ?>
<div class="ar_r_t"><div class="ar_l_t"><div class="ar_r_b"><div class="ar_l_b"><img src="http://appicon.manyou.com/logos/<?=$appid?>" alt="<?=$appinfo['appname']?>" /></div></div></div></div>
<?php } ?>
<p>
您可以通过QQ、MSN等IM工具，或者发送邮件，把下面的链接告诉你的好友，邀请他们加入进来。
<table cellspacing="0" cellpadding="0" >
<?php if($reward['credit']) { ?>
<?php if($list_str) { ?>
<tr>
<td>一行一个邀请链接，一个链接只能使用一次。<br/><textarea name="invite_urls" rows="4" style="width:98%;"><?=$list_str?></textarea></td>
</tr>
<?php } else { ?>
<tr><td>还没有邀请码。</td></tr>
<?php } ?>
<tr>
<td><img src="image/credit.gif" align="absmiddle"> 每一个邀请码需要<strong>扣减积分 <?=$reward['credit']?> 个/每条</strong>，您现在拥有积分 <a href="cp.php?ac=credit"><?=$space['credit']?></a> 个。</td>
</tr>
<tr class="notice">
<td>获取邀请码(还可获取 <?=$maxinvitenum?> 个)：<input type="text" name="invitenum" value="1" size="5" class="t_input" /> <input type="submit" name="invitesubmit" value="获取" class="submit" /></td>
</tr>
<?php } else { ?>
<tr><td style="font-size:14px;font-weight:bold;"><a onclick="javascript:setCopy('<?=$mailvar['4']?>');return false;" href="<?=$mailvar['4']?>"><?=$mailvar['4']?></a></td></tr>
<?php } ?>
</table>
</p>
</div>

</p>
</caption>


</table>

<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
</form>
<script type="text/javascript" src="http://widgets.manyou.com/misc/scripts/ab.js" charset="utf-8"></script>
<form method="post" action="cp.php?ac=invite&type=mail&appid=<?=$appid?>&ref" class="c_form">
<table cellspacing="0" cellpadding="0" class="formtable" width="100%">
<caption>
<h2>给好友发送 Email 邀请<?php if($appid) { ?>好友一起玩<?=$appinfo['appname']?><?php } ?></h2>
<p>通过Email发送邮件的方式，邀请你的好友。<br>多个Email使用","分割。</p>
<?php if($pay['invite']) { ?>
<p>
<img src="image/credit.gif" align="absmiddle"> 发送一个邮件需要<strong>扣减积分 <?=$pay['invite']?> 个/每条</strong>，您现在拥有积分 <a href="cp.php?ac=credit"><?=$space['credit']?></a> 个。
</p>
<?php } ?>
</caption>
<tr>
<td>

<div class="r_option"><a href="#"  onclick="MYABC.showChooser('email', '<?=$uri?>api/getmaillist.htm', null, false, false, null); return false"><img src="http://widgets.manyou.com/misc/images/ab/ab_button.gif" style="margin: 5px 0 0;" alt="从地址簿添加" /></a></div><div><br />好友Email地址</div>
<textarea name="email" id="email" rows="5" style="width:99%;"></textarea>
</td>
</tr>
<tr>
<td>
想对好友说的话<br/>
<input type="text" name="saymsg" id="saymsg" onkeyup="showPreview(this.value, 'sayPreview')" class="t_input" style="width:98%;">
</td>
</tr>
<tr>
<td><input type="submit" name="emailinvite" value="邀请" class="submit"></td>
</tr>
<tr>
<td>
<div class="title" style="margin: 10px 0;">预览邀请函</div>
<div class="c_mgs"><div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
<table border="0">
<tr>
<td valign="top"><?=$mailvar['0']?></td>
<td valign="top">
<?php if($appid) { ?>
<h3>Hi，我是<?=$mailvar['1']?>，在<?=$mailvar['2']?>上玩<?=$appinfo['appname']?>，邀请您也加入一起玩。</h3><br>
<?php } else { ?>
<h3>Hi，我是<?=$mailvar['1']?>，在<?=$mailvar['2']?>上建立了个人主页，邀请你也加入并成为我的好友。</h3><br>
请加入到我的好友中，你就可以通过我的个人主页了解我的近况，分享我的照片，随时与我保持联系。<br>
<?php } ?>
<br>
邀请附言：<br>
<span id="sayPreview"><?=$mailvar['3']?></span>
<br><br>
<strong>请你点击以下链接，接受好友邀请<?php if($appid) { ?>一起玩<?=$appinfo['appname']?><?php } ?>：</strong><br>
<?=$mailvar['4']?><br>
<br>
<strong>如果你拥有<?=$mailvar['2']?>上面的账号，请点击以下链接查看我的个人主页：</strong><br>
<?=$mailvar['5']?><br>
</td></tr></table>
</div></div></div></div></div>
</td>
</tr>
</table>

<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
</form>
</div>

<div id="sidebar">

<div class="sidebox">
<h2 class="title">已邀请好友</h2>
<?php if(count($flist) < 24) { ?>
<ul class="avatar_list">
<?php if(is_array($flist)) { foreach($flist as $key => $value) { ?>
<li>
<div class="avatar48"><a href="space.php?uid=<?=$value['fuid']?>&do=doing"><?php echo avatar($value[fuid],small); ?></a></div>
<p><a href="space.php?uid=<?=$value['fuid']?>" title="<?=$_SN[$value['fuid']]?>"><?=$_SN[$value['fuid']]?></a></p>
</li>
<?php } } ?>
</ul>
<?php } else { ?>
<div>
<?php $mod=''; ?>
<?php if(is_array($flist)) { foreach($flist as $key => $value) { ?>
<?=$mod?><a href="space.php?uid=<?=$value['fuid']?>" title="<?=$_SN[$value['fuid']]?>"><?=$_SN[$value['fuid']]?></a>
<?php $mod='、'; ?>
<?php } } ?>
</div>
<?php } ?>
</div>

<?php if($maillist) { ?>
<div class="sidebox">
<h2 class="title">尚未邀请成功的好友邮件</h2>
<ul class="sendmail">
<?php if(is_array($maillist)) { foreach($maillist as $key => $value) { ?>
<li id="sendmail_<?=$value['id']?>_li">
<a href="cp.php?ac=invite&op=resend&id=<?=$value['id']?>" id="mail_invite_<?=$value['id']?>" class="c_resend" onclick="ajaxmenu(event, this.id)" title="重发">重发</a>
<a href="javascript:;" title="链接" class="c_link" onclick="javascript:setCopy('<?=$value['url']?>');return false;">链接</a>
<a href="cp.php?ac=invite&op=delete&id=<?=$value['id']?>" id="del_invite_<?=$value['id']?>" class="c_delete" onclick="ajaxmenu(event, this.id)" title="删除">删除</a>
<?=$value['email']?>
</li>
<?php } } ?>
</ul>
</div>
<?php } ?>
</div>

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