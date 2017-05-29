<?php
/**
 * ViewAlbumLikes
 * version: 0.1.4
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 4 May 2017, 16:57 WIB
 * @link https://github.com/ommu/mod-photo-album
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "_view_album_likes".
 *
 * The followings are the available columns in table '_view_album_likes':
 * @property string $like_id
 * @property string $album_id
 * @property string $likes
 * @property string $unlikes
 * @property string $like_all
 */
class ViewAlbumLikes extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewAlbumLikes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '_view_album_likes';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'like_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('like_id, album_id', 'length', 'max'=>11),
			array('likes, unlikes', 'length', 'max'=>23),
			array('like_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('like_id, album_id, likes, unlikes, like_all', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'like_id' => Yii::t('attribute', 'Like'),
			'album_id' => Yii::t('attribute', 'Album'),
			'likes' => Yii::t('attribute', 'Likes'),
			'unlikes' => Yii::t('attribute', 'Unlikes'),
			'like_all' => Yii::t('attribute', 'Like All'),
		);
		/*
			'Like' => 'Like',
			'Album' => 'Album',
			'Likes' => 'Likes',
			'Unlikes' => 'Unlikes',
			'Like All' => 'Like All',
		
		*/
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.like_id',$this->like_id);
		$criteria->compare('t.album_id',$this->album_id);
		$criteria->compare('t.likes',$this->likes);
		$criteria->compare('t.unlikes',$this->unlikes);
		$criteria->compare('t.like_all',$this->like_all);

		if(!isset($_GET['ViewAlbumLikes_sort']))
			$criteria->order = 't.like_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			$this->defaultColumns[] = 'like_id';
			$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'unlikes';
			$this->defaultColumns[] = 'like_all';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			//$this->defaultColumns[] = 'like_id';
			$this->defaultColumns[] = 'album_id';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'unlikes';
			$this->defaultColumns[] = 'like_all';
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

}