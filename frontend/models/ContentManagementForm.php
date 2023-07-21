<?php

namespace frontend\models;

use common\models\Cms;
use common\models\Ewallet;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use Yii;
use yii\db\Exception;

/**
 * UserManagementSearch represents the model behind the search form of `common\models\User`.
 */
class ContentManagementForm extends User
{
    /**
     * {@inheritdoc}
     */

    public $id;
    public $title;
    public $html;
    public $delete;
    public function rules()
    {
        return [
            // combine rules together
            [['id', 'admin_id'], 'safe'],
            [['title', 'html'], 'required'],
        ];
    }

    public function getPosting()
    {
        return Cms::find()
            ->alias('c')->select([
                    'c.id',
                    'c.admin_id',
                    'u.username',
                    'c.title',
                    'c.html',
                    'c.date_created',
                    'c.updated_at',
                ])
            ->leftJoin("user u", "u.id = c.admin_id")
            ->andWhere(['c.deleted_at' => null])
            ->orderBy("date_created DESC");
    }

    public function getPostingDataProvider($query)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['id'],
                'defaultOrder' => [
                ],
            ],
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        return $dataProvider;
    }

    public function addPosting()
    {

        if (!$this->validate()) {
            return null;
        }

        $posting = new Cms();
        $posting->title = $this->title;
        $posting->admin_id = Yii::$app->user->getId();
        $posting->html = $this->html;

        return $posting->save();

    }

    public function editPosting()
    {
        $post = $this->getPostById();

        if ($post == null) {
            Yii::$app->session->setFlash('error', 'Oops, something went wrong');
            return;
        }

        if (!$this->validate()) {
            Yii::$app->session->setFlash('error', 'Incorrect form submission ');
            return;
        }

        $post->title = $this->title;
        $post->html = $this->html;

        $post->update(false, ['title', 'html']);
        return;
    }

    public function deletePosting()
    {
        $post = $this->getPostById();
        $post->deleted_at = date("Y-m-d h:m:s",time());
        $post->update(false, ['deleted_at']);
    }


    public function getPostById()
    {
        return Cms::getById($this->id) ?? null;
    }
}