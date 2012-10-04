<?php $this->pageTitle=Yii::app()->name . ' - 用户注册';
$this->breadcrumbs=array(
    '用户注册',
);
?>
<div class="login" id="login">

<?php if(Yii::app()->user->hasFlash('register')): ?>
<div class="success">
    <?php echo Yii::app()->user->getFlash('register'); ?>
</div>
<?php else: ?>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'register-form',
    'enableAjaxValidation'=>true,
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>


    <?php echo $form->errorSummary(array($model)); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username'); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
        <p class="hint">
        </p>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'verifyPassword'); ?>
        <?php echo $form->passwordField($model,'verifyPassword'); ?>
        <?php echo $form->error($model,'verifyPassword'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email'); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <?php if (User::doCaptcha('register')): ?>
    <div class="row">
        <?php echo $form->labelEx($model,'verifyCode'); ?>

        <?php $this->widget('CCaptcha'); ?>
        <?php echo $form->textField($model,'verifyCode'); ?>
        <?php echo $form->error($model,'verifyCode'); ?>

        <p class="hint"></p>
    </div>
    <?php endif; ?>

    <div class="row submit">
        <?php echo CHtml::submitButton('注册'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>

</div>