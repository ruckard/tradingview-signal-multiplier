<?php

$ZIGNALY_SIGNAL_URL = "https://zignaly.com/api/signals.php";

$services['MyService1'] = "MySecretKey1";
$services['MyService2'] = "MySecretKey2";
$services['MyService3'] = "MySecretKey3";

$received_post = json_decode(file_get_contents("php://input"));
// Comment above line and uncomment below line to test with stdin
// $received_post = json_decode(file_get_contents("php://stdin"));

foreach ($services as $service_name => $service_key) {
    $newpost = array();
    foreach ($received_post as $param_name => $param_val) {
        $newpost["$param_name"] = $param_val;
        // echo "Param: $param_name; Value: $param_val<br />\n";
    }

    $newpost["key"] = $service_key;
    echo ("Service: $service_name - BEGIN" . "\n");
    $newpost_data = json_encode($newpost);
    echo ($newpost_data);
    // TODO: Move into a function
    // Send data - BEGIN

    // Create a new cURL resource
    $ch = curl_init($ZIGNALY_SIGNAL_URL);

    // Attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $newpost_data);

    // Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the POST request
    $result = curl_exec($ch);

    // Close cURL resource
    curl_close($ch);
    // Send data - END


    echo ("\n" . "Service: $service_name - END");
}

?>
