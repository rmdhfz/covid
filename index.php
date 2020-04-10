<?php

	$curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://spreadsheets.google.com/feeds/list/1lwnfa-GlNRykWBL5y7tWpLxDoCfs8BvzWxFjeOZ1YJk/1/public/values?alt=json');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36');
    $data = curl_exec($curl);
    // ------------------------------------------------------------------------
    if(curl_errno($curl) == 28) {
        $timeout = true;
    }else{
        $timeout = false;
    }
    curl_close($curl);
    if($timeout) {
        echo 'Timeout!'."\n";
        exit;
    }
    if(trim($data) == '') {
        echo 'Curl data empty!'."\n";
        exit;
    }
    // ------------------------------------------------------------------------
    $json = json_decode(mb_convert_encoding($data, 'HTML-ENTITIES', 'UTF-8'));
    // ------------------------------------------------------------------------
    if(!isset($json->{'feed'}->{'entry'})) {
        $found = false;
        echo 'No data!' . "\n";
        exit;
    }else {
        $found = true;
    }
    $updatedDatetimeUtc = $json->{'feed'}->{'updated'}->{'$t'};
    $updatedDatetime = trim(date("Y-m-d H:i:s", strtotime($updatedDatetimeUtc)));
    echo 'Updated Time UTC: ' . $updatedDatetimeUtc . "\n";
    // ------------------------------------------------------------------------
    if($found) {
        $counter = 1;
        foreach($json->{'feed'}->{'entry'} as $eachData) {
            $country = trim($eachData->{'title'}->{'$t'});
            $confirmedCases = trim($eachData->{'gsx$confirmedcases'}->{'$t'});
            $reportedDeaths = trim($eachData->{'gsx$reporteddeaths'}->{'$t'}) != '' ? trim($eachData->{'gsx$reporteddeaths'}->{'$t'}) : 0;
            echo $counter . '. ' . $country . ' - Confirmed Cases: ' . $confirmedCases . ' - Reported Deaths: ' . $reportedDeaths . "\n";
            $counter++;
        }
    }
?>