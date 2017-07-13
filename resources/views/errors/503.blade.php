<!DOCTYPE html>
<html>
    <head>
        <title>Lara is moving!</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: darkslategrey;
                display: table;
                font-weight: 600;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 60px;
                margin-bottom: 40px;
                font-weight: 900 !important;
            }

            .main-text {         
                font-size: 24px;
                margin-bottom: 40px;
            }

            .medium-text {
                font-size: 22px;
                margin-bottom: 30px;
                font-weight: 900 !important;
            }

            .tiny-text {
                font-size: 16px;
                margin-bottom: 20px;
            }

            a {
                letter-spacing: 3px !important;
            }

        </style>
    </head>
    <body>
        <!-- Countdown and redirect -->
        <script type="text/javascript">
            var count = 60;
            var redirect = "https://lara.il-sc.de/";
             
            function countDown(){
                var timer = document.getElementById("timer");
                if(count > 0){
                    count--;
                    timer.innerHTML = "This page will redirect automatically in "+count+" seconds.";
                    setTimeout("countDown()", 1000);
                }else{
                    window.location.href = redirect;
                }
            }
        </script>

        <div class="container">
            <div class="content">

                <div class="title">
                    Lara found a new home!
                </div>

                <div class="main-text">
                    As we are working towards making Lara available to all ILSC sections,<br>
                    we are moving to a new ILSC server.<br>
                    <br>

                    Please update your bookmarks with the new address:<br><br>
                    <a href="https://lara.il-sc.de/">LARA.IL-SC.DE</a><br>
                    <br>
                    <span id="timer">
                        <script type="text/javascript">countDown();</script>
                    </span>
                    <div class="tiny-text">
                        (Or just... you know... click on the link above... maybe?)
                    </div>

                    <br>
                    <hr>
                    <br>

                    <div class="medium-text">
                        The Lara development team wants to thank Throni (bc-Club) for allowing us to use his private server<br> 
                        from the very beginning of the project in 2014 until now,<br> 
                        as well as for his all-around help and support.<br>
                        <br>
                        THANK YOU!
                    </div>

                    <hr>

                </div>
            </div>
        </div>
    </body>
</html>
