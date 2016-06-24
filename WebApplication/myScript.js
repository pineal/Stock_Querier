//auto complete 
$(function () {
    function log(message) {
        $("<div>").text(message).prependTo("#log");
        $("#log").scrollTop(0);
    }

    $("#search").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/jsonp",
                dataType: "jsonp",
                data: {
                    input: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.Name + " (" + item.Exchange + ")",
                            value: item.Symbol
                        }
                    }));
                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            log(ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
        },
        open: function () {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function () {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    });
});

$(function () {
    load_fav();
    check_fav();
});



function get_historical_chart(symbol) {    
    var pp = "{\"Normalized\":true,\"NumberOfDays\":365,\"DataPeriod\":\"Day\",\"Elements\":[{\"Symbol\":\"" + symbol + "\",\"Type\":\"price\",\"Params\":[\"c\"]}]}";
    $.ajax({
        url: "http://dev.markitondemand.com/MODApis/Api/v2/InteractiveChart/jsonp",
        dataType: "jsonp",
        data: {
            parameters: pp
        },
        success: function (data) {
            var new_array = [];
            var raw_value;
            var raw_dates = data["Dates"];
            // console.log(raw_dates);
            $.each(data["Elements"], function (i, elements) {
                $.each(elements["DataSeries"], function (i, dataSeries) {
                    raw_value = dataSeries["values"];
                    //       console.log(raw_value);
                });
            });

            //   console.log(raw_dates[0]);
            for (i = 0; i < 100; i++) {
                var temp = [];
                temp.push(raw_dates[i]);
                temp.push(raw_value[i]);
                new_array.push(temp);
            }
            //     console.log(new_array);
            get_historical_old_chart(new_array);
            $('#myBtn2').prop('disabled', false);
            //$("#front_panel").toggleClass('item');
            //$("#back_panel").toggleClass('item active');
            $("#myCarousel").carousel("next");
        }

    });
};


function enable_panel_switch() {
    document.getElementById("myBtn2").disable = "";
}

function get_historical_old_chart(data) {
    $('#historical_chart').highcharts({
        chart: {
            zoomType: 'x'
        },
        title: {
            text: 'stock values'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
        },
        xAxis: {
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Exchange rate'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: 'USD to EUR',
            data: data
            }]
    });


}

function invalid_quote() {
    document.getElementById("invalid_quote").style.visibility = "visible";
    document.getElementById("search_form").className = "input-group col-lg-8 has-error";
}

function empty_quote() {
    document.getElementById("invalid_quote").style.visibility = "visible";
    document.getElementById("search_form").className = "input-group col-lg-8 has-error";
    document.getElementById("invalid_quote").innerHTML = " Empty Name";
}


function facebook_share() {
    alert("share to facebook");
}


function check_fav() {
    var classname = document.getElementById("fav_icon").className;
    document.getElementById("fav_icon").className;
    
    
    var favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    var stock_symbol = document.getElementById("stock_symbol").innerHTML;
    
    var flag = false;        
        for (i = 0; i<favorites.length;i++){
            console.log(favorites[i][0]);
            console.log(stock_symbol);
            if (favorites[i][0] == stock_symbol){                
                flag = true;
            }
        }        
    if (flag == true) {
        document.getElementById("fav_icon").className = "fa fa-star fa-2x text-danger";
    } else {
        document.getElementById("fav_icon").className = "fa fa-star-o fa-2x";    
    }
    
}

function add_fav() {
    var classname = document.getElementById("fav_icon").className;
    if (classname != "fa fa-star fa-2x text-danger") {
        var favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        var new_item = [];
        new_item[0] = document.getElementById("stock_symbol").innerHTML;
        new_item[1] = document.getElementById("stock_name").innerHTML;
        new_item[2] = document.getElementById("stock_price").innerHTML;
        new_item[3] = document.getElementById("stock_change").innerHTML;
        new_item[4] = document.getElementById("stock_cap").innerHTML;
        var flag = false;        
        for (i = 0; i<favorites.length;i++){
            console.log(favorites[i][0]);
            console.log(new_item[0]);
            if (favorites[i][0] == new_item[0]){                
                flag = true;
            }
        }        
        if (flag == false) {
            document.getElementById("fav_icon").className = "fa fa-star fa-2x text-danger";
            favorites.push(new_item);
        }
        localStorage.setItem('favorites', JSON.stringify(favorites));
        load_fav();
    }
    else {
        
        
    }

}


function load_fav() {
    var table = document.getElementById("fav_table");
    $("#fav_table tr").remove();
    var symbols = JSON.parse(localStorage.getItem("favorites"));

    for (i = 0; i < symbols.length; i++) {
        var row = table.insertRow(-1);
        row.id = "row_" + i;
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        cell1.innerHTML = "<a href=\"javascript:validate('" + symbols[i][0] + "')\">" + symbols[i][0] + "</a>";
        cell2.innerHTML = symbols[i][1];
        cell3.innerHTML = symbols[i][2];
        cell4.innerHTML = symbols[i][3];
        cell5.innerHTML = symbols[i][4];
        cell6.innerHTML = "<button onclick = 'delete_fav(" + i + ")'><i></i></button>"
        cell6.firstChild.className = "btn btn-default";
        cell6.lastChild.className = "fa fa-trash";
    }
}

function validate(symbol){
    document.getElementById("search").value = symbol;        
    document.getElementById("submit_form").submit();    
}

function delete_fav(row_id) {
    //alert("delete" + row_id);
    var x = document.getElementById("fav_table").rows.length;
    if (x == 1){
        i = 0;
    }
    else {
        i = parseInt(row_id);    
    }    
    console.log(i);
    document.getElementById("fav_table").deleteRow(i);
    var favorites = JSON.parse(localStorage.getItem("favorites"));
    favorites.splice(i, 1);
    localStorage.setItem('favorites', JSON.stringify(favorites));
    check_fav();
}


$(document).ready(function () {
    // Activate Carousel
    //load_fav();
    $("#myCarousel").carousel("pause");

    // Go to the previous item
    $("#myBtn").click(function () {
        $("#myCarousel").carousel("prev");
    });

    // Go to the next item
    $("#myBtn2").click(function () {
        $("#myCarousel").carousel("next");
    });

    // Enable Carousel Controls
    $(".left").click(function () {
        $("#myCarousel").carousel("prev");
    });
    $(".right").click(function () {
        $("#myCarousel").carousel("next");
    });
});