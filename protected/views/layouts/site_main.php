<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">
    <div id="main">
        <div id="header">
            <div id="logo"><?php
                $type=Yii::app()->request->getParam('type', '');
                $key=Yii::app()->request->getParam('key', '');
                echo '<a href="/">'.CHtml::encode(Yii::app()->name).'</a><span class="c_mark">总贴数:'.Yii::app()->tianya->getCountArticle().'</span>';
            ?></div>
            <div class="search">
                <label><input type="radio" <?php echo $type=='author' ? 'checked="checked"': '';?> value="author" name="type" id="type_author" />作者</label>
                <label><input type="radio" <?php echo $type=='title' ? 'checked="checked"': '';?> value="title" name="type" id="type_title" />标题</label>
                <input type="text" name="q" id="search_key" size="36" value="<?php echo $key;?>" maxlength="80" />
                <input type="button" id="search" value="站内搜索" />
            </div>
        </div><!-- header -->

        <div id="mainmenu">
            <?php
            $cid=Yii::app()->request->getParam('cid', 0);
            $tid=Yii::app()->request->getParam('tid', 0);
            $menu=Yii::app()->tianya->getMenu($cid, $tid);
            $cid=Yii::app()->request->getParam('cid', 1);
            $menu['items'][]=array('label'=>'登陆', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest);
            $menu['items'][]=array('label'=>'登出('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest);
            $menu['items'][]=array('label'=>'家园', 'url'=>'http://home.mtianya.com', 'visible'=>!Yii::app()->user->isGuest);
            $menu['items'][]=array('label'=>'论坛', 'url'=>'http://bbs.mtianya.com', 'visible'=>!Yii::app()->user->isGuest);

            $this->widget('zii.widgets.CMenu',array(
            'items'=>$menu['items'],
            'htmlOptions'=>array('class'=>'menu mouse'),
            )); ?>
        </div><!-- mainmenu -->
        <div id="item_top" class="main_menu"></div>

        <?php echo $content; ?>

        <div id="ad_left">
            <?php echo Ads::ad160x600();?>
        </div>
        <div id="ad_right">
            <?php
            echo Ads::ad160x600();
            ?>
        </div>
    </div>
</div><!-- page -->

<div id="footer">
    <div class="right"><?php echo Ads::footer_link();?></div>
</div><!-- footer -->


<script type="text/javascript">
/*<![CDATA[*/
document.body.style.margin="0 165px";
document.getElementById("ad_left").style.cssText='width:160px;left:2px;POSITION:absolute;TOP:120px;z-index:1;';
document.getElementById("ad_right").style.cssText='width:160px;right:2px;POSITION:absolute;TOP:120px;z-index:1;';

lastScrollY=0;
function heartBeat(){
    var diffY;
    if (document.documentElement && document.documentElement.scrollTop)
        diffY = document.documentElement.scrollTop;
    else if (document.body)
        diffY = document.body.scrollTop
    else
    {/*Netscape stuff*/}
    percent=.1*(diffY-lastScrollY);
    if(percent>0)percent=Math.ceil(percent);
    else percent=Math.floor(percent);
    document.getElementById("ad_left").style.top=parseInt(document.getElementById("ad_left").style.top)+percent+"px";
    document.getElementById("ad_right").style.top=parseInt(document.getElementById("ad_right").style.top)+percent+"px";
    lastScrollY=lastScrollY+percent;
}

window.setInterval(heartBeat,1);

//analytics
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-27014735-1']);
_gaq.push(['_setDomainName', 'mtianya.com']);
_gaq.push(['_trackPageview']);

(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

/*]]>*/
</script>

</body>
</html>
