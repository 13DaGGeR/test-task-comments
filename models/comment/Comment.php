<?php

namespace app\models\comment;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string $dt
 * @property int $author_id if user authorized
 * @property string $author_name if user is guest
 * @property string $text
 * @property int $parent
 *
 * @property Comment $parent0
 * @property Comment[] $comments
 * @property CommentAttach[] $commentAttaches
 */
class Comment extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'comment';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['dt'], 'safe'],
			[['author_id'], 'default', 'value'=>0],
			[['text', 'author_name'], 'default', 'value'=>''],
			[['author_id', 'parent'], 'integer'],
			[['text'], 'required'],
			[['text'], 'string', 'min'=>1],
			[['author_name'], 'string', 'max' => 255],
			[['parent'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['parent' => 'id']],
		];
	}
	
	public function beforeValidate() {
		if(!$this->dt) $this->dt = date('Y-m-d H:i:s');
		return parent::beforeValidate();
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'dt' => Yii::t('app', 'Dt'),
			'author_id' => Yii::t('app', 'Author ID'),
			'author_name' => Yii::t('app', 'Author Name'),
			'text' => Yii::t('app', 'Text'),
			'parent' => Yii::t('app', 'Parent'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParent0() {
		return $this->hasOne(Comment::className(), ['id' => 'parent']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getComments() {
		return $this->hasMany(Comment::className(), ['parent' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCommentAttaches() {
		return $this->hasMany(CommentAttach::className(), ['comment_id' => 'id']);
	}

}
