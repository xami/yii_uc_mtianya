<?php
$this->pageTitle=Yii::app()->name.' - '. '只看楼主,热帖直播';

$this->widget('application.components.OZDo', array(
    'size'=>38,
    'htmlOptions'=>array('class'=>'do','id'=>'dol')
));