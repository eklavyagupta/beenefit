
    <!-- ===============================================-->
    <!-- PHP START -->
    <!-- ===============================================-->

<?php

// login database info
$user ="azure";
$pass ='6#vWHD_$';
$db ="localdb";

$db =new mysqli('127.0.0.1:49297', $user, $pass, $db) or die ("Unable to connect");



// get value from input
$search_value=$_POST["search"];

// check the correct postcode or not
if (strlen($search_value) == 4){

  // sql query to get value
$sql1= "select longitude from `postcodes` where postcode = '$search_value'";

$lo = $db->query($sql1);

$sql2= "select lat from `postcodes` where postcode = '$search_value'";

$la = $db->query($sql2);

// get fetched value if postcode is avalible
if ($lo->num_rows > 0 ) {
  // output data of each row
  while($row1 = $lo->fetch_assoc()) {
    $new_long =  $row1["longitude"];
  
  }
    // output data of each row
  while($row = $la->fetch_assoc()) {
    $new_lat =  $row["lat"];
   
  }



// set api link
$SuburbApi = 'http://api.openweathermap.org/geo/1.0/zip?zip='.$search_value.',au&appid=f8602df67c495efda45f5097b5244ba6';

// call api
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $SuburbApi);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$sub_response = curl_exec($ch);

curl_close($ch);
// store into variables
$Sub_data = json_decode($sub_response);
$postcode = $Sub_data ->zip;
$suburb = $Sub_data ->name;






// set api link
$ApiUrl ='https://api.openweathermap.org/data/2.5/onecall?lat='.$new_lat.'&lon='.$new_long.'&exclude=minutely,hourly,daily,alerts&appid=f8602df67c495efda45f5097b5244ba6&units=metric';

// call api
$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $ApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);


// store into variables
$data = json_decode($response);


$Time = $data ->current ->dt;
$temperature = $data ->current ->temp;
$humidity = $data -> current ->humidity;
$wind_speed = $data ->current ->wind_speed;
$weather = $data ->current ->weather[0]->description;
$main_weather = $data ->current ->weather[0]->main;
$dateInLocal = date("Y-m-d", $Time);
$weather_icon =$data ->current ->weather[0]->icon;


// set api link
$air_api = 'http://api.openweathermap.org/data/2.5/air_pollution?lat='.$new_lat.'&lon='.$new_long.'&appid=f8602df67c495efda45f5097b5244ba6';

// call api
$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $air_api);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$air_response = curl_exec($ch);
// store into variables
curl_close($ch);
$air_data = json_decode($air_response);
$air_qi = $air_data ->list[0] ->main ->aqi;

// match number into air quality description
if($air_qi == 1){
  $airsituation = "Good";
}

if($air_qi == 2){
  $airsituation = "Fair";
}

if($air_qi == 3){
  $airsituation = "Moderate";
}

if($air_qi == 4){
  $airsituation = "Poor";
}

if($air_qi == 5){
  $airsituation = "Very Poor";
}





} 

// pop alert for wrong postcode
else {echo "<script>alert('Please enter a postcode of VIC'); location.href = 'index.html'</script>";
}


}
// pop alert for wrong postcode
else {echo "<script>alert('Please enter a postcode of VIC'); location.href = 'index.html'</script>";
}



?> 


<!-- ===============================================-->
<!-- PHP END -->
<!-- ===============================================-->



<!-- ===============================================-->
<!-- HTML START -->
<!-- ===============================================-->



<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Beenefit</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="assets/css/theme.css" rel="stylesheet" />

<style>    


.report-container {
    border: #E0E0E0 1px solid;
    padding: 20px 40px 40px 40px;
    border-radius: 2px;
    width: 550px;
    margin: 0 auto;
}

.weather-icon {
    
    margin-right: 20px;
}

.weather-forecast {
    color: #212121;
    font-size: 1.2em;
    font-weight: bold;
    margin: 20px 0px;
}


.time {
    line-height: 25px;
}
</style>
    

</head>

<body>

<!-- Start: Preloader section -->
<div id="loader-wrapper">
    <div id="loader"></div>
</div>
<!-- End: Preloader section -->

