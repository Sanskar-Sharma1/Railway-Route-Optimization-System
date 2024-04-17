<?php
	echo 'connecting to database....<br>';
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	echo 'database connected....<br>';
	
	// getting information from train data
	$query = 'SELECT train_id,route,speed FROM trains';
	$res = mysqli_query($con,$query);
	$initial = 43200;
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)) {
		//initializing
      	$train_id = $row["train_id"];
      	echo $train_id.'<br>';
      	
		$route = explode(",",$row["route"]);
      	$speed = $row["speed"];
      	$offset = 0;
      	$day = 0;
      	$forward = array();
		$reverse = array();
      	$stationstay = 3600;
      	//getting route information
      	$q = "SELECT * FROM route WHERE route_id = ".$route[0]." ORDER BY station_order;";
      	$res1 = mysqli_query($con,$q);
      	if(mysqli_num_rows($res1)>0) {
      		$f = 0;
      		while($row1 = mysqli_fetch_assoc($res1)) {				
				$forward[$f++] = $row1["station"];												
      		}
      	}else die('cannot find route data');
		$q = "SELECT * FROM route WHERE route_id = ".$route[1]." ORDER BY station_order;";
      	$res1 = mysqli_query($con,$q);
      	if(mysqli_num_rows($res1)>0) {
      		$f = 0;
      		while($row1 = mysqli_fetch_assoc($res1)) {				
				$reverse[$f++] = $row1["station"];												
      		}
      	}else die('cannot find route data');
		
      	//getting distancess between stations
      	$distance = array();
      	$distance[0] = 0;
      	for($i = 0;$i<count($forward)-1;$i++){
      		$q = "SELECT * FROM distances WHERE location1 = '".$forward[$i]."' AND location2 = '".$forward[$i+1]."' OR location1 = '".$forward[$i+1]."' AND location2 = '".$forward[$i]."';";      		
      		$res1 = mysqli_query($con,$q);
      		$x = 0;
      		if(mysqli_num_rows($res1)>0) {
      			while($row1 = mysqli_fetch_assoc($res1)) {
						$x = $row1['distance'];					
      			}
      		}else die('cannot find distance data');
      		$distance[$i+1] = $distance[$i]+$x;
      	}
      	$flag = 0;
      	$stationflag = 0;
      	//initialising data
      	$sunday = '';
      	$monday = '';
			$tuesday = '';
			$wednesday = '';
			$thursday = '';
			$friday = '';
			$saturday = '';
			//starting time
      	$t = $offset;
      	//scheduling the train
      	//number of stations
      	$nos = count($forward);
      	//total time in one run including the time spent at stations
      	$tt = (int)$distance[$nos-1]*3600/$speed+($nos-1)*$stationstay;
      	$ud = (int)604800/$tt;
      	if($ud % 2 != 0)
      		$ud--;
      	for($i=0;$t<$ud*$tt;$i++) {
      		if($flag == 0) {
					if($t<=86400) {
						$sunday = $sunday.$forward[$stationflag++]."@".($t  % 86400)."@0@".$i."#";
					}else if($t<=172800){
						$monday = $monday.$forward[$stationflag++]."@".($t  % 86400)."@0@".$i."#";
					}else if($t<=259200){
						$tuesday = $tuesday.$forward[$stationflag++]."@".($t  % 86400)."@0@".$i."#";
					}else if($t<=345600){
						$wednesday = $wednesday.$forward[$stationflag++]."@".($t  % 86400)."@0@".$i."#";
					}else if($t<=432000){
						$thursday = $thursday.$forward[$stationflag++]."@".($t  % 86400)."@0@".$i."#";
					}else if($t<=518400){
						$friday = $friday.$forward[$stationflag++]."@".($t  % 86400)."@0@".$i."#";
					}else if($t<=604800){
						$saturday = $saturday.$forward[$stationflag++]."@".($t  % 86400)."@0@".$i."#";
					}	      		
      		}else {
      			if($t<=86400) {
						$sunday = $sunday.$reverse[$stationflag++]."@".($t  % 86400)."@1@".$i."#";
					}else if($t<=172800){
						$monday = $monday.$reverse[$stationflag++]."@".($t  % 86400)."@1@".$i."#";
					}else if($t<=259200){
						$tuesday = $tuesday.$reverse[$stationflag++]."@".($t  % 86400)."@1@".$i."#";
					}else if($t<=345600){
						$wednesday = $wednesday.$reverse[$stationflag++]."@".($t  % 86400)."@1@".$i."#";
					}else if($t<=432000){
						$thursday = $thursday.$reverse[$stationflag++]."@".($t  % 86400)."@1@".$i."#";
					}else if($t<=518400){
						$friday = $friday.$reverse[$stationflag++]."@".($t  % 86400)."@1@".$i."#";
					}else if($t<=604800){
						$saturday = $saturday.$reverse[$stationflag++]."@".($t  % 86400)."@1@".$i."#";
					}
      		}
      		if($flag == 0) 
      			$t = $t + (int)($distance[$stationflag]-$distance[$stationflag-1])*3600/$speed + 3600;
      		else $t = $t + (int)($distance[$nos-$stationflag]-$distance[$nos-$stationflag-1])*3600/$speed +3600;
				if($stationflag == $nos-1) {
					if($flag == 0){ 
      				$flag = 1;
      				$stationflag = 0;
      			}
      			else { 
      				$flag = 0;
      				$stationflag = 0;
      			}
      		}
      	}
      	$q = "UPDATE schedule SET sunday = '$sunday' , monday = '$monday' , tuesday = '$tuesday' , wednesday = '$wednesday' , thursday = '$thursday' , friday = '$friday' , saturday = '$saturday' WHERE train_id = $train_id;";
      	if(mysqli_query($con,$q)){
      		echo '<br>successfull<br>';
      	}else echo mysqli_error($con);
   		echo '<br>';
   	}
	}else die('cannot find train data');
?>