<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'comments';


function tplComment($c, $lvl=0) {?>
	<div class="comment" style="padding-left: <?=$lvl==1? '2em': 0?>">
		<br />
		<br />
		<i><?=$c->id . ($c->parent? " -> $c->parent": '')?></i><br />
		<span class="author"><?=$c->author_name?></span>
		<p><?=$c->text?></p>
		<?if($c->commentAttaches){?>
			<div class="attaches">
				<?foreach($c->commentAttaches as $a){?>
					<a href="<?=$a->getUrl()?>" target="_blank"><?=$a->getUrl()?></a>
				<?}?>
			</div>
		<?}?>
		<a href="#" onclick="return setParent(<?=$c->id?>)">answer</a>
		<?if($c->comments){?>
			<div class="subcomments">
				<?foreach($c->comments as $answer){
					tplComment($answer, $lvl+1);
				}?>
			</div>
		<?}?>
	</div>
<?}

?>
<div class="comments">
	<?foreach ($models as $c){
		tplComment($c);
	}?>
</div>

<script>
function setParent(id) {
	document.getElementById('comment-form-parent').value = id;
	document.getElementById('comment-form-text').focus();
	return false;
}
</script>

<?=LinkPager::widget([
    'pagination' => $page,
]);?>

<?
$form = ActiveForm::begin([
	'id' => 'comment-form',
]);?>
	<?=$form->field($model, 'author_name')->label('Author')?>
	<?=$form->field($model, 'text')->textarea(['id'=>'comment-form-text'])->label('Comments')?>
	<?=$form->field($model, 'files[]')->fileInput(['multiple'=>true])->label('Attaches')?>
	<?=$form->field($model, 'parent')->hiddenInput(['id'=>'comment-form-parent'])->label('')?>
	<?=Html::submitButton('Send', ['class' => 'btn btn-primary'])?>
<? ActiveForm::end()?>

<? if($err = current($model->getFirstErrors())){ ?>
	<div class="error"><?=$err?></div>
<? } ?>

