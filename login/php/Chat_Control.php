<?php
    header('Content-Type: application/json');

    require_once("User.php");
	require_once("Message.php");
	require_once("Chat_Gestor.php");

    //LOG (ACTIVAR SOLO PARA PRUEBAS)
    $myLog = fopen("Chatlog_Controller.txt", "w") or die("Unable to open file!");

	$gestor_chat = new Chat_Gestor();

	$option = $_POST["OPTION"];

    fwrite($myLog,"OPTION: ".$option."\n");

    if(isset($option))
    {
		switch($option)
    	{
    		case "1":   $user_name = $_POST["USER_NAME"];
                        $user_pass = $_POST["USER_PASSWORD"];

                        //LOG
                    	//fwrite($myLog,"USER_NAME: ".$user_name."\n");
                        //fwrite($myLog,"USER_PASSWORD: ".$user_pass."\n");

                        if(isset($user_name) && isset($user_pass))
    					{
    						$user = new User(0,$user_name,$user_pass,NULL);

                            $json_return = $gestor_chat->new_user($user);

                            //fwrite($myLog,"RES: ".$json_return."\n");
    					}
    					break;

    		case "2":	$user_name = $_POST["USER_NAME"];
                        $user_pass = $_POST["USER_PASSWORD"];

                        //LOG
                        //fwrite($myLog,"USER_NAME: ".$user_name."\n");
                        //fwrite($myLog,"USER_PASSWORD: ".$user_pass."\n");

                        if(isset($user_name) && isset($user_pass))
    					{
    						$user = new User(0,$user_name,$user_pass,NULL);

    						$json_return = $gestor_chat->get_user($user);

                            //fwrite($myLog,"RES: ".$json_return["id"]."\n");
    					}
    					break;

    		case "3":	if(isset($request["USER_SEND"]) && isset($request["USER_RECEIVE"]) && isset($request["MESSAGE"]))
    					{
    						$message = new User(0,NULL,$request["USER_SEND"],$request["USER_RECEIVE"],$request["MESSAGE"]);

    						$json_return = $gestor_chat->set_message($message);
    					}
    					break;

    		case "4":	if(isset($request["USER_SEND"]))
    					{
    						$message = new User(0,NULL,$request["USER_SEND"],0,"");

    						$json_return = $gestor_chat->get_message($message);
    					}
    					break;

    		default:	$json_return = array("ERROR"=>"C001");
    					break;
    	}
    }
    else
    	$json_return = array("ERROR"=>"C002");
    
    echo json_encode($json_return);
?>