<!DOCTYPE html>
<html>
    <head>
        <title>welcome to Speedy portal</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="./css/main.css">

        <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        .button2 {background-color: #008CBA;} /* Blue */
        .button3 {background-color: #f44336;} /* Red */ 
        .button4 {background-color: #e7e7e7; color: black;} /* Gray */ 
        .button5 {background-color: #555555;} /* Black */
        </style>
    </head>

    <body>
        <?php 
            $pageUrl = "https://www.yourdomain.com";
            
            if (isset($_POST['multipleurl'])) 
            { 
                $pageUrl = $_POST['multipleurl'];
            } 
        ?>

        <div class="container">
            
            <form id="setupform" class="form-horizontal has-feedback" role="form" method="POST">
                <h1>Welcome to Sitespeed Portal</h1>
                <h2><span class="label label-default">Provide URL for performance test</span></h2>
                
                <div class="row">
                    <div class="col-xs-12">

                        <label for="comment">Paste 1 or more URL(s) below to test</label>
                        <textarea class="form-control" rows="5" id="multipleurl" name="multipleurl">https://www.yourdomain.com</textarea>


                        <br/>

                        <table class="table">
                              <thead class="thead-light">
                                <tr>
                                    <th scope="col">Choose Environment (www, stage, etc...)</th>
                                    <th scope="col">Select if you want to test mobile version</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                            <!-- <button id="options" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select environment
                                                <span class="caret"></span>
                                            </button> -->

                                            <a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#">
                                            <span id="env">Choose environment</span><span class="caret"></span></a>


                                            <ul class="dropdown-menu" name="environment">
                                                <li><a href="#">www</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">staging</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">qa1</a></li>
                                                <li><a href="#">qa2</a></li>
                                                <li><a href="#">qa3</a></li>
                                                <li><a href="#">qa4</a></li>
                                                <li><a href="#">qa5</a></li>
                                                <li><a href="#">qa6</a></li>
                                                <li><a href="#">qa7</a></li>
                                                <li><a href="#">qa8</a></li>
                                                <li><a href="#">qa9</a></li>
                                                <li><a href="#">qa10</a></li>
                                            </ul>
                                        </div>        
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="mobile" value="">Mobile Version</label>
                                        </div>
                                    </td>
                                </tr>
                              </tbody>
                        </table>

                        <table>
                            <tr>
                                <td>
                                    <button class="button button2" name="submit" id="submit" type="submit">Analyze!</button>
                                </td>
                                <td>
                                    <button class="button" name="homepage" id="homepage" type="submit">Analyze Home Page!</button>
                                </td>
                                <td>
                                    <button class="button button3" name="top25" id="top25" type="submit">Analyze Top 25! - BE CAREFUL!!!</button>
                                </td>
                                <td>
                                    <div id="loading2" style="display:none;"><img src="./images/preloader.gif" width="75%" height="75%" alt="" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="selection" id="selection" value="www">
            </form>

            <script>
                $(document).ready(function() {
                    $("ul li a").click(function() {
                        text = $(this).closest("li").text();
                        $(this).parents('.dropdown').find('.dropdown-toggle').html(text+' <span class="caret"></span>');
                        var prevSelection = $("input[name='selection']").val();
                        var re = new RegExp(prevSelection,"g");
                        var url = $('#multipleurl').val().replace(re, text);
                        $('#multipleurl').val(url);
                        $("input[name='selection']").val(text);

                    });
                });



                function init() {
                    // Clear forms here
                    document.getElementById("multipleurl").value = "";
                }
                window.onload = init;
            </script>

            <script type="text/javascript">
                (function (d) {
                  d.getElementById('setupform').onsubmit = function () {
                    d.getElementById('loading2').style.display = 'block';
                  };
                }(document));
            </script>


            <h2>Results of executing the command:</h2>

            <?php
                echo '<div class="pre"> <pre>';
                    if (isset($_POST["submit"]) || isset($_POST["homepage"]) || isset($_POST["top25"]))
                {
                    $randomNumber = rand();

                    $fileName='';
                    $preLoginScript = "";

                    if (strlen(trim($_POST['multipleurl']))>0) {
                        $fileName = $randomNumber.'.txt';
                        $fileHandle = fopen('/usr/local/var/www/'.$fileName, "a");
                        fwrite($fileHandle,$_POST['multipleurl']."\r\n");
                        fclose($fileHandle);
                    }

                    $mobile = '';
                    $environment = '';

                    if(isset($_POST['selection']))
                    {
                        $environment = $_POST['selection'];

                        if ("www" != $environment) {
                            $preLoginScript = " --preScript=" . $environment . ".js";
                        }

                    }

                    if ($environment == "")
                        $environment = "www";

                    // Sitespeed setting (default)

                    // for staging, prod, or Qa env seeking proxy 
                    // $proxysetting = ' --browsertime.proxy.http=proxy.yourdomain.com:80 --browsertime.proxy.https=proxy.yourcomain.com:80';
                    
                    // for local testing only
                    $proxysetting = '';

                    $resultBaseUrl = ' --resultBaseURL http://sitespeed.yourdomain.com:80/'.$environment;
                    $firstParty = ' --firstParty ".*(vzw|verizonwireless).*"';
                    $resultsUrl = ' --outputFolder sitespeed-result/'.$environment.'/$(date +\%Y-\%m-\%d-\%H-\%M-\%S)';
                    $videoIndex = ' --video --speedIndex';


                    $pageUrl = str_replace("www",$environment,$pageUrl);

                    if (strlen($fileName) > 0)
                    {
                        $pageUrl = $fileName;
                    }

                    $dockerCommand = 'docker run --shm-size=1g --rm -v /usr/local/var/www:/sitespeed.io sitespeedio/sitespeed.io';

                    // for Naveen - pls add sitespeed version below
                    if (isset($_POST["submit"])){
                         // Analyze button clicked
                         $dockerCommand = 'docker run --shm-size=1g --rm -v /usr/local/var/www:/sitespeed.io sitespeedio/sitespeed.io -b chrome '.$pageUrl.$proxysetting.$resultBaseUrl.$firstParty.$resultsUrl.$videoIndex.$preLoginScript;
                    } else if (isset($_POST["homepage"])){
                         // Analyze home page button clicked
                         $pageUrl = "https://www.yourdomain.com";
                         $dockerCommand = 'docker run --shm-size=1g --rm -v /usr/local/var/www:/sitespeed.io sitespeedio/sitespeed.io -b chrome '.$pageUrl.$proxysetting.$resultBaseUrl.$firstParty.$resultsUrl.$videoIndex.$preLoginScript;
                    } else if (isset($_POST["top25"])){
                        // Analyze top 25 urls
                         $pageUrl = "top25.txt";
                         $dockerCommand = 'docker run --shm-size=1g --rm -v /usr/local/var/www:/sitespeed.io sitespeedio/sitespeed.io -b chrome '.$pageUrl.$proxysetting.$resultBaseUrl.$firstParty.$resultsUrl.$videoIndex.$preLoginScript;

                    }

                     

                    if (isset($_POST['mobile'])) {
                        $dockerCommand = $dockerCommand.' --mobile';    
                        // Checkbox is selected
                    }

                    $dockerCommand = $dockerCommand.' -n 1 2>&1';

                    print_r($dockerCommand);
                    echo '<br>';
                    echo '<br>';

                    $output = array();
                    exec($dockerCommand, $output);

                    print_r($output); // contains the correct output

                    if (count($output)>0)
                    {
                        $urlToParse = $output[count($output)-1];
                        $strPos = strpos($urlToParse, "http");
                        $urlToClick = substr($urlToParse, $strPos);
                    }
                    
                }   
                
                echo '</pre></div><hr />';     
                echo '<a class="button button5" href="' . $urlToClick . '">View Results</a>';
            ?>



        </div>


        
    </body>
</html>