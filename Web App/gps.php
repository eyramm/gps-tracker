<?php


    if (!empty($_GET['latitude']) && !empty($_GET['longitude']) &&
        !empty($_GET['time']) && !empty($_GET['satellites']) &&
        !empty($_GET['speedOTG']) && !empty($_GET['course'])) {

        function getParameter($par, $default = null){
            if (isset($_GET[$par]) && strlen($_GET[$par])) return $_GET[$par];
            elseif (isset($_POST[$par]) && strlen($_POST[$par])) 
                return $_POST[$par];
            else return $default;
        }
	
		function pretty_date($date){
        return date("M d, Y @ h:i A", strtotime($date));
        }
		

        $file = 'gps.txt';
        $lat = getParameter("latitude");
        $lon = getParameter("longitude");
        $time = getParameter("time");
        $sat = getParameter("satellites");
        $speed = getParameter("speedOTG");
        $course = getParameter("course");
		
		
		$year= substr($time, 0,4);
		$month= substr($time, 4,2);
		$date= substr($time, 6,2);
		$hours= substr($time, 8,2);
		$minutes= substr($time, 10,2);

		$lastseen = pretty_date($date);

	
		
		$person = $lat.",".$lon.",".$time.",".$sat.",".$speed.",".$course."\n";

        echo "
            RECEIVED:\n
            Latitude: ".$lat."\n
            Longitude: ".$lon."\n
            Time: ".$lastseen."\n
            Satellites: ".$sat."\n
            Speed OTG: ".$speed."\n
            Course: ".$course;

        if (!file_put_contents($file, $person, FILE_APPEND | LOCK_EX))
            echo "\n\t Error saving Data\n";
        else echo "\n\t Data Save\n";
    }
    else {

?>



<!DOCTYPE html>
<html>
    
<head>
<title> My Project</title>



    <!-- Load Jquery -->

    
<!-- <script language="JavaScript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="css/styles.css">    
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scaleable=no">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

    <!-- Load Google Maps Api -->

    <!-- IMPORTANT: change the API v3 key -->
    <style>
    .google-maps {
        position: relative;
        padding-bottom: 75%; // This is the aspect ratio
        height: 0;
        overflow: hidden;
    }
    .google-maps iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
    }
</style>

    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAhYb-kGKvZ7v5Vd7Kl5463tq1myKnmYnM"></script>


    <!-- Initialize Map and markers -->

    <script type="text/javascript">
        var myCenter=new google.maps.LatLng(5.5759588,-0.2306058);
        var marker;
        var map;
        var mapProp;

        function initialize()
        {
            mapProp = {
              center:myCenter,
              zoom:15,
              mapTypeId:google.maps.MapTypeId.ROADMAP
            //setInterval(mark(),5000);

              };
             
          
            mark();
          
        }
		
		

        function mark()
        {

             map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
            var file = "gps.txt";
            $.get(file, function(txt) { 
                var lines = txt.split("\n");
                for (var i=0;i<lines.length;i++){
                   // console.log(lines[i]);
                    var words=lines[i].split(",");  
                               
                    if ((words[0]!="")&&(words[1]!=""))
                    {
                        //console.log(words[i],-2);
                        words[0].split('\n').reverse()[1];
                        words[1].split('\n').reverse()[1];
                        marker=new google.maps.Marker({
                              position:new google.maps.LatLng(words[0],words[1]),
                              });
                        marker.setMap(map);
                        map.setCenter(new google.maps.LatLng(words[0],words[1]));

                        document.getElementById('altitude').innerHTML=words[2];
                        document.getElementById('date').innerHTML=words[3];
												document.getElementById('satellites').innerHTML=words[4];
                        document.getElementById('speed').innerHTML=words[5];
                        document.getElementById('course').innerHTML=words[6];
                    }
                }
                marker.setAnimation(google.maps.Animation.BOUNCE);
            });

        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>

</head>
 
<body>
    <?php

        echo '    

        <!-- Draw information table and Google Maps div -->

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="index.php"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Main</a></li>
      <li class="active"><a href="gps.php">Last Seen</a></li>
      <li><a href="data.html">Data</a></li>
      </ul>
    </div>
  </div>
</nav>
        <div class="text-center container">
        <h3>Last Seen</h3>
                <div class="table-responsive">
                    <table class="table  table-bordered table-condensed">
                    <thead>
                        <tr>
                        <th>Time</th>
                        <th>Altitude</th>
                        <th>Satellites</th>
                        <th>Speed Over Ground(KM)</th>
                        <th>Course</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td id="date"></td>
                        <td id="altitude"></td>
                        <td id="satellites"></td>
                        <td id="speed"></td>
                        <td id="course"></td>
                    </tr>
                    </tbody>
                    </table>
                </div>

                <center>
                <div class="google-maps col-xs-12">
                <div id="googleMap" style="width:800px;height:400px;"></div>
                </div>
                </center>

                
        </div>';
    ?>
</body>
</html>

<?php } ?>
  