<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Temperatura</title>
</head>

<body style="text-align:center">
<?
$lat = -33.437778;
$lon = -70.650278;
$url = "http://api.openweathermap.org/data/2.1/find/station?lat=".$lat."&lon=".$lon."&cnt=1";
//http://api.raventools.com/api?key=B1DFC59CA6EC76FF&method=domains&format=json";
$ch = curl_init($url);
//curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
$json = curl_exec($ch);
if(!$json) {
    echo curl_error($ch);
}
//print_r(json_decode($json));
//$obj = json_decode($json);
$array = json_decode($json,TRUE);
//print_r($obj);
//print_r($array);
$list = $array['list'];
$cero = $list[0];
$main = $cero['main'];
$temp = $main['temp'];
$rtemp = $temp - 273.15;
echo $rtemp." C";
curl_close($ch);
?>
</body>
</html>