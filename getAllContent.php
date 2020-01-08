<?php
//phpinfo(); die;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

ob_start();
error_reporting(0); //E_ALL ^ E_NOTICE ^ E_DEPRECATED
ini_set('display_errors', 0);

$response = array();



if($_SERVER["REQUEST_METHOD"] == "GET")
{ 
    $type = $_GET['contentType'];
    $source = $_GET['source'];
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
    //echo $path;
    $response = getCategory($path,$type);

}
else
{
	header("HTTP/1.0 404 Not Found");
	die;
}

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
                    //echo "hellllllo"; die;
                    //echo $path; echo '<br>'; echo $file; echo '<br>'; echo $type; die;
		            $finalData = getContent($path,$file,$type); 
                    //$contentarray1 = array('categoryName' => $file,"content" => $finalData);
                    //$contentarray1 = array('categoryName' => $file,"content" => array($finalData));
                    $contentarray1 = array('categoryName' => $file,"content" => $finalData);
		          } 
                  elseif($type == 2)
                  {  
		             $finalData = getContent($path,$file,$type); 
                     //$contentarray1 = array('categoryName' => $file,"content" => array($finalData)); 
                     $contentarray1 = array('categoryName' => $file,"content" => $finalData); 
		          } 
                  elseif($type == 3) 
                  { 
                    $finalData = getContent($path,$file,$type);  
                    //$contentarray1 = array('categoryName' => $file,"content" => array($finalData));
                    $contentarray1 = array('categoryName' => $file,"content" => $finalData);
		          }
                  elseif($type == 4) 
                  {  
                      $finalData = getContent($path,$file,$type); 
                     //$contentarray1 = array('categoryName' => $file,"content" => array($finalData));
                      $contentarray1 = array('categoryName' => $file,"content" => $finalData);
		          } 
                  elseif($type == 5) 
                  { 
                    $finalData = getContent($path,$file,$type);  
                    //$contentarray1 = array('categoryName' => $file,"content" => array($finalData));
                    $contentarray1 = array('categoryName' => $file,"content" => $finalData);
		          }
                  else 
                  {  
                    $contentarray1 = array('contentType' => $file); } 
                    //shuffle($contentarray1);
                    //print_r($contentarray1); die;
                    array_push($contentarray, $contentarray1);
                    shuffle($contentarray);
                  }
                }
            }
            return $return_array = array('message'=> "success", 'status'=>true, 'data'=> $contentarray);
        } 
}

function getContent($path,$path1,$type)
{  
    //echo 'hii'; echo '<br>'; echo $path; echo '<br>'; echo $path1; echo $type; die;
  
    $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'];
    $finalarray = array();
    $dir    = $path.'/'.$path1;
    $files = scandir($dir, 1);
    shuffle($files);
    foreach($files as $filename)
    {
       if($filename == "." or $filename == "..") 
       { // ...  
       } 
       else 
       { 
            if($type == 3)
            {  
                // read file contents
		        $doc = $_SERVER['DOCUMENT_ROOT'];
                //echo $baseurl = $doc.'/'.$dir.'/'.$filename;
		        $baseurl = $doc.'/'.$dir.'/'.$filename;
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
                $baseurl = $doc.'/'.$dir.'/'.$filename;
		        //$baseurl = $doc.'/riccha_dev/'.$dir.'/'.$filename;
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
                //echo 'in video'; die;
                $finalarray[] = getVideoContent($dir,$filename,$type);
            }
            else 
            { 
                $name = substr($filename, 0, strrpos($filename, ".")); // get file name
		        //echo $domain.'/'.$dir.'/'.$filename;
                //$finalarray = array('title' => $name,"fileContent" => $domain.'/riccha_dev/'.$dir.'/'.$filename);
		        $finalarray[] = array('title' => $name,"fileContent" => $domain.'/'.$dir.'/'.$filename);
            }
       }
    }
    
    return $finalarray;
}

function getVideoContent($path,$path1,$type)
{  
    //echo $path; echo '<br>'; echo $path1; echo '<br>'; echo $type; die;
    $additional_url ='';
    $doc = $_SERVER['DOCUMENT_ROOT']; 
    $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'];
    $finalarray1 = array();
    $dir    = $path.'/'.$path1;
    $files = scandir($dir, 1);
    shuffle($files);
    
	$img = "";
	$imgpath = "";
	foreach($files as $filename)
    {
        if($filename == "." or $filename == "..")
        {  
		    continue;
        } 
        else 
        {   
            //echo $doc.'/'.$additional_url.$dir.'/'.$filename; die;
            //$finfo = mime_content_type( $filename );
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	        
	        $mime= finfo_file($finfo, $doc.'/'.$additional_url.$dir.'/'.$filename) . "\n";
	        finfo_close($finfo);
            //$mime = mime_content_type($doc.'/'.$additional_url.$dir.'/'.$filename);
            if($type == 4) 
            {
                if(strstr($mime, "video/"))
                {  $vid = $filename;} 
                elseif(strstr($mime, "image/"))
                {  $img = $filename;  $imgpath = $domain.'/'.$dir.'/'.$img;}
            } 
            elseif($type == 1)
            {
                if(strstr($mime, "image/"))
                {  
                    $img = $filename;
                    $imgpath = $domain.'/'.$dir.'/'.$img; 
                    $arra[]['imagePath']		= $imgpath;
		        }
		        else 
                { 
                    $gamePath = $domain.'/'.$dir.'/'.$filename;
                    $arra[]['gamePath']		= $gamePath;
                }
			    
            }

            if($type == 4) 
            {
                $finalarray1 = array('title' => $path1, "thumbnail" => $imgpath , "fileContent" => $domain.'/'.$dir.'/'.$vid);
            } 
			// elseif($type == 1)
            // {
            //     $finalarray1 = array('title' => $path1, "thumbnail" => $imgpath , "fileContent" => $domain.'/'.$dir.'/'.$filename); 
            // } 
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
    //print_r($finalarray1);
    return $finalarray1;
}





?>