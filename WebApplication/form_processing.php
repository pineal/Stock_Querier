<?php
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

function get_Quote($symbol) {    
    $url = 'http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=' . $symbol;    
    return get_JSON($url);
}

function get_Chart($symbol) {
    echo('<img src="http://chart.finance.yahoo.com/t?lang=en-US&width=400&height=300&s='.$symbol.'">');
}
    
function print_Table($json) {    
    echo ('<tr>');
    echo ('<th>' . 'Name' . '</th>');
    echo ('<td id="stock_name">' . $json['Name'] . '</td>');
    echo ('</tr>');

    echo ('<tr>');
    echo ('<th>' . 'Symbol' . '</th>');
    echo ('<td id="stock_symbol">' . $json['Symbol'] . '</td>');
    echo ('</tr>');
    
    $lastprice = "$ " . number_format($json['LastPrice'], 2, '.', '');
    echo ('<tr>');
    echo ('<th>' . 'Last Price' . '</th>');
    echo ('<td id="stock_price">' . $lastprice . '</td>');
    echo ('</tr>');
    
    $change =   number_format($json['Change'], 2, '.', '');
    $changeP =  number_format($json['ChangePercent'], 2, '.', '') . "%" ;
    echo ('<tr>');
    echo ('<th>' . 'Change(change Percent)' . '</th>');
    if ($change >= 0){
        echo ('<td id="stock_change"><span class = "text-success">' . $change . " (" . $changeP . ") " . ' <img src ="http://cs-server.usc.edu:45678/hw/hw8/images/up.png"></span></td>');    
    } else {
        echo ('<td id="stock_change"><span class = "text-danger">' . $change . " (" . $changeP . ") " . ' <img src ="http://cs-server.usc.edu:45678/hw/hw8/images/down.png"></span></td>');    
        }      
    echo ('</tr>');
    
    $year = date_parse($json['Timestamp'])["year"];
    $month = date_parse($json['Timestamp'])["month"];
    $day = date_parse($json['Timestamp'])["day"];
    $hour = date_parse($json['Timestamp'])["hour"];
    $min = date_parse($json['Timestamp'])["minute"];
    $sec = date_parse($json['Timestamp'])["second"];
    
    $newdate = $year .'-'. $month .'-'. $day;
    
    //$newdate = date('Y-M-d', $newdate);
    echo ('<tr>');
    echo ('<th>' . 'Time' . '</th>');
    echo ('<td>' . $newdate . '</td>');
    echo ('</tr>');
    
    $market_cap = $json['MarketCap'];
    if (strlen($market_cap) > 9) {
        $market_cap = $market_cap / 1000 / 1000 / 1000; 
        $market_cap =  number_format($market_cap, 2, '.', '') . " Billion" ;
    } else if (strlen($market_cap) > 6) {
        $market_cap = $market_cap / 1000 / 1000; 
        $market_cap =  number_format($market_cap, 2, '.', '') . " Million" ;    
    } else {
        $market_cap =  number_format($market_cap, 2, '.', '');            
    }
    
    echo ('<tr>');
    echo ('<th>' . 'Market Cap' . '</th>');
    echo ('<td id="stock_cap">' . $market_cap . '</td>');
    echo ('</tr>');
    

    echo ('<tr>');
    echo ('<th>' . 'Volume' . '</th>');
    echo ('<td>' . $json['Volume'] . '</td>');
    echo ('</tr>');

    
    
    $changeYTD =   number_format($json['ChangeYTD'], 2, '.', '');
    $changePYTD =  number_format($json['ChangePercentYTD'], 2, '.', '') . "%" ;
    echo ('<tr>');
    echo ('<th>' . 'Change YTD(Change Percent YTD)' . '</th>');  
        if ($changeYTD >= 0){
        echo ('<td><span class = "text-success">' . $changeYTD . " (" . $changePYTD . ") " . '</span><img src ="http://cs-server.usc.edu:45678/hw/hw8/images/up.png"></td>');    
    } else {
        echo ('<td><span class = "text-danger">' . $changeYTD . " (" . $changePYTD . ") " . '</span><img src ="http://cs-server.usc.edu:45678/hw/hw8/images/down.png"></td>');    
    }    
    
    echo ('</tr>');
    
    $highprice = "$ " . number_format($json['High'], 2, '.', '');
    echo ('<tr>');
    echo ('<th>' . 'High Price' . '</th>');
    echo ('<td>' . $highprice . '</td>');
    echo ('</tr>');

    $lowprice = "$ " . number_format($json['Low'], 2, '.', '');
    echo ('<tr>');
    echo ('<th>' . 'Low Price' . '</th>');
    echo ('<td>' . $lowprice . '</td>');
    echo ('</tr>');

    
    $openprice = "$ " . number_format($json['Open'], 2, '.', '');
    echo ('<tr>');
    echo ('<th>' . 'Open Price' . '</th>');
    echo ('<td>' . $openprice . '</td>');
    echo ('</tr>');

    
}

function get_News($symbol) {
    $url = "https://ajax.googleapis.com/ajax/services/search/news?" .
       "v=1.0&userip=INSERT-USER-IP&q=" . $symbol;
    $json = get_JSON($url);
    for ($i = 0; $i < sizeof($json['responseData']['results']); $i++) {
    echo ("<div class=\"panel panel-primary\">");
    echo ("<div class=\"panel-body active\">");        
    echo ($json['responseData']['results'][$i]['title']);
    echo("</br>");
    echo ($json['responseData']['results'][$i]['content']);
    echo("</br>"); 
    echo ("</div>");
    echo ("</div>");        
    }
}

function get_historical_chart ($symbol) {
    echo("<p>");
    echo ("<script>get_historical_chart('");
    echo ($symbol);
    echo ("');</script>");
    echo("</p>");
}


function add_fav($symbol) {
    
}

?>