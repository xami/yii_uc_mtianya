<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xami
 * Date: 12-9-22
 * Time: 下午6:16
 * To change this template use File | Settings | File Templates.
 */



class Tianya extends CApplicationComponent
{
    private $key='{"idwriter":"60257436","key":"866084533","chk":"e3f484000ae7ec99e2249b076fb09001"}';
    protected  $_article;
    protected  $_item;
    protected  $_channel;

    public function initSqlite($article){
        if(! $article instanceof OzActiveRecord){
            throw new CDbException('请传入表结构');
        }

        $sqlite_path = Yii::getPathOfAlias(
            'application.data.tianya.'.$article->cid.'.'.$article->tid.'.'.$article->aid.'.db'
        );
        $sqlite_file = $sqlite_path . DIRECTORY_SEPARATOR . 'orzero.sqlite';

        if(!is_dir($sqlite_path)){
            mkdir($sqlite_path, 0755, true);
        }
        if(!is_file($sqlite_file)){
            copy ( Yii::getPathOfAlias('application.data'). DIRECTORY_SEPARATOR . 'orzero.sqlite' , $sqlite_file);
        }

        //更换路径
        OzSqliteActiveRecord::$_oz_sqlite_config=array(
            'connectionString' => 'sqlite:'.$sqlite_file,
            'emulatePrepare' => true,
            'charset' => 'utf8',
            'class' => 'CDbConnection',      //此行必须添加
        );
    }

    public function getCountArticle($cid=0, $tid=0){
        $c = Yii::app()->cache->get(__FUNCTION__.'::'.$cid.'|'.$tid);
        if(!empty($c)){
            return $c;
        }

        if($tid>0){
            $item = Item::model()->with('count_article')->findByPk($tid);
            if(isset($item->count_article)){
                Yii::app()->cache->set(__FUNCTION__.'::'.$cid.'|'.$tid, $item->count_article, 3600);
                return $item->count_article;
            }else{
                return 0;
            }
        }

        if($cid>0){
            $channel = Channel::model()->with('count_article')->findByPk($tid);
            if(isset($channel->count_article)){
                Yii::app()->cache->set(__FUNCTION__.'::'.$cid.'|'.$tid, $channel->count_article, 3600);
                return $channel->count_article;
            }else{
                return 0;
            }
        }

        if($cid==0 && $tid==0){
            $c = Article::model()->count('status=1');
            Yii::app()->cache->set(__FUNCTION__.'::'.$cid.'|'.$tid, $c, 3600);
            return $c;
        }

    }

