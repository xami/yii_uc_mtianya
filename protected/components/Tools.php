<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xami
 * Date: 12-9-27
 * Time: 下午10:47
 * To change this template use File | Settings | File Templates.
 */
class Tools
{

    //取得字段间字符
    public static function cutContent($content='', $start='', $end='', $reg=false)
    {
        //是否启用正则
        if($reg){
            $e_start=preg_split($start, $content, 2, PREG_SPLIT_OFFSET_CAPTURE);
            if(empty($e_start[1][0]) || empty($e_start[1][1])){
                return false;
            }

            $e_end=preg_split($end, $e_start[1][0], 2, PREG_SPLIT_OFFSET_CAPTURE);
            if(empty($e_end[1][0]) || empty($e_end[1][1])){
                return false;
            }

            return $e_end[0][0];
        }else{
            $e_start=explode($start, $content);
            if(!isset($e_start[1])){
                return false;
            }
            $e_end=explode($end, $e_start[1]);
            if(!isset($e_end[1])){
                return false;
            }

            return $e_end[0];
        }

    }

    public static function cut_title_tag($full_title)
    {
        $full_title=trim($full_title);
        if(empty($full_title)){
            return false;
        }
        $cut=strpos($full_title, ']');
        if(strpos($full_title, '[')===0 && $cut!==false){
            $tag=self::cutContent($full_title, '[', ']');
            $title=mb_substr($full_title, $cut+1);
        }else{
            $tag='';
            $title=$full_title;
        }

        return array(
            'tag'=>$tag,
            'title'=>$title,
        );
    }

    public static function is_url($url){
        $validate=new CUrlValidator();
        if(empty($url)){
            return false;
        }
        if($validate->validateValue($url)===false){
            return false;
        }
        return true;
    }


}