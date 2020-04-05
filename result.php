
<h1> Health Traffic Light - HAT Testing </h1> <HR>

<h2> Result  </h2>

<p> username:

<?php

$name = $_POST['username'];
$accessToken = $_POST['token'];
echo $name;


$fever = $_POST['fever'];
$cough = $_POST['cough'];

//
// Do the risk calculation here
//





$data = array(
        "fever" => $fever
        , "cough" => $cough
        , "note" => ""
        , "score" => 3
        );
$saved = json_encode($data);
//
// Write it to the HAT
//
$dataEndpoint = "https://$name.hubat.net/api/v2.6/data/surreyapp/health";
$r = post($dataEndpoint, $accessToken, $saved);

//echo $r;
echo "<br>Result is saved to your HAT.";
echo "<hr> $saved";

//
// Get it from the HAT
//
$endpoint = "https://$name.hubat.net/api/v2.6/data/surreyapp/health";

$r = get($endpoint, $accessToken);
//echo "<textarea rows=10 cols=180> " . $r . "</textarea>";

$r = json_decode($r);

exit();
