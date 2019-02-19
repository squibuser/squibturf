<?php
header('Content-Type: text/html; charset=utf-8');
   mysql_connect('squibturf.com', 'admin', 'squibber') or die(mysql_error());   
   mysql_set_charset('utf8mb4'); 
	include('emoji.php');
    $type = $_POST['type'];
    $type();

    function getUserName($dataUser){
   	 mysql_select_db('Users') or die(mysql_error());
   	 $sql  = mysql_query("SELECT * FROM `Users` WHERE `userKey` = '$dataUser'");
   	 $row = mysql_fetch_assoc($sql);
     $name = $row['first']. " " . $row['last'];
     return $name;
				   
   }
    
    function getUserAvatar($dataUser){
     mysql_select_db('Users') or die(mysql_error());
   	 $sql  = mysql_query("SELECT * FROM `Users` WHERE `userKey` = '$dataUser'");
   	 $row = mysql_fetch_assoc($sql);
     $avatar = $row['avatar'];
     return $avatar;
   }

	
	
	
	//validate session
	function validate(){

       session_start(); 
       
       if(!isset($_SESSION['userKey'])){
	        $json = array('function' => 'squibturf.not_set'); 		    
		}else{
		       mysql_select_db('Users') or die(mysql_error());
		       $userKey = $_SESSION['userKey'];
			   $user_sql = mysql_query("SELECT * FROM `Users` WHERE `userKey` = '$userKey'");
			   $row = mysql_fetch_array($user_sql);
			   $occ = $row['OCC'];
	           $json = array('userKey'=> $_SESSION['userKey'], 'name' => $_SESSION['first']." ".$_SESSION['last'], 'avatar'=> $_SESSION['avatar'], 'error' => $response, 'occ' => $occ, 'function' => 'squibturf.load');
		}
		
	     echo json_encode($json);
	}

    
    
    
    
    function storeSquib(){

           $userKey = $_POST['userKey'];
           $msg = addslashes($_POST['msg']);
           $date = $_POST['date'];
           $squibImg = $_POST['squibImg'];
           $name = $_POST['name'];
           $avatar = $_POST['avatar'];
           $date = $_POST['date'];
           $lng = $_POST['lng'];
           $lat = $_POST['lat'];
           $type ='blank';
           $pindown = $_POST['pindown'];
           mysql_select_db('Squibs') or die(mysql_error());
           mysql_query("INSERT INTO `$userKey-SQUIBS` (`MSG`, `SQUIB_IMG`, `DATE`) VALUES ('$msg', '$squibImg', '$date')");
		   $squib_id =  mysql_insert_id();
		   
		   if($pindown == 'pindown'){
              mysql_select_db('PinSquibs') or die(mysql_error());
              mysql_query("INSERT INTO `Pin` (`MSG`, `SQUIB_IMG` ,`DATE`, `USER`, `TYPE`, `AVATAR`, `USER_KEY`, `SQUIB_ID`, `LAT`, `LNG`) VALUES ('$msg', '$squibImg', '$date','$name', '$type', '$avatar', '$userKey','$squib_id', '$lat','$lng')");

		   }
		   
          
		/*


	        squib.query("INSERT INTO `"+ data.userKey +"-SQUIBS` (`MSG`, `SQUIB_IMG`, `DATE`) VALUES ('"+ message +"', '"+ data.squibImg +"', '"+ date +"')");	        squib.query("SELECT * FROM  `"+ data.userKey +"-SQUIBS` ORDER BY POINT_ID DESC", function (err, rows, fields){
		         squib_id = rows[0].POINT_ID;
		         data.squib_id = squib_id;
		        
		        var pindown = data.pindown;
                if(pindown == 'pindown'){
                   pin.query("INSERT INTO `Pin` (`MSG`, `SQUIB_IMG` ,`DATE`, `USER`, `TYPE`, `AVATAR`, `USER_KEY`, `SQUIB_ID`, `LAT`, `LNG`) VALUES ('"+ message +"', '"+ data.squibImg +"', '"+ date +"','"+ data.name +"', '"+ data.type +"', '"+ data.avatar +"', '"+ data.userKey +"','" + data.squib_id +"', '"+ data.lat +"','"+ data.lng +"')");
                 }




*/


			echo 'squib stored';
}
    
    
    
    
    
    
    function storeDom(){
	 
	 /*
	 
	 feed.query("INSERT INTO `"+ data.yourKey +"-DOM` (`MSG`, `SQUIB_IMG`, `DATE`, `USER`, `TYPE`, `AVATAR`, `USER-KEY`, `SQUIB_ID`, `PINDOWN`) VALUES ('"+ msg +"', '"+ data.squibImg +"' ,'"+ date +"','"+ data.name +"', '"+ data.type +"', '"+ data.avatar +"', '"+ data.userKey +"', '" + data.squib_id +"', '"+ data.pindown +"')");
	 */
	 
 }
   
  
   
    
    
    
    //set session
    function set_session(){
       session_start();
       $first_name = $_POST['first_name'];
       $last_name = $_POST['last_name'];
       $id = $_POST['fb_id'];
       $email = strtolower($_POST['email']);
       $avatar = $_POST['avatar'];
       //if there is no email assocation
       if($email == '' || $email == null){
		  	$userKey =  md5($_POST['fb_id']);
		}else{
	       $userKey = md5($_POST['email']);
		}
		
       
		
   
       
       
	    mysql_select_db('Users') or die(mysql_error()); 
		$sql = "SELECT * FROM `Users` WHERE `userKey` = '$userKey'";
		if($result = mysql_query($sql)){
			if(!mysql_num_rows($result)){
	            mysql_query("INSERT INTO `Users`
	            (`userKey`, `Email`, `avatar`, `OCC`, `first`, `last`) 
	            VALUES ('$userKey', '$email', '$avatar', '', '$first_name', '$last_name')");
	        
				mysql_select_db('Contacts');
				mysql_query("CREATE TABLE IF NOT EXISTS `$userKey-Contacts` 
				(POINT_ID INT AUTO_INCREMENT NOT NULL, 
				`MSG` TEXT, 
				`DATE` VARCHAR(255), 
				`USER` VARCHAR(255), 
				`AVATAR` VARCHAR(255), 
				`USER-KEY` VARCHAR(255), 
				`STATUS` VARCHAR(255), 
				PRIMARY KEY (POINT_ID))
				CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
				
				mysql_select_db('Mail') or die(mysql_error());
				mysql_query("CREATE TABLE IF NOT EXISTS `$userKey-MAILBOX` 
				(POINT_ID INT AUTO_INCREMENT NOT NULL, 
				`BODY` TEXT, 
				`SUBJECT` VARCHAR(255), 
				`DATE` VARCHAR(255), 
				`NAME` VARCHAR(255),  
				`AVATAR` VARCHAR(255), 
				`USER-KEY` VARCHAR(255), 
				`TYPE` VARCHAR(255), 
				`CHAIN_ID` VARCHAR(255),
				`STATUS` VARCHAR(255),
				PRIMARY KEY (POINT_ID))
				CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
				
				mysql_select_db('Squibs') or die(mysql_error());
				mysql_query("CREATE TABLE IF NOT EXISTS `$userKey-SQUIBS`
				(POINT_ID INT AUTO_INCREMENT NOT NULL, 
				`MSG` TEXT, 
				`SQUIB_IMG` TEXT,
				`DATE` VARCHAR(255), 
				`REPLY` INT(11), 
				`TYPE` VARCHAR(255),
				PRIMARY KEY (POINT_ID))
				CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
				
			    mysql_select_db('SquibDom') or die(mysql_error());
				mysql_query("CREATE TABLE IF NOT EXISTS `$userKey-DOM` 
				(POINT_ID INT AUTO_INCREMENT NOT NULL, 
				`MSG` TEXT, 
				`SQUIB_IMG` TEXT, 
				`DATE` VARCHAR(255), 
				`USER` VARCHAR(255), 
				`TYPE` VARCHAR (255), 
				`AVATAR` VARCHAR(255), 
				`USER-KEY` VARCHAR(255), 
				`SQUIB_ID` INT(11), 
				`PINDOWN` VARCHAR(255), 
				PRIMARY KEY (POINT_ID))
				CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");  
				
			    mysql_select_db('Reply') or die(mysql_error());
				mysql_query("CREATE TABLE IF NOT EXISTS `$userKey-Reply` 
				(POINT_ID INT AUTO_INCREMENT NOT NULL, 
				`MSG` TEXT, `DATE` VARCHAR(255), 
				`USER` VARCHAR(255), 
				`TYPE` VARCHAR (255), 
				`AVATAR` VARCHAR(255), 
				`USER-KEY` VARCHAR(255), 
				`SQUIB_ID` INT(11), 
				PRIMARY KEY (POINT_ID))
				CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
				}
		  
	   }
	   
	   mysql_select_db('Users') or die(mysql_error()); 
	   $user_sql = mysql_query("SELECT * FROM `Users` WHERE `userKey` = '$userKey'");
	   $row = mysql_fetch_array($user_sql);
	   $_SESSION['userKey'] = $row['userKey'];
	   $_SESSION['first'] = $row['first'];
	   $_SESSION['last'] = $row['last'];
	   $_SESSION['avatar'] = $row['avatar'];	 
	   $occ = $row['OCC'];  
	   $cookieLifetime = 365 * 24 * 60 * 60;
	   setcookie(session_name(),session_id(),time()+$cookieLifetime); 
	   $json = array('userKey'=> $row['userKey'], 'name' => $row['first']." ".$row['last'], 'avatar'=> $row['avatar'], 'message' => "Loading", 'occ'=> $row['OCC'], 'function' => 'squibturf.load');	  
		echo json_encode($json);
       	
    }

    
    
    
    
    //sign out
	function sign_out(){
		
		session_start();
		session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name(),'',0,'/');
		session_regenerate_id(true);

        $json = array('function' => 'squibturf.restart_app'); 
		echo json_encode($json);
   	} 
    
    
    
    
    
    
   
   
    //login
    function login(){              
			   session_start();
			   $dbusername = $row['Email'];
			   $dbpassword = $row['Password'];
			   $_SESSION['username'] = $dbusername;
			   $_SESSION['Password'] = $dbpassword;
			   $_SESSION['userKey'] = $row['userKey'];
			   $_SESSION['fist'] = $row['first'];
			   $_SESSION['last'] = $row['last'];
			   $_SESSION['avatar'] = $row['avatar'];
			   
								   
				$cookieLifetime = 365 * 24 * 60 * 60;
				setcookie(session_name(),session_id(),time()+$cookieLifetime);
			   
			   $response = "";
			   $json = array('userKey'=> $row['userKey'], 'name' => $row['first']." ".$row['last'], 'avatar'=> $row['avatar'], 'error' => $response, 'message'=> "Loading", 'function' => 'squibturf.load');
		}

	
	
	
	
	//register user
	
	
	
	
	
	/*function register(){
         
       $first_name = $_POST['first_name'];
       $last_name = $_POST['last_name'];
       $email = strtolower($_POST['email']);
       $password = md5($_POST['password']);
       $userKey = md5($_POST['email']);
       $occ = $_POST['occ'];
	   mysql_select_db('Users') or die(mysql_error());
       mysql_query("INSERT INTO `Users`(`userKey`, `Email`, `Password`, `avatar`, `OCC`, `first`, `last`) 
       	VALUES ('$userKey', '$email', '$password', 'http://squibturf.com/app/www/image/avatar-blank.jpg', '$occ', '$first_name', '$last_name')");
	


		mysql_select_db('Contacts');
		mysql_query("CREATE TABLE IF NOT EXISTS `$userKey-Contacts` 
		(POINT_ID INT AUTO_INCREMENT NOT NULL, 
		`MSG` TEXT, 
		`DATE` VARCHAR(255), 
		`USER` VARCHAR(255), 
		`AVATAR` VARCHAR(255), 
		`USER-KEY` VARCHAR(255), 
		`STATUS` VARCHAR(255), 
		PRIMARY KEY (POINT_ID))");


		  $json = array('email' => $_POST['email'], 'password' => $_POST['password'], 'function' => 'squibturf.reg_complete');  
	      echo json_encode($json);

   } */
	
	
	
	
	//forgot password
	function forgot(){
         



   }
	
	
	
	
	
	
	
	
	//send email
	function send_mail(){
	

	
		   
		
	
	   	    function insert($token){
				 
					$repecipient = $_POST['repecipient']; 
					$sender = $_POST['sender'];    
					$date = $_POST['date'];
					$body = addslashes($_POST['body']);
					$subject = addslashes($_POST['subject']);


					mysql_select_db('Users') or die(mysql_error());
					$repecipient_query = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$repecipient'") or die(mysql_error());
					while($repecipient_row =  mysql_fetch_array($repecipient_query))
					{
						$repecipient_key = $repecipient_row['userKey']; 
						$repecipient_name = $repecipient_row['first']." ".$repecipient_row['last'];
						$repecipient_avatar = $repecipient_row['avatar'];
						$repecipient_email =  $repecipient_row['Email'];
					}

					$sender_query = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$sender'") or die(mysql_error());
					while($sender_row =  mysql_fetch_array($sender_query))
					{
						$sender_key = $sender_row['userKey']; 
						$sender_name = $sender_row['first']." ".$sender_row['last'];
						$sender_avatar = $sender_row['avatar'];
						$sender_email =  $sender_row['Email'];
					}
	                mysql_select_db('Mail') or die(mysql_error());
			        mysql_query("INSERT INTO `tokens`(`token`) VALUES ('$token')");
		            mysql_query("INSERT INTO `$repecipient-MAILBOX` 
					            (`BODY`, `SUBJECT`, `DATE`, `NAME`, `AVATAR`, `USER-KEY`, `TYPE`, `CHAIN_ID`, `STATUS`) VALUES 
					            ('$body', '$subject', '$date' ,'$sender_name', '$sender_avatar', '$sender_key', 'inBox', '$token', 'unread')"); 		             
			        mysql_query("INSERT INTO `$sender-MAILBOX` 
					            (`BODY`, `SUBJECT`, `DATE`, `NAME`, `AVATAR`, `USER-KEY`, `TYPE`, `CHAIN_ID`, `STATUS`) VALUES 
					            ('$body', '$subject', '$date', '$repecipient_name', '$repecipient_avatar', '$repecipient_key', 'sent', '$token', 'unread')");  
		           	 
		           	 
		           	 
		           	 $json = array('function' => 'squibturf.mail_sent', 'message' => "Message sent", 'token' => $token)
		           	 ;
		           	 echo json_encode($json);	
                }


		        function createToken(){
		        	 mysql_select_db('Mail') or die(mysql_error());
		            $token =  md5(uniqid(rand(), true));
					$token_sql = mysql_query("SELECT *  FROM `tokens` WHERE `token` = '$token'");
					$row = mysql_num_rows($token_sql);
					 

					if($row == 0){
					  insert($token); 
					}else{
	 					createToken();
					}              
		    	}
			      
                  createToken();
  }
	
	
	
	
	
	
	
	//get another users profile
	function profile(){
	    session_start();

        $userKey = $_SESSION['userKey'];
        $dataUser = $_POST['dataUser'];

         mysql_select_db('Contacts') or die(mysql_error());
         $contact_q = mysql_query("SELECT * FROM `$userKey-Contacts` WHERE `USER-KEY` = '$dataUser'") or die(mysql_error());
         $num_result = mysql_num_rows($contact_q);
         if($num_result > 0){
	        $status = mysql_fetch_array($contact_q);
	        $connect_status = $status['STATUS']; 
	     }else{
	     	$connect_status = "";
	     }
        

		mysql_select_db('Users') or die(mysql_error());
        $query = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$dataUser'") or die(mysql_error());
        
		while($row =  mysql_fetch_array($query))
		{
		   $json = array('userKey'=> $row['userKey'], 'sessionKey' => $userKey  ,'name' => $row['first']." ".$row['last'], 'avatar'=> $row['avatar'], 'email' => $row['Email'], 'occ' => $row['OCC'], 'connect_status' => $connect_status  ,'function' => 'squibturf.load_profile', 'message' => "Loading Profile");
		}
    
       echo json_encode($json);	
    
    }
	
	
	
	
	
	
	
	
	//check squib
	function squib(){
	   $userKey = $_POST['dataUser'];
	   $squib_ID = $_POST['squib_id'];
	   
	        mysql_select_db('Users') or die(mysql_error());
	        $query = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$userKey'") or die(mysql_error());
			while($row =  mysql_fetch_array($query))
			{
			   $userKey = $row['userKey']; 
			   $name = $row['first']." ".$row['last'];
			   $avatar = $row['avatar'];
			   $email =  $row['Email'];
			}
	   
	   
	        mysql_select_db('Squibs') or die(mysql_error());
	        $query1 = mysql_query("SELECT * FROM `$userKey-SQUIBS` WHERE `POINT_ID`='$squib_ID'") or die(mysql_error());
	        while($row1 =  mysql_fetch_array($query1))
			{
			   $msg = $row1['MSG'];
			   $date = $row1['DATE'];
			    
              
			   $squib_img = $row1['SQUIB_IMG'];
			   //array_push($json,  $row1['MSG'], $row1['DATE']);
			}
			
			
			
	        mysql_select_db('Reply') or die(mysql_error());
	        $query2 = mysql_query("SELECT * FROM `$userKey-Reply` WHERE `SQUIB_ID`='$squib_ID'");
	        $count =0;
	        while($row2 = mysql_fetch_array($query2)){
		       $count++; 
		       $dataUser = $row2['USER-KEY'];
			   $reply_name = getUserName($dataUser); 
			   $reply_avatar = getUserAvatar($dataUser);
			   array_push($row2, $reply_name);
			   array_push($row2, $reply_avatar);
		       $squibs[] = $row2; 
	        }
	      $timestamp = strtotime($date);			 
			  $month = date('M', $timestamp);
			  $day = date('j', $timestamp);
			$json = array( 'squib_id' => $squib_ID, 
			               'userKey' => $userKey, 
			               'avatar' => $avatar, 
			               'name' => $name, 
			               'email' => $email, 
			               'msg' => $msg, 
			               'date' => $month." ".$day, 
			               'squib_count' => $count, 
			               'squibs' => $squibs, 
			               'squib_img' => $squib_img,
			               'mesage' => "Gettin' your squibs...",
			               'function' => 'squibturf.get_squib');
			
			echo json_encode($json);
	   
   }
	
	
	
	
	
	
	
	
	//get pinned squib
	function pinned_squibs(){
		  session_start();
          $userKey = $_SESSION['userKey'];
          mysql_select_db('PinSquibs') or die(mysql_error());
          $userLat = floatval($_POST['lat']);
          $userLng = floatval($_POST['lng']);
          $difference = .005;
          $query = mysql_query( "SELECT * FROM `Pin`");

           while($row = mysql_fetch_array($query)){
           	   $lat = floatval($row['LAT']);  
           	   $lng = floatval($row['LNG']);
           	   $latDiff = abs($lat - $userLat);
           	   $lngDiff = abs($lng - $userLng);	

               if(($latDiff < $difference) && ($lngDiff < $difference)){
	       			
	       			$userKey = $row['USER_KEY'];
	       			$squib_id = $row['SQUIB_ID'];	

	       		    mysql_select_db('Squibs') or die(mysql_error());
	       			$squib_sql = mysql_query("SELECT * FROM `$userKey-SQUIBS` WHERE `POINT_ID` = '$squib_id'");
	       			$squib_row = mysql_fetch_assoc($squib_sql);
	       	
	       		    $name = getUserName($userKey);
                    $avatar = getUserAvatar($userKey);
					array_push($squib_row, $name);
					array_push($squib_row, $avatar);
					array_push($squib_row, $userKey);	
                    
					$date = $row['DATE']; 
					$timestamp = strtotime($date);
					$month = date('M', $timestamp);
					$day = date('j', $timestamp);
					$day_of_week = date('l', $timestamp);
					$squib_row['formatDate'] = $month." ".$day;
					$squib_row['yourKey'] = $userKey;
					$pinned_squibs[] = $squib_row;
               }
                   
           }  
            $json = array('pin' => $pinned_squibs, 'function' => 'squibturf.loadPinnedSquibs');
            echo json_encode($json);
			
   }
	
	
	
	
	
	
	
	
	
	//store contacts
	function contact(){

			$yourKey = $_POST['yourKey'];
			$userKey = $_POST['userKey'];
			$date = $_POST['date'];
			mysql_select_db('Users') or die(mysql_error());

            $youQuery = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$yourKey'");
			while($youRow =  mysql_fetch_array($youQuery))
			{
                 $youName = $youRow['first'] . ' ' . $youRow['last'];
                 $youAvatar = $youRow['avatar'];
                 $youOcc = $youRow['OCC'];
                 $email =  $row['Email'];

			}

            $userQuery = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$userKey'");
			while($userRow =  mysql_fetch_array($userQuery))
			{
                 $userName = $userRow['first'] . ' ' . $userRow['last'];
                 $userAvatar = $userRow['avatar'];
                 $userOcc = $userRow['OCC']; 
                 $email =  $row['Email'];
			} 

			mysql_select_db('Contacts');
            $user_sql = mysql_query("SELECT * FROM `$userKey-Contacts` WHERE `USER-KEY` = '$yourKey'");
	        $user_count = 0;
	        while($user_row = mysql_fetch_array($user_sql)){
                  $user_count++;
	        }     

	        if( $user_count < 1){
				         mysql_query("INSERT INTO `$userKey-Contacts` 
			            (`MSG`, `DATE`, `USER`, `AVATAR`, `USER-KEY`, `STATUS`) VALUES 
			            ('Contact request pending.', '$date', '$youName' ,'$youAvatar', '$yourKey', 'pending')");           
	        }

	        $your_sql = mysql_query("SELECT * FROM `$yourKey-Contacts` WHERE `USER-KEY` = '$userKey'");	
	        $your_count = 0;
	        while($your_row = mysql_fetch_array($your_sql)){
                  $your_count++;
	        }     
	       if( $your_count < 1){
					mysql_query("INSERT INTO `$yourKey-Contacts` 
					(`MSG`, `DATE`, `USER`, `AVATAR`, `USER-KEY`, `STATUS`) VALUES 
					('Contact request sent.', '$date', '$userName' ,'$userAvatar', '$userKey', 'sent')");
	      }
               $json = array('function' => 'squibturf.connect_sent', 'message' => "Connect request sent...");
	           echo json_encode($json);
   }
	
	
	
	
	
	
	
	
	
	//get contacts 
	function get_contacts(){

   	    mysql_select_db('Contacts');
	    $yourKey = $_POST['yourKey'];
    	$sql = mysql_query("SELECT * FROM `$yourKey-Contacts` ORDER BY `USER`");


    	$activity = '';	
    	$contacts = '';

    	while($row = mysql_fetch_array($sql)){
			$dataUser = $row['USER-KEY'];
			$msg = $row['MSG'];
			if(strlen($msg) > 100 ){ $msg = substr($msg, 0, 110).'...';}
			$status = $row['STATUS'];
            $name = getUserName($dataUser);
            $avatar = getUserAvatar($dataUser);
			if ($status == 'sent'){
					$activity .=  "<li class='table-view-cell contact-wrap $status'>
					<a class='show'  id='profileWindow' data-attr='$dataUser' data-message='Loading profile...' data-function='open_modal, get_profile'> 
					<div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
					  <h6 style='color:#000;'>Request sent to <strong style='color:#428bca;'>$name</strong></h6>$msg
					</a>
					<div class='buttons'>
						<button class='btn btn-primary pending'></button>
					</div>
					</li>";
	     	} else if($status == 'pending'){
	     		 $activity .=  "<li class='table-view-cell contact-wrap $status user-$dataUser'>
					<a class='show'  id='profileWindow' data-attr='$dataUser'  data-function='open_modal, get_profile' data-message='Loading profile...'> 
					<div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
					<h6 style='color:#000;'><strong style='color:#428bca;'>$name</strong> sent you a request</h6>$msg</a>
					<div class='buttons'>
							<button class='btn btn-positive accept' data-function='accept' data-attr='$dataUser'></button>
							<button class='btn btn-negative reject' data-function='reject'></button> 
					</div>
					</li>";
	     	}else{
	     		   mysql_select_db('Users');
	     		   $user_sql = mysql_query("SELECT * FROM `Users` WHERE `userKey` = '$dataUser'");
	     		   $user_row = mysql_fetch_assoc($user_sql);
	     		   $email = $user_row['Email'];
	     	       $contacts .=  "<li class='table-view-cell contact-wrap $status user-$dataUser'>
					<a class='show'  id='profileWindow' data-attr='$dataUser'  data-function='open_modal, get_profile' data-message='Loading profile...'> 
					<div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
					<h6 style='color:#000;'><strong style='color:#428bca;'>$name</strong> </h6>
					<strong>$email</strong></a>
					</li>";

		     	
	     	}
    	}

    	    
       $json = array('activity' => $activity ,'contacts' => $contacts ,'function' => 'squibturf.contacts', 'message' => "Findin' your contacts...");
	   echo json_encode($json);

   }
	
	
	
	
	
	
	
	//squib
	function get_squibs(){
   	    mysql_select_db('Squibs');    
   	    $yourKey = $_POST['yourKey'];
    	$avatar = $_POST['avatar'];
    	$start = $_POST['index'];
    	$end = $start + 5;
    	$trash_active = $_POST['trash_active'];
    	
    	
   	    $sql = mysql_query("SELECT * FROM `$yourKey-SQUIBS` ORDER BY `REPLY` DESC LIMIT $start, 10") or die(mysql_error());
   	    $string = '';	
    	while($row = mysql_fetch_array($sql)){
			$msg = $row['MSG'];
			if(strlen($msg) > 100 ){ $msg = substr($msg, 0, 110).'...';}
			$squib_ID = $row['POINT_ID'];
			$date = $row['DATE'];
			$timestamp = strtotime($date);			 
			  $month = date('M', $timestamp);
			  $day = date('j', $timestamp);
			$reply = $row['REPLY'];
			$pindown = $row['TYPE'];
			$squibImg = $row['SQUIB_IMG']; 
            $string .= "<li class='table-view-cell squib-wrap $pindown squib-$squib_ID' >
			                <a class='squib profile show $trash_active' id='replyWindow' data-attr='$yourKey' data-function='open_modal, select_squib'  data-message='Loading squib...' data-squib='$squib_ID'> 
				            <div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
				            <h6>Squib<span> posted on </span>$month $day</span></h6><span class='msg'> $msg </span><div class='squibImg'>$squibImg</div>";
				   
                   if ($reply >= 1){
				         $string .= "<span class='badge badge-primary'>$reply</span>";
				   }
				         $string .= "</a><span class='btn btn-negative btn-block delete-squib' data-function='delete_squib' data-message='Deleting this squib...!'>Delete</span></li>";
       } 

       $json = array('squibs' => $string, 'function' => 'squibturf.squibs', 'message' => "Fetchin' your squibs...");
	   echo json_encode($json);
            
   }
	
	
	
	
	
	
	
	//mail
	function mail_request(){
			mysql_select_db('Mail');   
			$yourKey = $_POST['yourKey'];
			$request = $_POST['request'];
			$sql = mysql_query("SELECT DISTINCT `CHAIN_ID` FROM `$yourKey-MAILBOX` ORDER BY `DATE` DESC ");
			$string = '';

			while($row = mysql_fetch_array($sql)){  
				  $chain_id = $row['CHAIN_ID'];
				  mysql_select_db('Mail');  
                  $chain_sql = mysql_query("SELECT * FROM `$yourKey-MAILBOX` WHERE `CHAIN_ID` = '$chain_id' AND `TYPE` = '$request' ORDER BY `POINT_ID` DESC LIMIT 1");
                  $row_mail = mysql_fetch_array($chain_sql);
					$body = $row_mail['BODY'];
					$subject = $row_mail['SUBJECT'];
					$date = $row_mail['DATE'];
					$user_key = $row_mail['USER-KEY'];
					$type = $row_mail['TYPE'];
					$chain_id = $row_mail['CHAIN_ID'];
					$status = $row_mail['STATUS'];

					$name = getUserName($user_key); 
					$avatar = getUserAvatar($user_key);
					
					 
              $timestamp = strtotime($date);			 
			  $month = date('M', $timestamp);
			  $day = date('j', $timestamp);
                

                    if($type == 'trash'){
                   	   $string ="<li> <a href=''>Now items to show. </a></li>";
                   
                    }

                    if ($type == 'sent'){
			              $string .= "<li class='table-view-cell mail-wrap $status'>
			                <a class='squib profile show' id='mailWindow' data-attr='$user_key' data-mail='$chain_id' data-function='open_modal, get_mail'  data-message='Loading mail...'> 
				            <div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
				            <h6><strong>$month $day</strong>  <span class='mail-info' style='float:right; color:#428bca'> <strong>To: $name</strong> </span></h6> <strong>$subject</strong> <br/> $body </a></li>";
				    }
				    if($type == 'inBox'){
				    	  $string .= "<li class='table-view-cell mail-wrap $status'>
			                <a class='squib profile show' id='mailWindow' data-attr='$user_key' data-mail='$chain_id' data-function='open_modal, get_mail'  data-message='Loading mail...'> 
				            <div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
				            <h6><strong>$month $day</strong>  <span class='mail-info' style='float:right; color:#428bca'> <strong>From: $name</strong> </span></h6> <strong>$subject</strong> <br/> $body </a></li>";
				    }



			}  

            if($string == ''){
            	$string .= "<li> <a href=''>Now items to show. </a></li>";

            }

			$arr = array('mail' => $string, 'request' => $request,'function' => 'squibturf.load_mail', 'message' => "Loading' $type mail...");
			echo json_encode($arr);	
   }   
	
	
	
	
	
	
	
	//get mail
	function get_mail(){
	
		mysql_select_db('Mail');   
		$userKey = $_POST['userKey'];
		$mail_id = $_POST['mail_id'];

	    mysql_query("UPDATE `$userKey-MAILBOX` SET `STATUS` = 'read' WHERE `CHAIN_ID` = '$mail_id'");

        $sql = mysql_query("SELECT * FROM `$userKey-MAILBOX` WHERE `CHAIN_ID` = '$mail_id' ORDER BY `POINT_ID` ASC");
            
		$mail = '';

		$collapse = 'collapse';
		$numResults = mysql_num_rows($sql);
		$count = 0;
		while($row = mysql_fetch_array($sql)){
			
			
			  
			  $body = $row['BODY'];
			  $subject = $row['SUBJECT'];
			  $date = $row['DATE'];
			  $dataUser = $row['USER-KEY'];
			  $type = $row['TYPE'];
              $chain_id = $row['CHAIN_ID'];
			  $name = getUserName($dataUser);
              $avatar = getUserAvatar($dataUser);
              
              $timestamp = strtotime($date);			 
			  $month = date('M', $timestamp);
			  $day = date('j', $timestamp);
		     
		      //  $subject  
		    if(++$count == $numResults){ $collapse = ''; }
		    if($type == 'inBox'){
			  $mail .= "<div class='profile-content $collapse'  data-function='mail_icon' data-mail='$mail_id' data-user='$user_key'>
			        	 <div class='top'> 
			        	     <div class='content-avatar' style='background:url($avatar) center no-repeat; background-size:auto 65px;'></div><br />
			          	     <div class='content-info' style='clear:both;'><strong>$name</strong> </div>
			          	     <span class='subject-chat'>$subject</span>
			          	     <span class='mail-date'>Sent on $month $day</span>
			          	  </div> 
			          	 <div class='mail-body'><p>$body</p></div>
			          	
				      </div>";
		    }
		    else{
		      session_start();
              $userKey = $_SESSION['userKey'];
              $name = $_SESSION['first']." ".$_SESSION['last'];
              $avatar = $_SESSION['avatar'];
		      $mail .= "<div class='profile-content $collapse' data-function='mail_icon' data-mail='$mail_id' data-user='$userKey'>
			        	 <div class='top'> 
			        	     <div class='content-avatar' style='background:url($avatar) center no-repeat; background-size:auto 65px;'></div><br />
			          	     <div class='content-info' style='clear:both;'><strong>$name</strong> </div>
			          	     <span class='subject-chat'>$subject</span>
			          	     <span class='mail-date'>Sent on $month $day</span>
			          	  </div> 
			          	 <div class='mail-body'><p>$body</p></div>
			          	
				      </div>";
		    }
		  }	

			$mail .=    "<div class='mail-reply'>
			<textarea id='reply-body' placeholder='Click here to Reply'></textarea>
			<button class='reply-btn btn btn-primary' data-function='reply_email' data-to-user='$dataUser' data-from-user='$userKey' data-mail='$chain_id'>reply</button>
			</div>" ;     
            $arr = array('mail' => $mail, 'subject'=> $subject, 'name' => $name, 'function' => 'squibturf.email', 'message' => "Checking' your mail");
			echo json_encode($arr);
		
	}
	
	
	
	
	
	
	//history
	function history(){
	   $userKey = $_POST['yourKey'];
	   mysql_select_db('SquibDom') or die(mysql_error());
	   $date_query = mysql_query("SELECT DISTINCT `DATE` FROM `$userKey-DOM` ORDER BY `POINT_ID` DESC LIMIT 10");

	  
	           $string = "";

	   while($date_row = mysql_fetch_array($date_query)){
		       $date = $date_row['DATE']; 
		       $timestamp = strtotime($date);
		       $month = date('M', $timestamp);
		       $day = date('j', $timestamp);
	           $day_of_week = date('l', $timestamp);
	     

		       $string .= '<h5 class="date-line"><span class="left">'. $day_of_week .'</span><span class="right"> '. $month .' ' . $day . '</span></h5>';
			   mysql_select_db('SquibDom') or die(mysql_error());
			   $query = mysql_query("SELECT * FROM `$userKey-DOM` WHERE `DATE`= '$date'  ORDER BY `POINT_ID` DESC");
		       while($row =  mysql_fetch_array($query))
				{
				    $dataUser = $row['USER-KEY'];
				    $domType = $row['TYPE'];
				    $msg = $row['MSG'];
				    $squib_ID = $row['SQUIB_ID'];
				    $pindown = $row['PINDOWN'];
				    $img = $row['SQUIB_IMG'];
				    $name = getUserName($dataUser);
                    $avatar = getUserAvatar($dataUser);
				    if(strlen($msg) > 100 ){ $msg = substr($msg, 0, 110).'...';}

				    if ($domType == 'profile'){
					         $string .= "<li class='table-view-cell $domType-wrap'>
					           <a class='$domType show '  id='profileWindow' data-function='open_modal, get_profile'  data-attr='$dataUser'  data-message='Loading profile...'> 
					           <div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
					           <h6>$name</h6>$msg</a>
					         </li>";

					}
					else{
					        $string .= "<li class='table-view-cell $domType-wrap $pindown'>
							  <a class='$domType profile show ' id='replyWindow' data-attr='$dataUser' data-function='open_modal, select_squib' data-squib='$squib_ID'  data-message='Loading squib...'> 
								   <div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
								   <h6>Squib<span> from </span>$name</span></h6> <span class='msg'>$msg</span><div class='squibImg'>$img</div></a>
						     </li>";
					}	       
				}
	   }
			$json = array('dom' => $string, 'function' => 'squibturf.history', 'message' => "Grabbin' your histroy...");
			   echo json_encode($json);
	}
	
	
	
	
	
	
	//accept
	function accept_contact(){
	   	       mysql_select_db('Contacts');
               $userKey = $_POST['userKey'];
               $dataUser = $_POST['dataUser'];
               
               mysql_query("UPDATE `$userKey-Contacts` SET `STATUS` = 'connected' WHERE `USER-KEY` = '$dataUser'");	
               mysql_query("UPDATE `$dataUser-Contacts` SET `STATUS` = 'connected' WHERE `USER-KEY` = '$userKey'");
               //
               $json = array('dataUser'=> $dataUser, 'userKey'=> $userKey, 'function' => 'squibturf.connect_complete');
			   echo json_encode($json);
		
	}
	
	
	
	
	
	
	//settings
	function settings(){
	     session_start();
	     $dataUser =  $_SESSION['userKey'];
         mysql_select_db('Users') or die(mysql_error());
         $query = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$dataUser'") or die(mysql_error());
         $string = '';
		 while($row =  mysql_fetch_array($query))
		 {         
		 	      $first = $row['first']; 
		 	      $last = $row['last'];
		 	      $avatar = $row['avatar'];
			      $email = $row['Email'];
			      $occ =$row['OCC'];

              $string = '<ul class="table-view"> 
			                  <h4> Profile Settings </h4>
							  <li class="table-view-cell">First Name <p class="first">'. $first .'</p></li>
							  <li class="table-view-cell">Last Name <p class="last">'. $last .'</p></li>
							  <li class="table-view-cell">Avatar <div class="content-avatar" style="background:url('.$avatar.') center no-repeat; background-size:auto 100px;"></div></li>
							  <li class="table-view-cell">Email <p class="Email">'.$email.'</p></li>
							  <li class="table-view-cell">Personal Description/Occupation <p class="OCC">'.$occ.'</p><span class="icon icon-edit edit-background" data-function="edit_background"></span></li>
		                </ul>
		                				<button class="btn btn-positive faq" data-function="faq">Click for FAQ Page </button>

		                ';

		      $json = array('dom' => $string, 'function' => 'squibturf.load_settings', 'message' => "Loadin' your settings...");
		 }
    
         echo json_encode($json);	  
	}
	
	
	
	
	
	
	//reply email 
	function reply_email(){
	     $dataToUser = $_POST['dataToUser'];
	     $dataFromUser = $_POST['dataFromUser'];
	     $chainId = $_POST['chainId'];
	     $date = $_POST['date'];
		 $body = addslashes($_POST['body']);
		 $subject =  addslashes($_POST['subject']);
		 
		 
			mysql_select_db('Users') or die(mysql_error());
			$repecipient_query = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$dataToUser'") or die(mysql_error());
			while($repecipient_row =  mysql_fetch_array($repecipient_query))
			{
				$repecipient_key = $repecipient_row['userKey']; 
				$repecipient_name = $repecipient_row['first']." ".$repecipient_row['last'];
				$repecipient_avatar = $repecipient_row['avatar'];
				$repecipient_email =  $repecipient_row['Email'];
			}
			
			$sender_query = mysql_query("SELECT * FROM `Users` WHERE `userKey`='$dataFromUser'") or die(mysql_error());
			while($sender_row =  mysql_fetch_array($sender_query))
			{
				$sender_key = $sender_row['userKey']; 
				$sender_name = $sender_row['first']." ".$sender_row['last'];
				$sender_avatar = $sender_row['avatar'];
				$sender_email =  $sender_row['Email'];
			}

		     
			mysql_select_db('Mail') or die(mysql_error());
			mysql_query("INSERT INTO `$dataToUser-MAILBOX` 
			            (`BODY`, `SUBJECT`, `DATE`, `NAME`, `AVATAR`, `USER-KEY`, `TYPE`, `CHAIN_ID`, `STATUS`) VALUES 
			            ('$body', '$subject', '$date' ,'$sender_name', '$sender_avatar', '$sender_key', 'inBox', '$chainId', 'unread')");

			mysql_query("INSERT INTO `$dataFromUser-MAILBOX` 
				        (`BODY`, `SUBJECT`, `DATE`, `NAME`, `AVATAR`, `USER-KEY`, `TYPE`, `CHAIN_ID`, `STATUS`) VALUES 
			            ('$body', '$subject', '$date', '$repecipient_name', '$repecipient_avatar', '$repecipient_key', 'sent', '$chainId', 'unread')");  
		   
		   $json = array('function' => 'squibturf.mail_sent', 'message' => "Message Sent...");
		   echo json_encode($json);
		
	}

    
   
   
   
    function update_squib(){
    	mysql_select_db('Squibs') or die(mysql_error());
		$userKey = $_POST['dataUser'];
		$squib_Id = $_POST['dataSquib'];
		$new_squib =addslashes($_POST['new_squib']);
	    mysql_query("UPDATE `$userKey-SQUIBS` SET `MSG` = '$new_squib' WHERE `POINT_ID`='$squib_Id'") or die(mysql_error());


	    mysql_select_db('PinSquibs') or die(mysql_error());
	    mysql_query("UPDATE `Pin` SET `MSG` = '$new_squib' WHERE `SQUIB_ID`='$squib_Id'") or die(mysql_error());

	    $json = array('userKey' => $userKey, 'squibId'=> $squib_Id, 'new_squib' => stripslashes($new_squib), 'function' => 'squibturf.update_complete', 'message' => "Squib Updated...");
	    echo json_encode($json);
    }
   


   
   
   
    function delete_squib(){
   	    session_start();
   	    $userKey = $_SESSION['userKey'];
   	    $squib_id = $_POST['dataSquib'];
   	  	mysql_select_db('Squibs') or die(mysql_error());
        mysql_query("DELETE FROM `$userKey-SQUIBS` WHERE `POINT_ID`='$squib_id'") or die(mysql_error());
      
        mysql_select_db('PinSquibs') or die(mysql_error());
        mysql_query("DELETE FROM `Pin` WHERE `USER_KEY`='$userKey' AND `SQUIB_ID`='$squib_id'") or die(mysql_error());

      
       $json = array('function' => 'squibturf.delete_complete', 'squib_id' => $squib_id, 'userKey' => $userKey, 'error' => $delete, 'message' => 'Deleted...');
       echo json_encode($json);
  
   }
   
    
    
   
   
   
    function search(){
   	   mysql_select_db('Users') or die(mysql_error());
   	   $value = $_POST['value'];
	   $search_query = mysql_query( "SELECT * FROM `Users` WHERE `first` LIKE '%{$value}%' 
	                 OR `last` LIKE '%{$value}%'
	                 OR `occ` LIKE '%{$value}%'") or die(mysql_error());
	    $string ='';
	    while($row =  mysql_fetch_array($search_query))
				{
				    $dataUser = $row['userKey'];
				   // $domType = $row['TYPE'];
				    $msg = $row['OCC'];
				   // $squib_ID = $row['SQUIB_ID'];
				   // $pindown = $row['PINDOWN'];
				    $name = getUserName($dataUser);
                    $avatar = getUserAvatar($dataUser);
                  
				    if(strlen($msg) > 100 ){ $msg = substr($msg, 0, 110).'...';}

					         $string .= "<li class='table-view-cell profile-wrap'>
					           <a class='profile show'  id='profileWindow'  data-attr='$dataUser' data-function='open_modal, get_profile'  data-message='Loading profile...'> 
					           <div class='avatar-wrap' style='background:url($avatar) center no-repeat; background-size:auto 50px;'></div>
					           <h6>$name</h6>$msg</a>
					         </li>";

					

	    }
	            
	            $json = array('dom' => $string, 'function' => 'squibturf.results', 'message' => "Searching...");
			   echo json_encode($json);
   }
   
   
    
    
    
    
    
    function update_occ(){
	    session_start();
	    $userKey = $_SESSION['userKey'];
	   	mysql_select_db('Users') or die(mysql_error());
	   	$new_occ = $_POST['new_occ'];
	   	mysql_query("UPDATE `Users` SET `OCC`='$new_occ' WHERE `userKey`='$userKey'") or die(mysql_error());
	   	$json = array('function' => 'squibturf.done');
			   echo json_encode($json);
	   
   }
   
    
    
    
    
    
    
    
    function set_occ(){
	      
	    
	    
	    session_start();
	    $userKey = $_SESSION['userKey'];
	    
	   	mysql_select_db('Users') or die(mysql_error());
	   	$new_occ = $_POST['new_occ'];
	   	mysql_query("UPDATE `Users` SET `OCC`='$new_occ' WHERE `userKey`='$userKey'") or die(mysql_error());
	   	$json = array('function' => 'squibturf.occ_set', 'userKey' => $userKey);
	    echo json_encode($json);

      }
   
   
    
    
    
    
    
    
    function getAllPinSquibs(){
	      mysql_select_db('PinSquibs') or die(mysql_error());
          $query = mysql_query( "SELECT * FROM `Pin`");
		  while($row = mysql_fetch_array($query)){
		     $pinned_squibs[] = $row;	 
		 }
		 $json = array('pin' => $pinned_squibs);
		 echo json_encode($json);

   }



?>
