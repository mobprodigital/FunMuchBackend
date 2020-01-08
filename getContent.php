<?php
//phpinfo(); die;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

header('Content-Type: application/json');
ob_start();
error_reporting(E_ALL); //E_ALL ^ E_NOTICE ^ E_DEPRECATED
ini_set('display_errors', 1);

$response = array();


if($_SERVER["REQUEST_METHOD"] == "GET")
{ 
    $type = $_GET['contentType']; 
    if(isset($_GET['source'])) {  $source = $_GET['source']; } else {  $source = ''; }
   
    
    if($type == 1) { $path = 'funmunch/games'; } 
    elseif($type == 2) 
    { 
        if($source === 'mobile'){ $path = 'funmunch/images-mobile'; }
        else{ $path = 'funmunch/images'; }
    } 
    elseif($type == 3) { $path = 'funmunch/jokes'; }
    elseif($type == 4) { $path = 'funmunch/videos'; } 
    elseif($type == 5) { $path = 'funmunch/shayri'; }
    else { $path = 'funmunch'; }
    $response = getCategory($path,$type);

}
else
{
	header("HTTP/1.0 404 Not Found");
	die;
}
//echo '<pre>'; print_r($response);
echo json_encode($response,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

function getCategory($path,$type)
{
    //echo $path; echo '<br>'; echo $type; die;
    $dir          = $path; //path
    $contentarray = array(); //main array
    $finalarray = array();
    
    if(is_dir($dir))
    { 
        if($dh = opendir($dir))
        {
            while(($file = readdir($dh)) != false)
            {
                if($file == "." or $file == "..")
                { //... 
                } 
                else 
                { 
                  if($type == 1) 
                  { 
		    //echo $path; echo '<br>'; echo $file; echo '<br>'; echo $type;
		          $finalData = getContent($path,$file,$type); 
                    $contentarray1 = array('categoryName' => $file,"content" => $finalData);
                  } 
                  elseif($type == 2)
                  {  $finalData = getContent($path,$file,$type); 
                     $contentarray1 = array('categoryName' => $file,"content" => $finalData); 
                  } 
                  elseif($type == 3) 
                  { $finalData = getContent($path,$file,$type);  
                    $contentarray1 = array('categoryName' => $file,"content" => $finalData);
                  }
                  elseif($type == 4) 
                  {  $finalData = getContent($path,$file,$type); 
                     $contentarray1 = array('categoryName' => $file,"content" => $finalData);
                  } 
                  elseif($type == 5) 
                  { $finalData = getContent($path,$file,$type);  
                    $contentarray1 = array('categoryName' => $file,"content" => $finalData);
                  }
                  else {  $contentarray1 = array('contentType' => $file); } 
                 
                  array_push($contentarray, $contentarray1);
                }
            }
        }
    return $return_array = array('message'=> "success", 'status'=>true, 'data'=> $contentarray);
    } 
}
function getContent($path,$path1,$type)
{ 
    //echo $path; echo '<br>'; echo $path1; echo '<br>'; echo $type; die;
    //$additional_url = 'riccha_dev/';
    $additional_url = '';
    //echo $domain = $_SERVER['SERVER_NAME'];
    $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'];
    $finalarray = array();
    $dir    = $path.'/'.$path1; 
    //echo '<br>';
    $files = scandir($dir, 1);
    foreach($files as $filename)
    {
       if($filename == "." or $filename == "..") { // ...  
       } 
       else 
       { 
            if($type == 3)
            {  
                // read file contents
		$doc = $_SERVER['DOCUMENT_ROOT']; 
                $baseurl = $doc.'/'.$additional_url.$dir.'/'.$filename; 
                //$fileData = file_get_contents($baseurl);
                $fileToRead = fopen($baseurl,"r");
		$fileData = array();
                while(! feof($fileToRead))
                {
                    $fileData[] = fgets($fileToRead);
                }
                fclose($fileToRead);
                // ends
                $name = substr($filename, 0, strrpos($filename, ".")); // get file name
                $finalarray[] = array('title' => $name,"fileContent" => $fileData);
            }
            elseif($type == 5)
            {  
                // read file contents
		$doc = $_SERVER['DOCUMENT_ROOT']; 
                $baseurl = $doc.'/'.$additional_url.$dir.'/'.$filename; 
                //$fileData = file_get_contents($baseurl);
                $fileToRead = fopen($baseurl,"r");
		        $fileData = array();
                while(! feof($fileToRead))
                {
                    $fileData[] = fgets($fileToRead);
                }
                fclose($fileToRead);
                // ends
                $name = substr($filename, 0, strrpos($filename, ".")); // get file name
                $finalarray[] = array('title' => $name,"fileContent" => $fileData);
            }
            elseif($type == 4)
            {  

               $finalarray[] = getVideoContent($dir,$filename,$type);
            } 
            elseif($type == 1)
            {
                //echo $dir; echo '<br>'; echo $filename; echo '<br>'; echo $type; die;
		        $finalarray[] = getVideoContent($dir,$filename,$type);
            }
            else 
            { 
                $name = substr($filename, 0, strrpos($filename, ".")); // get file name
                
                $finalarray[] = array('title' => $name,"fileContent" => $domain.'/'.$additional_url.$dir.'/'.$filename);
            }
       }
    }
    
    return $finalarray;
}

function getVideoContent($path,$path1,$type)
{  
    $doc = $_SERVER['DOCUMENT_ROOT']; 
    $additional_url = '';
    $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'];
   
    $finalarray1 = array();
    $dir    = $path.'/'.$path1;
    
    $files = scandir($dir, 1);
    $img = "";
    $imgpath = "";
	
    foreach($files as $filename){ 
        
        if($filename == "." or $filename == "..")
        { 
			continue;
        } 
        else 
        {  
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
			$mime= finfo_file($finfo, $doc.'/'.$additional_url.$dir.'/'.$filename) . "\n";
			finfo_close($finfo);
			
			
			
			if($type == 4) 
				{
					if(strstr($mime, "video/"))
					{  $vid = $filename; } 
					elseif(strstr($mime, "image/"))
					{  $img = $filename; $imgpath = $domain.'/'.$dir.'/'.$img;}
				} 
			elseif($type == 1)
				{
					if(strstr($mime, "image/")){ 
						$img = $filename; 
						$imgpath = $domain.'/'.$dir.'/'.$img;

						$arra[]['imagePath']		= $imgpath;
					}else { 
						$gamePath = $domain.'/'.$additional_url.$dir.'/'.$filename;
						$arra[]['gamePath']		= $gamePath;
					}
				}
		   
			if($type == 4) {
					$finalarray1 = array('title' => $path1, "thumbnail" => $imgpath , "fileContent" => $domain.'/'.$additional_url.$dir.'/'.$vid);
				}
		   
		}
 	
	}
	
	if($type == 1)
	{
		$finalarray1['title'] = $path1;
	    if(isset($arra[0]['imagePath']) && (!empty($arra[0]['imagePath']))){
			$finalarray1['thumbnail']	= $arra[0]['imagePath'];
		   
	    }else{
			$finalarray1['thumbnail']	= $arra[1]['imagePath'];

	    }
	   
	    if(isset($arra[0]['gamePath']) && (!empty($arra[0]['gamePath']))){
			$finalarray1['fileContent']	= $arra[0]['gamePath'];
		   
	    }else{
			$finalarray1['fileContent']	= $arra[1]['gamePath'];

	    }
	}		
				
	return $finalarray1;
}



?>