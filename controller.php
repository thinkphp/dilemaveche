<?php

if(isset($_GET['page']) && $_GET['page'] != '' && $_GET['do'] == 'Ajax') {

     //specified the filename to a stream
     $filename = "lastpage.txt";

      //open the file for reading only 
     //and created a pointer to file
     /*
       @param $filename (String) - specified a local file then it will try to open a stream on that file.
       @param $mode              - the mode parameter specifies the type of access you require to the stream.
                                 It may be any of the following:
                                    - 'r' open for reading only;place the file pointer at the beginning of the file
                                    - 'r+' open for reading and writing; 
                                    - 'w'  for writing only
                                    - 'w+' for reading and writing only
                                    - 'a' for the writing only;place the file pointer at the end of the file.
                                    - 'a+' for reading and writing
                                    - 'x' an error of level E_WARNING. if the file does not exist, attempt to create it.
                                    - 'x+' generating an error of level E_WARNING

      @returns Retuns a file pointer resource on success OR FALSE on error.  
      */
     $handle = fopen($filename,"r");

        //I want to get contents of a file into a string (which is the lastpage!!!)
       //A resource is a special variable, holding a reference to an external resource. Resource are created and used by 
      //special functions. 
     /** Description:
       *            - reads up to length bytes from the file pointer referenced by handle
       *            - Reading stops as soon as on of the following conditions is met:
       *                 -> length bytes have been read
       *                 -> EOF (end of file) is reached
       *                 -> a packet becomes available (for network stream)
       *                 -> 8152 bytes have been read (after opening userspace stream)
       *
       * @Binary    - safe file read
       * @param $handle (resource)  - a pointer resource that is tipically created using fopen()
       * @param $length (number)   - up to Length number of bytes read.
       * @returns - Returns the read string or FALSE on failure.
       */     
       $lastpage = intVal(fread($handle,filesize($filename)));

       //closes open file pointer
       fclose($handle);

       /*
         $_GET - An associative array of variables passed to the current script via the URL paramaters
       */
       //get the value of the parameter 'page' passed via URL
       $page = intVal($_GET['page']);

       //define endpoint YQL
       $endpoint = 'http://query.yahooapis.com/v1/public/yql?q=';

       //define YQL statement
       $yql = 'select * from html where url="http://www.dilemaveche.ro/autor/andrei-plesu?page='.$page.'" and xpath="//div[@id=\'content_left\']/div[@class=\'node\']"';

      //encode YQL 
      $url = $endpoint . urlencode($yql) . '&diagnostics=false';

      //get results and echo
      $results = get($url);
 
      //define var that represent the button for 'more items'
      $more = '';
      //define var that represent the button for 'back items'
      $back = '';

      $begin = '<div class="controls">';
      $end = '</div>';

        if($page > 0) {
              $back = '<a class="back" href="controller.php?page='.($page-1).'">back</a>';
        } 
        if(($page) < $lastpage) {
              $more = '<a class="more" href="controller.php?page='.($page+1).'">More</a>';
        }
        echo$begin.$back.$more.$end.$results.$begin.$back.$more.$end; 

} else {
    //define endpoint YQL
    $endpoint = 'http://query.yahooapis.com/v1/public/yql?q=';
    //define YQL statement
    $yql = 'select * from html where url="http://www.dilemaveche.ro/autor/andrei-plesu" and xpath="//div[@id=\'content_left\']/div[@class=\'node\']"';
    //encode YQL 
    $url = $endpoint . urlencode($yql) . '&diagnostics=false';
    //get results
    $results = get($url);
    $yql_last_page = 'select href from html where url="http://www.dilemaveche.ro/autor/andrei-plesu" and xpath="//ul[@class=\'pager\']/li[@class=\'pager-last last\']/a"';
    $results_last_page = $endpoint . urlencode($yql_last_page). '&format=json&diagnostics=false';
    $output_last_page = json_decode(get($results_last_page));
    $last_page = $output_last_page->query->results->a->href;
    $last_page = preg_match('/(=[0-9]+)/',$last_page, $matches);
    $lastPage = split("=",$matches[1]);
    $lastPage = $lastPage[1];
    $handle = fopen("lastpage.txt","w");
    fwrite($handle,$lastPage);
    fclose($handle);
    $more = '<a class="more" href="controller.php?page=1">More</a>';
    $results = $more.$results.$more;
}

//using cURL
function get($url) {
          $ch = curl_init();
          curl_setopt($ch,CURLOPT_URL,$url);
          curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
          curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
          $data = curl_exec($ch);
          $data = preg_replace('/<\?.*?>/','',$data);
          $data = preg_replace('/<\!--.*-->/','',$data);
          $data = preg_replace('/.*?<results>/','',$data);
          $data = preg_replace('/<\/results>.*/','',$data);
          $data = preg_replace('/<a href="\//','<a href="http://www.dilemaveche.ro/',$data);  
          $data = preg_replace('/\shref="\//',' href="http://www.dilemaveche.ro/',$data);  
          curl_close($ch); 
          if(empty($data)) {return 'Server Timeout. Try agai later!';}
                 else {return $data;}
}//end function get
?>
