<?php
function gkwg($word){
$url = "http://www.google.com/complete/search?hl=ja&output=toolbar&ie=utf_8&oe=utf_8&q=".urlencode($word);
$gkwg_xml = simplexml_load_file($url);
 if(isset( $gkwg_xml->CompleteSuggestion ) ){
  foreach ($gkwg_xml->CompleteSuggestion as $completeSuggestion) {
   $suggest_word_array[] = $completeSuggestion->suggestion->attributes()->data;
  }
  return $suggest_word_array;
 }else{
  return false;
 }
}
function get_noun($txet){
  $exe_path = '';
	$descriptorspec = array(
		0 => array("pipe", "r"),
		1 => array("pipe", "w")
	);
	$process = proc_open($exe_path, $descriptorspec, $pipes);
	if (is_resource($process)) {
		fwrite($pipes[0], $txet);
		fclose($pipes[0]);
		$tmp = stream_get_contents($pipes[1]);
		fclose($pipes[1]);
		proc_close($process);
	}
	$result=[];
	$result = explode("\r\n", $tmp);
  $word_list=[];
  foreach($result as $val) {
  	$tmp=explode(",",$val);
  	$tmp=explode("\t",$tmp[0]);
  	if (@$tmp[1]=='名詞') {
      $word_list[]=$tmp[0];
  	}
  }
  $str="";
  foreach($word_list as $val) {
    $str.=$val." ";
  }
  return $str;
}


$word=get_noun($_POST["a"]);
echo $word."<br><br>";
//$word=$_POST["a"]." ";
$ar_gkwg=gkwg($word);
if($ar_gkwg){
	foreach($ar_gkwg as $gkwg){
		echo $gkwg.'<br>';
	}
}
?>
