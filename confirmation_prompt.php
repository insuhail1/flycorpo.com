<?php  
session_start();

require "init.php";
include("functions.php");

$count = $_SESSION['count'];

$allAirports = curl("http://webapi.i2space.co.in/Flights/AvailableFlights?source=".$_SESSION['from_address']."&destination=".$_SESSION['to_address']."&journeyDate=".$_SESSION['start_Date']."&tripType=1&flightType=1&adults=".$_SESSION['Adults']."&children=".$_SESSION['Children']."&infants=".$_SESSION['Infant']."&travelClass=".$_SESSION['classvalue']."&userType=5&returnDate=".$_SESSION['endDate'], [], "application/json", false, [
    "ConsumerKey: 816EDF7E056EB6E0D84B997CAB3F000C4ABE2FC7",
    "ConsumerSecret: 0EC008F43044CADBA7D71D50CCBC6948F756799F",
]);


$IntBaseFare=0;
$IntTax=0;
$IntAmount=0;

$obj = json_decode($allAirports);

?><!DOCTYPE HTML>
<html>

<head>
    <title>Flycorpo</title>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name="keywords" content="flybuzz" />
    <meta name="description" content="flybuzz">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css'>
    <!-- /GOOGLE FONTS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/schemes/bright-turquoise.css"/> </head>
    <link rel="stylesheet" href="css/mystyles.css">
    <script src="js/modernizr.js"></script>
    <link rel="stylesheet" href="css/schemes/bright-turquoise.css"/>

</head>

<body>

    <!-- FACEBOOK WIDGET -->
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- /FACEBOOK WIDGET -->
    <div class="global-wrap">
        <?php include("header.php"); ?>
     <section class="flight-search-result">
        <div  class="container">

            
            <div class="row">
                
                
                
                          
<?php

   

     $key =$obj->DomesticOnwardFlights[$count];
    foreach ($key->FlightSegments as $FlightSegment) {
    // echo "{
    //     <br>ArrivalAirportCode :  ".$FlightSegment->ArrivalAirportCode;
        
    //     echo "<br>DepartureAirportCode :  ".$FlightSegment->DepartureAirportCode;
    //     echo "<br>DepartureAirportCode :  ".$FlightSegment->DepartureAirportCode;
    // echo "<br>DepartureAirportCode :  ".$FlightSegment->DepartureAirportCode;
    // echo "<br>ArrivalDateTime :  ".$FlightSegment->ArrivalDateTime;
    // echo "<br>FlightNumber :  ".$FlightSegment->FlightNumber;
    // echo "<br>OperatingAirlineCode :  ".$FlightSegment->OperatingAirlineCode."}<br>";
    //  echo $FlightSegment->OperatingAirlineCode; echo $FlightSegment->OperatingAirlineFlightNumber;
     $AirLineName=$FlightSegment->AirLineName;
     $OperatingAirlineCode=$FlightSegment->OperatingAirlineCode;
     $OperatingAirlineFlightNumber =$FlightSegment->OperatingAirlineFlightNumber;
     $departdate = substr($FlightSegment->DepartureDateTime, 0,10);
     $departtime = substr($FlightSegment->DepartureDateTime, 11,5);
     $IntDepartureAirportName = $FlightSegment->IntDepartureAirportName;

    $Duration = $FlightSegment->Duration;
    $ArrivalTime = substr($FlightSegment->ArrivalDateTime, 11,5);
    $ArrivalDate = substr($FlightSegment->ArrivalDateTime, 0,10);
    $IntArrivalAirportName = $FlightSegment->IntArrivalAirportName;

    $key1 = $key->FareDetails;
    $key2 = $key1->FareBreakUp;
    $key3 = $key2->FareAry[0];
    $IntBaseFare = $key3->IntBaseFare;
    $IntTax = $key3->IntTax;
    $key4 = $key3->IntTaxDataArray[0];
    $IntAmount = $key4->IntAmount;
}
    

if(isset($_SESSION['email'])){
  $usertype ='user';
  $booker_email = $_SESSION['email'];
}
else if (isset($_SESSION['agent_email'])){
  $usertype ='agent';
  $booker_email = $_SESSION['agent_email'];


}else {
  $usertype ='none';
  $booker_email= 'none';

}
$flag = 0;

