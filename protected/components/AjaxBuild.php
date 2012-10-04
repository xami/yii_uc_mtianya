<?php
class AjaxBuild extends CController
{
	public $type;
	public $id;
	
	public function run($actionID='') {
		$ajaxSetup = 'jQuery.ajaxSetup({'.
			'type:\'POST\','.
			'url:\''.CController::createUrl('api/'.$this->type).'\','.
			'cache:false,'.
			'success:reRunAjax'.
		'});';

		//判断是否为有效资源
		$id = intval($this->id);
		if($id>=0){
            $criteria=new CDbCriteria;
            $criteria->condition='`status`=1 AND `id`=:id';
            $criteria->params=array(':id'=>$id);

			if($this->type=='channel'){
				$data=Channel::model()->find($criteria);
			}else if($this->type=='item'){
				$data=Item::model()->find($criteria);
			}else if($this->type=='article'){
				$data=Article::model()->find($criteria);
			}else return false;
			if(empty($data)) return false;
		}

        if($this->type=='article'){
            $reRunAjax = 'function reRunAjax(loop){loop=parseInt(loop);'.
					'if(loop>0){jQuery.ajax({"data":{"id":'.$data->id.', "loop":loop}});}}'.
					'jQuery.ajax({"data":{"id":'.$data->id.', "loop":0}});';
        }else if($this->type=='item'){
            $reRunAjax = 'function reRunAjax(src){'.
					'if(src.length>10){jQuery.ajax({"data":{"id":'.$data->id.', "src":src}});}}'.
					'jQuery.ajax({"data":{"id":'.$data->id.', "src":""}});';
        }else if($this->type=='channel'){
            $reRunAjax = 'function reRunAjax(loop){loop=parseInt(loop);'.
					'if(loop>0){jQuery.ajax({"data":{"id":loop}});}}'.
					'jQuery.ajax({"data":{"id":'.$data->id.'}});';
        }

		
		$cs=Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		echo CHtml::script('jQuery(function($){'.$ajaxSetup.$reRunAjax.'});');
	}
}