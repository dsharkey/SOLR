<?php

function startsWith($haystack,$needle,$case=true) {
    if($case){return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);}
    return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
}


$con = mysql_connect('localhost:8888', 'root', 'root');
    if (!$con)
      {
      die('Could not connect: ' . mysql_error());
      }
    mysql_select_db("newOnlineDiscourse", $con);


$query = "SELECT * FROM cd_file_table WHERE 'ignore'=0 AND id=36";

$result = mysql_query($query);
if (!$result) {
    die('Invalid query: ' .$query." ". mysql_error());
}
$count=0;
while($row = mysql_fetch_assoc($result)) {
    $source="";
    if(startswith($row['filename'],"www"))
        $source = urlencode("http://".$row['filename']);
    else
        $source = urlencode("http://www.".$row['filename']);

   // echo $source."<br>";
    $desc="literal.desc=".$row['description'];
    $title =urlencode($row['title']);
    $authors=$row['authors'];
    //need to split authors
   
    $year = $row['year'];
    $authors=split("#",$authors);
    $authFin = array();
    foreach($authors as $author){
        $author=split(',',$author);
        if (count($author)>1){
            $authFin[]=$author[1]." ".$author[0];
        }else
            $authFin[]=$author[0];
    }
    $id = $row['id'];

    $url="http://localhost:8983/solr/update/extract?";
    $params="uprefix=attr_&literal.id=".$id."&literal.sup_title=".$title."&wt=json";
    foreach($authFin as $author){
        $params.="&literal.authors=".urlencode($author);
    }
    $params.="&stream.url=".$source;

    $url.=$params;
    $ch = curl_init($url);

    echo $url."<br>";
    curl_setopt($ch, CURLOPT_POSTFIELDS, $desc);
   // curl_setopt($ch,CURLOPT_AUTOREFERER,true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);


    $resp=json_decode(curl_exec($ch));
    if($resp->responseHeader->status!=0)
            echo "fail ". $id."<br>";
    else echo"success ".$id." ".$row['title']." "."<br>";
    curl_close($ch);
    $count++;
}

?>