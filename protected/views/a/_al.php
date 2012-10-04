<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xami
 * Date: 12-9-23
 * Time: 上午1:35
 * To change this template use File | Settings | File Templates.
 */
?>


<div class="view">
<?php
echo
//    CHtml::link(CHtml::encode($data->item->name), '/item-'.$data->channel->id.'-'.$data->item->id.'/index.html').'&nbsp;'.
    ((!empty($data->tag)) ? CHtml::link(CHtml::encode($data->tag), '/search/tag/'.CHtml::encode($data->tag).'/index.html') : '').'&nbsp;'.
    CHtml::link(CHtml::encode($data->title), '/article/'.$data->id.'/index.html', array('target'=>'_blank')).
    '(已整理:'.(int)(($data->pcount/20)+1).'页/访问:'.$data->hot.'次&nbsp;|&nbsp;'.
    '作者:'.CHtml::link(CHtml::encode($data->un), '/search/author/'.CHtml::encode($data->un).'/index.html').'&nbsp;|&nbsp;'.
    '原帖:访问'.$data->reach.'/回复'.$data->reply.')';
?>
</div>