<!-- DOCUMENT WRAPPER STARTS -->


    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 backdrop" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand d-flex align-items-center fw-bolder fs-2 fst-italic" href="index.html">
            <div class="text-warning">BEENEFIT</div>
          </a>
          <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto pt-2 pt-lg-0">
              <li class="nav-item px-2"><a class="nav-link fw-medium active" style="border-radius: 5px;" aria-current="page" href="index.html">Home</a></li>
              <li class="nav-item px-2"><a class="nav-link fw-medium" style="border-radius: 5px;" href="timeline.html">Timeline</a></li>
              <li class="nav-item px-2"><a class="nav-link fw-medium" style="border-radius: 5px;" href="weather.html">Discovery</a></li>
              <li class="nav-item px-2"><a class="nav-link fw-medium active" style="border-radius: 5px;" href="bee_identification.html">Health Check</a></li>
            </ul>
          </div>
        </div>
      </nav>

      </br>
      </br>



        <section class="py-5">
        <div class="bg-holder d-none d-sm-block" style="background-image:url(assets/img/illustrations/category-bg.png);background-position:right top;background-size:200px 320px;">
        </div>
        
        <!--/.bg-holder-->

        <div class="container">
          <div class="row flex-center">

            <div class="col-md-5 order-md-0 text-center text-md-start">
            
                
                <div class="weather-forecast">
                    <!-- print temperature -->
                    <h2><b><?php echo $temperature; ?>°C</b>
                    <!-- get the weather icon by calling api -->
                    <img src="http://openweathermap.org/img/wn/<?php echo $weather_icon; ?>@4x.png" class="weather-icon" style="vertical-align:middle" /> 
                        </h2>
                        <h2><b><?php echo ucwords($weather); ?></b></h2>
                </div>
                </br>
                <div class="time">
                    <!-- print humidity, wind_speed, air quality-->
                    <div><h4>Humidity: <?php echo $humidity; ?> %</h4></div>
                    <div><h4>Wind: <?php echo $wind_speed; ?> m/s</h4></div>
                    <div><h4>Air quality:  <?php echo $airsituation; ?></h4></div>
                </div>
                  <!-- print suburb-->
              <p><b><?php echo $suburb; ?></b></p>
                  <!-- print date -->
                  <p><b><?php echo  $dateInLocal; ?></b></p>
            </div>
            
            <div class="col-md-5 text-center text-md-start">
              <h1><b><p style="color:#ffaa00">Suggestion: </p></b></h1>
                <h4> 
                  <?php
                      // print the suggestion if there is no harmful weather for beekeeping
                      if (
                        $temperature > 10 & 
                          $temperature <= 35 &
                          $air_qi <= 2 &
                          $main_weather != "Rain" & $main_weather != "Thunderstorm" & $main_weather != "Drizzle" &
                          $humidity >= 75 &
                          $humidity <= 80 &
                          $wind_speed < 5.5
                          ){
                            echo "Your bees are in good condition, just a reminder: Please remember to refill the water feeder for your bees : )";

                      }
                      // print the suggestion based on different weather situation
                      else {
                        if ($temperature <= 10) {
                          echo"· Close off the screened-bottom board when the hive in open plains. "."<br>"."<br>".
                        "· Provide a little insulation to the outside of their hives if possible: like terrarium heaters."."<br>"."<br>".
                        "· Make sure there aren't drafty holes in their equipment that let cold air or, worse, water in."."<br>"."<br>".
                        "· Hives are distributed evenly throughout the orchard individually."."<br>"."<br>";
                        }

                        elseif ($temperature > 10 & $temperature < 30 ){
                          echo 
                          " · Your bees are happy with current temperature. reminders: to refill the water feeder three times a week."."<br>"."<br>";
                        }
                        
  
                        elseif ($temperature > 35 & $temperature < 37){
                          echo "· Hive inspection should be postponed during extreme hot days."."<br>"."<br>".
                          " · Provide broken shade over the hives(note that avoid total shade for the whole day."."<br>"."<br>".
                          " · Ideally clean all the imflammable materials like grass and leaves around the hives."."<br>"."<br>".
                          "· Spraying water on the external walls of the hive."."<br>"."<br>";
                        }
                        elseif ($temperature > 37 ){
                          echo "· Hive inspection should be postponed during extreme hot days."."<br>"."<br>".
                          " · Place a spare hive cover on top of the hive cover (a sheet of hardboard or a car windscreen heat shield can be used for shading of side of the hive)."."<br>"."<br>".
                          " · Place bricks under the hives so the hives is around 150 mm from the ground."."<br>"."<br>".
                          " · Spraying water on the external walls of the hive."."<br>"."<br>".
                          " · Recommended to refill the water feeder on a daily base."."<br>"."<br>";
                        }
                        
  
  
  
                        # Air quality suggestion
  
  
  
                        if ($air_qi >= 3) {
                          echo"· Put a net cover on top if possible."."<br>"."<br>";
                          
                        } 
  
  
  
                        # Rain suggestion
  
                        if ($main_weather == "Rain"|$main_weather == "Thunderstorm" |$main_weather == "Drizzle") {
                          echo "· Build a full hive."."<br>"." · Hives are distributed evenly throughout the orchard individually."."<br>"."<br>";
                          
                        } 
  
  
  
  
  
  
                        # Humidity suggestion
  
  
                        if ($humidity >80 |$humidity<75) {
                          echo " · Forego upper ventilation or quilts and simply place a thick sheet of nonporous insulation (e.g. 2″-4″ thick styrofoam) above the inner cover."."<br>"."<br>".
                          " · Or rear shim tilting the hive forward about 3 degrees."."<br>"."<br>";
                          
                        }
  
  
  
                        # Wind Speed suggestion
  
  
                        if ($wind_speed > 5.5) {
                          echo "· Place bees in places with frequent winds."."<br>"."<br>"." · Ensure The location of the bee farm should be set at the downwind of the honey powder source, so that the bees will go against the wind when they are out of the nest without load.";
                          
                        }
                      }
                      

                    ?>
                </h4>

            </div>
          </div>
        </div>
      </section>
      
      <!-- ============================================-->
      <!-- subscribe function -->
      <!-- <section> begin ============================-->
      <section class="py-8" id = "sub">
        <div class="container">
          <div class="row flex-center">
            <div class="col-md-5 order-md-1 text-center text-md-end"><img class="img-fluid mb-4" src="assets/img/illustrations/feature.png" width="450" alt="" /></div>
            <div class="col-md-5 text-center text-md-start">
              <!-- Email function title -->
              <h6 class="fw-bold fs-2 fs-lg-3 display-3 lh-sm">Subscribe Us</h6>
              <p class="my-4 pe-xl-8">Enter your Email and Postcode to get Latest Bee Weather Alerts.</p>
              <div class="col-lg-6 d-flex justify-content-lg-end justify-content-center">
                <form class="row row-cols-lg-auto g-0 align-items-center" action = "email.php"  method="POST">
                  <div class="col-5display-3">
                    <label class="visually-hidden" for="colFormLabel">Username</label>
                    <div class="input-group">
                      <input class="rounded-end-0 form-control" id="colFormLabel" type="email" name= "email" placeholder="Email" required/>
                    </div>
                    </br>
                    <div class="input-group">
                      <input class="rounded-end-0 form-control" id="colFormLabel" type="text" name= "postcode" placeholder="Vic Postcode" maxlength="4" pattern="\d{4}" required />
                    </div>
                  </div>
                  <div class="display-15" style = "padding-top:25px">
                    <button class="btn btn-primary rounded-start-0" type="submit">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- end of .container-->
        <!-- end of .container-->
      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->


      <section class="py-0">
        <div class="bg-holder" style="background-image:url(assets/img/illustrations/footer-bg.png);background-position:center;background-size:cover;">
        </div>
        <!--/.bg-holder-->

        <div class="container">
          <div class="row flex-center py-6">
            <div class="col-lg-6 d-flex justify-content-lg-end justify-content-center">
            </div>
          </div>
          <div class="row justify-content-center">
          <div class="col-auto mb-2">
              <p class="mb-0 fs--1 my-2 text-center">Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></p>
              <p class="mb-0 fs--1 my-2 text-center">Presented by <a href="https://www.beenefit.studio" title="BEENEFIT">BEENEFIT</a></p>
            </div>
          </div>
        </div>
      </section>






    </main>
<!-- DOCUMENT WRAPPER ENDS -->

    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="assets/js/theme.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400&amp;display=swap" rel="stylesheet">

</body>

</html>


<!-- ===============================================-->
<!-- HTML EMD -->
<!-- ===============================================-->

