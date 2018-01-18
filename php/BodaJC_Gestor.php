<?php
class BodaJC_Gestor
{
	function __construct()
	{}
	
	function includes()
	{
		if(file_exists("BodaJC_DB.php"))
		{
			require_once("BodaJC_DB.php");
		}
		else
		{
			echo 'ERROR GE001';
		}
	}
	
	function getWebLinkInfo($weblink)
	{
		if(isset($weblink) && $weblink != "")
		{
			$this->includes();
			$DB_boda = new BodaJC_DB();
			
			$invitado = $DB_boda->getWebLinkInfo($weblink);
            
            return $invitado;
        }
	}
    
    function setConfirmation($confirmacion)
    {
        $this->includes();
        $DB_boda = new BodaJC_DB();
        
        $result = $DB_boda->setConfirmation($confirmacion);
        
        if($result == "000")
        {
            $invitado = $this->getWebLinkInfo($confirmacion["WEBLINK"]);
            
            $conEmail["EMAIL_FROM"] = "camch9000@gmail.com";
            $conEmail["EMAIL_CC"] = "camch9000@gmail.com";
            $conEmail["EMAIL_TO"] = $invitado["email"];
            $conEmail["NOMBRE"] = $invitado["nombre"] . " " .$invitado["apellido"];
            $conEmail["NOMBRE2"] = $invitado["nombre"];
            $conEmail["ACCION"] = "CONFIRMADO";
            $conEmail["IMAGEN"] = "5";
            $conEmail["MENSAJE"] = 'Usted ha confirmado su invitacion para la boda de Jessica y Carlos, nos alegra mucho contar con tu compañia, nos veremos pronto. <br /><br /> Si seleccionaste la opcion para recibir informacion de hospedaje, en los proximos dias estaras recibiendo otro email con informacion sobre alojamiento.<br /><br /> Recuerda que tienes hasta el 15 de Agosto para modificar tu invitacion.<br /><br /> <p style="color:red">&#163;   &#8364;   &#36;   Bs<p>';
            $conEmail["WEBLINK"] = $confirmacion["WEBLINK"];
            
            $result = $this->sendConEmail($conEmail);
            
            if($result == "000")
            {
                $conEmail["EMAIL_FROM"] = $invitado["email"];
                $conEmail["EMAIL_CC"] = "jalexrg1992@gmail.com";
                $conEmail["EMAIL_TO"] = "camch9000@gmail.com";
                $conEmail["NOMBRE"] = $invitado["nombre"] . " " .$invitado["apellido"];
                $conEmail["NOMBRE2"] = "Jessica/Carlos";
                $conEmail["ACCION"] = "CONFIRMADO";
                $conEmail["IMAGEN"] = "4";
                $conEmail["MENSAJE"] = "Nos alegra informarte que ".$invitado["nombre"] . " " .$invitado["apellido"]. " ha confirmado la invitacion." ;
                $conEmail["WEBLINK"] = "N0V105";

                $result = $this->sendConEmail($conEmail);
            }
        }
            
        return $result;
    }
    
    function setDeclinar($weblink)
    {
        $this->includes();
        $DB_boda = new BodaJC_DB();
        
        $result = $DB_boda->setDeclinar($weblink);
        
        if($result == "000")
        {
            $invitado = $this->getWebLinkInfo($weblink);
            
            $conEmail["EMAIL_FROM"] = "camch9000@gmail.com";
            $conEmail["EMAIL_CC"] = "camch9000@gmail.com";
            $conEmail["EMAIL_TO"] = $invitado["email"];
            $conEmail["NOMBRE"] = $invitado["nombre"] . " " .$invitado["apellido"];
            $conEmail["NOMBRE2"] = $invitado["nombre"];
            $conEmail["ACCION"] = "DECLINAR";
            $conEmail["IMAGEN"] = "4";
            $conEmail["MENSAJE"] = "Usted ha declinado su invitacion para la boda de Jessica y Carlos, Lamentamos mucho no poder contar con tu compañia, esperamos verte pronto.<br /><br /> Recuerda que tienes hasta el 31 de Julio para modificar tu invitacion.";
            $conEmail["WEBLINK"] = $weblink;
            
            $result = $this->sendConEmail($conEmail);
            
            if($result == "000")
            {
                $conEmail["EMAIL_FROM"] = $invitado["email"];
                $conEmail["EMAIL_CC"] = "jalexrg1992@gmail.com";
                $conEmail["EMAIL_TO"] = "camch9000@gmail.com";
                $conEmail["NOMBRE"] = $invitado["nombre"] . " " .$invitado["apellido"];
                $conEmail["NOMBRE2"] = "Jessica/Carlos";
                $conEmail["ACCION"] = "DECLINAR";
                $conEmail["IMAGEN"] = "4";
                $conEmail["MENSAJE"] = "Lamentamos informarles que ".$invitado["nombre"] . " " .$invitado["apellido"]. " ha declinado la invitacion." ;
                $conEmail["WEBLINK"] = "N0V105";

                $result = $this->sendConEmail($conEmail);
            }
        }
        
        return $result;
    }
    
