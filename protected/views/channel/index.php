<?php
$cid=Yii::app()->request->getParam('cid', 0);

$channel=Channel::model()->findByPk($cid);
$this->pageTitle=Yii::app()->name.' - '. $channel->name;

if($channel->status==0){
    $this->render('error', array('code'=>'404','message'=>'当前板块不存在或者已经被删除'));
    return;
}

$criteria=new CDbCriteria;
$criteria->with=array('channel');
$criteria->condition='`t`.`status`=1 AND `channel`.`status`=1 AND `channel`.`id`=:cid';
$criteria->params=array(':cid'=>$cid);

$sort=isset($_REQUEST['A_sort']) ? trim($_REQUEST['A_sort']) : '';
switch ($sort):
    case 'page':
        $criteria->order='`t`.`page` DESC';
        break;
    case 'pcount':
        $criteria->order='`t`.`pcount` DESC';
        break;
    case 'reach':
        $criteria->order='`t`.`reach` DESC';
        break;
    case 'reply':
        $criteria->order='`t`.`reply` DESC';
        break;
    case 'hot':
        $criteria->order='`t`.`hot` DESC';
        break;
    case 'uptime':
        $criteria->order='`t`.`uptime` DESC';
        break;
    default:
        $sort='';
        $criteria->order='`t`.`id` DESC';
endswitch;


$dataProvider=new CActiveDataProvider('Article',array(
    'criteria'=>$criteria,
    'pagination'=>array(
        'pageSize'=>10,
    ),
));

?>

<div class="up_head">
<?php
echo '排序：<a '.(($sort=='page')?'class="selected"':'').' href="/channel/'.$cid.'-page/index.html">'.'原帖总页数</a>';
echo '&nbsp;<a '.(($sort=='pcount')?'class="selected"':'').' href="/channel/'.$cid.'-pcount/index.html">'.'整理贴数</a>';
echo '&nbsp;<a '.(($sort=='reach')?'class="selected"':'').' href="/channel/'.$cid.'-reach/index.html">'.'访问量</a>';
echo '&nbsp;<a '.(($sort=='reply')?'class="selected"':'').' href="/channel/'.$cid.'-reply/index.html">'.'回复数</a>';
echo '&nbsp;<a '.(($sort=='hot')?'class="selected"':'').' href="/channel/'.$cid.'-hot/index.html">'.'本站访问量</a>';
echo '&nbsp;<a '.(($sort=='uptime')?'class="selected"':'').' href="/channel/'.$cid.'-uptime/index.html">'.'最新更新</a>';
echo '&nbsp;<span class="right"><a target="_blank" href="/api/'.$cid.'/sitemaps.xml"><img src="/images/xml.gif"></a></span>';
?>
</div>

<?php
$this->widget('application.vendors.OListView', array(
'dataProvider'=>$dataProvider,
'itemView'=>'//a/_al',
'ajaxUpdate'=>false,
'jump'=>false,
));
echo Ads::ad728x90();
?>

<?php
$this->widget('application.components.AjaxBuild', array(
    'type' => 'channel',
    'id'=>$channel->id,
));
