<!doctype html>
<!--[if IE 5]><html dir="ltr" lang="cn-ZH" class="ie5 no-js"><![endif]-->
<!--[if IE 6]><html dir="ltr" lang="cn-ZH" class="ie6 no-js"><![endif]-->
<!--[if IE 7]><html dir="ltr" lang="cn-ZH" class="ie7 no-js"><![endif]-->
<!--[if IE 8]><html dir="ltr" lang="cn-ZH" class="ie8 no-js"><![endif]-->
<!--[if gt IE 8]><!-->
<html dir="ltr" lang="cn-ZH" class="no-js"><!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link rel="stylesheet" type="text/css" href="/css/layout-default-latest.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/css/o.css" />
</head>

<body class="single single-post single-format-standard singular">

<div class="container" id="wrap">

    <header id="head" class="cf">
        <hgroup>
            <h1>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo CHtml::encode($this->pageTitle); ?></a>
            </h1>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
            'tagName'=>'h2',
            'htmlOptions'=>array()
        )); ?>
        </hgroup>
        <nav></nav>
        <div class="left"></div>
    </header>
    <div id="body" class="cf">
        <?php echo $content; ?>
    </div>

    <footer id="foot">
        <?php
        echo '<div style="float:right;clear:both;">'.Ads::ad728x90().'</div>';
        ?>
    </footer>
</div>

<div id='ad_left'><?php echo Ads::ad160x600();?></div>
<div id='ad_right'><?php echo Ads::ad160x600();?></div>

<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-27014735-1']);
_gaq.push(['_setDomainName', '.mtianya.com']);
_gaq.push(['_trackPageview']);

(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

//document.body.style.margin="0 165px";
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
</script>
</body>
</html>