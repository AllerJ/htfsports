<?php

	$request_color = "#".$_GET['color'];

	list($r, $g, $b) = sscanf($request_color, "#%02x%02x%02x");
	
	$imagePath = "generic-user-w.png";
	
	$opacity = .5;
	
	$color = "rgba($r, $g, $b, $opacity)";

	$im = new Imagick(realpath($imagePath));
	$im->setImageAlphaChannel(Imagick::ALPHACHANNEL_EXTRACT);
	$im->setImageBackgroundColor($color);
	$im->setImageAlphaChannel(Imagick::ALPHACHANNEL_SHAPE);
    header("Content-Type: image/jpg");
    echo $im->getImageBlob();




?>