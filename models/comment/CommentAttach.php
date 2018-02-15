<?php

namespace app\models\comment;

use Yii;
use yii\web\UploadedFile;


/**
 * This is the model class for table "comment_attach".
 *
 * @property int $id
 * @property int $comment_id
 * @property string $data json
 *
 * @property Comment $comment
 */
class CommentAttach extends \yii\db\ActiveRecord implements iCommentAttach {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'comment_attach';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['comment_id'], 'integer'],
			[['data'], 'string'],
			[['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['comment_id' => 'id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'comment_id' => Yii::t('app', 'Comment ID'),
			'data' => Yii::t('app', 'Data'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getComment() {
		return $this->hasOne(Comment::className(), ['id' => 'comment_id']);
	}

	/**
	 * upload new file, create AR object, save it and return
	 * @param array $file
	 */
	public static function upload(UploadedFile $file, int $comment_id): CommentAttach {
		$fn = "$file->baseName-".round(microtime(1), 4).".$file->extension";
		if($file->saveAs(Yii::getAlias('@app')."/web/upload/$fn")){
			$model = new static([
				'comment_id' => $comment_id,
			]);
			$model->parseMeta($file, $fn);
			$model->save();
			return $model;
		} else {
			throw new Exception('Upload error', 500);
		}
	}
	
	/**
	 * generate metadata for specific file type
	 * @param UploadedFile $file
	 * @param string $fn
	 */
	protected function parseMeta(UploadedFile $file, string $fn) {
		$this->data = ['fn' => $fn];
	}

	public function beforeSave($insert) {
		if(!is_string($this->data)) {
			$this->data = json_encode($this->data);
		}
		parent::beforeSave($insert);
	}
	
	public function beforeValidate() {
		if(!is_string($this->data)) {
			$this->data = json_encode($this->data);
		}
		parent::beforeValidate();
	}
	
	public function getAbsFn() {
		return Yii::getAlias('@basedir').'/';
	}

	public function getUrl() {
		return '/';
	}
}
