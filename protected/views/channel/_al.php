<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xami
 * Date: 12-9-23
 * Time: 上午1:35
 * To change this template use File | Settings | File Templates.
 */
?>

<li>
    <a href="<?php echo $this->createUrl('a/index',array('cid'=>$data->cid, 'tid'=>$data->tid, 'aid'=>$data->id))?>"><?php echo $data->title;?></a>
</li>
