<?php
/**
 * WidgetAlbumPhoto
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-photo
 *
 */

class WidgetAlbumPhoto extends CWidget
{

	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() 
	{
		$module = strtolower(Yii::app()->controller->module->id);
		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		$currentModule = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
		$currentModuleAction = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		
		//import model
		Yii::import('application.vendor.ommu.album.models.AlbumPhoto');
		Yii::import('application.vendor.ommu.album.models.Albums');
		
		$criteria=new CDbCriteria;
		$criteria->condition = 'publish = :publish';
		$criteria->params = array(
			':publish'=>1,
		);
		$criteria->order = 'creation_date DESC';
		$model = AlbumPhoto::model()->findAll($criteria);

		$this->render('album_photo',array(
			'model' => $model,
			'module'=>$module,
			'controller'=>$controller,
			'action'=>$action,
			'currentAction'=>$currentAction,
			'currentModule'=>$currentModule,
			'currentModuleAction'=>$currentModuleAction,
		));
	}
}
