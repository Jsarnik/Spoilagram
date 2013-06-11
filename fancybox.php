<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Spoilagram</title>
<link rel="stylesheet" type="text/css" href="framework.css"/>
<link rel="stylesheet" type="text/css" href="style.css"/>
<style type="text/css">

#fancybox {
	height: 320px;
	width: 320px;
	position: relative;
}
.links {
float:left;
padding:10px;
}
</style>

</head>

<body style="height:auto;">

<?php
$newImage = $_POST['newImage'];
$imagelink = basename($newImage);

$fbStatus = 'Add%20watermarks%20to%20your%20photos.%20Render%20them%20commercially%20worthless.%20Protect%20and%20Protest. http%3A%2F%2Fwww.spoilagram.com%2Fuploads%2F' . $imagelink;

$twStatus = 'Protect%20and%20Protest. http%3A%2F%2Fwww.spoilagram.com%2Fuploads%2F' . $imagelink;

?>


<div id="fancybox">
	<div style="width:320px;">
		<div style="position: absolute; left: 50px; background-color: #f9f9f9; text-align:center;">
				<div>SHARE!</div>
				
				<a href="http://www.facebook.com/sharer.php?u=<?php echo $fbStatus; ?>" target="_blank"><img class="links" src="images/facebook-btn.png"/></a>
<a href="http://www.twitter.com/home?status=<?php echo $twStatus; ?> %23Spoilagram" target="_blank"><img class="links" src="images/tweet-btn.png"/></a>
		</div>
	</div>
<img src="<?php echo $newImage; ?>"/>
</div>
</body>
</html>