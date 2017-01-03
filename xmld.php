<?php
$dir='/home/jazzradiofr/www/winradio/';

$odir=opendir($dir);
while ($f = readdir($odir)){
	if ((substr($f,strlen($f)-4)=='.xml') && (substr($f,0,4)=='prog')) {
	if (difft(filemtime($dir.$f))==true) {
//		updxml($dir);
		updjson($dir);
	}
	}
}
updjson($dir);

function updjson($dir) {
	$odir=opendir($dir);
        while ($f = readdir($odir)) {
		$js='';
                if ((substr($f,strlen($f)-4)=='.xml') && (substr($f,0,4)=='prog') && (filesize($dir.$f)>0) ) {
			$num=str_replace('.xml','',str_replace('prog','',$f));
                        $xml=simplexml_load_file($dir.$f, null , LIBXML_NOCDATA);
			$morceaux=$xml->morceau;
			foreach ($morceaux as $morceau) {
			$start_date=$morceau->date_prog;
			$artist=$morceau->chanteur;
			$title=$morceau->chanson;
                        $cover=$morceau->pochette;
			$js.='{"start_date":"'.$start_date.'","artist":"'.$artist.'","title":"'.$title.'","cover":"'.$cover.'"},';
			}
			$json='['.substr($js,0,strlen($js)-1).']';
			file_put_contents($dir.'prog'.$num.'.json',$json);
		}
	}
}

function xml2js($xmlnode) {
    $root = (func_num_args() > 1 ? false : true);
    $jsnode = array();

    if (!$root) {
        if (count($xmlnode->attributes()) > 0){
            $jsnode["$"] = array();
            foreach($xmlnode->attributes() as $key => $value)
                $jsnode["$"][$key] = (string)$value;
        }

        $textcontent = trim((string)$xmlnode);
        if (count($textcontent) > 0)
            $jsnode["_"] = $textcontent;

        foreach ($xmlnode->children() as $childxmlnode) {
            $childname = $childxmlnode->getName();
            if (!array_key_exists($childname, $jsnode))
                $jsnode[$childname] = array();
            array_push($jsnode[$childname], xml2js($childxmlnode, true));
        }
        return $jsnode;
    } else {
        $nodename = $xmlnode->getName();
        $jsnode[$nodename] = array();
        array_push($jsnode[$nodename], xml2js($xmlnode, true));
        return json_encode($jsnode);
    }
}   

function updxml($dir) {
	$odir=opendir($dir);
	while ($f = readdir($odir)){
	        if ((substr($f,strlen($f)-4)=='.xml') && (substr($f,0,4)=='prog')) {
			$xml=simplexml_load_file($dir.$f);
$xml=$xml->morceau[0];
foreach($xml->children() as $child)
  {
  echo $child->getName() . ": " . $child . "\n";
  }
print_r($xml->morceau);exit();
        	}
	}
}

function difft($t) {
	$r=false;
	if (($t+60)>time()) $r=true;
	return $r;
}

?>