    function getWeblinkCon($weblink)
    {
        $this->includes();
        $DB_boda = new BodaJC_DB();
        
        $result = $DB_boda->getWeblinkCon($weblink);
        
        return $result;
    }
    
    function getAuth($credentials, $filtro)
    {
        /*LOG (ACTIVAR SOLO PARA PRUEBAS)*/
//        $myLog = fopen("bodaLogGestor5.txt", "w") or die("Unable to open file!");
        
        $this->includes();
        $DB_boda = new BodaJC_DB();
        
//        fwrite($myLog,"Id:" . $filtro ." \n");
        
        if($filtro == "login")
        {
            $result = $DB_boda->getAuth("'".$credentials["username"]."'",$filtro);
            
            if(isset($result) && ($result["password"] == $credentials["password"]))
            {
//                fwrite($myLog,"OK \n");
                
                return array("id" => $result["id"],
                             "role" => $result["role"],
                             "name" => $result["name"],
                             "login" => $result["login"],
                             "result" => "OK");
            }
            else
            {
//                fwrite($myLog,"login_failed \n");
                return array("result" => "login_failed");
            }
        }
        else
            if($filtro == "user_id")
            {
//                fwrite($myLog,"IdC:".$credentials["id"]." \n");
                $result = $DB_boda->getAuth($credentials["id"],$filtro);
//                fwrite($myLog,"IdR:".$result["id"]." \n");
                
                if(is_array($result))
                    return $result;
                else
                    return false;
            }
        
//        fwrite($myLog,"Client: ".$credentials["username"]." / ".$credentials["password"]."\n");
//        fwrite($myLog,"Server: ".$result["name"]." / ".$result["password"]."\n"); 
        
        return $result;
    }
    
    function getAllData($credentials)
    {
        /*LOG (ACTIVAR SOLO PARA PRUEBAS)*/
//        $myLog = fopen("bodaLogGestor6.txt", "w") or die("Unable to open file!");
        
        $result = $this->getAuth($credentials,"user_id");
        
//        fwrite($myLog, "CId: " . $credentials["id"] . " \n");
//        fwrite($myLog, "Result: " . $result["id"] . " \n");
        
        if(is_array($result) && ($result["id"] == $credentials["id"]))
        {
//            fwrite($myLog,"Informacion OK \n");
            $this->includes();            
            $DB_boda = new BodaJC_DB();
        
            $result = $DB_boda->getAllData();
        
            return $result;
        }
        else
            return "001";        
       
    }
    
