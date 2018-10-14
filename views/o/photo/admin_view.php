<?php
/**
 * Album Photos (album-photo)
 * @var $this PhotoController
 * @var $model AlbumPhoto
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-photo
 *
 */

	$this->breadcrumbs=array(
		'Album Photos'=>array('manage'),
		$model->media_id=>array('view','id'=>$model->media_id),
		'View',
	);
?>

<div class="box">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'media_id',
			array(
				'name'=>'publish',
				'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->media_id)), $model->publish),
				'type' => 'raw',
			),
			array(
				'name'=>'cover',
				'value'=>$model->cover == 1 ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type' => 'raw',
			),
			'parent',
			array(
				'name'=>'album_id',
				'value'=>$model->album->title,
			),
			array(
				'name'=>'media',
				'value'=>$model->media ? CHtml::image(Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/album/'.$model->album_id.'/'.$model->media, 600, 600, 3)) : '-',
				'type' => 'raw',
			),
			array(
				'name'=>'media_filename',
				'value'=>$model->media ? $model->media : '-',
			),
			array(
				'name'=>'caption',
				'value'=>$model->caption ? $model->caption : '-',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
			),
			array(
				'name'=>'creation_search',
				'value'=>$model->creation->displayname ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
			),
			array(
				'name'=>'modified_search',
				'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
			),
		),
	)); ?>
</div>