    public function getMenu($cid, $tid){
        $_menu = Yii::app()->cache->get(__FUNCTION__.'::'.$cid);
        if(empty($_menu)){
            $c=Channel::model()->with(array('items','items:count_article'))->findAll('t.status=1');
            $items=array();
            $i_items=array();
            $breadcrumbs=array();
            if(!empty($c)){
                foreach($c as $o){
                    $i_items[$o->id]=array();
                    $breadcrumbs[$o->id]=array();
                    if(!empty($o->items)) foreach($o->items as $ii_one){
                        $i_items[$o->id][]=array(
                            'tid'=>$ii_one->id,
                            'name'=>$ii_one->name.'('.$ii_one->count_article.')',
                        );

//                        $breadcrumbs[$o->id][$ii_one->name.'('.$ii_one->count_article.')']=Yii::app()->createUrl('item', array('cid'=>$o->id,'tid'=>$ii_one->id));
                        $breadcrumbs[$o->id][$ii_one->name.'('.$ii_one->count_article.')']='/item/'.$o->id.'/'.$ii_one->id.'/index.html';
                    }

                    $items[] = array(
                         'label'=>$o->name,
                         //'url'=> Yii::app()->createUrl('channel', array('cid'=>$o->id)),
                        'url'=> '/channel/'.$o->id.'/index.html',
                         'itemOptions'=>array('onmouseover'=>'javascript:cmenu($(this),'.$o->id.');','class'=>($cid==$o->id) ? 'active' : '')
                    );
                }
            }


            $_menu =array(
                'items'=>$items,
                'i_items'=>$i_items,
                'breadcrumbs'=>$breadcrumbs,
            );
            Yii::app()->cache->set('menu-js', 'var item_list='.json_encode($_menu['i_items']), 3600*24);
        }


        $cs=Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
//        $cs->registerScript('menu', 'var item_list='.json_encode($_menu['i_items']),CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->createUrl('api/menu',array('cid'=>$cid)), CClientScript::POS_HEAD);

        $cs->registerScript('menu_run', '
function cmenu(o, num){
    $("#mainmenu li").removeClass("active");
    o.addClass("active");
    var item_html="";
    if(item_list[num].length > 1)
        for(var item=0;item<item_list[num].length;item++){
            //item_html += \'<a tid="\'+item_list[num][item]["tid"]+\'" href="/index.php?r=item&cid=\'+num+\'&tid=\'+item_list[num][item]["tid"]+\'">\'+item_list[num][item]["name"];
            item_html += \'<a tid="\'+item_list[num][item]["tid"]+\'" href="/item/\'+num+\'/\'+item_list[num][item]["tid"]+\'\/index.html">\'+item_list[num][item]["name"];
            if(item<(item_list[num].length-1)){
                item_html += "</a>&nbsp;|&nbsp;";
            }
        }
    item_html+="";
    var item_top = document.getElementById("item_top");
    item_top.innerHTML= item_html;
    $("#item_top a[tid='.$tid.']").addClass("select");
}
        ', CClientScript::POS_HEAD);

        $cs->registerScript('search', '
        $("body").on("click",
        "#search",
        function(){
            var type=null;
            var type_author = document.getElementById("type_author");
            if(type_author.checked == true)
            {
                type="author";
            }
            var type_title = document.getElementById("type_title");
            if(type_title.checked == true)
            {
                type="title";
            }
            var key = document.getElementById("search_key").value+"";
            if(key==""){
                alert("请选输入内容");
                return false;
            }
            if(type==null){
                alert("请选先择搜索类型");
            }else{
                var r_url="/search/"+type+"/"+encodeURIComponent(key)+"/index.html";
                window.location=r_url;
            }

        });
        ', CClientScript::POS_END);


        Yii::app()->cache->set(__FUNCTION__.'::'.$cid, $_menu, 3600*24);
        return $_menu;

    }

    public function setArticle($id){
        $article=Article::model()->find('`id`='.$id.' AND `status`=1');
        if(!isset($article->src) || empty($article->src)){
            return $this->_article = false;
        }
        $this->initSqlite($article);
        $_C=new C;
        $_P=new P;
        $time=time();

        if($article->cto<=1){
            $article->cto=1;
            $page=$_P->findByPk(1);
            if(empty($page)){
                $page=clone $_P;
                $page->id=1;
            }
            $page->link=$article->src;
            $page->count=0;
            $page->info='';
            $page->status=0;
            $page->mktime=$time;
            $page->uptime=$time;
            $page->save();
        }else{
            $page=$_P->findByPk($article->cto);
            if(empty($page->id)){
                $article->cto=0;
                $article->save();
                return $this->_article=true;
            }
        }

        //构造带注册信息的链接
        $static_key=json_decode($this->key);
        $page->link=html_entity_decode($page->link);
        $cut_src=parse_url($page->link);
        parse_str($cut_src['query'], $cut_query);
        $cut_query['idwriter']=$static_key->idwriter;
        $cut_query['key']=$static_key->key;
        $cut_query['chk']=$static_key->chk;
        $static_query=http_build_query($cut_query);
        $statci_src=$cut_src['scheme'].'://'.$cut_src['host'].
            $cut_src['path'].'?'.$static_query;

        $find=$this->getPC($statci_src);

        if($find == -5){
            $article->status=-1;
            $article->save();
            return $this->_article=false;
        }

        if(is_numeric($find) && intval($find)<0){
            return $this->_article=false;
        }

        $next_page=$_P->findByPk($article->cto+1);
        if(empty($next_page)){
            $next_page=clone $_P;
            $next_page->id=$article->cto+1;
        }
        if(!isset($find['next_link'])||empty($find['next_link'])){
            return $this->_article=false;
        }

        $next_page->link=$find['next_link'];
        $next_page->count=0;
        $next_page->info='';
        $next_page->status=-1;
        $next_page->mktime=$time;
        $next_page->uptime=$time;
        $article->title=$find['title'];
        $article->tag=$find['tag'];
        $article->page=$find['page'];
        //作者保持固定不变,除非为空的情况
        (isset($find['un']) && !empty($find['un']) && !empty($article->un)) && $article->un=$find['un'];
        $article->uptime=$time;
        ($page->id==1) && $article->reach=intval($find['reach']);
        ($page->id==1) && $article->reply=intval($find['reply']);
        if(!isset($find['post'])||empty($find['post'])){
            $page->count=0;
        }else{
            $page->count=count($find['post']);
        }
        $page->status=1;
        $page->save();
        $next_page->save();

        if(isset($find['post']) && !empty($find['post'])) foreach($find['post'] as $post){
            $content = $_C->find('pos=:pos',array(':pos'=>$post['pos']));
            if(empty($content)){
                $content=clone $_C;
            }
            $content->pid=$page->id;
            $content->pos=$post['pos'];
            $content->text=$post['body'];
            $content->info='';
            $content->status=1;						//1正常，-1删除，2审核修改中（此时text内容进行serialize存储），3审核删除中
            $content->mktime=$time;
            $content->uptime=$time;

            if($content->save()==false){
                return $this->_article=false;
            }
        }

        $article->pcount=$_C->count();
        if(($next_page->id<=$find['page']) && ($next_page->link!==false))
            $article->cto=$next_page->id;

        if($article->save()===false){
            return $this->_article=false;
        }

        if($article->page>$article->cto+1){
            return $this->_article=true;
        }

        $this->_article=false;
    }

    public function getChannel(){
        return $this->_channel;
    }

    public function setChannel($id){
        $id=intval($id);
        $key=$id+1;
        //配置轮询的范围
        if($id<1 || $id>20){
            return $this->_channel=false;
        }

        $_url='http://3g.tianya.cn/nav/more.jsp?chl='.$key;
        //缓存一周
        $html=$this->OZSnoopy($_url, '', '',3600*24*7);
        if(empty($html)){
            return $this->_channel=false;
        }

        //校验页面是否下载完成
        $title=Tools::cutContent($html, '<title>天涯导航_', '</title>');
        if(strlen($title)<2){
            return $this->_channel=false;
        }
        $footer=Tools::cutContent($html, '<div class="f" id="bottom">', '</div>');
        if(strpos($footer, '天涯首页')===false){
            return $this->_channel=false;
        }

        $channel = new Channel();
        $_channel = $channel->findByPk($id);

        //更新频道的item列表
        $content=Tools::cutContent($html, '<div class="p">', '</div>');
        $find=self::find_item_info($content);

        if(isset($find['key']) && isset($find['name'])){
            if(empty($find['key']) || (($count = count($find['key'])) !== count($find['name']))){
                return $this->_channel=false;
            }

            //保存频道
            if(isset($_channel->id) && $_channel->id>0){
                if($_channel->status!=1)
                {
                    return $this->_channel=$_channel->id+1;
                }

                if(($title != $_channel->name) || ($count != $_channel->count)){
                    $_channel->name = trim($title);
                    $_channel->count = $count;
                    $_channel->save();
                }
            }else{
                $_channel = clone $channel;
                $_channel->key = $key;
                $_channel->name = trim($title);
                $_channel->count = $count;
                $_channel->status = 1;
                $_channel->uptime = time();
                $_channel->type = 'tianya';
                $_channel->save();
            }
            $item=new Item();
            $_item=array();
            for($i=0;$i<$count;$i++){
                $_item[$i] = $item->find('`cid`=:cid AND `key` LIKE :key',
                    array(':cid'=>$_channel->id,':key'=>$find['key'][$i]));

                if(isset($_item[$i]->id) && $_item[$i]->id>0){
                    //不更新item跳过
                    if($_item[$i]->status!=1) continue;
                    if($_item[$i]->name != $find['name'][$i]){
                        $_item[$i]->name = $find['name'][$i];
                        $_item[$i]->save();
                    }
                }else{
                    $_item[$i] = clone $item;
                    $_item[$i]->cid=$_channel->id;
                    $_item[$i]->key=$find['key'][$i];
                    $_item[$i]->name=$find['name'][$i];
                    $_item[$i]->count=0;
                    $_item[$i]->status=1;
                    $_item[$i]->save();
                }
            }
        }
        return $this->_channel=$key;
    }

    public function getItem(){
        return $this->_item;
    }

    public function setItem($id, $next_src=''){
        $tid=intval($id);
        $criteria=new CDbCriteria;
        $criteria->with=array('count_article');
        $criteria->condition='`t`.`status`=1 AND `t`.`id`=:tid';
        $criteria->params=array(':tid'=>$tid);
        $_item=Item::model()->find($criteria);
        if(empty($_item)){
            return $this->_item=false;
        }

        $_url='http://3g.tianya.cn/bbs/list.jsp?item='.$_item->key;
        if(!empty($next_src)){
            $_url=htmlspecialchars_decode($next_src);
            if(!Tools::is_url($_url)){
                return $this->_item=false;
            }
        }
        $html=$this->OZSnoopy($_url);
        if(empty($html)){
            return $this->_item=false;
        }

        $title=Tools::cutContent($html, '<br/>'."\r\n".'论坛-', "\r\n".'</div>');
        //校验页面是否下载完成
        $footer=Tools::cutContent($html, '<div class="lk">', '<br/>');
        if(empty($title) || strpos($footer, '下一页')===false || strpos($footer, $_item->key)===false){
            return $this->_item=false;
        }
        $_item->name=$title;

        //帖子列表
        $content=Tools::cutContent($html, '<div class="p">', '</div>');
        $find=self::find_article_info($content);

        if(isset($find['link']) && isset($find['content'])){
            if(empty($find['link']) || empty($find['content']) || (($count = count($find['link'])) !== count($find['content']))){
                return $this->_item=false;
            }

            $article= new Article();
            $criteria=new CDbCriteria;

            $_article=array();
            for($i=0,$j=0;$i<$count;$i++){
                if(!isset($find['reach'][$i]) || $find['reach'][$i]<50000)	//没有100000访问量
                    continue;
                if(!isset($find['reply'][$i]) || $find['reply'][$i]<1000)	//没有10000回复
                    continue;
                //有100000访问量 或者 有10000回复继续整理
//				if((!isset($find['reach'][$i]) || $find['reach'][$i]<100000) && (!isset($find['reply'][$i]) || $find['reply'][$i]<1000))
//					continue;

                $aid=intval(Tools::cutContent($find['link'][$i], '&id=', '&idwriter=0&key=0&chk='));
                if($aid<0){
                    return $this->_item=false;
                }
                if(strpos($find['content'][$i], '[')===0 && $cut=strpos($find['content'][$i], ']')!==false){
                    $tag=Tools::cutContent($find['content'][$i], '[', ']');
                    $title_cut=explode(']', $find['content'][$i]);
                    $title=array_pop($title_cut);
                }else{
                    $tag='';
                    $title=$find['content'][$i];
                }
                $un=$find['author'][$i];
                $time=time();
                $src='http://3g.tianya.cn/bbs/'.$find['link'][$i];

                $criteria->condition='`tid`=:tid AND `aid`=:aid';
                $criteria->params=array(':tid'=>$tid, ':aid'=>$aid);
                $_article[$i] = $article->find($criteria);
                //pr($find['content'][$i]);

                if(isset($_article[$i]->id) && $_article[$i]->id>0){
                    //不更新状态，跳过
                    if($_article[$i]->status!=1) continue;
                    if($_article[$i]->title != $title || $_article[$i]->tag != $tag || $_article[$i]->un != $un){
                        $_article[$i]->title = $title;
                        $_article[$i]->tag = $tag;
                        !empty($un) && $_article[$i]->un = $un;
                        $_article[$i]->reach = $find['reach'];
                        $_article[$i]->reply = $find['reply'];
                        $_article[$i]->save();
                    }
                }else{
                    $_article[$i] = clone $article;
                    $_article[$i]->cid=$_item->cid;
                    $_article[$i]->tid=$tid;
                    $_article[$i]->aid=$aid;
                    $_article[$i]->title=$title;
                    $_article[$i]->tag=$tag;
                    $_article[$i]->key='';
                    $_article[$i]->page=0;
                    $_article[$i]->un=$un;
                    $_article[$i]->cto=0;
                    $_article[$i]->pcount=0;
                    $_article[$i]->mktime=$time;
                    $_article[$i]->uptime=$time;
                    $_article[$i]->src=urldecode($src);
                    $_article[$i]->status=1;
                    $_article[$i]->reach = $find['reach'][$i];
                    $_article[$i]->reply = $find['reply'][$i];
                    $_article[$i]->hot = 0;
                    $_article[$i]->save();
                }
            }
            $j++;
        }


        $_item->uptime=time();
        //更新统计
        $_item->count=$_item->count_article;
        $_item->save();


        if($j>0){
//            pd($footer);
            preg_match("'\|<a\s+href=\"(.*?)\">下一页</a>'isx", $footer, $matches);
//            pd($matches);
            if(!empty($matches[1])){
                return $this->_item='http://3g.tianya.cn/bbs/'.$matches[1];
            }
            preg_match("'href=\"(.*?)\">下一页</a>'isx", $footer, $matches);
            if(!empty($matches[1])){
                return $this->_item='http://3g.tianya.cn/bbs/'.$matches[1];
            }
        }

        return $this->_item=false;
    }

    public function getArticle(){
        return $this->_article;
    }


    public function getPC($link){
        $html=$this->OZSnoopy(htmlspecialchars_decode($link));
        $fulltitle=Tools::cutContent($html, '<title>', '</title>');				//取得标题
        if($fulltitle=='掌中天涯(beta)'){
            if(Tools::cutContent($html, '<div class="p lk">', '<br/>')=='该贴不存在'){
                return -5;
            }
        }

        //校验页面是否下载完成
        $nav=Tools::cutContent($html, '<div class="p3">', '</div>');
        if(strpos($nav, '只看楼主')===false || strpos($nav, '最新回帖')===false || strpos($nav, '去底部')===false){
            return -2;
        }

        if(strpos($fulltitle, '[')===0 && $cut=strpos($fulltitle, ']')!==false){
            $tag=Tools::cutContent($fulltitle, '[', ']');
            $title_cut=explode(']', $fulltitle);
            $title=array_pop($title_cut);
        }else{
            $tag='';
            $title=$fulltitle;
        }

        $footer=Tools::cutContent($html, '<form  action="artgo.jsp"  method="get">', '</form>');
        if(empty($footer)){
            $footer=Tools::cutContent($html, '<form  action="art.jsp"  method="get">', '</form>');
        }
        if(strpos($footer, 'name="item"')===false || strpos($footer, 'name="id"')===false) {
            return -3;
        }

        $page_content=Tools::cutContent($html, '<div class="pg">', '</div>');
        $next_link=self::find_next_link($page_content);
        if(!Tools::is_url($next_link)){
            return -4;
        }

        //帖子列表
        $find=self::find_author_post(Tools::cutContent($html, '<div class="p3">', '<form  action="artgo.jsp"  method="get">'));
        if(empty($find)){
            $find=self::find_author_post(Tools::cutContent($html, '<div class="p3">', '<form  action="art.jsp"  method="get">'));
        }
        $page_cut_1=Tools::cutContent($footer, '(', '页)');
        if(!empty($page_cut_1)){
            $page_cut_2=explode('/', $page_cut_1);
            if(!isset($page_cut_2[1]) || empty($page_cut_2[1])){
                $find['page']=0;
            }else{
                $find['page']=intval($page_cut_2[1])>0 ? intval($page_cut_2[1]) : 0;
            }
        }else{
            $find['page']=0;
        }

        $find['title']=$title;
        $find['tag']=$tag;
        $find['next_link']=$next_link;
        return $find;
    }

    private static $_snoopy;
    public function OZSnoopy($URI='', $formvars="", $referer='', $expire=600)
    {
        if(self::$_snoopy == null){
            self::$_snoopy = new Snoopy();
            self::$_snoopy->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
            self::$_snoopy->rawheaders["Pragma"] = "no-cache";
        }

        if(empty($URI)){
            return self::$_snoopy;
        }

        if(Tools::is_url($URI)===false){
            return false;
        }

        if(!empty($referer)&&Tools::is_url($referer)){
            self::$_snoopy->referer = $referer;
        }else{
            self::$_snoopy->referer = $URI;
        }


        $cache = Yii::app()->cache;		//默认缓存30秒远程数据
        if(is_array($formvars) && !empty($formvars)){
            $key = md5(md5($URI).md5(serialize($formvars)));
            if(!empty($cache[$key])){
                return $cache[$key];
            }

            if(self::$_snoopy->submit($URI,$formvars)!==false){
                $cache->set($key, self::$_snoopy->results, $expire);
                return self::$_snoopy->results;
            }
        }else{
            $key = md5($URI);
            if(!empty($cache[$key]))
                return $cache[$key];
            if(self::$_snoopy->fetch($URI)!==false){
                $cache->set($key, self::$_snoopy->results, $expire);
                return self::$_snoopy->results;
            }
        }

        return false;
    }

    public function getCacheFile($key)
    {
        $fcache=Yii::app()->fcache;
        $fcache->directoryLevel=5;
        $base=$fcache->cachePath.DIRECTORY_SEPARATOR.'img';
        for($i=0;$i<$fcache->directoryLevel;++$i)
        {
            if(($prefix=substr($key,$i+$i,2))!==false)
                $base.=DIRECTORY_SEPARATOR.$prefix;
        }
        return $base.DIRECTORY_SEPARATOR.$key.$fcache->cacheFileSuffix;
    }

    public function getCacheImg($key='', $render=false){
        if(empty($key)){
            return false;
        }
        $file=$this->getCacheFile($key);
        $dir=dirname($file);
        if(!is_file($file)){
            $data=Yii::app()->cache->get($key);
            if(!is_dir($dir))
                mkdir($dir, 0755, true);
            file_put_contents($file, $data);
        }elseif($render==true){
            include_once(
                Yii::getPathOfAlias(
                    'application.extensions.image'
                ).DIRECTORY_SEPARATOR.'Image.php'
            );
            try{
                $image = new Image($file);
                $image->render();
                exit;
            }catch (Exception $e) {
                if(is_file($file))
                    unlink($file);
            }
        }

        return $file;
    }


    public static function find_next_link($document) {
        preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links);
        while(list($key,$val) = each($links[2])) {
            if(!empty($val))
                $match['link'][] = $val;
        }
        while(list($key,$val) = each($links[3])) {
            if(!empty($val))
                $match['link'][] = $val;
        }
        while(list($key,$val) = each($links[4])) {
            if(!empty($val))
                $match['content'][] = $val;
        }
        //pd($match['link']);
        $key = array_search('下一页', $match['content']);
        if(isset($match['link'][$key]))
            return 'http://3g.tianya.cn/bbs/'.$match['link'][$key];
        return false;
    }

    public static function find_author_post($document) {
        $match=false;
        preg_match_all("'<div\s+class=\"lk\">(.*?)</div>[\r\n]*?<div\s+class=\"sp\s+lk\">(.*?)</div>'isx",$document,$cut);
        //print_r($cut);
        if((isset($cut[1][0]) && isset($cut[2][0])) && ($count=count($cut[1]))===count($cut[2])){
//		    pr($cut);
//			pr($count);echo "\r\n\r\n\r\n\r\n";
            $j=0;
            for($i=0;$i<$count;$i++){
                $head=$cut[1][$i];
                $body=$cut[2][$i];
//				pr($head);echo "\r\n\r\n";
//				pr($body);echo "\r\n";
                if(strpos($head, '楼主:')===0){	//匹配顶楼
                    $match['reach']=intval(Tools::cutContent($head, '访问:', '回复:'));
                    $match['reply']=intval(Tools::cutContent($head, '回复:', '<br/>'));
                    unset($body_info);
                    preg_match_all("'(.*?)(<br\/>[\s\W]{4,6})*?<a\shref=\"?rep\.jsp\?'isx",$body,$body_info);
                    $match['post'][$j]['body']=$body_info[1][0];
                    $match['post'][$j]['pos']=0;
                    $j++;
                }else if(strpos($head, '<span class="red">楼主</span>')!==false){
                    unset($body_info);
                    preg_match_all("'(.*?)(<br\/>[\s\W]{4,6})*?<a\shref=\"?rep\.jsp\?[^>]+?>.*?(\d+?)[^\d]+</a>'isx",$body,$body_info);
//					pr($body_info);
                    $match['post'][$j]['body']=$body_info[1][0];
                    $match['post'][$j]['pos']=$body_info[3][0];
                    $j++;
                }else{
                    continue;
                }
                $un=Tools::cutContent($head, '">', '</a><br/>');
                if(!empty($un)){
                    $match['un']=$un;
                }

            }
        }

        return $match;
    }



    public static function find_article_info($document) {
        preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?art(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links);

        while(list($key,$val) = each($links[2])) {
            if(!empty($val))
                $match['link'][] = html_entity_decode('art'.$val);
        }
        while(list($key,$val) = each($links[3])) {
            if(!empty($val))
                $match['link'][] = html_entity_decode('art'.$val);
        }
        while(list($key,$val) = each($links[4])) {
            if(!empty($val))
                $match['content'][] = $val;
        }
        //访问数，回复数，作者
        preg_match_all("'<span\s.*?class=\s*([\"\'])?(?(1)gray\\1)[^>]*?>\s*?\((\d+?)/(\d+?)\s+?(.*?)\)</span>'isx",$document,$info);
        //print_r($info);die;
        while(list($key,$val) = each($info[2])) {
            if(!empty($val))
                $match['reach'][] = $val;
            else
                $match['reach'][] = 0;
        }
        while(list($key,$val) = each($info[3])) {
            if(!empty($val))
                $match['reply'][] = $val;
            else
                $match['reply'][] = 0;
        }
        while(list($key,$val) = each($info[4])) {
            if(!empty($val))
                $match['author'][] = $val;
            else
                $match['author'][] = '';
        }

        return $match;
    }

    public static function find_item_info($html) {
        preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$html,$links);
        while(list($key,$val) = each($links[2])) {
            if(!empty($val)){
                $parse_url=parse_url($val);
                parse_str($parse_url['query'], $parse1);
                $match['key'][] = $parse1['item'];
            }
        }
        while(list($key,$val) = each($links[3])) {
            if(!empty($val)){
                $parse_url=parse_url($val);
                parse_str($parse_url['query'], $parse2);
                $match['key'][] = $parse2['item'];
            }
        }
        while(list($key,$val) = each($links[4])) {
            if(!empty($val))
                $match['name'][] = $val;
        }
        return $match;
    }