    function sendCEmail($cEmail)
    {
        $cabeceras = "From: jessicaycarlos@bodajc.com.ve \n"
					."Reply-To: " .$cEmail["EMAIL"]. "\n"
					."CC: jalexrg1992@gmail.com \n";
        
		$cabeceras .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $asunto = "(BodaJC.com.ve) " .$cEmail["NOMBRE"];
		$email_to = "camch9000@gmail.com";
        
        $contenido = '<!DOCTYPE html>
                        <html>
                          <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <style type="text/css">
                               body
                               {
                                height: 100%;
                                background-color: #00c1c6;
                               }
                              <!--.logo
                              {
                                width: 100%;
                                text-align: center;
                              }

                              .logo img
                              {
                                width: 12rem;
                                height: 10rem;
                              }-->

                              .container
                              {
                                padding-right: 20px;
                                padding-left: 20px;
                                margin-right: auto;
                                margin-left: auto;
                              }

                              .firma
                              {
                                text-align: right;
                              }

                              #datosAzul
                                {
                                    color: #d78d78;
                                    font-weight: bold;
                                    margin: 0;
                                    padding: 0;
                                    border: 0;
                                    font-size: 100%;
                                    font: inherit;
                                    vertical-align: baseline;
                                    olor: #313f47;
                                    line-height: 1.5;
                                    margin: 0 0 0.75em 0;
                                    font-size: 1.85em;
                                    letter-spacing: 0.22em;
                                    margin: 0 0 0.525em 0;
                                    text-align: center;
                                }
                                #datosAzul a
                                {
                                color: inherit;
                                    text-decoration: none;
                                }
                                #datosAzul a:hover
                            {
                                color: white;
                            }
                            </style>
                          </head>

                          <body>
                            <div class="container">
                              <h1 id="datosAzul"><a href="https://www.instagram.com/explore/tags/bodajc27/" target="_blank">&#35;BodaJC27</a></h1>
                              <!--<div class="logo">
                                <img src="http://www.bodajc.com.ve/images/Logo_Pareja4.png" alt="Pareja">
                              </div>-->

                              <div class="texto_curso">
                                <p>Hola Carlos / Jessica,</p>
                                <br />
                                <p>Hemos recibido una mensaje de <b>'.$cEmail["NOMBRE"].'</b> desde <b><a href="http://www.bodaJC.com.ve" target="_blank">www.bodaJC.com.ve</a></b>,
                                  te dejamos la información necesaria para que te puedas poner en contacto con ella/el.</p>
                                <br />
                                <p>Nombre: <b>'.$cEmail["NOMBRE"].'</b><p>
                                <p>Email: <b>'.$cEmail["EMAIL"].'</b><p>
                                <p>Mensaje: <b>'.$cEmail["MENSAJE"].'</b><p>
                              </div>

                              <div class="firma">
                                <p>Camch9000 | by Ing. Carlos A. Matheus</p>
                              </div>
                            </div>

                          </body>
                        </html>';
        
        if(mail($email_to, $asunto, $contenido, $cabeceras))
            return "000";	
		else
            return "EG001";
    }
    
    function sendConEmail($conEmail)
    {
        $cabeceras = "From: jessicaycarlos@bodajc.com.ve \n"
					."Reply-To: " .$conEmail["EMAIL_FROM"]. "\n"
					."CC: ".$conEmail["EMAIL_CC"]." \n";
        
		$cabeceras .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $asunto = "(BodaJC.com.ve) " .$conEmail["NOMBRE"]. " (" .$conEmail["ACCION"]. ")";
		$email_to = $conEmail["EMAIL_TO"];
        
        $contenido = '<!DOCTYPE html>
                        <html>
                          <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <style type="text/css">
                               body
                               {
                                height: 100%;
                                background-color: #00c1c6;
                               }
                              <!--.logo
                              {
                                width: 100%;
                                text-align: center;
                              }

                              .logo img
                              {
                                width: 12rem;
                                height: 10rem;
                              }-->

                              .container
                              {
                                padding-right: 20px;
                                padding-left: 20px;
                                margin-right: auto;
                                margin-left: auto;
                              }

                              .firma
                              {
                                text-align: right;
                              }

                              #datosAzul
                                {
                                    color: #d78d78;
                                    font-weight: bold;
                                    margin: 0;
                                    padding: 0;
                                    border: 0;
                                    font-size: 100%;
                                    font: inherit;
                                    vertical-align: baseline;
                                    olor: #313f47;
                                    line-height: 1.5;
                                    margin: 0 0 0.75em 0;
                                    font-size: 1.85em;
                                    letter-spacing: 0.22em;
                                    margin: 0 0 0.525em 0;
                                    text-align: center;
                                }
                                #datosAzul a
                                {
                                color: inherit;
                                    text-decoration: none;
                                }
                                #datosAzul a:hover
                            {
                                color: white;
                            }
                            </style>
                          </head>

                          <body>
                            <div class="container">
                              <h1 id="datosAzul"><a href="https://www.instagram.com/explore/tags/bodajc27/" target="_blank">&#35;BodaJC27</a></h1>
                              <!--<div class="logo">
                                <img src="http://www.bodajc.com.ve/images/Logo_Pareja'.$conEmail["IMAGEN"].'.png" alt="Pareja">
                              </div>-->

                              <div class="texto_curso">
                                <p>Hola '.$conEmail["NOMBRE2"].'</p>
                                <br />
                                <p>'.$conEmail["MENSAJE"].'</p>
                                <br />
                              </div>

                              <div class="firma">
                                <p>Jessica y Carlos | <a href="www.bodajc.com.ve#/'.$conEmail["WEBLINK"].'" target="_blank">www.bodajc.com.ve/</a></p>
                              </div>
                            </div>

                          </body>
                        </html>';
        
        if(mail($email_to, $asunto, $contenido, $cabeceras))
            return "000";	
		else
            return "EG001";
    }
}
?>