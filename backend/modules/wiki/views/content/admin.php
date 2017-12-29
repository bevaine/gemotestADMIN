<?php
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel asinfotrack\yii2\wiki\models\WikiSearch */
?>
<p>
    <?= Html::a('Создать', ['index'], ['class' => 'btn btn-success']) ?>
</p>
<?= GridView::widget([
	'dataProvider'=>$dataProvider,
	'filterModel'=>$searchModel,
	'columns'=>[
        ['class' => 'yii\grid\SerialColumn'],
		[
			'attribute' => 'id',
			//'width' => '100px'
		],
		//'isOrphan:boolean',
		'title:ntext',
		[
			'class' => 'yii\grid\ActionColumn'
		],
	],
]) ?>
