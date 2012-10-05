<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xami
 * Date: 12-10-2
 * Time: 上午12:04
 * To change this template use File | Settings | File Templates.
 */

class Ads{
    public static function share(){
        return
            $share=<<<EOF
<!-- JiaThis Button BEGIN -->
<div id="ckepop">
	<span class="jiathis_txt">分享到：</span>
	<a class="jiathis_button_qzone">QQ空间</a>
	<a class="jiathis_button_tsina">新浪微博</a>
	<a class="jiathis_button_tqq">腾讯微博</a>
	<a class="jiathis_button_renren">人人网</a>
	<a class="jiathis_button_douban">豆瓣</a>
	<a class="jiathis_button_fav">收藏夹</a>
</div>
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- JiaThis Button END -->
EOF;
    }

    public static function link728x15(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 728x15-链接单元 */
google_ad_slot = "4456137852";
google_ad_width = 728;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function link468x15(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 468x15-链接单元 */
google_ad_slot = "1339611153";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function ad336x280(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 336x280-大矩形 */
google_ad_slot = "3354931463";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }


    public static function ad250x250(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 250x250-正方形 */
google_ad_slot = "4163535743";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function ad468x60(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 468x60-横幅 */
google_ad_slot = "8866488934";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function ad160x600(){
        return
            $ad=<<<EOF
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
EOF;
    }

    public static function ad728x90(){
        return
            $ad=<<<EOF
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4726192443658314";
/* 728x90-首页横幅 */
google_ad_slot = "1018549157";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOF;
    }

    public static function footer_link(){
        return $link=<<<EOF
<a title="我的天涯家园" href="http://home.mtianya.com">我的天涯家园</a>&nbsp;&nbsp;
<a title="我的天涯论坛" href="http://bbs.mtianya.com">我的天涯论坛</a>&nbsp;&nbsp;
<a title="文章列表" href="/search/title/mtianya/index.html">文章列表</a>&nbsp;&nbsp;
<a title="网站地图" href="/sitemap">网站地图</a>&nbsp;&nbsp;
<a title="文章索引" href="/api/sitemaps.xml"><img alt="文章链接" src="/images/xml.gif" /></a>
EOF;
    }
}