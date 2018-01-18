<?php
    //LOG (ACTIVAR SOLO PARA PRUEBAS)
//    $myLog = fopen("bodalog_Controller.txt", "w") or die("Unable to open file!");

    $error = 0;

    if(file_exists('BodaJC_Gestor.php'))
		require_once 'BodaJC_Gestor.php';
	else
	{
        $json_return = array("ERROR"=>"C001");
        $error = 1;        
	}
    
    $gestor_boda = new BodaJC_Gestor();
    
    if($error != 1)
    {
        $request = json_decode(file_get_contents("php://input"),true);
        $option = $request["OPTION"];
        
//        fwrite($myLog,"Option: ".$option."\n");

        if(isset($option))
        {
            switch($option)
            {
                            /*BUSCAR DATOS DEL INVITADO SEGUN EL WEBLINK*/
                case "1":   $weblink = $request["WEBLINK"];
//                            fwrite($myLog,"Weblink1: ".$weblink."\n");
                            if(isset($weblink))
                            {
                                $result = $gestor_boda->getWebLinkInfo($weblink);

                                if(is_array($result))
                                { 
                                    $json_return = array("ERROR"=>"000", "INVITADO"=>$result);
                                }
                                else
                                    $json_return = array("ERROR"=>$result);                           
                            }
                            else
                                $json_return = array("ERROR"=>"C101");
                            break;
                    
                            /*DECLINAR INVITACION*/
                case "2":   $weblink = $request["WEBLINK"];
//                            fwrite($myLog,"Weblink2: ".$weblink."\n");
                            if(isset($weblink))
                            {
                                $result = $gestor_boda->setDeclinar($weblink);
                                $json_return = array("ERROR"=>$result);
                            }
                             else
                                $json_return = array("ERROR"=>"C201");
                            break;
                    
                            /*CONFIRMAR INVITACION*/
                case "3":   $confirmacion = $request["CONFIRMACION"];
//                            fwrite($myLog,"Confirmacion: ".$confirmacion["EMAIL"]."\n");
                            if(isset($confirmacion))
                            {
                                $result = $gestor_boda->setConfirmation($confirmacion);
                                $json_return = array("ERROR"=>$result);
                            }
                            else
                                $json_return = array("ERROR"=>"C301");
                            break;
                            
                            /*ENVIO DE EMAIL DE CONTACTO*/
                case "4":   $cEmail = $request["DATOS_EMAIL"];
//                            fwrite($myLog,"Email: ".$cEmail["EMAIL"]."\n");
                            if(isset($cEmail))
                            {
                                $result = $gestor_boda->sendCEmail($cEmail);
                                $json_return = array("ERROR"=>$result);
                            }
                            else
                                $json_return = array("ERROR"=>"C401");
                            break;
                
                case "5":   /*BUSCAR WEBLINK Y VER SI YA FUE CONFIRMADO*/
                            $weblink = $request["WEBLINK"];
//                            fwrite($myLog,"Weblink2: ".$weblink."\n");
                            if(isset($weblink))
                            {
                                $result = $gestor_boda->getWeblinkCon($weblink);
//                                fwrite($myLog,"result: ".$result."\n");
                                if($result >= -2 && $result <= 1 )
                                        $json_return = array("ERROR"=>"000", "CONFIRMADO"=>$result);
                                    else
                                        $json_return = array("ERROR"=>$result);                           
                            }
                            else
                                $json_return = array("ERROR"=>"C501");
                            break;
                    
                case "6":   /*AUTENTIFICACION*/
                            $credentials = $request["CREDENTIALS"];
//                            fwrite($myLog,"Username: ".$credentials["username"]."\n");
//                            fwrite($myLog,"Password: ".$credentials["password"]."\n");
                            if(isset($credentials))
                            {
                                $result = $gestor_boda->getAuth($credentials,"login");
//                                fwrite($myLog,"Password: ".$credentials["password"]."\n");
                                if(is_array($result))
                                { 
//                                    fwrite($myLog,"Name: ".$result["name"]."\n");
//                                    fwrite($myLog,"Role: ".$result["role"]."\n");
//                                    fwrite($myLog,"Id: ".$result["id"]."\n");
//                                    fwrite($myLog,"Result: ".$result["result"]."\n");
                                    $json_return = array("ERROR"=>"000", "USER"=>$result);
                                }
                                else
                                    $json_return = array("ERROR"=>$result);                           
                            }
                            else
                                $json_return = array("ERROR"=>"C601");
                            break;
                    
                case "7":   /*GET ALL DATA OF THE CONFIRMATION*/
                            $credentials = $request["CREDENTIALS"];
//                            fwrite($myLog,"Id: ".$credentials["id"]."\n");
//                            fwrite($myLog,"login: ".$credentials["login"]."\n");
                            if(isset($credentials))
                            {
                                $result = $gestor_boda->getAllData($credentials);
//                                fwrite($myLog,"Result: ".is_array($result)."\n");
                                if(is_array($result))
                                {
                                    $json_return = array("ERROR"=>"000", "DATA"=>$result);
                                }
                                else
                                    $json_return = array("ERROR"=>$result);                           
                            }
                            else
                                $json_return = array("ERROR"=>"C701");
                            break;
                    
                            /*NO SE ENCONTRO LA OPCION ENVIADA*/
                default:    $json_return = array("ERROR"=>"C002");
                            break;
            }
        }
        else
            $json_return = array("ERROR"=>"C003");
    }
    
    header('Content-Type: application/json');
    echo json_encode($json_return);
?>