<?php
	/**
	 * This file uploads a file in the back end, without refreshing the page
	 *  
	 */
	echo "uploading...";
	session_start();
	$dirName="../files/";
	
	if (isset($_POST['id'])) { // start uploading
		
		@mkdir($dirName,0755);
		
		$uploadFile=$dirName.($_FILES[$_POST['id']]['name']);
		
		if(!is_dir($dirName)) {
			echo '<script>alert("Failed to find the final upload directory:'.$dirName.'");</script>';
		}
		if (!move_uploaded_file($_FILES[$_POST['id']]['tmp_name'], $dirName.($_FILES[$_POST['id']]['name']))) {	
			echo '<script>alert("Failed to upload file:'.$_FILES[$_POST['id']]['name'].'");</script>';
		}
		
		echo "uploading ".($uploadFile);
	}
	else { // while uploading
		
		if (isset($_GET['filename'])){
			
			$uploadFile=$dirName.($_GET['filename']);
			if (file_exists($uploadFile)) {
				
				echo "upload_done";
			}
			else {
				
				echo "<img id=\"upload_animation\" src='php/loading.gif' alt='uploading...' />";
			}
			
		} else {
			
			echo "this is invisible";
			
		}
	}
	
	
?>