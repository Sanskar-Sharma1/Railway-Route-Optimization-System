<?php
session_start();
	if(isset($_SESSION['adminusername'])){
		$name = '<h3 style="color: white;font-weight: bold;">'.$_SESSION['adminusername'].'<h3><form method="post" action="logout.php" ><input type="submit" value="Logout" style="padding: 5px;font-size: 16px;"></input></form>';
	}else $name = '<a href="../login.php"><h3 class="display-5" style="color: white;font-weight: bold;text-decoration: underline;">USER LOGIN<h3></a>';
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
?>

<html>
	<head>
		<link rel="stylesheet" href="../homepage.css"/>
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<script type="text/javascript" src="../js/bootstrap.js"></script>
	</head>
	<body>
		<div class="navbar" id="upper" style="border-radius: 3px;">
			<div class="navbar-brand"><a href="admindashboard.php"><img class="rounded-circle" src="../images/logo.png" alt="railway logo" style="height: 12%;"></a></div>
			<div class="navbar-brand"><h1 class="display-5" style="color: white;font-weight: bold;font-style: italic;text-decoration: underline;">INDIAN RAILWAY CORPORATION<h1></div>
			<div class="navbar-brand"><?php echo $name;?></div>		
		</div>
		<div class="row" style="margin-left: 2px;">
			<div class="col-3" id="form_division">
				<form method="post" class="form" action="../routesearch.php">
						<p style="color: white;font-size: 23px;">From:<br><input list="stations" id="liststyle" name="source" style="color: gray; width: 100%;padding: 3px 15px;margin: 10px 5px;display: inline-block;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;">
                                                                            <datalist id="stations">
    																						<option value="agartala">
																							<option value="aizawl">
    																						<option value="bengaluru">
    																						<option value="bhopal">
    																						<option value="bhubneshwar">
    																						<option value="chandigar">
    																						<option value="chennai">
    																						<option value="dehradoon">
    																						<option value="delhi">
    																						<option value="dispur">
    																						<option value="gandhinagar">
    																						<option value="gangtok">
    																						<option value="goa">
    																						<option value="hyderabad">
    																						<option value="itanagar">
    																						<option value="jaipur">
    																						<option value="kohima">
    																						<option value="kolkata">
    																						<option value="lucknow">
    																						<option value="mumbai">
    																						<option value="patna">
    																						<option value="raipur">
    																						<option value="ranchi">
    																						<option value="shilong">
    																						<option value="shimla">
    																						<option value="srinagar">
    																						<option value="thiruvanathapuram">
  																							</datalist><br>
						To:<br><input id="liststyle" list="stations" name="destination" style="color: gray; width: 100%;padding: 3px 15px;margin: 10px 5px;display: inline-block;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;"><br>
						date:<br><input type="date" name="date" style="color: gray;"><br>
						<input class="btn" type="submit" value="Find Trains" style="color: white;font-size: 23px;"/>		
						</p>
				</form>		
			</div>
			<div class="col-9" id="form_division" >
                <h3 style="color: white;font-weight: bold;">Change Cost<h3>
                <hr style="border-color: white;border-width: 3px;">
				<Form class="form" action="" method="post">
					<label style="color: white;">Train type</label>
                    <input list="type" id="liststyle" name="type">
                    <datalist id="type">
                        <option value="passenger"></option>
                        <option value="express"></option>
                        <option value="rajdhani"></option>
                        <option value="shatabdi"></option>
                    </datalist>
                    <label style="color: white;">Cost per Km</label>
                    <input type="text" name="cost">
                    <h3 style="color: white;font-weight: bold;">Change Multiplicity<h3>
                    <hr style="border-color: white;border-width: 3px;">
                    <label style="color: white;">Class</label>
                    <input list="class" id="liststyle" type="text" name="class">
                    <datalist id="class">
                        <option value="general"></option>
                        <option value="sleeper"></option>
                        <option value="AC3"></option>
                        <option value="AC2"></option>
                        <option value="AC1"></option>
                    </datalist>
                    <label style="color: white;">Multiplicity</label>
                    <input type="text" name="multiple">
                    <input type="submit" value="Submit">
                </Form>
                <?php
                    if(isset($_POST['type']) && isset($_POST['cost'])){
                        $query = "UPDATE cost SET `cost/km(Rs)`=".$_POST['cost']." WHERE `train_type`='".$_POST['type']."';";
                        
                        if(mysqli_query($con,$query)){
                            echo "<div class=\"alert alert-success\">
                                        Cost Successfully Updated...
                                  </div>";
                        }
                    }
                    if(isset($_POST['class']) && isset($_POST['multiple'])){
                        $query = "UPDATE class_multiplicity SET multiple=".$_POST['multiple']." WHERE class_type='".$_POST['class']."';";
                        if(mysqli_query($con,$query)){
                            echo "<div class=\"alert alert-success\">
                                        Multiplicity Successfully Updated...
                                  </div>";
                        }
                    }
                ?>
			</div>
		</div>
	</body>
</html>
