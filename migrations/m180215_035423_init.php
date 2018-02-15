<?php

use yii\db\Migration;

/**
 * Class m180215_035423_init
 */
class m180215_035423_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
		$this->createTable('comment', [
			'id' => $this->bigPrimaryKey(),
			'dt' => $this->dateTime()->defaultExpression('NOW()'),
			'author_id' => $this->bigInteger()->null()->comment('if user authorized'),
			'author_name' => $this->char(255)->null()->comment('if user is guest'),
			'text' => $this->text()->notNull(),
			'parent' => $this->bigInteger()->null(),
		]);
		$this->createIndex('comment_parent', 'comment', 'parent');
		$this->addForeignKey('comment_parent_fk', 'comment', 'parent', 'comment', 'id', 'CASCADE');
		
		$this->createTable('comment_attach', [
			'id' => $this->bigPrimaryKey(),
			'comment_id' => $this->bigInteger(),
			'data' => $this->text()->comment('json'),
		]);
		$this->createIndex('comment_attach_source', 'comment_attach', 'comment_id');
		$this->addForeignKey('comment_attach_source_fk', 'comment_attach', 'comment_id', 'comment', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
		$this->dropTable('comment_attach');
        $this->dropTable('comment');
	}

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180215_035423_init cannot be reverted.\n";

        return false;
    }
    */
}
