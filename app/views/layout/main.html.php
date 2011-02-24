<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?php echo $this->page_title?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="http://<?php echo $GLOBALS['host_base'] ?>/css/horde.css" media="screen" />
</head>
<body>
	<div class="area">
        <div class="inside">
            <?php echo $this->renderPartial('banner'); ?>
            <div class="podest" id="podest"></div>
            <?php echo $this->contentForLayout ?>
            <?php echo $this->renderPartial('footer');?>
        </div>
    </div>
</body>
<!-- Don't include yet, it's based on prototypejs -->
<!--<script src="js/informer.js"></script>-->
</html>