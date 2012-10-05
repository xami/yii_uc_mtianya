<?php
$cid=Yii::app()->request->getParam('cid', 0);
$tid=Yii::app()->request->getParam('tid', 0);
$channel=Channel::model()->findByPk($cid);
$item=Item::model()->findByPk($tid);

$this->pageTitle=Yii::app()->name.' - '. $channel->name. ' - ' . $item->name;

if($channel->status==0 || $item->status==0){
    $this->render('error', array('code'=>'404','message'=>'当前分类不存在或者已经被删除'));
    return;
}

$criteria=new CDbCriteria;
$criteria->with=array('item');
$criteria->condition='`t`.`status`=1 AND `item`.`status`=1 AND `item`.`id`=:tid';
$criteria->params=array(':tid'=>$tid);

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
echo '排序：<a '.(($sort=='page')?'class="selected"':'').' href="/item/'.$cid.'/'.$tid.'-page/index.html">'.'原帖总页数</a>';
echo '&nbsp;<a '.(($sort=='pcount')?'class="selected"':'').' href="/item/'.$cid.'/'.$tid.'-pcount/index.html">'.'整理贴数</a>';
echo '&nbsp;<a '.(($sort=='reach')?'class="selected"':'').' href="/item/'.$cid.'/'.$tid.'-reach/index.html">'.'访问量</a>';
echo '&nbsp;<a '.(($sort=='reply')?'class="selected"':'').' href="/item/'.$cid.'/'.$tid.'-reply/index.html">'.'回复数</a>';
echo '&nbsp;<a '.(($sort=='hot')?'class="selected"':'').' href="/item/'.$cid.'/'.$tid.'-hot/index.html">'.'本站访问量</a>';
echo '&nbsp;<a '.(($sort=='uptime')?'class="selected"':'').' href="/item/'.$cid.'/'.$tid.'-uptime/index.html">'.'最新更新</a>';

echo '&nbsp;<span class="right"><a target="_blank" href="/api/'.$cid.'/'.$tid.'/sitemaps.xml"><img src="/images/xml.gif" /></a></span>';
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
    'type' => 'item',
    'id'=>$item->id,
));