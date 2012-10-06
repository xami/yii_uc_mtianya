<?php

class ApiController extends Controller
{
	public function actionIndex()
	{
        $key=Yii::app()->request->getParam('key', '');
        if($key=='mtianya'){
            $this->layout='//layouts/site';
        }

		$this->render('index');
	}

    public function actionLinkMap(){
        $cache=Yii::app()->cache;
        $link=$cache['channel::link::all'];
        if(empty($link)){
            $criteria=new CDbCriteria;
            $criteria->with=array('count_article','items','items:count_article');
            $criteria->condition='`t`.`status`=1';
            $channel=Channel::model()->findAll($criteria);

            $link='<div class="link">';
            if(!empty($channel)) foreach($channel as $channel_one){
                $link.='<h2><a href="/channel/'.$channel_one->id.'/index.html" target="_blank">'.$channel_one->name.'</a>
                <a href="/api/'.$channel_one->id.'/sitemaps.xml" target="_blank"><img src="/images/xml.gif"></a></h2>';
                $link.='<ul>';
                if(!empty($channel_one->items)) foreach($channel_one->items as $item_one){
                    $link.='<li>
                    <a href="/item/'.$channel_one->id.'/'.$item_one->id.'/index.html" target="_blank">'.$item_one->name.'('.$item_one->count_article.')</a>
                    <a href="/api/'.$channel_one->id.'/'.$item_one->id.'/sitemaps.xml" target="_blank"><img src="/images/xml.gif"></a>
                    </li>';
                }
                $link.='</ul>';
            }
            $link.='</div>';

            $cache->set('channel::link::all', $link, 3600*12);
        }

        $this->layout='//layouts/link';
        $this->render('link', array('link'=>$link));
    }

    public function actionSitemap(){
        $aid=Yii::app()->request->getParam('aid', 0);
        $type=Yii::app()->request->getParam('type', 0);
        $article=Article::model()->with(array('channel','item'))->findByPk($aid);

        if(empty($article) || $article->status==0){
            return;
        }

        Yii::app()->tianya->initSqlite($article);
        $page=(int)(($article->pcount/20)+1);
        if($type=='xml'){
            $cache=Yii::app()->cache;
            $xml=$cache['article::xml::'.$aid];
            if(empty($xml)){
                $xml=<<<EOF
<?xml version='1.0' encoding='UTF-8'?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
EOF;

                for($i=1;$i<=$page;$i++){
                    $c_id=($i-1)*20+1;
                    $c_key=C::model()->findByPk($c_id);
                    $uptime=isset($c_key->uptime)?$c_key->uptime:time();
                    $dt = date("Y-m-d",$uptime)."T".date("H:i:s",$uptime)."+00:00";
                    $xml.='
<url>
    <loc>http://www.mtianya.com/article/12730/'.$i.'.html</loc>
    <lastmod>'.$dt.'</lastmod>
    <changefreq>hourly</changefreq>
    <priority>0.5</priority>
</url>
';
                }

                $xml.=<<<EOF
</urlset>
EOF;
                $cache->set('article::xml::'.$aid, $xml, 3600);
            }
        }

        header("Content-type: text/xml");
        echo $xml;
    }

    public function actionSitemaps()
    {
        $type=Yii::app()->request->getParam('type', 0);
        $cid=Yii::app()->request->getParam('cid', 0);
        $tid=Yii::app()->request->getParam('tid', 0);
        if($type=='xml'){
            $cache=Yii::app()->cache;
            $xml=$cache['channel::xml::'.$cid.'::'.$tid];
            if(empty($xml)){
                $criteria=new CDbCriteria;
                if($tid>0){
                    $criteria->with=array('item');
                    $criteria->condition='`t`.`status`=1 AND `item`.`status`=1 AND `item`.`id`=:tid';
                    $criteria->params=array(':tid'=>$tid);
                }elseif($cid>0){
                    $criteria->with=array('channel');
                    $criteria->condition='`t`.`status`=1 AND `channel`.`status`=1 AND `channel`.`id`=:cid';
                    $criteria->params=array(':cid'=>$cid);
                }elseif($cid==0 && $tid==0){
                    $criteria->with=array('channel','item');
                    $criteria->condition='`t`.`status`=1 AND `channel`.`status`=1 AND `item`.`status`=1';
                }

                $criteria->order='`t`.`uptime` DESC';

                $articles=Article::model()->findAll($criteria);

                $xml=<<<EOF
<?xml version='1.0' encoding='UTF-8'?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

EOF;
                if(!empty($articles)) foreach($articles as $article){
                    $dt = date("Y-m-d",$article->uptime)."T".date("H:i:s",$article->uptime)."+00:00";
                    $xml.='
	<sitemap>
		<loc>http://www.mtianya.com/api/'.$article->id.'/sitemap.xml</loc>
		<lastmod>'.$dt.'</lastmod>
	</sitemap>
';
                }

                $xml.=<<<EOF

</sitemapindex>
EOF;
                $cache->set('channel::xml::'.$cid.'::'.$tid, $xml, 3600*24);
            }

            header("Content-type: text/xml");
            echo $xml;
        }
    }

