<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.ico">
    <title>Admin - Costo Facile</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href=".plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- datepicket -->
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="plugins/bower_components/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/default.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- column chart -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Cliente', 'Clienti', 'Articoli', 'Preventivi'],
                <? echo file_get_contents("Import/GraficoClienti.json"); ?>
            ]);

            var options = {
                legend: 'top',
                chart: {
                    title: '',
                    subtitle: 'Clienti, Articoli e Preventivi'
                }
            };

            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
    <!-- pie chart platform -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Sistema Operativo', 'Sessioni'],
                <? echo file_get_contents("Import/Platform.json"); ?>
            ]);

            var options = {
                legend: 'top',
                title: '',
                is3D: true
            };
            var chart = new google.visualization.PieChart(document.getElementById('platform'));
            chart.draw(data, options);
        }
    </script>
    <!-- pie chart browser -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Browser', 'Sessioni'],
                <? echo file_get_contents("Import/Browser.json"); ?>
            ]);

            var options = {
                legend: 'top',
                title: '',
                is3D: true
            };
            var chart = new google.visualization.PieChart(document.getElementById('browser'));
            chart.draw(data, options);
        }
    </script>
</head>

<body class="fix-header">
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part" style="padding-bottom: 10px;" >
                    <!-- Logo -->
                    <a class="logo" href="dashboard.php">
                        <img src="plugins/images/logo-text.png" alt="home" style="width: 90%" class="light-logo" />
                        <!--<span class="hidden-xs"><img src="plugins/images/logo-icon.png" alt="home" class="light-logo" /></span>-->
                        <!--<img src="plugins/images/admin-logo.png" alt="home" class="dark-logo" /><img src="plugins/images/admin-logo-dark.png" alt="home" class="light-logo" />
                        <span class="hidden-xs"><img src="plugins/images/admin-text.png" alt="home" class="dark-logo" /><img src="plugins/images/admin-text-dark.png" alt="home" class="light-logo" /></span>-->
                    </a>
                </div>
                <!-- /Logo -->
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a class="profile-pic" href="#"><b class="hidden-xs">Admin</b></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3>
                </div>
                <ul class="nav" id="side-menu">
                    <li style="padding: 70px 0 0;">
                        <a href="dashboard.php" class="waves-effect"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="javascript.void(0)" class="waves-effect" data-toggle="modal" data-target="#passwordModal"><i class="fa fa-key fa-fw" aria-hidden="true"></i>Cambio Password Demo</a>
                    </li>
                    <li style="background-color: #7ace4c;color: white">
                        <a class="waves-effect" onclick="refreshData()" ><i class="fa fa-recycle fa-fw" aria-hidden="true"></i>Aggiorna Dati</a>
                    </li>
                    <li>
                        <a href="index.html" class="waves-effect"><i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>Ultimo aggiornamento: <? echo date ("d m Y - H:i:s.", filemtime("Import/LogError.json")) ?></a>
                    </li>
                    <!--<li>
                        <a href="fontawesome.html" class="waves-effect"><i class="fa fa-font fa-fw" aria-hidden="true"></i>Icons</a>
                    </li>
                    <li>
                        <a href="map-google.html" class="waves-effect"><i class="fa fa-globe fa-fw" aria-hidden="true"></i>Google Map</a>
                    </li>
                    <li>
                        <a href="blank.html" class="waves-effect"><i class="fa fa-columns fa-fw" aria-hidden="true"></i>Blank Page</a>
                    </li>
                    <li>
                        <a href="404.html" class="waves-effect"><i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>Error 404</a>
                    </li>-->

                </ul>
            </div>
            
        </div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page Content -->
        <!-- ============================================================== -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Dashboard</h4> </div>
                    <!--<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <a href="https://wrappixel.com/templates/ampleadmin/" target="_blank" class="btn btn-danger pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Upgrade to Pro</a>
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                        </ol>
                    </div>-->
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- ============================================================== -->
                <!-- Different data widgets -->
                <!-- ============================================================== -->
                <!-- .row -->
                <?
                    //ciclo i clienti per prendermi i dati
                    $totali_json = file_get_contents("Import/Totali.json");
                    $totali = json_decode($totali_json, true);
                ?>
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Totale Clienti</h3>
                            <ul class="list-inline two-part">
                                <li>
                                    <div id="sparklinedash"></div>
                                </li>
                                <li class="text-right"><i class="ti-arrow-up text-success"></i> <span class="counter text-success"><? echo $totali[0]["clienti"] ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Totale Articoli</h3>
                            <ul class="list-inline two-part">
                                <li>
                                    <div id="sparklinedash2"></div>
                                </li>
                                <li class="text-right"><i class="ti-arrow-up text-purple"></i> <span class="counter text-purple"><? echo $totali[0]["articoli"] ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Totale Preventivi</h3>
                            <ul class="list-inline two-part">
                                <li>
                                    <div id="sparklinedash3"></div>
                                </li>
                                <li class="text-right"><i class="ti-arrow-up text-info"></i> <span class="counter text-info"><? echo $totali[0]["preventivi"] ?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/.row -->
                <!--row -->
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Analisi X Cliente</h3>
                            <!--<ul class="list-inline text-right">
                                <li>
                                    <h5><i class="fa fa-circle m-r-5 text-info"></i>Mac</h5> </li>
                                <li>
                                    <h5><i class="fa fa-circle m-r-5 text-inverse"></i>Windows</h5> </li>
                            </ul>
                            <div id="ct-visits" style="height: 405px;"></div>-->
                            <div id="columnchart_material" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- table -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <!--<div class="col-md-3 col-sm-4 col-xs-6 pull-right">
                                <select class="form-control pull-right row b-none">
                                    <option>March 2017</option>
                                    <option>April 2017</option>
                                    <option>May 2017</option>
                                    <option>June 2017</option>
                                    <option>July 2017</option>
                                </select>
                            </div>-->
                            <h3 class="box-title">Stato Clienti</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NOME</th>
                                            <th>DATA INIZIO</th>
                                            <th>DATE FINE</th>
                                            <th>RATA MENSILE</th>
                                            <th>PAGATO</th>
                                            <th>AZIONI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?
                                        //ciclo i clienti per prendermi i dati
                                        $result = file_get_contents("Import/Clienti.json");
                                        $array_result = json_decode($result, true);
                                        for($i=0;$i<count($array_result);$i++)
                                        {
                                            echo '<tr>
                                                    <td>'.$array_result[$i]["id"].'</td>
                                                    <td class="txt-oflo">'.$array_result[$i]["descrizione"].'</td>
                                                    <td>'.$array_result[$i]["data_inizio"].'</td>
                                                    <td class="txt-oflo">'.$array_result[$i]["data_fine"].'</td>
                                                    <td class="txt-oflo">€'.$array_result[$i]["rata_mensile"].'</td>
                                                    <td><span class="text-success">€'.$array_result[$i]["pagato"].'</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success mr-1 mb-1" class="waves-effect" data-toggle="modal" data-target="#serviceModal"
                                                                title="Servizi Attivi" onclick="checkServiziCliente('.$array_result[$i]["id"].',\''.$array_result[$i]["descrizione"].'\',\''.$array_result[$i]["data_inizio_orig"].'\',\''.$array_result[$i]["data_fine_orig"].'\')">
                                                            <i class="fa fa-gear"></i>
                                                        </button>
                                                    </td>
                                                </tr>';
                                        }
                                     ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2018 &copy; db_compare </footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>

    <!-- Modal servizi clienti -->
    <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="serviceModalTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                    
                    <div class="row">
                        <div class='col-sm-6'>
                            <div class="form-group">
                                <div class='input-group date' id='dtp_inizio' onclick="$('#dtp_inizio').datetimepicker({locale: 'it'});">
                                    <input type='text' class="form-control" id="data_inizio" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <div class="form-group">
                                <div class='input-group date' id='dtp_fine' onclick="$('#dtp_fine').datetimepicker({locale: 'it'});">
                                    <input type='text' class="form-control" id="data_fine" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="serviceModalTable"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>                                    
    <!-- FINE Modal servizi clienti -->
    <!-- Modal cambio password -->
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserisci la nuova password per la versione DEMO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Nuova Password</label>
                        <input type="password" class="form-control" id="nuova_password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Conferma Password</label>
                        <input type="password" class="form-control" id="conferma_password" placeholder="Password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-success" onclick="reset_password_demo()">Cambia Passowrd</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FINE Modal cambio password -->

    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- moment -->
    <script src="js/moment.min.js"></script>
    <script src="js/moment-with-locales.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!-- datepicket -->
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Counter js -->
    <script src="plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
    <script src="plugins/bower_components/counterup/jquery.counterup.min.js"></script>
    <!-- chartist chart -->
    <script src="plugins/bower_components/chartist-js/dist/chartist.min.js"></script>
    <script src="plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!-- Sparkline chart JavaScript -->
    <script src="plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="js/dashboard1.js"></script>
    <script src="plugins/bower_components/toast-master/js/jquery.toast.js"></script>
</body>

</html>
