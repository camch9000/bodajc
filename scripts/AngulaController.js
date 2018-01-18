/*INICIALIZANDO APLICACION ANGULARJS*/
var boda = angular.module('BodaJC27', ['ngRoute', 'ngAnimate']);

/*GUARDAR VARIABLE GLOBARL WEB LINK*/
boda.service('sWeblink', function(){
    var weblink = "";
    var confirmado = ""; 
    
    return{
        getWeblink: function(){
            return weblink;
        },
        setWeblink: function(value){
            weblink = value;
        },
        getConfirmado: function(){
            return confirmado;
        },
        setConfirmado: function(value){
            confirmado = value;
        }
    };
});

/*CONFIGURACION DE AUTENTIFICACION*/
boda.constant('AUTH_EVENTS',{
    loginSuccess: 'auth-login-success',
    loginFailed: 'auth-login-failed',
    logoutSuccess: 'auth-logout-success',
    sessionTimeout: 'auth-session-timeout',
    notAuthenticated: 'auth-not-authenticated',
    notAuthorized: 'auth-not-authorized' 
});

boda.constant('USER_ROLES',{
    admin: 'admin',
    guest: 'guest'
});

boda.service('Session', function(){
    this.create = function(userId, userRole, userName, userLogin){
        this.id = userId;
        this.userId = userId;
        this.userRole = userRole;
        this.userName = userName;
        this.userLogin = userLogin;
    };
    
    this.destroy = function(){
        this.id = null;
        this.userId = null;
        this.userRole = null;
        this.userName = null;
    };
});

