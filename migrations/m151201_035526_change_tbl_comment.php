<?php

use yii\db\Schema;
use yii\db\Migration;

class m151201_035526_change_tbl_comment extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%comments}}', 'moderator', $this->boolean()->defaultValue(false));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%comments}}', 'moderator');
    }
}
