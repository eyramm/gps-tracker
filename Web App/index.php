<?php

    if (!empty($_GET['latitude']) && !empty($_GET['longitude']) && 
    	!empty($_GET['altitude']) && 
        !empty($_GET['time']) && !empty($_GET['satellites']) &&
        !empty($_GET['speedOTG']) && !empty($_GET['course'])) {

    	//latitude, longitude, altitude, date, satellites, speedOTG, course

        function getParameter($par, $default = null){
            if (isset($_GET[$par]) && strlen($_GET[$par])) return $_GET[$par];
            elseif (isset($_POST[$par]) && strlen($_POST[$par])) 
                return $_POST[$par];
            else return $default;
        }
	
		
        $file = 'gps.txt';
        $lat = getParameter("latitude");
        $lon = getParameter("longitude");
        $alt = getParameter("altitude");
        $time = getParameter("time");
        $sat = getParameter("satellites");
        $speed = getParameter("speedOTG");
        $course = getParameter("course");
		
		
		$year= substr($time, 0,4);
		$month= substr($time, 4,2);
		$date= substr($time, 6,2);
		$hours= substr($time, 8,2);
		$minutes= substr($time, 10,2);
		$lastseen = $date."-".$month."-".$year." at ".$hours.":".$minutes;

        
		$person = $lat.",".$lon.",".$alt.",".$lastseen.",".$sat.",".$speed.",".$course."\n";

        echo '
            RECEIVED:\n
            Latitude: ".$lat."\n
            Longitude: ".$lon."\n
            Time: ".$time."\n
            Satellites: ".$sat."\n
            Speed OTG: ".$speed."\n
            Course: ".$course';

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


    
<!-- <script language="JavaScript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="css/styles.css">    
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scaleable=no">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
    <!-- Load Google Maps Api -->

    <!-- IMPORTANT: insert the API key -->

    <script src=""></script>


    <!-- Initialize Map and markers -->
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

    <script type="text/javascript">
      //  var myCenter =new google.maps.LatLng(5.44300,-8.340000);
        var marker;
        var map;
        var mapProp;

        function initialize()
        {
            mapProp = {
              zoom:15,
              mapTypeId:google.maps.MapTypeId.ROADMAP
              };
             
          
            mark();
          
        }
        
        
        setInterval(function(){
            mark();
        }, 30000);
		
		
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
                        marker=new google.maps.Marker({
                              position:new google.maps.LatLng(words[0],words[1]),
                              });
                        marker.setMap(map);
                        map.setCenter(new google.maps.LatLng(words[0],words[1]));
                    }
                }
                marker.setAnimation(google.maps.Animation.BOUNCE);
            });

        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>
 
<body>

<a href="./vetrap.apk">Get the Android app </a>
<?php
        echo '      
<!DOCTYPE html>

    <style>
      #googleMap {
        width: 800px;
        height: 400px;
        background-color: grey;
      }
    </style>

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
        <li class="active"><a href="index.php">Live</a></li>
      <li><a href="gps.php">Last Seen</a></li>
      <li><a href="data.html">Logs</a></li>
      </ul>
    </div>
  </div>
</nav>

    <center>
    <div class="google-maps col-xs-12">
    <div id="googleMap">
    </div>
    </div>
    </center>

</div>
';
?>

</body>
</html>

<?php } ?>