    public static function filterPost($in=''){
        if(empty($in)){
            return false;
        }
        $in=preg_replace_callback('/<img\s+src="(.*?)[\n]?"\/><a\s+href="(.*?)">(.*?)<\/a>/i',array('self','mk_link'),$in);
        $in=preg_replace_callback('/(<a\s+.*?href=\s*([\"\']?))([^\'^\"]*?)((?(2)\\2)[^>^\/]*?>)(.*?)(<\/a>)/isx',array('self','mk_href'),$in);
        return $in;
    }

    public static function mk_href($matches)
    {
        if(substr($matches[3],0,7)!=='http://'){
            return $matches[0];
        }
        $t=strip_tags($matches[5]);
        $t=str_replace("\r\n", '', $t);
        $src=Yii::app()->createUrl('api/href',array('src'=>urlencode($matches[3])));
        return $matches[1].$src.$matches[2].' target="_blank">'.$matches[5].$matches[6];
    }

    public static function mk_link($matches)
    {
        if($matches[3]=='(原图)'){
            $img_src=trim($matches[2]);
            $img_s=Yii::app()->createUrl('api/img',array('w'=>925, 'src'=>urlencode($img_src)));
            $img_b=Yii::app()->createUrl('api/img',array('src'=>urlencode($img_src)));
            return '<a href="'.$img_b.'" target="_blank"><img src="'.$img_s.'" /></a>';
        }else{
            return $matches[0];
        }
    }


}