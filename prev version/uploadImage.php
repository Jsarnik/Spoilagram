				<?php




if (isset($_POST['upload'])){
						$destination = "uploads/";
						$uploadedfile = basename( $_FILES['image']['name']);				
						$setImage = $destination.$uploadedfile;
						
						$ext = pathinfo($uploadedfile, PATHINFO_EXTENSION);

						if ($uploadedfile != ''){
							if ($ext != 'jpeg' && $ext != 'jpg' && $ext != 'png' && $ext != 'gif') {
								echo '<script type="text/javascript">'
									, 'errorMessage(\'File is not a supported image type! Supported types: .JPEG, .PNG, .GIF\');'
									, '</script>';
									$setImage = 'images/stock.png';
									return '';
							}
											
							if(move_uploaded_file($_FILES['image']['tmp_name'], $setImage)) {
								//Check for image size must be > 540x540
								list($width, $height) = getimagesize($setImage);
									if ($width < 540 || $height < 540) {
										//JAVASCRIPT FOR TOO SMALL OF AN IMAGE
										unlink($setImage);
										$setImage = 'images/stock.png';
										echo '<script type="text/javascript">'
										, 'errorMessage(\'Image is too small, images must be greater than 540x540 pixels!\');'
										, '</script>';
									}
								else {
									if  ($width > 540 && $height > 540) {
									$newName = "uploads/" . randomFileName() . "." . $ext;
									rename ($setImage, $newName);
									$setImage = $newName;
									//resizeImage($ext, $newName, $width, $height);
									}
									$Success = true;
								}
							} 
						}
					}
					
					function randomFileName() {
						
						function randVowel() {
							$vowels = array("a", "e", "o", "u");
							$random_key = array_rand($vowels,1);
							return $vowels[$random_key];
						}

						function randConsonant() {
							$consonants = array("B", "C", "D", "V", "G", "T", "Y", "Z", "X", "Q");
							$random_key = array_rand($consonants,1);
							return $consonants[$random_key];
						}

						function randNumbers() {
							$numbers = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
							$random_key = array_rand($numbers,1);
							return $numbers[$random_key];
						}

						$randomName = "spoiled_" . randConsonant() . randNumbers() . randVowel() . randNumbers() . randConsonant(). randVowel() . randNumbers();
						
						return $randomName;
					}	
					
				?>