boda.factory('AuthService', function($http, Session){
    var authService = {};
    
    authService.login = function(credentials){
         var req = {
             method: "POST",
             url: "/php/BodaJC_Controller.php",
             data: { CREDENTIALS: credentials,
                     OPTION: "6"
                   },
             headers: { 'Content-Type': 'Content-Type: application/json' }
                    };
        return $http(req).then(function successCallback(res){
            if(res.data.ERROR == "000"){
                if(res.data.USER.result == "OK"){
                    Session.create( res.data.USER.id,
                                    res.data.USER.role,
                                    res.data.USER.name,
                                    res.data.USER.login);
                        return res.data.USER;
                }
                else{
                    swal("ERROR", "Usuario o contraseña incorrecto, intente de nuevo", "error");
                }
            }
            else
            {
                console.log("ERROR 11: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
            }
        },function errorCallback(response){
                console.log("ERROR 12: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
            }); 
    };
    
    authService.isAuthenticated = function(){
        return !!Session.userLogin;
    };
    
    authService.isAuthorized = function(authorizeRoles){
        if(!angular.isArray(authorizeRoles)){
            authorizeRoles = [authorizeRoles];
        }
        
        return (authService.isAuthenticated() && authorizeRoles.indexOf(Session.role) !== -1);
    };
    
    authService.getData = function(){
        return {"id": Session.userId,
                "login": Session.userLogin,
                "name": Session.userName}; 
    }
    }
    
    return authService;
});

boda.service('filteredListService', function () {
     
    
    this.searched = function (valLists,toSearch) {
        return _.filter(valLists, 
        function (i) {
            /* Search Text in all 3 fields */
            return searchUtil(i, toSearch);
        });        
    };
    
    this.paged = function (valLists,pageSize)
    {
        retVal = [];
        for (var i = 0; i < valLists.length; i++) {
            if (i % pageSize === 0) {
                retVal[Math.floor(i / pageSize)] = [valLists[i]];
            } else {
                retVal[Math.floor(i / pageSize)].push(valLists[i]);
            }
        }
        return retVal;
    };
 
});

/*CONFIGURANTO EL ROUTING*/
boda.config(['$routeProvider', function($routeProvider){
    $routeProvider.when('/:weblink', {
        templateUrl: '/routes/confirmacion.html',
        controller: 'bodaController'
    }).otherwise({
        templateUrl: '/routes/noLink.html'
    });
}]);

/*CONTROL bodaController*/
boda.controller('bodaController',["$routeParams", "$scope", "$location", "$http", "sWeblink", 'USER_ROLES', 'AuthService', function($routeParams, $scope, $location, $http, sWeblink, USER_ROLES, AuthService){
    var webl = $routeParams.weblink;
    
    $scope.setCurrentUser = function (user) {
        $scope.currentUser = user;
    };
    
    if(webl == 'N0V105') {
        $scope.bodaJC.page= 4;
        
        $scope.currentUser = null;
        $scope.userRoles = USER_ROLES;
        $scope.isAuthorized = AuthService.isAuthorized;
       
        if(!AuthService.isAuthenticated)
            $scope.bodaJC.control = 2;
        else
            $scope.bodaJC.control = 1;
   }
    else    
        if(webl != null){
            var req = {
                        method: "POST",
                        url: "/php/BodaJC_Controller.php",
                        data: { WEBLINK: webl,
                                OPTION: "5"
                              },
                        headers: { 'Content-Type': 'Content-Type: application/json' }
                    };

            $http(req).then(function successCallback(response){
                if(response.data.ERROR == "000"){
                    if(response.data.CONFIRMADO != -2){
                        sWeblink.setConfirmado(response.data.CONFIRMADO);
                        sWeblink.setWeblink(webl);
                    }
                    else{
                        $location.path("/");
                    }
                }
                else
                {
                    console.log("ERROR 09: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                    swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
                }
            },function errorCallback(response){
                console.log("ERROR 10: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
            });
        }        
    
    $scope.init = function()
    {
        $scope.bodaJC = {
            page: 1,
            portada: 1,
            waiting: false
        };
    }
    
}]);

/*CONTROL no_boda*/
boda.controller('no_boda',["$scope", "$http", "sWeblink", function($scope, $http, sWeblink){
    var webl =  sWeblink.getWeblink();
    if(webl != null)
        $scope.show=false;
    else
        $scope.show=true;
    
    $scope.noPuedo = function()
    {
        swal({
            title: "¿ESTAS SEGURO?",
            text: "Al confirmar serás retirado de la lista de invitados!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "No Asistir",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        },function(){
            
        
            if(webl != null)
            {
                var req = {
                    method: "POST",
                    url: "/php/BodaJC_Controller.php",
                    data: { WEBLINK: webl,
                            OPTION: "2"
                          },
                    headers: { 'Content-Type': 'Content-Type: application/json' }
                };
                
                $http(req).then(function successCallback(response){
                    if(response.data.ERROR == "000"){
                        swal("Invitacion eliminada!", "Lamentamos mucho no poder contar con tu compañía, pero esperamos verte pronto", "error");
                        $scope.bodaJC.portada= 3;
                        flipCard();
                    }
                    else{
                        swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
                        console.log("ERROR 01: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                    }
                },function errorCallback(response){
                    console.log("ERROR 02: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                    swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
                });
            }
        });
        
    }
}]);

/*CONTROL formController*/
boda.controller('formController',["$scope", "$http", "sWeblink", function($scope, $http, sWeblink)
{
    var webl =  sWeblink.getWeblink();
    var maxN;
    
    if(webl != "")
    {
        var req = {
            method: "POST",
            url: "/php/BodaJC_Controller.php",
            data: { WEBLINK: webl,
                    OPTION: "1"
                  },
            headers: { 'Content-Type': 'Content-Type: application/json' }
        };
        
        $http(req).then(function successCallback(response){
            if(response.data.ERROR == "000")
            {
                $scope.nombre = response.data.INVITADO.prefijo + " " + response.data.INVITADO.nombre + " " + response.data.INVITADO.apellido;
                if(response.data.INVITADO.postfijo != "Nada")
                    $scope.postfijo = " y " + response.data.INVITADO.postfijo;
                $scope.email = response.data.INVITADO.email ? response.data.INVITADO.email : "";
                $scope.phone = response.data.INVITADO.telefono ? response.data.INVITADO.telefono : "";
                $scope.maxA = response.data.INVITADO.cantidad_adultos;
                $scope.maxAv = 0;
                if(response.data.INVITADO.cantidad_ninos == null || response.data.INVITADO.cantidad_ninos <= 0 || response.data.INVITADO.cantidad_ninos == undefined)
                    $scope.myNinos = {display: 'none'};                
                else 
                {
                    $scope.maxN = response.data.INVITADO.cantidad_ninos;
                    $scope.maxNv = 0;
                    $scope.myNinos = {display: 'inline-block'};
                }
                
                maxN = response.data.INVITADO.cantidad_ninos;
            }
            else
            {
                console.log("ERROR 03: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
            }    
            
        },function errorCallback(response){
            console.log("ERROR 04: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
            swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
        });
    }
    
    /*ENVIAR CONFIRMACION*/
    $scope.setConfirmar = function()
    {
        var ban = 0;
        
        if($scope.adultos == undefined){
            $("#validarData .contentShow #cant_adultos").css({"border": "solid 3px #f42922"});
            ban = 1;
        }
        else {
            $("#validarData .contentShow #cant_adultos").css({"border": "none"});
        }
        
        if(maxN > 0){
            if($scope.ninos == undefined){
                $("#validarData .contentShow #cant_ninos").css({"border": "solid 3px #f42922"});
                ban = 1;
            }
            else{
                $("#validarData .contentShow #cant_ninos").css({"border": "none"});
            }
        }
        
        if(!validatePhone($scope.phone)){
            $("#validarData .contentShow #phone").css({"border": "solid 3px #f42922"});
            ban = 1;
        }
        else{
            $("#validarData .contentShow #phone").css({"border": "none"});
        }
        
        if(!validaEmail($scope.email)){
            $("#validarData .contentShow #email").css({"border": "solid 3px #f42922"});
            ban = 1;
        }
        else{
            $("#validarData .contentShow #email").css({"border": "none"});
        }
        
        if(ban != 1)
        {
           var confirmado = {
                WEBLINK: webl,
                EMAIL: $scope.email,
                TELEFONO: $scope.phone,
                ADULTOS_CONFIRMADOS: $scope.adultos,
                NINOS_CONFIRMADOS: $scope.ninos,
                HOSPEDAJE: $scope.hotel == undefined ? 0 : $scope.hotel,
                MENSAJE: $scope.mensaje       
            };        
            var req = {
                method: "POST",
                url: "/php/BodaJC_Controller.php",
                data: { CONFIRMACION: confirmado,
                        OPTION: "3"
                      },
                headers: { 'Content-Type': 'Content-Type: application/json' }
            };
            $http(req).then(function successCallback(response){
                if(response.data.ERROR == "000")
                {
                    swal("Yaaay!", "Hemos confirmado tu invitacion", "success");
                    $scope.bodaJC.portada = 2;
                    flipCard();
                }
                else
                {
                    console.log("ERROR 05: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                    swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
                }    

            },function errorCallback(response){
                console.log("ERROR 06: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
            }); 
        }               
    }
}]);

boda.controller('noLink_Controller',["$scope", "$http", function($scope, $http){
    $scope.contactoEmail = function(){
        var nombre = $scope.cNombre;
        var email = $scope.cEmail;
        var mensaje = $scope.cMessage;
        var ban = 0;
        
        if(nombre == null)
        {
            $(".contentShow .field #cNombre").css({"border": "solid 3px #f42922"});
            ban = 1;
        }
        else
            $(".contentShow .field #cNombre").css({"border": "solid 2px green"});
        
        if(email == null || !validaEmail(email))
        {
            $(".contentShow .field #cEmail").css({"border": "solid 3px #f42922"});
            ban = 1;       
        }
        else
            $(".contentShow .field #cEmail").css({"border": "solid 2px green"});
        
        if(mensaje == null)
        {
            $(".contentShow .field #cMessage").css({"border": "solid 3px #f42922"});
            ban = 1;
        }
        else
            $(".contentShow .field #cMessage").css({"border": "solid 2px green"});
        
        if(ban == 0)
        {
            var datos_email = {
                NOMBRE: nombre,
                EMAIL: email,
                MENSAJE: mensaje
            };
            
            var req = {
                method: "POST",
                url: "/php/BodaJC_Controller.php",
                data: { DATOS_EMAIL: datos_email,
                        OPTION: "4"
                      },
                headers: { 'Content-Type': 'Content-Type: application/json' }
            };
            
            $http(req).then(function successCallback(response){
                if(response.data.ERROR == "000")
                {
                    swal("Mensaje Enviado", "Te estaremos contactando pronto","success");
                    $scope.bodaJC.portada = 4;
                    flipCard();
                }
                else
                {
                    console.log("ERROR 07: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                    swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
                }    

            },function errorCallback(response){
                console.log("ERROR 08: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
            });
        }     
    };
}]);

boda.controller('link_confirmado',["$scope","sWeblink", function($scope, sWeblink){
    
    var webl =  sWeblink.getWeblink(); 
    var con = sWeblink.getConfirmado();
    
    if(webl != ""){
        $scope.bodaJC.bShow = 1;
        if(con != -1)
            if(con == 0)
                swal("Usted ya ha declinado su asistencia", "Puede realizar modificaciones hasta el 15 de Agosto","warning");
            else
                swal("Usted ya ha confirmado su asistencia", "Puede realizar modificaciones hasta el 15 de Agosto","warning");
    }
    else
        $scope.bodaJC.bShow = 2;
}]);

/*CONTROL PARA CREDENCIALES*/
boda.controller('NoviosLoginController',['$scope', '$rootScope', 'AUTH_EVENTS', 'AuthService', function($scope, $rootScope, AUTH_EVENTS, AuthService){
    $scope.credentials = {
        username: '',
        password: ''
    };
    
    $scope.login = function(credentials){
        if(credentials.username == '' || credentials.password == '')
        {
            swal("ERROR!", "Debe ingresar un usuario y contraseña", "warning");
            return;
        }
        
        $scope.bodaJC.waiting = true;
        waitingLogin(true);
        AuthService.login(credentials).then(function(user){
            if(user != null){
                $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                $scope.setCurrentUser(user);
                $scope.bodaJC.control = 2;
            }
            else{
                $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
            }
        }, function(){
            $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
        });
        waitingLogin(false);
        $scope.bodaJC.waiting = false;
    };
}]);

/*CONTROL PARA TABLAS*/
boda.controller('tableController', ['$scope','AuthService', '$http', 'filteredListService', function($scope, AuthService, $http, filteredListService){
    var credenciales = AuthService.getData();
    
    $scope.pageSize = 6;    
    $scope.allItems = getDataTable(credenciales);
    $scope.reverse = false;
    
    $scope.resetAll = function(){
        $scope.filteredList = $scope.allItems;
        $scope.newEmpId = '';
        $scope.newName = '';
        $scope.newEmail = '';
        $scope.searchText = '';
        $scope.currentPage = 0;
        $scope.Header = ['','','','','','',''];
    };
    
    $scope.add = function () {
        $scope.allItems.push({
            confirmado: $scope.newConfirmacion,
            nombre: $scope.newNombre,
            apellido: $scope.newApellido,
            adultos_confirmados: $scope.newAdultos,
            ninos_confirmados: $scope.newNinos,
            relacion: $scope.newRelacion,
            tipo_relacion: $scope.newParentesco
        });
        $scope.resetAll();
    }; 
    
    $scope.search = function () {
        $scope.filteredList = 
       filteredListService.searched($scope.allItems, $scope.searchText);
        
        if ($scope.searchText == '') {
            $scope.filteredList = $scope.allItems;
        }
        $scope.pagination(); 
    };
    
    $scope.pagination = function () {
        $scope.ItemsByPage = filteredListService.paged( $scope.filteredList, $scope.pageSize );         
    };
    
    $scope.setPage = function () {
        $scope.currentPage = this.n;
    };

    $scope.firstPage = function () {
        $scope.currentPage = 0;
    };

    $scope.lastPage = function () {
        $scope.currentPage = $scope.ItemsByPage.length - 1;
    };
    
    $scope.range = function (input, total) {
        var ret = [];
        if (!total) {
            total = input;
            input = 0;
        }
        for (var i = input; i < total; i++) {
            if (i != 0 && i != total - 1) {
                ret.push(i);
            }
        }
        return ret;
    };
    
    $scope.sort = function(sortBy){
        $scope.resetAll();  
        
        $scope.columnToOrder = sortBy; 
             
        //$Filter - Standard Service
        $scope.filteredList = $filter('orderBy')($scope.filteredList, $scope.columnToOrder, $scope.reverse); 

        if($scope.reverse)
             iconName = 'glyphicon glyphicon-chevron-up';
         else
             iconName = 'glyphicon glyphicon-chevron-down';
              
        if(sortBy === 'confirmado')
            $scope.Header[0] = iconName;
        else 
            if(sortBy === 'nombre')
                $scope.Header[1] = iconName;
            else
                if(sortBy === 'apellido')
                    $scope.Header[2] = iconName;
                else
                    if(sortBy === 'adultos_confirmados')
                        $scope.Header[3] = iconName;
                    else
                        if(sortBy === 'ninos_confirmados')
                            $scope.Header[4] = iconName;
                        else
                            if(sortBy === 'relacion')
                                $scope.Header[5] = iconName;
                            else
                                $scope.Header[6] = iconName;
         
        $scope.reverse = !$scope.reverse;   
        
        $scope.pagination();    
    };
    
    //By Default sort ny Name
     $scope.sort ('apellido'); 
    
    $scope.getDataTable = function(credenciales){
        var req = {
                    method: "POST",
                    url: "/php/BodaJC_Controller.php",
                    data: { CREDENTIALS: credenciales,
                            OPTION: "7"
                          },
                    headers: { 'Content-Type': 'Content-Type: application/json' }
                };
    
        $http(req).then(function successCallback(response){
                if(response.data.ERROR == "000"){
//                    $scope.invitados = response.data.DATA;
                    return response.data.DATA;
//                    $scope.sortType = 'fecha_confirmado';
                }
                else
                {
                    console.log("ERROR 13: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                    swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
                }    
            },function errorCallback(response){
                console.log("ERROR 14: " + response.data.ERROR + " || Status: " + response.status + " || " + response.data);
                swal("DISCULPE!", "Ha ocurrido un error, vuelva a intentarlo (recargue la pagina o presione F5)", "warning");
            });
    };
}]);