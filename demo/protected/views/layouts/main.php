<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<!--[if lt IE 9]>
		<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet/less" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/less/styles.less" />
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/less-1.2.1.min.js"></script>
	<script type="text/javascript">

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-29040179-1']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

	</script>
</head>

<body id="top">

<?php $this->widget('BootNavbar',array(
	'brand'=>CHtml::encode(Yii::app()->name),
	'items'=>array(
		array(
			'class'=>'bootstrap.widgets.BootMenu',
			'items'=>array(
				array('label'=>'Home', 'url'=>'#'),
				array('label'=>'About', 'url'=>'#'),
				array('label'=>'Contact', 'url'=>'#'),
			),
		),
		'<div class="version pull-right">yii-bootstrap-0.9.8</div>',
	),
)); ?>

<div class="container">

	<div class="hero-unit">
		<h1><?php echo CHtml::encode(Yii::app()->name); ?></h1>
	</div>

	<?php if (!empty($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.BootCrumb', array(
			'links'=>$this->breadcrumbs,
		)); ?>
	<?php endif?>

	<div class="row">
		<?php echo $content; ?>
	</div>

	<hr />

	<footer>

		<p class="powered">
			Powered by <?php echo CHtml::link('Yii 1.1.9', 'http://www.yiiframework.com', array('target'=>'_blank')); ?> //
			<?php echo CHtml::link('jQuery 1.7.1', 'http://www.jquery.com', array('target'=>'_blank')); ?> //
			<?php echo CHtml::link('Yii-Bootstrap 0.9.8', 'http://www.yiiframework.com/extension/bootstrap', array('target'=>'_blank')); ?> //
			<?php echo CHtml::link('Bootstrap 2', 'http://twitter.github.com/bootstrap', array('target'=>'_blank')); ?> //
			<?php echo CHtml::link('LESS 1.2.1', 'http://www.lesscss.org', array('target'=>'_blank')); ?>
		</p>

		<p class="copy">
			&copy; Christoffer Niska 2011
		</p>

	</footer>

</div>

</body>
</html>
