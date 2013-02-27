<?php
$aid = Yii::app()->request->getParam('aid', 0);
$article=Article::model()->with(array('channel','item'))->findByPk($aid);

if($article->channel->status==0 || $article->item->status==0 || $article->status==0){
    $this->render('error', array('code'=>'404','message'=>'当前文章不存在或者当前板块已经被删除'));
    return;
}
$this->breadcrumbs=array(
    $article->channel->name=>'/channel/'.$article->cid.'/index.html',
    $article->item->name=>'/item/'.$article->cid.'/'.$article->tid.'/index.html',
    $article->title=>'/article/'.$article->id.'/index.html',
);

$this->pageTitle='[我的天涯]'.$article->title;


Yii::app()->tianya->initSqlite($article);
//echo $src='http://www.tianya.cn/techforum/content/'.$article->item->key.'/1/'.$article->aid.'.shtml';

//Yii::app()->oz_sqlite->connectionString = 'sqlite:'.$sqlite_file;
//		$criteria=new CDbCriteria;
//		$criteria->condition='status=1';
//		$criteria->order='pos ASC';
$dataProvider=new CActiveDataProvider('C',array(
//		    'criteria'=>$criteria,
    'pagination'=>array(
        'pageSize'=>20,
    ),
));

//echo '<div style="float:left;margin-top:-8px;">'.Ads::share().Ads::link468x15().'</div>';
echo '<div style="float:right;margin-top:-25px;margin-right:-15px;"><a href="/api/'.$article->id.'/sitemap.xml"><img src="/images/xml.gif"></a></div>';

$this->widget('application.vendors.OListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_article',
    'ajaxUpdate'=>false,
    'viewData'=>array('article'=>$article),
    'jump'=>true,
));

$this->widget('application.components.AjaxBuild', array(
    'type' => 'article',
    'id'=>$article->id,
));

if($article->cto==0){
    $cs=Yii::app()->clientScript;
    $cs->registerScript('fresh','function myrefresh(){window.location.reload();}setTimeout(myrefresh,6000);', CClientScript::POS_END);
}

$article->hot=$article->hot+1;
$article->save();

?>
