<?php 
//require('includes/application_top.php');

// place this code inside a php file and call it f.e. "download.php"


$root = $_SERVER['DOCUMENT_ROOT'].mb_substr($_SERVER['PHP_SELF'],0,-mb_strlen(strrchr($_SERVER['PHP_SELF'],"/")));
//$root = strstr($root, "/admin",true);
$root = str_replace("/admin","",$root);


if(isset($_GET['file']) && isset($_GET['path'])) {

	$ftype = $_GET['file'];
	$fname = $_GET['path'];
	
	if($ftype=='img') 
		$rootPath = $root ."/images/users_badges/";
	else
		$rootPath = $root ."/images/users_names/";
	
	if(isset($_GET['type'])) {
		$rootPath = $root ."/images/users_files/";
	}
	
	$fullPath = $rootPath.$fname;
	if(file_exists($fullPath)) {
	
		if ($fd = fopen ($fullPath, "r")) {
			$fsize = filesize($fullPath);
			$path_parts = pathinfo($fullPath);
			$ext = strtolower($path_parts["extension"]);
			switch ($ext) {
				case "pdf":
				header("Content-type: application/pdf"); // add here more headers for diff. extensions
				header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
				break;
				default;
				header("Content-type: application/octet-stream");
				header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
			}
			header("Content-length: $fsize");
			header("Cache-control: private"); //use this to open files directly
			while(!feof($fd)) {
				$buffer = fread($fd, 2048);
				echo $buffer;
			}
		}
		fclose ($fd);
		exit;
	}
	exit;
}