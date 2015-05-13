<!DOCTYPE html>
<html lang="en" ng-app="myApp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Zona Abonados</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/toaster.css" rel="stylesheet">
    <link href="css/xeditable.css" rel="stylesheet">
    <link href="css/less.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <script src="js/angular.min.js"></script>
    <script src="js/angular-route.min.js"></script>
    <script src="js/angular-animate.min.js"></script>
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/toaster.js"></script>
    <script src="js/xeditable.min.js"></script>
    <!-- Libs -->
    <script src="app/app.js"></script>
    <script src="app/data.js"></script>
    <script src="app/directives.js"></script>
    <script src="app/authCtrl.js"></script>
    <style>
        a {
            color: green;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]><link href= "css/bootstrap-theme.css"rel= "stylesheet" >

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="sitio" ng-cloak="">
    <div id="pag" class="home">
        <div class="fondo">
            <div id="cabecera">
                <h1><a href="http://clubsantos.mx/"><img src="http://clubsantos.mx/images/cabecera/cabecera.png"></a></h1>
                <ol class="elementos">
                    <li class="patrocinadores">
                        <ol class="pp">
                            <li><img src="http://clubsantos.mx/images/cabecera/icons/1.png">
                            </li>
                            <li><img src="http://clubsantos.mx/images/cabecera/icons/2.png">
                            </li>
                            <li>
                                <a href="http://www.celticfc.net/" target="_blank"><img src="http://clubsantos.mx/images/cabecera/icons/3.png">
                                </a>
                            </li>
                            <li><img src="http://clubsantos.mx/images/cabecera/icons/4.png">
                            </li>
                            <li><img src="http://clubsantos.mx/images/cabecera/icons/5.png">
                            </li>
                        </ol>
                    </li>
                    <li>
                        <a href="http://www.terra.com.mx/" target="_blank" class="terra"><img src="http://clubsantos.mx/images/cabecera/logoterradeportes.png">
                        </a>
                        <ul id="clock">
                            <li id="sec" style="-webkit-transform: rotate(198deg);"></li>
                            <li id="hour" style="-webkit-transform: rotate(475.5deg);"></li>
                            <li id="min" style="-webkit-transform: rotate(306deg);"></li>
                        </ul>
                    </li>
                </ol>
            </div>
            <div id="menu">
                <div id="menuTop">
                    <ol class="pp">
                        <li><a href="http://clubsantos.mx/contenidos/club">Club</a>
                        </li>
                        <li><a href="http://clubsantos.mx/plantel">Plantel</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/TSM">TSM</a>
                        </li>
                    </ol>
                    <ol class="redes">
                        <li>
                            <a href="https://twitter.com/clubsantos" target="_blank"><img src="http://clubsantos.mx/images/menu/tw.png">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/clubsantoslagunaoficial" target="_blank"><img src="http://clubsantos.mx/images/menu/fb.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://instagram.com/clubsantoslaguna" target="_blank"><img src="http://clubsantos.mx/images/menu/is.png">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/channel/UC51-rRe-cnJn5V670538Lrw" target="_blank"><img src="http://clubsantos.mx/images/menu/yb.png">
                            </a>
                        </li>
                    </ol>
                </div>
                <div id="menuBottom">
                    <ol class="pp">
                        <li><a href="http://clubsantos.mx/">Inicio</a>
                        </li>
                        <li><a href="http://clubsantos.mx/noticias">Noticias</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/torneos">Torneos</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/boletos">Boletos</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/guerrerosdecorazon">Guerreros de corazon</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/zonafan">Zona fan</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/socialmedia">Redes sociales</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/compras">Compras</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/media">Media</a>
                        </li>
                        <li><a href="http://clubsantos.mx/contenidos/bolsadetrabajo">Bolsa de trabajo</a>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- Angular partials -->
            <div class="listBanners" style="margin-top:20px;">

                <div data-ng-view="" id="ng-view" class="slide-animation"></div>

            </div>
            <!-- End Angular partials -->
            <div id="foot">
                <div class="listPatrcinadores">
                    <ol style="padding-top: 20px;">
                        <li>
                            <a href="http://www.corona.com.mx/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/4.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www1.soriana.com/site/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/5.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.grupolala.com" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/6.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.penoles.com.mx/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/7.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.pepsi.com" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/8.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.banamex.com" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/9.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.bridgestone.com.mx/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/10.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.chevrolet.com.mx/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/11.png">
                            </a>
                        </li>
                    </ol>
                    <ol style="padding-top: 35px;width: 81%;margin: auto;">
                        <li>
                            <a href="http://www.azteca.com/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/12.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.aeromexico.com/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/13.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.torreon.gob.mx/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/14.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://www.odm.com.mx/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/15.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://coahuila.gob.mx/" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/16.png">
                            </a>
                        </li>
                        <li>
                            <a href="http://global.puma.com/es_MX/home?locale=es_MX" target="_blank"><img src="http://clubsantos.mx/imagesSitio/patrocinadores/17.png">
                            </a>
                        </li>
                    </ol>
                    <ol>
                    </ol>
                </div>
                <div class="derechos">
                    <p>
                        <strong>Club Santos Laguna 2014</strong> Todos los derechos reservados
                        <a href="http://clubsantos.mx/contenidos/terminosycondiciones">Términos y condiciones</a> /
                        <a href="http://clubsantos.mx/contenidos/politica_privacidad">Política de privacidad </a>
                    </p>
                </div>
                <p class="powerby"><a href="http://arkebit.com/" target="_blank">Powered by Arkebit</a>
                </p>
            </div>
        </div>
    </div>
    <div>

</body>
<toaster-container toaster-options="{'time-out': 3000}"></toaster-container>

</html>