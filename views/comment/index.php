<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'comments';

?>
<div class="comments">
	<?foreach ($models as $c){?>
		<div class="comment">
			<br />
			<br />
			<i><?=$c->id?></i><br />
			<span class="author"><?=$c->author_name?></span>
			<p><?=$c->text?></p>
			<?if($c->commentAttaches){?>
				<div class="attaches">
					<?foreach($c->commentAttaches as $a){?>
						<a href="<?=$a->getUrl()?>" target="_blank"><?=$a->getUrl()?></a>
					<?}?>
				</div>
			<?}?>
			<a href="#" onclick="document.getElementById('comment-form-parent').value=<?=$c->id?>;return false;">answer</a>
		</div>
	<?}?>
</div>
<?=LinkPager::widget([
    'pagination' => $page,
]);?>




<?
$form = ActiveForm::begin([
	'id' => 'comment-form',
]);?>
	<?=$form->field($model, 'author_name')->label('Author')?>
	<?=$form->field($model, 'text')->textarea()->label('Comments')?>
	<?=$form->field($model, 'files[]')->fileInput(['multiple'=>true])->label('Attaches')?>
	<?=$form->field($model, 'parent')->hiddenInput(['id'=>'comment-form-parent'])->label('')?>
	<?=Html::submitButton('Send', ['class' => 'btn btn-primary'])?>
<? ActiveForm::end()?>

<? if($err = current($model->getFirstErrors())){ ?>
	<div class="error"><?=$err?></div>
<? } ?>

