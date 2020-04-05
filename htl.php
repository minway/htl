<h1> Health Traffic Light - HAT Testing </h1> <HR>

<h2> This is a test page of HAT data access. </h2>
<h2> Please answer the following questions.  </h2>

<p> username:

<?php

$name = $_POST['username'];
$psw = $_POST['psw'];
echo $name;


//
// Get User Token
//
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://$name.hubat.net/users/access_token",
  //CURLOPT_URL => "https://testing.hubat.net/users/access_token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Accept: application/json",
    "password: $psw",
    "username: $name"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

//echo $response;

$r = json_decode($response);
//var_dump($r);

if (array_key_exists('accessToken', $r) == FALSE) {
        echo "<h2>Fail to sign in.</h2>";
        exit();
}

$accessToken = $r->{'accessToken'};

//echo "Access Token: $accessToken";

echo "<hr>";


//
// Get App Token
//
$app = "https://$name.hubat.net/api/v2.6/applications/app-102-dev/access-token";

$r = get($app, $accessToken);
//echo "<textarea rows=10 cols=180> " . $r . "</textarea>";

$r = json_decode($r);

if (array_key_exists('accessToken', $r) == FALSE) {
        echo "<h2>Fail to auth application.</h2>";
        exit();
}


$accessToken = $r->{'accessToken'};

?>

<form method="post" action="result.php">

Do you have fever?
<input type="radio" name="fever" value="1">Yes
<input type="radio" name="fever" value="0">No
<hr>

Do you have cough?
<input type="radio" name="cough" value="1">Yes
<input type="radio" name="cough" value="0">No
<hr>

<input type="hidden" name="token" value="

<?php
        echo $accessToken
?>
">

<input type="hidden" name="username" value="
<?php
        echo $name
?>
">

<button type="submit" >Submit</button>
</form>


<?php
//
// Write it to the HAT
//
$dataEndpoint = "https://$name.hubat.net/api/v2.6/data/surreyapp/health";
$input = '{
        "description": "Health Traffic Light",
        "data": {
                "fever": true,
                "cough": true,
                "note": "na",
                "score": 3
        }
}';
//echo $input;
$r = post($dataEndpoint, $accessToken, $input);

//echo $r;


//
// Get it from the HAT
//
$endpoint = "https://$name.hubat.net/api/v2.6/data/surreyapp/health";

$r = get($endpoint, $accessToken);
//echo "<textarea rows=10 cols=180> " . $r . "</textarea>";

$r = json_decode($r);



exit();

function post($endPoint, $accessToken, $input) {

$curl = curl_init();

curl_setopt_array($curl, array(

  CURLOPT_URL => $endPoint,

  CURLOPT_RETURNTRANSFER => true,
//  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "X-Auth-Token: $accessToken"
  ),
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => $input
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

return $response;
}

function get($endPoint, $accessToken) {

$curl = curl_init();

curl_setopt_array($curl, array(

  CURLOPT_URL => $endPoint,

  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "X-Auth-Token: $accessToken"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

return $response;
}


?>