if ($_SESSION['Adults']>0) {
    # code...
for ($i=0; $i <$_SESSION['Adults'] ; $i++) { 
    $c=$i+1;



  ${"adultname" . $c} =$_POST["adultname".$c];
 ${"adultmobile" . $c} =$_POST["adultmobile".$c];
 ${"adultemail" . $c} =$_POST["adultemail".$c]; 
 $email = ${"adultemail" . $c};
 $name = ${"adultname" . $c};
 $mobile = ${"adultmobile" . $c};
 $passengertype = "Adult";




 $query = "INSERT INTO `bookedflight`(AirLineName, OperatingAirlineCode, OperatingAirlineFlightNumber, departtime, departdate, IntDepartureAirportName, Duration, ArrivalTime, ArrivalDate, IntArrivalAirportName, IntBaseFare, IntTax, IntAmount, email, name, mobile, passengertype, usertype,booker_email) 
    VALUES ('$AirLineName', '$OperatingAirlineCode', '$OperatingAirlineFlightNumber', '$departtime', '$departdate', '$IntDepartureAirportName', '$Duration','$ArrivalTime','$ArrivalDate','$IntArrivalAirportName','$IntBaseFare','$IntTax','$IntAmount','$email','$name','$mobile','$passengertype','$usertype','$booker_email')"; 
            
                  if (mysqli_query($link,$query)) {
                    
                      // $condition=1;
                    $flag = 1;

                  } else {
                      // echo $link->error;
                      // echo "<p>There was a problem signing you up - please try again later.</p>";
                    $flag = 2;
                      
                  }
    
}
}

if ($_SESSION['Children']>0) {
    # code...
for ($i=0; $i <$_SESSION['Children'] ; $i++) { 
$c=$i+1;

 ${"childname" . $c}  =$_POST["childname".$c];
 ${"childmobile" . $c}  =$_POST["childmobile".$c];
 // $childemail =$_POST["childemail".$c]; 


 $name = ${"childname" . $c};
 $mobile = ${"childmobile" . $c};
 $passengertype = "Child";




 $query = "INSERT INTO `bookedflight`(AirLineName, OperatingAirlineCode, OperatingAirlineFlightNumber, departtime, departdate, IntDepartureAirportName, Duration, ArrivalTime, ArrivalDate, IntArrivalAirportName, IntBaseFare, IntTax, IntAmount, name, mobile, passengertype, usertype,booker_email) 
    VALUES ('$AirLineName', '$OperatingAirlineCode', '$OperatingAirlineFlightNumber', '$departtime', '$departdate', '$IntDepartureAirportName', '$Duration','$ArrivalTime','$ArrivalDate','$IntArrivalAirportName','$IntBaseFare','$IntTax','$IntAmount','$name','$mobile','$passengertype','$usertype','$booker_email')"; 
            
                  if (mysqli_query($link,$query)) {
                    
                      // $condition=1;
                    $flag = 1;

                  } else {
                      // echo $link->error;
                      // echo "<p>There was a problem signing you up - please try again later.</p>";
                    $flag = 2;
                      
                  }
    
}
}

