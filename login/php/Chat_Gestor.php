<?php
	require_once("User.php");
	require_once("Message.php");
	require_once("DB_Connection.php");

	class Chat_Gestor
	{
		function __construct()
		{}

		function new_user($user)
		{
			//LOG (ACTIVAR SOLO PARA PRUEBAS)
    		//$myLog = fopen("Chatlog_Gestor.txt", "w") or die("Unable to open file!");

			//fwrite($myLog,"USER_NAME: ".$user->user_name."\n");
			//fwrite($myLog,"USER_PASSWORD: ".$user->user_pass."\n");
			if($user != NULL)
			{
				$DB_chat = new DB_Connection();

				$res = $DB_chat->new_user($user);

				//fwrite($myLog,"RES: ".$res."\n");

				return $res;
			}
			else
				return "GE001";
		}

		function get_user($user)
		{
			if($user != NULL)
			{
				$DB_chat = new DB_Connection();

				$res = $DB_chat->get_user($user);

				if(is_array($res))
					return $res;
				else
					return array ("status"=>"0", "id"=>$res->user_id, "user_name"=>$res->user_name);
			}
			else
				return "GE002";
		}

		function set_message($message)
		{
			if($message != NULL)
			{
				if(isset($message->user_send) && $message->user_send != NULL &&
				   isset($message->user_receive) && $message->user_receive != NULL &&
				   isset($message->message) && $message->message != NULL)
				{
					$DB_chat = new DB_Connection();

					$res = $DB_chat->set_message($message);

					return $res;
				}
				else
					return "GE003";				
			}
			else
				return "GE004";
		}

		function get_message($message)
		{
			if($message != NULL)
			{
				if(isset($message->user_send) && $message->user_send != NULL)
				{
					$DB_chat = new DB_Connection();

					$res = $DB_chat->get_message($message);

					return $res;
				}
			}
		} 
	}

?>