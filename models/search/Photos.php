<?php
/**
 * Photos
 *
 * Photos represents the model behind the search form about `ommu\album\models\Photos`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 6 January 2020, 02:23 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */

namespace ommu\album\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\album\models\Photos as PhotosModel;

class Photos extends PhotosModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'album_id', 'member_id', 'user_id', 'creation_id', 'modified_id'], 'integer'],
			[['photo', 'title', 'caption', 'creation_date', 'modified_date', 'updated_date', 'albumTitle', 'memberDisplayname', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = PhotosModel::find()->alias('t');
        } else {
            $query = PhotosModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			// 'album album', 
			// 'member member', 
			// 'user user', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['albumTitle', '-albumTitle'])) || (isset($params['albumTitle']) && $params['albumTitle'] != '')) {
            $query->joinWith(['album album']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['memberDisplayname', '-memberDisplayname'])) || (isset($params['memberDisplayname']) && $params['memberDisplayname'] != '')) {
            $query->joinWith(['member member', 'user user']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')) {
            $query->joinWith(['creation creation']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')) {
            $query->joinWith(['modified modified']);
        }

		$query->groupBy(['id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['albumTitle'] = [
			'asc' => ['album.title' => SORT_ASC],
			'desc' => ['album.title' => SORT_DESC],
		];
		$attributes['memberDisplayname'] = [
			'asc' => ['member.displayname' => SORT_ASC],
			'desc' => ['member.displayname' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.album_id' => isset($params['album']) ? $params['album'] : $this->album_id,
			't.member_id' => isset($params['member']) ? $params['member'] : $this->member_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['trash'])) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        } else {
            if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                $query->andFilterWhere(['IN', 't.publish', [0,1]]);
            } else {
                $query->andFilterWhere(['t.publish' => $this->publish]);
            }
        }

        if (isset($params['memberDisplayname']) && $params['memberDisplayname'] != '') {
            $query->andWhere(['or', 
                ['like', 'member.displayname', $this->memberDisplayname],
                ['like', 'user.displayname', $this->memberDisplayname]
            ]);
        }

		$query->andFilterWhere(['like', 't.photo', $this->photo])
			->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.caption', $this->caption])
			->andFilterWhere(['like', 'album.title', $this->albumTitle])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
