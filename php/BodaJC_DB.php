<?php
	class BodaJC_DB
	{
        var $bdUser = 'u910704041_boda';
        var $bdPass = '01020401';
        var $bdName = 'u910704041_boda';
        var $bdHost = 'mysql.hostinger.co';
        
        function __construct()
		{ }
		
        /*OPTENER INFORMACION DEL INVITADO*/
		function getWebLinkInfo($weblink)
		{
            //LOG (ACTIVAR SOLO PARA PRUEBAS)
//            $myLog = fopen("bodalog_BD1.txt", "w") or die("Unable to open file!");
//            fwrite($myLog,"Weblink: " . $weblink . "\n");
            global $bdUser, $bdPass, $bdName, $bdHost;
			$con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");
            
			if(isset($weblink) && $weblink != null)
            {
                $query = "SELECT IV.nombre, IV.apellido, IV.email, IV.telefono, IV.cantidad_adultos, IV.cantidad_ninos, IV.confirmado, IV.hospedaje,               PO.postfijo, RE.relacion, PR.prefijo
                          FROM Invitados IV LEFT JOIN
                                     Prefijo PR ON IV.prefijo = PR.prefijo_id LEFT JOIN
                                     Postfijo PO ON IV.postfijo = PO.postfijo_id LEFT JOIN
                                     Relacion RE ON IV.relacion = RE.relacion_id
                               WHERE IV.web_link = '".$weblink."'";
                
//                fwrite($myLog,"QUERY: ".$query."\n");
                
                $res = mysqli_query($con, $query) or die("Unable to execute query");
                
                if($datos = mysqli_fetch_array($res))
                {
                    $invitado = array("nombre" => $datos[0],
                                      "apellido" => $datos[1],
                                      "email" => $datos[2],
                                      "telefono" => $datos[3],
                                      "cantidad_adultos" => $datos[4],
                                      "cantidad_ninos" => $datos[5],
                                      "confirmado" => $datos[6],
                                      "hotel" => $datos[7],
                                      "postfijo" => $datos[8],
                                      "relacion" => $datos[9],
                                      "prefijo" => $datos[10]);

                    return $invitado;
                }
                else
                    return "EBD01I";
            }
            else
                return "EBD02I";
		}
        
        /*CONFIRMAR INVITACION*/
        function setConfirmation($confirmacion)
        {
            //LOG (ACTIVAR SOLO PARA PRUEBAS)
//            $myLog = fopen("bodalogBD2.txt", "w") or die("Unable to open file!");
//            fwrite($myLog,"Option: Llego BD\n");
            global $bdUser, $bdPass, $bdName, $bdHost;
            $con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");
            
            $query = "UPDATE Invitados SET ";
            
            if(isset($confirmacion["WEBLINK"]) && $confirmacion["WEBLINK"] != null)
            {   
                //CONFIRMADO
                $query = $query . ' confirmado = 1 ';
                //EMAIL
                if(isset($confirmacion["EMAIL"]) && $confirmacion["EMAIL"] != null)
                    $query = $query . " ,email='" . $confirmacion["EMAIL"] . "' ";
                //TELEFONO
                if(isset($confirmacion["TELEFONO"]) && $confirmacion["TELEFONO"] != null)
                    $query = $query . " ,telefono='" . $confirmacion["TELEFONO"]. "' ";
                //CANTIDAD ADULTOS
                if(isset($confirmacion["ADULTOS_CONFIRMADOS"]) && $confirmacion["ADULTOS_CONFIRMADOS"] != null)
                    $query = $query . ' ,adultos_confirmados=' . $confirmacion["ADULTOS_CONFIRMADOS"];
                //CANTIDAD NINOS
                if(isset($confirmacion["NINOS_CONFIRMADOS"]) && $confirmacion["NINOS_CONFIRMADOS"] != null)
                    $query = $query . ' ,ninos_confirmados=' . $confirmacion["NINOS_CONFIRMADOS"];
                //HOTEL
                if(isset($confirmacion["HOSPEDAJE"]) && $confirmacion["HOSPEDAJE"] != null)
                    $query = $query . ' ,hospedaje=' . $confirmacion["HOSPEDAJE"];
                //MENSAJE
                if(isset($confirmacion["MENSAJE"]) && $confirmacion["MENSAJE"] != null)
                    $query = $query . " ,mensaje= '" . $confirmacion["MENSAJE"] ."' ";
                
                if(isset($confirmacion["WEBLINK"]) && $confirmacion["WEBLINK"] != null)
                {
                    $query = $query . ' ,fecha_confirmado=NOW() ';
                    $query = $query . " WHERE web_link = '" . $confirmacion["WEBLINK"] ."' ";
                }
                else
                    return "EBD01C";
                
//                fwrite($myLog,"QUERY: ".$query."\n");
                
                $res = mysqli_query($con, $query) or die("Unable to execute query");
                
                return "000";
            }
            else
                return "EBD02C";
        }
        
        /*DECLINAR INVITACION*/
        function setDeclinar($weblink)
        {
            /*LOG (ACTIVAR SOLO PARA PRUEBAS)*/
//            $myLog = fopen("bodalogBD3.txt", "w") or die("Unable to open file!");
//            fwrite($myLog,"LLEGO A setDeclinar: ".$weblink."\n");
            global $bdUser, $bdPass, $bdName, $bdHost;
            $con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");
            
			if(isset($weblink) && $weblink != null)
            {
                $query = "UPDATE Invitados SET confirmado = 0, fecha_confirmado = NOW() WHERE web_link = '" . $weblink ."'";
//                fwrite($myLog,"QUERY: ".$query."\n");
                mysqli_query($con, $query) or die("EBD01D - " . mysqli_error());
                
                return "000";
            }
            else
                return "EBD02D";
        }
        
        /*VERIFICAR SI EXISTE WEBLINK Y SI YA FUE CONFIRMADO*/
        function getWeblinkCon($weblink)
        {
            /*LOG (ACTIVAR SOLO PARA PRUEBAS)*/
//            $myLog = fopen("bodalogBD4.txt", "w") or die("Unable to open file!");
//            fwrite($myLog,"LLEGO A setDeclinar: ".$weblink."\n");
            global $bdUser, $bdPass, $bdName, $bdHost;
            $con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");
            
            if(isset($weblink) && $weblink != null)
            {
                $query = "SELECT confirmado FROM Invitados WHERE web_link = '" . $weblink ."'";
//                fwrite($myLog,"QUERY: ".$query."\n");
                $res = mysqli_query($con, $query) or die("EBD01W - " . mysqli_error());
                
                if($datos = mysqli_fetch_array($res))
                {
//                    fwrite($myLog,"Result: ".$datos[0]."\n");
                    return $datos[0];
                }
                
                return -2;
            }
            else
                return "EBD02W";
        }
        
        function getAuth($credentials, $filtro)
        {
            /*LOG (ACTIVAR SOLO PARA PRUEBAS)*/
//            $myLog = fopen("bodalogBD5.txt", "w") or die("Unable to open file!");
//            fwrite($myLog,"LLEGO A getAuth: ".$credentials."\n");
            
            global $bdUser, $bdPass, $bdName, $bdHost;
            $con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");
            
            if(isset($credentials) && $credentials != null)
            {
                $query = "SELECT USR.user_Id, USR.role, USR.password, USR.name, USR.login FROM User USR WHERE USR." . $filtro . " = " . $credentials;
                
//                fwrite($myLog,"Query: ".$query."\n");
                
                $res = mysqli_query($con, $query) or die("EBD01W - " . mysqli_error());
                
//                fwrite($myLog,"Role: ".$datos[1]."\n");
                
                if($datos = mysqli_fetch_array($res))
                {
                    $user = array("id" => $datos[0],
                                  "role" => $datos[1],
                                  "password" => $datos[2],
                                  "name" => $datos[3],
                                  "login" => $datos[4]);

                    return $user;
                }
                else
                    return "001";
            }
            else
                return "EBD01AU";
        }
        
        function getAllData()
        {
            /*LOG (ACTIVAR SOLO PARA PRUEBAS)*/
//            $myLog = fopen("bodalogBD6.txt", "w") or die("Unable to open file!");
//            fwrite($myLog,"LLEGO A getAuth: ".$credentials["username"]."\n");
            
            global $bdUser, $bdPass, $bdName, $bdHost;
            $con = mysqli_connect($this->bdHost, $this->bdUser, $this->bdPass, $this->bdName) or die("Unable to Connect to data base");
            
//            fwrite($myLog,"LLEGO \n");
            
            $query = "SELECT IV.confirmado, 
                             IV.fecha_confirmado,
                             IV.nombre,
                             IV.apellido,
                             IV.cantidad_adultos,
                             IV.adultos_confirmados,
                             IV.cantidad_ninos,
                             IV.ninos_confirmados,
                             IV.mensaje,
                             IV.email,
                             IV.telefono,
                             RL.relacion,
                             TR.tipo_relacion
                        FROM Invitados IV LEFT JOIN 
                                Relacion RL ON IV.relacion = RL.relacion_Id LEFT JOIN
                             Tipo_Relacion TR ON IV.tipo_relacion = TR.tipo_relacion_id
                       WHERE IV.confirmado IN (0,1) 
                    ORDER BY IV.fecha_confirmado DESC";
            
//            fwrite($myLog,$query);
            
            $res = mysqli_query($con, $query) or die("EBD01AD - " . mysqli_error());
            
            while($datos = mysqli_fetch_array($res))
            {
                $invitados[] = array("confirmado" => $datos[0],
                                     "fecha_confirmado" => $datos[1],
                                     "nombre" => $datos[2],
                                     "apellido" => $datos[3],
                                     "cantidad_adultos" => $datos[4],
                                     "adultos_confirmados" => $datos[5],
                                     "cantidad_ninos" => $datos[6],
                                     "ninos_confirmados" => $datos[7],
                                     "mensaje" => $datos[8],
                                     "email" => $datos[9],
                                     "telefono" => $datos[10],
                                     "relacion" => $datos[11],
                                     "tipo_relacion" => $datos[12]);
            }
            
            return $invitados;
        }
	}
?>	