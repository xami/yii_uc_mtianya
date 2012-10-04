<?php
$key=Yii::app()->request->getParam('key', '');
$type=Yii::app()->request->getParam('type', '');
if($type!='title' && $type !='author' && $type !='tag' ){
    $error['message']='错误的链接';
    $this->renderPartial('error', $error);
    return;
}
if(empty($key)){
    $error['message']='请设置关键词';
    $this->renderPartial('error', $error);
    return;
}

$this->pageTitle='搜索::'.$key;

$criteria=new CDbCriteria;
$criteria->with=array('channel','item');
$criteria->condition=' `t`.`status`=1 AND `channel`.`status`=1 AND `channel`.`status`=1 ';

$page_size=10;
if($key=='mtianya'){
    $page_size=37;

}else{
    if($type=='title'){
        $criteria->compare('title',$key,true);
    }elseif($type =='author'){
        $criteria->compare('un',$key,true);
    }elseif($type =='tag'){
        $criteria->compare('tag',$key,true);
    }
}

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
        'pageSize'=>$page_size,
    ),
));

?>

<div class="up_head">
    <?php
    echo '排序：<a '.(($sort=='page')?'class="selected"':'').' href="/search/page/'.$type.'/'.$key.'/index.html">'.'原帖总页数</a>';
    echo '&nbsp;<a '.(($sort=='pcount')?'class="selected"':'').' href="/search/pcount/'.$type.'/'.$key.'/index.html">'.'整理贴数</a>';
    echo '&nbsp;<a '.(($sort=='reach')?'class="selected"':'').' href="/search/reach/'.$type.'/'.$key.'/index.html">'.'访问量</a>';
    echo '&nbsp;<a '.(($sort=='reply')?'class="selected"':'').' href="/search/reply/'.$type.'/'.$key.'/index.html">'.'回复数</a>';
    echo '&nbsp;<a '.(($sort=='hot')?'class="selected"':'').' href="/search/hot/'.$type.'/'.$key.'/index.html">'.'本站访问量</a>';
    echo '&nbsp;<a '.(($sort=='uptime')?'class="selected"':'').' href="/search/uptime/'.$type.'/'.$key.'/index.html">'.'最新更新</a>';
    ?>
</div>

<?php
$this->widget('application.vendors.OListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'//a/_al',
    'ajaxUpdate'=>false,
    'jump'=>false,
));
if(!$key=='mtianya'){
echo Ads::ad728x90();
}else{
    echo '<div style="float:right;">'.Ads::ad728x90().'</div>';
}
?>

