<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="title" content="Spoilagram"> 
<meta name="description" content="Add watermarks to your photos. Render them commercially worthless. Protect and Protest.">
<meta name="keywords" content="spoil, instagram, photos, pictures, images, watermarks, gram, facebook, tag, hash, protect, protest, social, media, rebel, twitter"> 
<meta name="robots" content="NOODP">

<!--For Facebook-->
<meta property="og:image" content="http://www.spoilagram.com/images/social_thumb.jpeg"/>
<meta property="og:url" content="http://www.spoilagram.com"/>
<meta property="og:site_name" content="Spoilagram"/>
<meta property="og:description" content="Add watermarks to your photos. Render them commercially worthless. Protect and Protest."/>

<title>Spoilagram</title>
<link rel="stylesheet" type="text/css" href="framework.css"/>
<link rel="stylesheet" type="text/css" href="style.css"/>
<style type="text/css">
</style>

<script type="text/javascript" src="script.js"></script>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<!-- Fancybox -->
<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

</head>

<body>

<script type="text/javascript">
var browser = navigator.userAgent.toLowerCase();
if(browser.indexOf("firefox") > -1)
{
  $(document).on('click', 'label', function(e) {
    if(e.currentTarget === this && e.target.nodeName !== 'INPUT') {
      $(this.control).click();
    }
  });
} 
</script>


<div id="Container_Window">
	<div id="Container_Content"> 	
	<div id="progressBar"><div id="progress-Color"></div></div>
<div id="status"></div>
			<div id="Header"></div><!--Header-->

<?php
					
					$setImage = "images/stock.png";							
					
					if (isset($_POST['upload'])){

						$destination = "uploads/";
						$uploadedfile = basename( $_FILES['image']['name']);				
						$setImage = $destination.$uploadedfile;
						
						$ext = strtolower(pathinfo($uploadedfile, PATHINFO_EXTENSION));

						if ($uploadedfile != ''){
							if ($ext != 'jpeg' && $ext != 'jpg' && $ext != 'png' && $ext != 'gif') {
								echo '<script type="text/javascript">'
									, 'errorMessage(\'File is not a supported image type! Supported types: .JPEG, .PNG, .GIF\');'
									, '</script>';
									$setImage = 'images/stock.png';
									return '';
							}
											
							if(move_uploaded_file($_FILES['image']['tmp_name'], $setImage)) {
								//Check for image size must be > 400x400
								list($width, $height) = getimagesize($setImage);
									if ($width < 400 || $height < 400) {
										//JAVASCRIPT FOR TOO SMALL OF AN IMAGE
										unlink($setImage);
										$setImage = 'images/stock.png';
										$Success = false;
										echo '<script type="text/javascript">'
										, 'errorMessage(\'Image is too small, images must be greater than 400x400 pixels!\');'
										, '</script>';
									}
								else {
									if  ($width > 400 && $height > 400) {
									$newName = "uploads/" . randomFileName() . "." . $ext;
									rename ($setImage, $newName);									
									//resizeImage
									resizeImage(strtolower($ext), $newName, $width, $height);
									
									$setImage = $newName;	
									}
									$Success = true;
								}
							} 
						}
					}
					
					function resizeImage($filetype, $img, $width, $height)
					{
						if (($filetype == 'jpg') || ($filetype == 'jpeg')) {
							$src= imagecreatefromjpeg($img);
						}
						else if ($filetype == 'png') {
							$src= imagecreatefrompng($img);
						}
						else if ($filetype == 'gif') {
							$src= imagecreatefromgif($img);
						}
						else {
							echo '<script type="text/javascript">'
							, 'errorMessage(\'An error has occured!\');'
							, '</script>';
						}
						
						//find out if width or height is greater and set the smallest = 400px to maintain proportions

						if ($width > 450 || $height > 450) {
						$min_dimension = 450;
						} else {
						$min_dimension = 400;
						}

						if ($width > $height) {
							$new_h = $min_dimension;
							$new_w = ($min_dimension/$height) * $width;
							}
						else {
							$new_w = $min_dimension;
							$new_h = ($min_dimension/$width) * $height;
							}

						$dest = imagecreatetruecolor($new_w,$new_h);

						imagecopyresized($dest,$src,0,0,0,0,$new_w,$new_h,$width,$height);
				
						// use output buffering to capture outputted image stream
						ob_start();
						imagejpeg($dest);
						$i = ob_get_clean(); 
						
						// Save file
						$fp = fopen ($img,'w');
						fwrite ($fp, $i);
						fclose ($fp);	

						return $img;						
					}
					
					function randomFileName() {
											
						function randVowel() {
							$vowels = array("a", "e", "o", "u");
							$random_key = array_rand($vowels,1);
							return $vowels[$random_key];
						}

						function randConsonant() {
							$consonants = array("B", "C", "D", "V", "G", "T", "Y", "Z", "X", "Q","M", "P", "R", "F", "P", "L");
							$random_key = array_rand($consonants,1);
							return $consonants[$random_key];
						}

						function randNumbers() {
							$numbers = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
							$random_key = array_rand($numbers,1);
							return $numbers[$random_key];
						}
						
						function createName() {
							$randomName = randConsonant() . randNumbers() . randVowel() . randNumbers() . randConsonant(). randVowel() . randNumbers() . '_OG';				
							return $randomName;
						}
						
						if (file_exists('uploads/' . createName())) {
							createName();
						}
						else {
							return createName();
						}
											
					}						
					
				?>
		
			<form method="post" action="index.php" enctype="multipart/form-data" id="doStuff" onsubmit="javascript:startUpload();">
				<input style="visibility:hidden;" type="submit" id="upload" name="upload" />
				<input style="visibility:hidden;" id="original" name="original" type="text" value="<?php echo $setImage; ?>"/>
				<input style="visibility:hidden;" id="spoil" name="spoil" value="<?php echo $_POST['spoil']; ?>" />
				<label id="fdialog" for="image"><img src="images/upload_btn.png" id="Upload_btn"></label>
