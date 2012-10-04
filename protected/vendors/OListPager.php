<?php
/**
 * CListPager class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CListPager displays a dropdown list that contains options leading to different pages of target.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CListPager.php 2799 2011-01-01 19:31:13Z qiang.xue $
 * @package system.web.widgets.pagers
 * @since 1.0
 */
class OListPager extends CBasePager
{
    /**
     * @var string the text shown before page buttons. Defaults to 'Go to page: '.
     */
    public $header;
    /**
     * @var string the text shown after page buttons.
     */
    public $footer;
    /**
     * @var string the text displayed as a prompt option in the dropdown list. Defaults to null, meaning no prompt.
     */
    public $promptText;
    /**
     * @var string the format string used to generate page selection text.
     * The sprintf function will be used to perform the formatting.
     */
    public $pageTextFormat;
    /**
     * @var array HTML attributes for the enclosing 'div' tag.
     */
    public $htmlOptions=array();

    public $jump=false;

    /**
     * Initializes the pager by setting some default property values.
     */
    public function init()
    {
        if($this->header===null)
            $this->header='&nbsp;&nbsp;总页数：<span id="c_page" value="'.$this->getPageCount().'" class="des">'.$this->getPageCount().'</span>'.
                '&nbsp;&nbsp;翻页：';
        if(!isset($this->htmlOptions['id']))
            $this->htmlOptions['id']=$this->getId();
        if($this->promptText!==null)
            $this->htmlOptions['prompt']=$this->promptText;
        if(!isset($this->htmlOptions['onchange']))
            $this->htmlOptions['onchange']="if(this.value!='') {window.location=this.value;};";
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        if(($pageCount=$this->getPageCount())<=1)
            return;
        $pages=array();
        for($i=0;$i<$pageCount;++$i)
            $pages[$this->createPageUrl($i)]=$this->generatePageText($i);
        $selection=$this->createPageUrl($this->getCurrentPage());
        echo $this->header;
        echo CHtml::dropDownList($this->getId(),$selection,$pages,$this->htmlOptions);
        if($this->jump==true){
            echo '&nbsp;&nbsp;直接跳转：';
            echo CHtml::textField('go_page','',array('id'=>'go_page','style'=>'width:40px;'));
            echo '&nbsp;&nbsp;'.CHtml::button('确定', array('id'=>'yt_go_page'));
        }
        echo $this->footer;

        $params=$this->getPages()->params===null ? $_GET : $this->getPages()->params;
        $params[$this->getPages()->pageVar]='';

        if($this->jump==true){
            $base_url=$this->getController()->createUrl($this->getPages()->route, $params);
            $cs=Yii::app()->clientScript;
            $cs->registerScript('go_page', '
            $("body").on("click",
            "#yt_go_page",
            function(){
                var go_page=$("#go_page").val();
                max_page=$("#c_page").html();
                if(isNaN(go_page)){
                    alert("请输入正确的页数");
                    return false;
                }
                if(go_page<1){
                    go_page=1;
                }
                if(go_page>max_page){
                    go_page=max_page;
                }
                go_page--;

                window.location=$("select#yw1 option:eq("+go_page+")").val();

            });
            ', CClientScript::POS_END);
        }
        /*
        $cs->registerScript('go_page', "
        function go_page(link){
            window.location=link;
        }
        $('body').on('click',
        '#yt_go_page',
        function(){
            jQuery.ajax({
                'type':'GET',
                'success':go_page,
                'data':'page='+$('#go_page').val(),
                'url':'/index.php?r=site/pageLink',
                'cache':false
            });
            return false;
        });
        ");
        */
    }

    public function getPageLink($id=0){
        $id=intval($id);
        return $this->createPageUrl($id);
    }

    /**
     * Generates the list option for the specified page number.
     * You may override this method to customize the option display.
     * @param integer $page zero-based page number
     * @return string the list option for the page number
     */
    protected function generatePageText($page)
    {
        if($this->pageTextFormat!==null)
            return sprintf($this->pageTextFormat,$page+1);
        else
            return $page+1;
    }
}