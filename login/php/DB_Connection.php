<?php
	require_once("User.php");
	require_once("Message.php");

	class DB_Connection
	{
        var $bdUser = 'u217188989_chat';
        var $bdPass = '123456';
        var $bdName = 'u217188989_chat';
        var $bdHost = 'mysql.hostinger.co';

        function __construct()
		{}

		function new_user($user)
		{
            //$myLog = fopen("Chatlog_DB.txt", "w") or die("Unable to open file!");

			global $bdUser, $bdPass, $bdName, $bdHost;
			$con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");

            //fwrite($myLog,"USER_NAME: ".$user->user_name."\n");
            //fwrite($myLog,"USER_PASSWORD: ".$user->user_pass."\n");

			if($user != NULL)
            {
                $query = "SELECT US.user_id
                          FROM User US 
                          WHERE US.user_name = '".$user->user_name."'";

                $res = mysqli_query($con, $query) or die("Unable to execute query");

                if(!($data = mysqli_fetch_array($res)))
                {
                    $query = "INSERT INTO User (user_name, user_password) VALUES ('".$user->user_name."','".$user->user_pass."')";

                    //fwrite($myLog,"QUERY2: ".$query."\n");

                    $res = mysqli_query($con, $query) or die("Unable to execute query");

                    return array ("status"=>"0");
                }
                else
                    return array ("status"=>"DB001");
            }
            else
                return "DB002";
		}

		function get_user($user)
		{
            //$myLog = fopen("Chatlog_DB.txt", "w") or die("Unable to open file!");

			global $bdUser, $bdPass, $bdName, $bdHost;
			$con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");

            //fwrite($myLog,"USER_NAME: ".$user->user_name."\n");
            //fwrite($myLog,"USER_PASSWORD: ".$user->user_pass."\n");

			if($user != NULL)
            {
            	$query = "SELECT US.user_id,
            					 US.user_password,
            					 US.create_date
                          FROM User US 
                          WHERE US.user_name = '".$user->user_name."' AND US.user_password = '". $user->user_pass ."'";

                //fwrite($myLog,"QUERY: ".$query."\n");

                $res = mysqli_query($con, $query) or die("Unable to execute query");

                if($data = mysqli_fetch_array($res))
                {
                    if($data[0] != NULL )
                    {
                        //fwrite($myLog,"USER_ID: ".$data[0]."\n");

                    	$user2 = new User($data[0],$user->user_name,$data[1],$data[2]);

                    	return $user2;                   	
                    }
                    else
                    	return array ("status"=>"DB003");
                }
                else
                	return array ("status"=>"DB004");
            }
            else
            	return array ("status"=>"DB005");
		}

		function set_message($message)
		{
			global $bdUser, $bdPass, $bdName, $bdHost;
			$con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");

			if($message != NULL)
            {
            	$query = "INSERT INTO Message (user_send, user_receive, message) VALUES ('".$message->user_send."','".$message->user_receive."','".$message->message."')";

            	$res = mysqli_query($con, $query) or die("Unable to execute query");

            	return 0;
            }
            else
            	return array ("status"=>"DB006");
		}

		function get_message($message)
		{
			global $bdUser, $bdPass, $bdName, $bdHost;
			$con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");

			if($message != NULL)
            {
            	$query = "SELECT ME.message_id,
            					 ME.create_date,
            					 ME.user_send,
            					 ME.user_receive,
            					 ME.message 
            				FROM Messages ME 
            			   WHERE ME.user_send = '".$message->user_send."'";

            	if(isset($message->user_receive) && $message->user_receive != null)
                    $query = $query . " AND user_receive = '" . $message->user_receive . "' ";
                if(isset($message->create_date) && $message->create_date != null)
                    $query = $query . " AND create_date = '" . $message->create_date . "' ";

                $res = mysqli_query($con, $query) or die("Unable to execute query");

                while($data = mysqli_fetch_array($res))
            	{
            		$message = new Message($data[0], $data[1], $data[2], $data[3], $data[4]);

            		$messageArray[] = array("message" => $message);
            	}

            	return $messageArray;
            }
            else
            	return array ("status"=>"DB007");
		}
	}
?>