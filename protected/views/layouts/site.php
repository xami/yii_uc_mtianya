<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/yiiplayground/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/user.css" />

    <meta name='robots' content='noindex,nofollow' />
</head>
<body>


<div id="content">
    <?php echo $content; ?>
</div><!-- content -->


</body>
</html>