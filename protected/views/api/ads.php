<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xami
 * Date: 12-10-1
 * Time: 下午9:44
 * To change this template use File | Settings | File Templates.
 */

$ad='
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 160x600-宽幅摩天大楼 */
google_ad_slot = "0203809730";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
';

include_once(
    Yii::getPathOfAlias(
        'application.extensions.simple_html_dom'
    ).DIRECTORY_SEPARATOR.'simple_html_dom.php'
);

$html_obj = str_get_html($html);
$count=count($html_obj->find('a'));
for($i=0;$i<$count;$i++){
    $href=$html_obj->find('a',$i)->href;
    if(substr($href,0,7)!=='http://'){
        if(substr($href,0,1)=='/'){
            $html_obj->find('a',$i)->href=$base_url.$href;
        }else{
            $html_obj->find('a',$i)->href=$base_path.'/'.$href;
        }
    }
}

$count=count($html_obj->find('form'));
for($i=0;$i<$count;$i++){
    $action=$html_obj->find('form',$i)->action;
    if(substr($action,0,7)!=='http://'){
        if(substr($action,0,1)=='/'){
            $html_obj->find('form',$i)->action=$base_url.$action;
        }else{
            $html_obj->find('form',$i)->action=$base_path.'/'.$action;
        }
    }
}

echo str_replace('</body>','<div id="ad_left">'.Ads::ad160x600().'</div><div id="ad_right">'.Ads::ad160x600().'</div></body>', $html_obj->save());

?>

<script type="text/javascript">
/*<![CDATA[*/
document.body.style.margin="0 165px";
document.getElementById("ad_left").style.cssText='width:160px;left:2px;POSITION:absolute;TOP:120px;z-index:1;border:1px dotted;';
document.getElementById("ad_right").style.cssText='width:160px;right:2px;POSITION:absolute;TOP:120px;z-index:1;border:1px dotted;';

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
/*]]>*/
</script>