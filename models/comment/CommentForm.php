<?php

namespace app\models\comment;

/**
 * @author 13dagger
 */
class CommentForm extends Comment {
	public $files = [];
	
	/**
	 * @inheritdoc
	 */
	public function rules(){
		return array_merge(parent::rules(), [
			[['files'], 'file', 'maxFiles'=>100, 'skipOnEmpty'=>true],
		]);
	}
}
