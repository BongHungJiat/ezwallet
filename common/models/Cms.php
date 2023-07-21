<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms".
 *
 * @property int $id
 * @property int $admin_id
 * @property string $title
 * @property string $html
 * @property string|null $date_created
 * @property string|null $updated_at
 * @property string|null $deleted_at
 */
class Cms extends \yii\db\ActiveRecord
{
    public $username;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'html'], 'required'],
            [['html'], 'string'],
            [['date_created', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'html' => 'Html',
            'date_created' => 'Date Created',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public static function getById($id){
        $post = static::findOne(['id' => $id]);
        return $post;
    }
}
