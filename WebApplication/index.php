<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script src="js/bootstrap-switch.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/myScript.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
        $(function() {
            $(document).tooltip();
        });
    </script>
</head>

<body>
    <div class="container">
        <?php include 'form_processing.php';?>
            <form id="submit_form" action="?get_quote" method="post">
                <div class="form-group panel panel-default">
                    <h4 class="text-center">Stock Market Search</h4>
                    <label for="search" class="col-xs-3 control-label">Enter Stock Name or Symbol<span class="text-danger">*</span></label>
                    <div id="search_form" class="input-group col-lg-8">
                        <input type="search" class="form-control" id="search" name="search" placeholder="Apple Inc or AAPL">
                        <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span>Get Quote</button>
                        </span>
                    </div>
                    <div class="text-center" role="alert">
                        <span id="invalid_quote" class="text-danger glyphicon glyphicon-exclamation-sign" aria-hidden="true"> Not a valid Name</span>
                    </div>
                </div>
            </form>
            <hr>
            <div id="myCarousel" class="carousel slide">
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div id="front_panel" class="item active">

                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading col-lg-12">
                                    <div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-4">
                                                <h5><strong>Favorite List</strong></h5></div>
                                            <div id="fav_list_icons" class="pull-right">

                                                <button class="btn btn-default">
                                                    <i class="fa fa-refresh" aria-hidden="true" onclick="load_fav()"></i>
                                                </button>
                                                <button type="button" id="myBtn2" class="btn btn-default" id="myBtn" disabled="disabled">
                                                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover col-lg-12">
                                        <thead class="table active">
                                            <tr>
                                                <th>Symbol </th>
                                                <th>Company Name </th>
                                                <th>Stock Price </th>
                                                <th>Change (Change Percent) </th>
                                                <th>Market Cap </th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody id="fav_table">
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="back_panel" class="item">
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-active">
                                    <button type="button" id="myBtn" class="btn btn-default" id="myBtn"><i class="fa fa-caret-left" aria-hidden="true"></i></button>
                                </div>
                                <div class="panel-body">
                                    <ul class="nav nav-pills">
                                        <li class="active"><a data-toggle="tab" href="#current_stock">Current Stock</a></li>
                                        <li><a data-toggle="tab" href="#historical_chart">Historical Charts</a></li>
                                        <li><a data-toggle="tab" href="#news_feed">News Feeds</a></li>
                                    </ul>
                                    <hr>
                                    <div class="panel-default tab-content col-lg-12 col-md-8 col-sm-4">
                                        <div id="current_stock" class="tab-pane fade in active">
                                            <div class="row">
                                                <div class="col-lg-7">
                                                    <h4>Stock Details</h4>
                                                    <table class="table table-hover col-lg-12">
                                                        <?php         
    if (isset($_POST["search"])) {
        $symbol = $_POST["search"];
        $json = get_Quote($symbol);
        if (empty($_POST["search"])){
            echo ("<script> empty_quote(); </script>");        
        }else if (!empty($json["Message"])){            
            echo ("<script> invalid_quote(); </script>");        
        }        
        else{
        print_Table($json);
    }        
}         
?>
                                                    </table>
                                                </div>
                                                <div id="stock_chart" class="col-lg-5">
                                                    <div id="icons" class="row col-lg-12 text-right">
                                                        <button class="btn btn-default btn-sm"><i id="facebook_icon" class="fa fa-facebook-official fa-2x" onclick="facebook_share()" aria-hidden="true"></i></button>
                                                        <button class="btn btn-default btn-sm"><i id="fav_icon" class="fa fa-star-o fa-2x" aria-hidden="true" onclick="add_fav()"></i></button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-8 col-sm-4" id="yahoo_chart">
                                                            <?php
                                                            if (isset($_POST["search"])) {
                                                                get_Chart($_POST["search"]);
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                        <div id="historical_chart" class="tab-pane fade">
                                            <h4>Historical Chart</h4>
                                            <?php
        if (isset($_POST["search"])) {
            get_historical_chart($_POST["search"]);
        }
        ?>


                                        </div>
                                        <div id="news_feed" class="tab-pane fade">
                                            <h4>News Feed</h4>
                                            <?php
        if (isset($_POST["search"])) {
            get_News($_POST["search"]);
        }
        ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

</body>

</html>