<?php

function getRequest(string $url, array $data)
{
    $dataencoded = http_build_query($data);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url . '?' . $dataencoded);
    $curl_result = curl_exec($curl);
    curl_close($curl);

    return json_decode($curl_result, true);
}
function postRequest(string $url, array $data)
{
    // $dataencoded = http_build_query($data);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $curl_result = curl_exec($curl);
    curl_close($curl);

    return json_decode($curl_result, true);
}

?>