<input type="file" name="image" id="image" style="visibility:hidden;">
			</form>

			<!---->
			<script type="text/javascript">				
					$('#image').change(function(e){	
					$('#upload').click();	
					});
			</script>
			
<div id="move">Click and drag to crop image!</div>

            <div id="WaterMarks" style="display:none;">
<img src="images/CloseButton.png" id="close_button" onclick="javascript:displayOverlay(false)">
</div>

<div id="Photo_Border"></div>
			<div id="Photo_Frame">
				<img id="uploadedImage" class="drag" src="<?php echo $setImage; ?>" onmouseover="javascript:moveTool(true);" onmouseout="javascript:moveTool(false);" />
			</div>

				<?php
					if ($Success){
						echo '<script type="text/javascript">'
						, 'uploadSave(false);'
						, '</script>';
					}					
				?>

            <div id="Step1"></div>
            <div id="Step1_txt"><b>Upload a photo: <span style="font-size: .7em;">(400x400 px or larger)</span></div>		
            <div id="Step2"></div>
            <div id="Step2_txt"><b>Choose a Watermark:</b></div>
            <div id="Thumb_Container">
            	<div id="Thumb_Pages">
                	<div class="Pages">
                        <div class="Thumbs"><img class="thumbs" id="thumb1" src="images/thumbs/01allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb1');" onmouseout="javascript:thumbnails(false, 'thumb1')"; onclick="javascript:Overlay('thumb1','01allwatermarks');"/>Don't</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb2" src="images/thumbs/02allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb2');" onmouseout="javascript:thumbnails(false, 'thumb2');" onclick="javascript:Overlay('thumb2','02allwatermarks');"/>LOL</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb3" src="images/thumbs/03allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb3');" onmouseout="javascript:thumbnails(false, 'thumb3');" onclick="javascript:Overlay('thumb3','03allwatermarks');"/>Pay up</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb4" src="images/thumbs/04allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb4');" onmouseout="javascript:thumbnails(false, 'thumb4');" onclick="javascript:Overlay('thumb4','04allwatermarks');"/>Screw u</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb5" src="images/thumbs/05allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb5');" onmouseout="javascript:thumbnails(false, 'thumb5');" onclick="javascript:Overlay('thumb5','05allwatermarks');"/>Up yours</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb6" src="images/thumbs/06allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb6');" onmouseout="javascript:thumbnails(false, 'thumb6');" onclick="javascript:Overlay('thumb6','06allwatermarks');"/>Spoiled</div>                        			
						
                        <div class="Thumbs"><img class="thumbs" id="thumb7" src="images/thumbs/07allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb7');" onmouseout="javascript:thumbnails(false, 'thumb7');" onclick="javascript:Overlay('thumb7','07allwatermarks');"/>Do not touch</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb8" src="images/thumbs/08allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb8');" onmouseout="javascript:thumbnails(false, 'thumb8');" onclick="javascript:Overlay('thumb8','08allwatermarks');"/>Do not cross</div>          

                        <div class="Thumbs"><img class="thumbs" id="thumb9" src="images/thumbs/09allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb9');" onmouseout="javascript:thumbnails(false, 'thumb9');" onclick="javascript:Overlay('thumb9','09allwatermarks');"/>Arrow spears</div>
                        
                        </div>
                        <div class="Pages">								
						
                        <div class="Thumbs"><img class="thumbs" id="thumb10" src="images/thumbs/10allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb10');" onmouseout="javascript:thumbnails(false, 'thumb10');" onclick="javascript:Overlay('thumb10','10allwatermarks');"/>Barbed</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb11" src="images/thumbs/11allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb11');" onmouseout="javascript:thumbnails(false, 'thumb11');" onclick="javascript:Overlay('thumb11','11allwatermarks');"/>Smile</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb12" src="images/thumbs/12allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb12');" onmouseout="javascript:thumbnails(false, 'thumb12');" onclick="javascript:Overlay('thumb12','12allwatermarks');"/>Syke</div>                        			
						
                        <div class="Thumbs"><img class="thumbs" id="thumb13" src="images/thumbs/13allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb13');" onmouseout="javascript:thumbnails(false, 'thumb13');" onclick="javascript:Overlay('thumb13','13allwatermarks');"/>Skullz</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb14" src="images/thumbs/14allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb14');" onmouseout="javascript:thumbnails(false, 'thumb14');" onclick="javascript:Overlay('thumb14','14allwatermarks');"/>Turd</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb15" src="images/thumbs/15allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb15');" onmouseout="javascript:thumbnails(false, 'thumb15');" onclick="javascript:Overlay('thumb15','15allwatermarks');"/>All my base</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb16" src="images/thumbs/16allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb16');" onmouseout="javascript:thumbnails(false, 'thumb16');" onclick="javascript:Overlay('thumb16','16allwatermarks');"/>Stache</div>    

                        <div class="Thumbs"><img class="thumbs" id="thumb17" src="images/thumbs/17allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb17');" onmouseout="javascript:thumbnails(false, 'thumb17');" onclick="javascript:Overlay('thumb17','17allwatermarks');"/>Ninjas</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb18" src="images/thumbs/18allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb18');" onmouseout="javascript:thumbnails(false, 'thumb18');" onclick="javascript:Overlay('thumb18','18allwatermarks');"/>404 not  found</div>				
						
                        </div>
                        <div class="Pages">								
						
                        <div class="Thumbs"><img class="thumbs" id="thumb19" src="images/thumbs/19allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb19');" onmouseout="javascript:thumbnails(false, 'thumb19');" onclick="javascript:Overlay('thumb19','19allwatermarks');"/>Do not disturb</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb20" src="images/thumbs/20allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb20');" onmouseout="javascript:thumbnails(false, 'thumb20');" onclick="javascript:Overlay('thumb20','20allwatermarks');"/>Guy Fawkes</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb21" src="images/thumbs/21allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb21');" onmouseout="javascript:thumbnails(false, 'thumb21');" onclick="javascript:Overlay('thumb21','21allwatermarks');"/>Handcuffs</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb22" src="images/thumbs/22allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb22');" onmouseout="javascript:thumbnails(false, 'thumb22');" onclick="javascript:Overlay('thumb22','22allwatermarks');"/>Password denied</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb23" src="images/thumbs/23allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb23');" onmouseout="javascript:thumbnails(false, 'thumb23');" onclick="javascript:Overlay('thumb23','23allwatermarks');"/>Fail</div>
                        
                        <div class="Thumbs"><img class="thumbs" id="thumb24" src="images/thumbs/24allspoil_thumbs.png" onmouseover="javascript:thumbnails(true, 'thumb24');" onmouseout="javascript:thumbnails(false, 'thumb24');" onclick="javascript:Overlay('thumb24','24allwatermarks');"/>Dead end</div>           
                        </div>                                        
                        </div>
            </div>
            <div id="Page_Num">Page 1/3</div>
            <div id="left" onclick="javascript:divSlide('Thumb_Pages', 2);"></div>  
            <div id="right" onclick="javascript:divSlide('Thumb_Pages', 1);"></div>  
            
    <div class="Divider"></div>  
    
    <div id="Step3"></div>
    <div id="Step3_txt"><b>Save & post to your favorite social networks:</b></div>
    <div id="Save_btn" class="pointers" onclick="javascript:uploadSave(true);"></div>
			
				<a href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.spoilagram.com" target="_blank"><div id="Facebook"></div></a>

<a href="http://www.twitter.com/home?status=Protect%20and%20Protest. http%3A%2F%2Fwww.spoilagram.com %23Spoilagram" target="_blank"><div id="Twitter"></div></a>

<div style="position:absolute; top:800px; width:1200px;"><div style="display: block; text-align: center; margin: 0 auto; font-size: 06pt; ">Created by <a href="http://antoniomarcato.com">Antonio Marcato</a>, <a href="https://twitter.com/brian_frost">Brian Frost</a> & <a href="mailto:Sarnik.Justin@gmail.com">Justin Sarnik</a></div></div>

    </div><!--Container_Content-->
</div><!--Container_Window-->
</body>
</html>	