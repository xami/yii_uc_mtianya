<?php
class OZDo extends CWidget
{
/**
	 * @var string the tag name for the breadcrumbs container tag. Defaults to 'div'.
	 */
	public $tagName='div';
	/**
	 * @var array the HTML attributes for the breadcrumbs container tag.
	 */
	public $htmlOptions=array('class'=>'do','id'=>'do');
	/**
	 * @var boolean whether to HTML encode the link labels. Defaults to true.
	 */
	public $encodeLabel=true;
	
	public $size=60;
	
	public $ad_top='

	';
	public $ad_bottom='
';

	public $ad_link='';
	/**
	 * @var string the first hyperlink in the breadcrumbs (called home link).
	 * If this property is not set, it defaults to a link pointing to {@link CWebApplication::homeUrl} with label 'Home'.
	 * If this property is false, the home link will not be rendered.
	 */
	public $homeLink='/js/zeroclipboard/';

	
	/**
	 * Renders the content of the portlet.
	 */
	public function run()
	{
		$cs = Yii::app()->getClientScript();
//		$cs->registerCSSFile($this->homeLink.'css/site/site.css');
		$cs->registerScriptFile($this->homeLink.'ZeroClipboard.js');
		
		$orzero='
var clip = null;
ZeroClipboard.setMoviePath( "/js/zeroclipboard/ZeroClipboard10.swf" );
function copy_link(){
	clip = new ZeroClipboard.Client();

	clip.addEventListener("mouseOver", function (client) {
		// update the text on mouse over
		if($("#orzero_link").val() != null)
			clip.setText( $("#orzero_link").val() );
	});

	clip.addEventListener("complete", function (client, text) {
		alert("您已经成功复制内容,可以推荐给您的好友或群里面的朋友,按Ctrl+v即可粘贴下面的内容:\n[" + text + "]\nMTIANYA.COM 祝您阅读愉快!");
	});

	clip.glue( "orzero_button" );
}

function orzero(){
'.
CHtml::ajax(array(
	'url'=>Yii::app()->createUrl('api/do'),
	'data'=>array('src'=>'js:$(\'#ssrc\').val()'),
	'type'=>'post',
	'dataType'=>'json',
	'beforeSend'=>"function()
	{
		$('#sinfo').html('');
		$('#smg').addClass('smg-loading').html('正在分析链接,请稍等');
		$('#smg').addClass('list-view-loading');
		$('#so').attr('disabled', 'disabled');
	}",
	'complete'=>"function()
	{
		$('#smg').removeClass('smg-loading');
		$('#smg').removeClass('list-view-loading');
		$('#so').removeAttr('disabled');
		
	}",
	'error'=>"function()
	{
		alert('分析失败,请稍候再试');
	}",
	'success'=>"function(data)
	{
		if(data==null){
			$('#smg').removeClass('smg-loading').html('分析出错或服务器忙,请确认链接的正确性,部分板块水帖过多,不予整理,可以再次点击<span class=\"green\">[只看楼主]</span>,分析链接');
			$('#smg').removeClass('list-view-loading');
			$('#so').removeAttr('disabled');
		}else if(data.responseStatus==200 || data.responseStatus==301 || data.responseStatus==220){
			$('#smg').html(((data.responseStatus==200)?'只看楼主':'原帖')+':<a target=\"_blank\" href=\"'+data.responseData.link+'\">'+data.responseData.title+'</a>');

			$('#sinfo').addClass('sinfo').html(
				((data.responseStatus==301)?data.responseDetails+'<br />':'<div id=\"find\"><input type=\"text\" class=\"ssrc\" size=\"60\" id=\"orzero_link\" value=\"推荐: '+
				data.responseData.link+' '+data.responseData.title+
				'(我的天涯,只看楼主)\" /><input type=\"button\" value=\"复制\" onClick=\"copy_link();\" class=\"bt_m so\" id=\"orzero_button\" /></div>')+
				'<span class=\"grey\">我的天涯整理,只看楼主:</span><a target=\"_blank\" href=\"'+data.responseData.link+'\"><span id=\"orzero_title\">'+data.responseData.title+
				'</span><span class=\"red\">>>>点此进入'+((data.responseStatus==220||data.responseStatus==200)?'阅读':'原帖')+'</span></a><br />'+
				'<span class=\"grey\">作者:</span><a target=\"_blank\" href=\"/search/author/'+
				data.responseData.un+'/index.html\">'+
				data.responseData.un+'(所有文章)</a>&nbsp;'+
				'<span class=\"grey\">页数:</span>'+((data.responseData.page>50) ? '<span class=\"green\">' : '<span class=\"wd red\">')+
				data.responseData.page+'</span>&nbsp;'+
				'<span class=\"grey\">回复:</span>'+((data.responseData.reply>1000) ? '<span class=\"green\">' : '<span class=\"wd red\">')+
				data.responseData.reply+'&nbsp;</span>'+
				'<span class=\"grey\">访问:</span>'+((data.responseData.reach>100000) ? '<span class=\"green\">' : '<span class=\"wd red\">')+
				data.responseData.reach+'&nbsp;'
			);
			if(data.responseStatus==200){
				copy_link();
			}
		}else{
			$('#smg').html(data.responseDetails);
		}
		
	}",
)).'}';
		$packer = new JavaScriptPacker($orzero, 'Normal', true, false);
		$packed = $packer->pack();
		
		$cs->registerScript('do', $packed, CClientScript::POS_END);
		
//		$cs->registerScript('snoti', '$( "#snoti" ).dialog({ title:"只看楼主说明", autoOpen: false, width: 450, resizable: false });', CClientScript::POS_READY);
		$cs->registerScript('open_snoti', '$( "#open_snoti" ).click(function() {$( "#snoti" ).toggle()});', CClientScript::POS_READY);
		

		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		
		echo $this->ad_link;
		echo $this->ad_top;

		echo CHtml::tag('div', array('class'=>'copy','id'=>'smg'), 
		'<div class="key">下面文本框输入原帖的链接地址(支持<a target="_blank" href="http://www.tianya.cn/bbs/index.shtml"><span class="grey">[天涯论坛]</span></a>),
		然后点击<span class="green">[分析]</span>,
		程序自动分析后会给出脱水版的帖子链接,可直接进入阅读,方便只看楼主<span id="open_snoti" class="right link">详细说明</span></div>');

		echo '<div id="link" class="search">';
		echo CHtml::textField('ssrc', '', array('class'=>'ssrc','size'=>$this->size)); 
		echo CHtml::button('分析', array( 'id'=>'so','class'=>'so bt_m','onclick'=>"{orzero();}" ) );
		echo '</div>';
		
		echo $this->ad_bottom;

		echo CHtml::tag('div', array('id'=>'snoti','class'=>'snoti'),
'<span class="green">整理说明:</span>
满足下面<span class="green">[整理条件]</span>的帖子会给出脱水版的阅读链接(帖子只包含楼主的回复),
目前支持[天涯论坛]
主版和副版的部分板块帖子整理(站务相关和城市副版帖子不予整理),
后面会陆续开通其他论坛(如:猫扑,百度贴吧)的整理功能
<br /><br />
<span class="green">[整理条件]:</span>
为避免水贴过多,需要原帖页数大于<span class="green">50</span>,
回复数大于<span class="green">1000</span>,
访问量大于<span class="green">100000</span>,
才予以整理,感谢使用,阅读愉快!');

		echo CHtml::tag('div', array('id'=>'sinfo','class'=>'sinfo'), '');
		echo CHtml::closeTag($this->tagName);
		
	}
}