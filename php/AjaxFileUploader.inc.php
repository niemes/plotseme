<?php
/**
 * This class uploads a file, without refreshing the page (Using Javascript)
 * 
 * @author Rochak Chauhan
 * 
 * @todo all the PHP 4.x users are requested to remove "PUBLIC", "PRIVATE" and "PROTECTED" keywords before the functions
 * @version 2 
 * 
 */
@session_start();
class AjaxFileuploader {
	// PHP 4.x users replace "PRIVATE" from the following lines with "var". Also remove all the PUBLIC, PRIVATE and PROTECTED Kaywords from the class
	private $uploadDirectory='../files/';
	private $uploaderIdArray=array();

	/**
	 * Constructor Function
	 * 
	 */
	public function AjaxFileuploader($uploadDirectory) {
		if (trim($uploadDirectory) != '' && is_dir($uploadDirectory)) {
			$this->uploadDirectory=trim($uploadDirectory);
		}
	}

	/**
	 * 
	 * This function return all the files in the upload directory, sorted according to their file types
	 *
	 * @return array
	 */		
	public function getAllUploadedFiles() {
		$returnArray = array();
		$allFiles = $this->scanUploadedDirectory();
		return $returnArray;
	}

	/**
	 * 
	 * This function scans uploaded directory and returns all the files in it
	 *  
	 * @return array
	 */
	private function scanUploadedDirectory() {
		$returnArray = array();
		if ($handle = opendir($this->uploadDirectory)) {
			while (false !== ($file = readdir($handle))) {
				if (is_file($this->uploadDirectory.$file)) {
					$returnArray[] = $file;
				}
			}
			closedir($handle);
		}
		else {
			die("<b>ERROR: </b> Could not read directory: ". $this->uploadDirectory);
		}
		return $returnArray;
	}

	/**
	 * This function returns html code for uploading a file
	 * 
	 * @param string $uploaderId
	 * 
	 * @return string
	 */
	public function showFileUploader($uploaderId) {
		if (in_array($uploaderId, $this->uploaderIdArray)) {
			die($uploaderId." already used. please choose another id.");
			return '';
		}
		else {
			$this->uploaderIdArray[] = $uploaderId;
			
			$agent = $_SERVER['HTTP_USER_AGENT'];
			
			if(preg_match("/firefox/si", $agent)) {
				return '<form id="formName'.$uploaderId.'" method="post" enctype="multipart/form-data" action="php/file_upload.php?dirname='.$this->uploadDirectory.'" target="iframe'.$uploaderId.'">
				<input type="hidden" name="id" value="'.$uploaderId.'" />							
				<span id="uploader'.$uploaderId.'">
				<input id="process_upload_button" name="'.$uploaderId.'" type="file" value="'.$uploaderId.'" onchange=\'return uploadFile(this,"'.$this->uploadDirectory.'")\' /></span>
				<span id="loading'.$uploaderId.'"></span>						
				<iframe style="display:none" name="iframe'.$uploaderId.'" src="php/file_upload.php" width="400" height="100" > </iframe>
				</form>';
				
			} else {
				return '<form id="formName'.$uploaderId.'" method="post" enctype="multipart/form-data" action="php/file_upload.php?dirname='.$this->uploadDirectory.'" target="iframe'.$uploaderId.'">
				<input type="hidden" name="id" value="'.$uploaderId.'" />							
				<span id="uploader'.$uploaderId.'">
				<input style="visibility:hidden; position:fixed; top:0; left:0;" id="process_upload_file" name="'.$uploaderId.'" type="file" value="'.$uploaderId.'" onchange=\'return uploadFile(this,"'.$this->uploadDirectory.'")\' /></span>
				<span id="loading'.$uploaderId.'"></span>						
				<iframe style="display:none" name="iframe'.$uploaderId.'" src="php/file_upload.php" width="400" height="100" > </iframe>
				</form>
				<input type="button" id="process_upload_button" value="upload file" onClick="var btnSubmitTags = document.getElementById(\'process_upload_file\');btnSubmitTags.click();" />';
				
			}		
		}


	}
}
?>