    public function actionMenu()
    {
        $this->renderPartial('menu',array('js'=>Yii::app()->cache->get('menu-js')));
    }

    public function actionArticle()
    {
        $id=Yii::app()->request->getParam('id', 0);
        Yii::app()->tianya->article=$id;
        echo Yii::app()->tianya->article;
    }

    public function actionItem()
    {
        $id=Yii::app()->request->getParam('id', 0);
        Yii::app()->tianya->item=$id;
        echo Yii::app()->tianya->item;
    }

    public function actionChannel()
    {
        $id=Yii::app()->request->getParam('id', 0);
        Yii::app()->tianya->channel=$id;
        echo Yii::app()->tianya->channel;
    }

    public function actionHref(){
        $src=html_entity_decode(urldecode(Yii::app()->request->getParam('src', '')));

        if(!Tools::is_url($src)){
            die('Not URL !');
        }

        $html = Yii::app()->tianya->OZSnoopy($src,'','', 3600);

        $cut_src=parse_url($src);
        $base_url=$cut_src['scheme'].'://'.$cut_src['host'];


        $cp=strrpos($cut_src['path'], '/');
        if($cp>0){
            $base_path=$cut_src['scheme'].'://'.$cut_src['host'].substr($cut_src['path'], 0, $cp);
        }else{
            $base_path=$cut_src['scheme'].'://'.$cut_src['host'];
        }


        $this->renderPartial('ads',array('html'=>$html,'base_url'=>$base_url,'base_path'=>$base_path));
    }


    public function actionImg(){
        $src  = urldecode(Yii::app()->request->getParam('src', ''));       //图片水印链接
        $src=MCrypy::decrypt($src, Yii::app()->tianya->mc_key, 128);

        if(empty($src) || !Tools::is_url($src)){
            throw new CException('Src must be real url', 1);
        }

        //缩放
        $height    = intval(Yii::app()->request->getParam('h', 0));
        $width     = intval(Yii::app()->request->getParam('w', 0));
        $ww     = 180;
        $mark_src  = trim(Yii::app()->request->getParam('ms', ''));      //图片水印链接
        $mark    =  trim(Yii::app()->request->getParam('m', 'MTIANYA.COM'));       //文字水印
        $key = md5(serialize(array($src, $height, $width, $ww, $mark, $mark_src)));
        $img_file=Yii::app()->tianya->getCacheImg($key);
        //添加水印处理
        include_once(
            Yii::getPathOfAlias(
                'application.extensions.image'
            ).DIRECTORY_SEPARATOR.'Image.php'
        );

        if(is_file($img_file) && is_readable($img_file)){
            try{
                $image = new Image($img_file);
                $image->render();
                return;
            }catch (Exception $e){
                $this->watermark($key,$src);
            }
        }
    }

    public function watermark($key, $src){
        $img_data=Yii::app()->cache->get($key);
        if(!empty($img_data)){
            Yii::app()->tianya->getCacheImg($key, $render=true);
        }else{
            //从网络取得
            set_time_limit(300);
            $img_data=Yii::app()->tianya->OZSnoopy($src,'','', 3600*24);
            //目标网址数据为空
            if(empty($img_data)){
                throw new CException('Can\'t get url data', 2);
            }
            //解析二次跳转的目标内容
            include_once(
                Yii::getPathOfAlias(
                    'application.extensions.simple_html_dom'
                ).DIRECTORY_SEPARATOR.'simple_html_dom.php'
            );
            $html_obj = str_get_html($img_data);
            $count=count($html_obj->find('img'));
            if($count==1){
                $real_src = $html_obj->find('img',0)->src;
                $img_data=Yii::app()->tianya->OZSnoopy($real_src,'','', 3600);
            }

            //保存临时文件,原始图片
            Yii::app()->cache->set($key, $img_data);
        }

        //添加水印处理
        include_once(
            Yii::getPathOfAlias(
                'application.extensions.image'
            ).DIRECTORY_SEPARATOR.'Image.php'
        );

        $image = new Image($img_file);
        $ws=$image->width;
        $hs=$image->height;
        if(empty($ws) || empty($hs)){
            throw new CException('Can\'t process the image', 2);
        }

        //设置图片大小
        if($width > 0){
            if($height==0){     //长、宽都设置，则直接使用
                $height=($width/$ws)*$hs;
            }
        }else{
            $width=$ws;
            $height=$hs;
        }

        //不能比原始尺寸大
        if($width>$ws || $height>$hs) {
            $width=$ws;
            $height=$hs;
        }

        if($width>0 && $height>0)
            $image->resize($width, $height);

        if($width>285){
            //设置水印
            if(!empty($mark)){
                $image->watermark($mark, false, $ww);
            }else if(!empty($mark_src)){
                $image->watermark($mark_src,true, $ww);
            }
        }

        //覆盖保持处理后的图片
        $image->save();
        $image->render();
    }

