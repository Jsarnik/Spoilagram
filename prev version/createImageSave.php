<?php

		$image= $_POST['Image'];
		$overlay= $_POST['Overlay'];
		$X_pos= $_POST['X_pos'];
		$Y_pos= $_POST['Y_pos'];
		
		if ($image != ''){
			if ($overlay != '') {
			
			try{					
				$newImage = 'uploads/' . randomFileName() . '.jpg';	
				
				if (!copy($image, $newImage)){
					throw new Exception('Failed to copy!');
				}					

				$dest = cropImage($newImage, $X_pos, $Y_pos);
				$src = $overlay;
				$dest= imagecreatefromjpeg($dest);
				$src= imagecreatefrompng($src);

				imagecopy($dest, $src, 0, 0, 0, 0, 400, 400);

				// use output buffering to capture outputted image stream
				ob_start();
				imagejpeg($dest);
				$i = ob_get_clean();

				//Save file
				$fp = fopen ($newImage, 'w');
				fwrite ($fp, $i);
				fclose ($fp);				

				echo $newImage;				
				
			} catch (Exception $e) {
					echo 'error';
				}								
			}
			else {
				echo 'error';
			}
		}
		else {	
			echo 'error';			
		}								
					
		function cropImage($img, $x, $y) {

			$src= imagecreatefromjpeg($img);
			$dest = imagecreatetruecolor(400,400);

			$src_w = imagesx($src);
			$src_h = imagesy($src);

			imagecopy($dest, $src,$x,$y,0,0,$src_w,$src_h);	
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
				$randomName = "spoiled_" . randConsonant() . randNumbers() . randVowel() . randNumbers() . randConsonant(). randVowel() . randNumbers();				
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