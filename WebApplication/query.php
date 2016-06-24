<?php    
$symbol;
$api;
$queries = array_values(explode( '&', parse_url(curPageURL())["query"]));
foreach($queries as $query) {
    $a = explode('=', $query);    
    //echo $a[0];
    switch ($a[0]) {
        case 'api':     
            $api = $a[1];    
            break;
        case 'symbol':
            $symbol = $a[1];        
            break;
        default:
            break;
    }
}
if ($api == 'get_quote'){
    echo json_encode(get_Quote($symbol));
}

else if ($api == 'get_chart'){
    //echo json_encode(get_Quote($symbol));
    get_Chart($symbol);
}

else if ($api == 'get_hist_chart'){
    get_historical_chart($symbol);        
}
    
function get_historical_chart ($symbol) {
    echo ("<script src='http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js'></script>    ");
    echo ("<script src='myScript.js'></script>");
    echo ("<script src='https://code.highcharts.com/highcharts.js'></script>");
    echo ("<script src='https://code.highcharts.com/modules/exporting.js'></script>");
    echo ("<div id='historical_chart'>");
    echo ("</div>");
    echo("<p>");
    echo ("<script>get_historical_chart('");
    echo ($symbol);
    echo ("');</script>");
    echo("</p>");
}    

//get current url
function curPageURL() {
 $pageURL = 'http';
// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
// $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}    
 function get_Chart($symbol) {
    echo('<img src="http://chart.finance.yahoo.com/t?lang=en-US&width=400&height=300&s='.$symbol.'">');
}

function get_Quote($symbol) {    
    $url = 'http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=' . $symbol;    
    return get_JSON($url);
}

function get_JSON($url) {
    $ch = curl_init();
    // Set the url, number of GET vars, GET data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, false);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Execute request
    $result = curl_exec($ch);
    // Close connection
    curl_close($ch);
    // get the result and parse to JSON
    $result_arr = json_decode($result, true);
    return $result_arr;    
}   
?>