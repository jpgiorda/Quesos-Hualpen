<?php

    echo "</br>";
    echo"<ul />";
    echo "<br /><li>Tweets: ";
    
    
    $oauth_hash = '';
    $oauth_hash .= 'oauth_consumer_key=E6O8k3hnG4DVuoyPyqxoUg&';
    $oauth_hash .= 'oauth_nonce=' . time() . '&';
    $oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
    $oauth_hash .= 'oauth_timestamp=' . time() . '&';
    $oauth_hash .= 'oauth_token=1428999014-7yu5M3KAroiu6fwDgGyGoDNrFivRARgsChYLAOk&';
    $oauth_hash .= 'oauth_version=1.0';

    $base = '';
    $base .= 'GET';
    $base .= '&';
    $base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
    $base .= '&';
    $base .= rawurlencode($oauth_hash);

    $key = '';
    $key .= rawurlencode('RA9HZZq4vpZuI2ovGS0jkPZpoz14RYRmUUMT0jE49NY');
    $key .= '&';
    $key .= rawurlencode('QqtKpepPXqX3ZQ3SiF20AyXcyfgsdyxhEj1saQFpWk');

    $signature = base64_encode(hash_hmac('sha1', $base, $key, true));
    $signature = rawurlencode($signature);

    $oauth_header = '';
    $oauth_header .= 'oauth_consumer_key="E6O8k3hnG4DVuoyPyqxoUg", ';
    $oauth_header .= 'oauth_nonce="' . time() . '", ';
    $oauth_header .= 'oauth_signature="' . $signature . '", ';
    $oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
    $oauth_header .= 'oauth_timestamp="' . time() . '", ';
    $oauth_header .= 'oauth_token="1428999014-7yu5M3KAroiu6fwDgGyGoDNrFivRARgsChYLAOk", ';
    $oauth_header .= 'oauth_version="1.0", ';
    $curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');

    $curl_request = curl_init();
    curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
    curl_setopt($curl_request, CURLOPT_HEADER, false);
    curl_setopt($curl_request, CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json');
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
    $json = curl_exec($curl_request);

    curl_close($curl_request);


    $twitter_data = json_decode($json);
    
    echo"<ul />";

    for($i=0;$i<4;$i++){
        echo "<br /><li>";
        $resultado = print_r($twitter_data[$i]->created_at);

        echo "<br />";
        
        $resultado = print_r($twitter_data[$i]->text);  
        
        echo "</li>";
    }
    
    echo "<ul>";

    // set username and password
    $username = 'quesoshualpen3';
    $password = 'integra3';
    // add the message you want to send
    $message = 'is tweeting using php and curl';
    // set the twitter API address
    $url = 'http://twitter.com/statuses/update.json';
    // setup a curl process
    $curl_handle = curl_init();
    // set the url of the curl process
    curl_setopt($curl_handle, CURLOPT_URL, "$url");
    // saves the return value as a string value instead of outputting to browser
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    // to send data as $_POST fields as required by the twitter API
    curl_setopt($curl_handle, CURLOPT_POST, 1);
    // set the post fields
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
    // set the username and password for the connection
    curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
    // exectute the curl request and save output as $buffer variable
    $buffer = curl_exec($curl_handle);
    // close the curl connection
    curl_close($curl_handle);
    // decode json output into array
    $json_output = json_decode($buffer, true);
    // check for success or failure
    if (isset($json_output['error'])) {
        // tweet not successful, display error
        echo "Fail: ".$json_output['error'];
    }
    else {
        // tweet is successful
        //$json_output contains return variables for this tweet
        echo "Success";
    }
 ?>