    public function actionDo()
    {
        $src=isset($_REQUEST['src']) ? urldecode(trim($_REQUEST['src'])) : '';
        $type=isset($_REQUEST['type']) ? trim($_REQUEST['type']) : 'tianya';

        $error='';

        if(!Tools::is_url($src)){
            $error='请提供正确的链接地址,并且需要在前面加http://';
        }

        //目前只支持tianya
        if(!in_array($type,array('tianya'))){
            $error='不支持此类型';
        }

        $C1=Yii::app()->tianya->is_tianya($src);
        $key=array_keys($C1);
        $C1_ST=array_pop($key);

        if($C1_ST!=200){
            $error=$C1[$C1_ST];
        }

        if(!empty($error)){
            pd(json_encode(array(
                'responseStatus'=>'500',
                'responseDetails'=>$error,
                'responseData'=>null,
            )));
        }

        $article=Article::model()->find('aid=:aid AND tid=:tid AND cid=:cid', array(
            ':aid'=>$C1[200]['aid'],
            ':tid'=>$C1[200]['tid'],
            ':cid'=>$C1[200]['cid'],
        ));

        if(empty($article)){
            $article=new Article();
            $C2=Yii::app()->tianya->get_tianya($C1[200]['html']);
            $now=time();

            $article->cid=$C1[200]['cid'];
            $article->tid=$C1[200]['tid'];
            $article->aid=$C1[200]['aid'];

            $article->title=$C2['title'];
            $article->tag=$C2['tag'];
            $article->key='';
            $article->page=$C2['page'];
            $article->un=$C2['un'];
            $article->cto=0;
            $article->pcount=0;
            $article->mktime=$now;
            $article->uptime=$now;
            $article->src=$C1[200]['src'];
            $article->status=1;
            $article->reach=$C2['reach'];
            $article->reply=$C2['reply'];
            $article->hot=0;

            if($article->page>50 || $article->reach>100000 || $article->reply>1000){
                $article->save();
            }
        }else{
            pd(json_encode(array(
                'responseStatus'=>'200',
                'responseDetails'=>'我的天涯',
                'responseData'=>array(
                    'link'=>'http://www.mtianya.com/article/'.$article->id.'/index.html',
                    'title'=>$article->title,
                    'un'=>$article->un,
                    'page'=>$article->page,
                    'reach'=>$article->reach,
                    'reply'=>$article->reply,
                    'aid'=>$article->id,
                    'tid'=>$article->item->id,
                    'cid'=>$article->channel->id,
                ),
            )));
        }
//		pd($article);
//		pr($C1);
//		pr($C2);
        if(isset($article->id) && $article->id>0){
            pd(json_encode(array(
                'responseStatus'=>'200',
                'responseDetails'=>'我的天涯',
                'responseData'=>array(
                    'link'=>'http://www.mtianya.com/article/'.$article->id.'/index.html',
                    'title'=>$article->title,
                    'un'=>$article->un,
                    'page'=>$article->page,
                    'reach'=>$article->reach,
                    'reply'=>$article->reply,
                    'aid'=>$article->id,
                    'tid'=>$article->item->id,
                    'cid'=>$article->channel->id,
                ),
            )));
        }else{
            if($article->page<100)
                $reson='原帖页数('.$article->page.')';
            else if($article->reach<100)
                $reson='原帖访问量('.$article->reach.')';
            else if($article->reply<100)
                $reson='原帖回复数('.$article->reply.')';
            pd(json_encode(array(
                'responseStatus'=>'301',
                'responseDetails'=>'<span class="red">'.$reson.'不满足</span><span class="green">[整理条件]</span>,<span class="red">请更换其他的帖子或阅读原帖</span>',
                'responseData'=>array(
                    'link'=>$src,
                    'title'=>$article->title,
                    'un'=>$article->un,
                    'page'=>$article->page,
                    'reach'=>$article->reach,
                    'reply'=>$article->reply,
                    'tid'=>$article->item->id,
                    'cid'=>$article->channel->id,
                ),
            )));
        }
    }


	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}