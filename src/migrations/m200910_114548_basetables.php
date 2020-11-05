<?php

use yii\db\Migration;

/**
 * Class m200910_114548_basetables
 */
class m200910_114548_basetables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%forms_form}}', [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'subject' => $this->text(),
            'email_intro' => $this->text(),
            'email_outro' => $this->text(),
            'copy_to_attribute' => $this->string(),
            'recipients' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        $this->createTable('{{%forms_submission}}', [
            'id' => $this->primaryKey(),
            'form_id' => $this->integer()->notNull(),
            'useragent' => $this->string(),
            'language' => $this->string(),
            'url' => $this->string(),
            'is_done' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex('form_id', '{{%forms_submission}}', ['form_id']);

        $this->createTable('{{%forms_submission_value}}', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'attribute' => $this->string()->notNull(),
            'label' => $this->string()->notNull(),
            'hint' => $this->string(),
            'value' => $this->text(),
            'format' => $this->string(),
        ]);

        $this->createIndex('submission_id', '{{%forms_submission_value}}', ['submission_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%forms_form}}');

        $this->dropTable('{{%forms_submission}}');

        $this->dropTable('{{%forms_submission_value}}');
    }
}