if ($_SESSION['Infant']>0) {
    # code...
for ($i=0; $i <$_SESSION['Infant'] ; $i++) { 
$c=$i+1;

 ${"infantname" . $c}  =$_POST["infantname".$c];
  ${"infantmobile" . $c}  =$_POST["infantmobile".$c];
 // $adultemail =$_POST["adultemail".$c]; 

  $name = ${"infantname" . $c};
 $mobile = ${"infantmobile" . $c};
 $passengertype = "Infant";




 $query = "INSERT INTO `bookedflight`(AirLineName, OperatingAirlineCode, OperatingAirlineFlightNumber, departtime, departdate, IntDepartureAirportName, Duration, ArrivalTime, ArrivalDate, IntArrivalAirportName, IntBaseFare, IntTax, IntAmount, name, mobile, passengertype, usertype,booker_email) 
    VALUES ('$AirLineName', '$OperatingAirlineCode', '$OperatingAirlineFlightNumber', '$departtime', '$departdate', '$IntDepartureAirportName', '$Duration','$ArrivalTime','$ArrivalDate','$IntArrivalAirportName','$IntBaseFare','$IntTax','$IntAmount','$name','$mobile','$passengertype','$usertype','$booker_email')"; 
            
                  if (mysqli_query($link,$query)) {
                    
                      // $condition=1;
                    $flag = 1;

                  } else {
                      // echo $link->error;
                      // echo "<p>There was a problem signing you up - please try again later.</p>";
                    $flag = 2;
                      
                  }

    
}
}

    




 ?>
                    
     <?php  
     if ($flag==1) {

    echo "<h3 class=\"text-center\"> Flight(s) has been booked.</h3>";         # code...
     }
     else{
    echo "<h3 class=\"text-center text-danger\"> Flight(s) has not been booked.</h3>";         # code...

     }
     ?>
        
        
     </section> 
      
      
       

        <footer>
					<div class="upper-footer-blk">
						<div class="container">
							<div class="row">
								<div class="col-sm-9">
									<div class="row">
										<div class="col-sm-2">
											<h5>Corporate</h5>
											<ul>
			                  <li><a href="#">Investor Information</a></li>
			                  <li><a href="#">About FlyCorpo</a></li>
			                  <li><a href="#">Careers</a></li>
			                </ul>
										</div>
										<div class="col-sm-2">
											<h5>Legal</h5>
											<ul>
			                  <li><a href="#">Terms & Conditions</a></li>
			                  <li><a href="#">Policies</a></li>
			                  <li><a href="#">Disclaimer</a></li>
			                </ul>
										</div>
										<div class="col-sm-2">
											<h5>Media Center</h5>
											<ul>
			                  <li><a href="#">Press Releases</a></li>
			                  <li><a href="#">Media Contacts</a></li>
			                </ul>
										</div>
										<div class="col-sm-2">
											<h5>Support</h5>
											<ul>
												<li><a href="#">Contact Us</a></li>
												<li><a href="#">FAQs</a></li>
												<li><a href="#">Special Assistance</a></li>
												<li><a href="#">Feedback</a></li>
											</ul>
										</div>
										<div class="col-sm-2">
											<h5>Others</h5>
											<ul>
												<li><a href="#">Optional Charges</a></li>
												<li><a href="#">Explore</a></li>
												<li><a href="#">Subscribe for Offers</a></li>
												<li><a href="#">Fare Sheets</a></li>
												<li><a href="#">Sitemap</a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
										<div class="payment-options">
											<a href="#"><img src="img/footer/payments-gateways/mastercard.png" alt="mastercard icon"></a>
											<a href="#"><img src="img/footer/payments-gateways/visa.png" alt="visa icon"></a>
											<a href="#"><img src="img/footer/payments-gateways/paypal.png" alt="paypal icon"></a>
										</div>
										<div class="payment-options">
											<a href="#"><img src="img/footer/payments-gateways/american-express.png" alt="american-express icon"></a>
											<a href="#"><img src="img/footer/payments-gateways/mastero.png" alt="mastero icon"></a>
											<a href="#"><img src="img/footer/payments-gateways/google-wallets.png" alt="google-wallets icon"></a>
										</div>
								</div>
							</div>
						</div>
					</div>
					<div class="middle-footer-blk">
						<div class="container">
							<div class="middle-footer-in">
								<div class="row">
									<div class="col-sm-2">
										<a href="#"><img src="img/footer/footer_logo.png" alt="footer logo"></a>
									</div>
									<div class="col-sm-2">
										<a href="#"><span><img src="img/footer/mail.png" alt="mail icon"></span>
											<aside>
												<h5>Email</h5>
												<small>nfo@flycorpo.com</small>
											</aside>
										</a>
									</div>
									<div class="col-sm-2">
										<a href="#"><span><img src="img/footer/phone.png" alt="phone icon"></span>
											<aside>
												<h5>Phone</h5>
												<small>040-42703020<br>040-42703021</small>
											</aside>
										</a>
									</div>
									<div class="col-sm-4">
										<a href="#"><span><img src="img/footer/location.png" alt="location icon"></span>
											<aside>
												<h5>Location</h5>
												<small>Location Door No.1-96/4, Above Hyderabad Hosts,<br>Madhapur Main Road, Hyderabad-81.</small>
											</aside>
										</a>
									</div>
									<div class="col-sm-2">
										<h5>Connect with us</h5>
										<ul class="social-media">
			                <li><a href="#" target="_blank"><img src="img/footer/connect-with-us/facebook.jpg" alt="facebook"></a></li>
											<li><a href="#" target="_blank"><img src="img/footer/connect-with-us/twitter.jpg" alt="twitter"></a></li>
			                <li><a href="#" target="_blank"><img src="img/footer/connect-with-us/google_pluse.jpg" alt="google_pluse"></a></li>
			                <li><a href="#" target="_blank"><img src="img/footer/connect-with-us/linkedin.jpg" alt="linkedin"></a></li>
			                <li><a href="#" target="_blank"><img src="img/footer/connect-with-us/youtube.jpg" alt="youtube"></a></li>
			              </ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="lower-footer-blk">
						<div class="container">
							<span class="foot-text">&copy;2018 Flycorpo.com. All Rights reserved</span>
							<span class="foot-text text-right">Designed and Developed By<img src="img/footer/powerd_by_logo.png" alt="powerd logo"></span>
						</div>
					</div>
				</footer>
        <!---Earch Modal --->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLongTitle">Search for Flight</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
       
                
                <form>
                    <div class="tabbable">
                        <ul class="nav nav-pills nav-sm nav-no-br mb10" id="flightChooseTab">
                            <li class="active"><a href="#flight-search-1" data-toggle="tab">Round Trip</a>
                            </li>
                            <li><a href="#flight-search-2" data-toggle="tab">One Way</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="flight-search-1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon input-icon-highlight"></i>
                                            <label>From</label>
                                            <input class="typeahead form-control" placeholder="City, Airport or U.S. Zip Code" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon input-icon-highlight"></i>
                                            <label>To</label>
                                            <input class="typeahead form-control" placeholder="City, Airport or U.S. Zip Code" type="text" />
                                        </div>
                                    </div>
                                </div>
                                <div class="input-daterange" data-date-format="MM d, D">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                <label>Departing</label>
                                                <input class="form-control" name="start" type="text" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                <label>Returning</label>
                                                <input class="form-control" name="end" type="text" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-lg form-group-select-plus">
                                                <label>Passengers</label>
                                                <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                                    <label class="btn btn-primary active">
                                                        <input type="radio" name="options" />1</label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="options" />2</label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="options" />3</label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="options" />4</label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="options" />5</label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="options" />5+</label>
                                                </div>
                                                <select class="form-control hidden">
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option selected="selected">6</option>
                                                    <option>7</option>
                                                    <option>8</option>
                                                    <option>9</option>
                                                    <option>10</option>
                                                    <option>11</option>
                                                    <option>12</option>
                                                    <option>13</option>
                                                    <option>14</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="flight-search-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon input-icon-highlight"></i>
                                            <label>From</label>
                                            <input class="typeahead form-control" placeholder="City, Airport or U.S. Zip Code" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon input-icon-highlight"></i>
                                            <label>To</label>
                                            <input class="typeahead form-control" placeholder="City, Airport or U.S. Zip Code" type="text" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                            <label>Departing</label>
                                            <input class="date-pick form-control" data-date-format="MM d, D" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-lg form-group-select-plus">
                                            <label>Passengers</label>
                                            <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                                <label class="btn btn-primary active">
                                                    <input type="radio" name="options" />1</label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" />2</label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" />3</label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" />4</label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" />5</label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" />5+</label>
                                            </div>
                                            <select class="form-control hidden">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                                <option selected="selected">6</option>
                                                <option>7</option>
                                                <option>8</option>
                                                <option>9</option>
                                                <option>10</option>
                                                <option>11</option>
                                                <option>12</option>
                                                <option>13</option>
                                                <option>14</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-lg" type="submit">Search for Flights</button>
                </form>
            </div>
      </div>
      <!--<div class="modal-footer">

         
      </div>-->
    </div>
  </div>
  <!---End search modal--->
  <!--- Fight details modal--->
  <div class="booking-item-details modal fade" id="booking-item-details-view" tabindex="-1" role="dialog" 		aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    	<div class="modal-dialog" role="document">
                                        	
   									 		<div class="modal-content">
                                            	<div class="modal-header">
                                                    <h4 class="modal-title" id="exampleModalLongTitle">Flight Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">×</span>
                                                    </button>
                                         		 </div>
                                                <div class="row modal-body">
                                                    <div class="col-md-12">
                                                        
                                                        <h6 class="list-title theme-color-text">London (LHR) to Charlotte (CLT)</h6>
                                                        <ul class="list">
                                                            <li>US Airways 731</li>
                                                            <li>Economy / Coach Class ( M), AIRBUS INDUSTRIE A330-300</li>
                                                            <li>Depart 09:55 Arrive 15:10</li>
                                                            <li>Duration: 9h 15m</li>
                                                        </ul>
                                                        <h6 class="theme-color-text">Stopover: Charlotte (CLT) 7h 1m</h6>
                                                        <h6 class="list-title">Charlotte (CLT) to New York (JFK)</h6>
                                                        <ul class="list">
                                                            <li>US Airways 1873</li>
                                                            <li>Economy / Coach Class ( M), Airbus A321</li>
                                                            <li>Depart 22:11 Arrive 23:53</li>
                                                            <li>Duration: 1h 42m</li>
                                                        </ul>
                                                        <p>Total trip time: 17h 58m</p>
                                                    </div>
                                                </div>
                                
                                   
                                   
                                </div>
                                     </div>
                                 
                                     </div>
                                     <!---end Filght search modal--->
</div>

        <script src="js/jquery.js"></script>
       
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/slimmenu.js"></script>
        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/bootstrap-timepicker.js"></script>
        <script src="js/nicescroll.js"></script>
        <script src="js/dropit.js"></script>
        <script src="js/ionrangeslider.js"></script>
        <script src="js/icheck.js"></script>
        <script src="js/fotorama.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="js/typeahead.js"></script>
        <script src="js/card-payment.js"></script>
        <script src="js/magnific.js"></script>
        <script src="js/owl-carousel.js"></script>
        <script src="js/fitvids.js"></script>
        <script src="js/tweet.js"></script>
        <script src="js/countdown.js"></script>
        <script src="js/gridrotator.js"></script>
        <script src="js/custom.js"></script>
        <script>


		</script>
    </div>
</body>

</html>


