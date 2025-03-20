<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PrendaSol | Inicio</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/css-loader.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    {{-- <link rel="stylesheet" href="{{ asset('template/dist/css/skins/_all-skins.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('template/dist/css/skins/skin-blue.css') }}">

    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/morris.js/morris.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="{{ asset('template/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="{{ asset('template/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('template/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/bower_components/sweetalert2/dist/sweetalert2.min.css') }}">
    <style type="text/css">
        .swal2-popup {
            font-size: 1.6rem !important;
        }

        .logo:hover {
            background: rgb(39, 39, 39) !important;
        }

        .txt_info_span_plazo_pagos {
            font-weight: bold;
            margin-left: 6px;
        }

        .bg-rojo {
            background: rgb(172, 1, 1);
            color: white;
        }

        .bg-amarillo {
            background: rgb(233, 183, 19);
            color: black;
        }

        .font-weight-bold {
            font-weight: bold;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="index2.html" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>A</b>LT</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Prenda</b>SOL</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" style="background:rgb(39, 39, 39);">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                @if (Session::get('SEXO') == 5)
                                    <img src="{{ asset('template/dist/img/avatar5.png') }}" class="user-image"
                                        alt="User Image">
                                @endif
                                @if (Session::get('SEXO') == 6)
                                    <img src="{{ asset('template/dist/img/avatar3.png') }}" class="user-image"
                                        alt="User Image">
                                @endif

                                @if (Session::get('SEXO') == '')
                                    <img src="{{ asset('template/dist/img/photo4.jpg') }}" class="user-image"
                                        alt="User Image">
                                @endif
                                <span class="hidden-xs">{{ Session::get('NOMBRE_COMPLETO') }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    {{-- <img src="{{ asset('template/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image"> --}}
                                    @if (Session::get('SEXO') == 5)
                                        <img src="{{ asset('template/dist/img/avatar5.png') }}" class="img-circle"
                                            alt="User Image">
                                    @endif
                                    @if (Session::get('SEXO') == 6)
                                        <img src="{{ asset('template/dist/img/avatar3.png') }}" class="img-circle"
                                            alt="User Image">
                                    @endif

                                    @if (Session::get('SEXO') == '')
                                        <img src="{{ asset('template/dist/img/photo4.jpg') }}" class="img-circle"
                                            alt="User Image">
                                    @endif

                                    <p>
                                        {{ Session::get('NOMBRE_COMPLETO') }}
                                        <small>2019</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                {{-- <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li> --}}
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ url('Perfil') }}" class="btn btn-default btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ route('salirSesion') }}" class="btn btn-default btn-flat">Cerrar
                                            Sesión</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    @if (Session::get('SEXO') == 5)
                        <div class="pull-left image">
                            <img src="{{ asset('template/dist/img/avatar5.png') }}" class="img-circle"
                                alt="User Image">
                        </div>
                    @endif
                    @if (Session::get('SEXO') == 6)
                        <div class="pull-left image">
                            <img src="{{ asset('template/dist/img/avatar3.png') }}" class="img-circle"
                                alt="User Image">
                        </div>
                    @endif

                    @if (Session::get('SEXO') == '')
                        <div class="pull-left image">
                            <img src="{{ asset('template/dist/img/photo4.jpg') }}" class="img-circle" alt="User Image">
                        </div>
                    @endif


                    <div class="pull-left info">
                        <p>{{ Session::get('USUARIO') }} </p>
                        <a href="#"><i class="fa fa-circle text-success"></i> En Línea:
                            {{ Session::get('CAJA') }}</a>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->

                @if (Session::get('ID_ROL') == 1 || Session::get('ID_ROL') == 3)
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MENU PRINCIPAL</li>
                        <li><a href="{{ url('Inicio') }}"><i class="fa fa-circle-o text-aqua"></i>
                                <span>INICIO</span></a></li>
                        @inject('menuMaster', 'App\Http\Controllers\Administracion\MenuController')
                        @if ($menuMaster->menuMaster(Session::get('ID_ROL')))
                            @foreach ($menuMaster->menuMaster(Session::get('ID_ROL')) as $mm)
                                <li class="active treeview">
                                    <a href="#">
                                        <i class="fa fa-dashboard"></i> <span>{{ $mm->modulo }}</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        @if ($menuMaster->subMenus($mm->id))
                                            @foreach ($menuMaster->subMenus($mm->id) as $sm)
                                                <li><a href="{{ url($sm->url) }}"><i class="fa fa-circle-o"></i>
                                                        {{ $sm->opcion }}</a></li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                @else
                    {{-- {{ $datoInicioFinCaja->fecha_cierre }} --}}
                    @isset($datoInicioFinCaja->fecha_hora)
                        @if (!$datoInicioFinCaja->fecha_cierre)
                            @isset($datoInicioFinCaja->fecha_hora)
                                <ul class="sidebar-menu" data-widget="tree">
                                    <li class="header">MENU PRINCIPAL</li>
                                    <li><a href="{{ url('Inicio') }}"><i class="fa fa-circle-o text-aqua"></i>
                                            <span>INICIO</span></a></li>
                                    @inject('menuMaster', 'App\Http\Controllers\Administracion\MenuController')
                                    @if ($menuMaster->menuMaster(Session::get('ID_ROL')))
                                        @foreach ($menuMaster->menuMaster(Session::get('ID_ROL')) as $mm)
                                            <li class="active treeview">
                                                <a href="#">
                                                    <i class="fa fa-dashboard"></i> <span>{{ $mm->modulo }}</span>
                                                    <span class="pull-right-container">
                                                        <i class="fa fa-angle-left pull-right"></i>
                                                    </span>
                                                </a>
                                                <ul class="treeview-menu">
                                                    @if ($menuMaster->subMenus($mm->id))
                                                        @foreach ($menuMaster->subMenus($mm->id) as $sm)
                                                            <li><a href="{{ url($sm->url) }}"><i class="fa fa-circle-o"></i>
                                                                    {{ $sm->opcion }}</a></li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            @endisset
                        @endif
                    @endisset
                @endif

            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">


            <!-- Main content -->
            <section class="content">
                @isset($opciones)
                    @if ($opciones == 1)
                        <!-- Content Header (Page header) -->
                        <section class="content-header">
                            <h1>
                                Principal
                                <small>Datos Generales</small>
                            </h1>

                        </section>
                        <!-- Small boxes (Stat box) -->
                        <div class="row">
                            @if ($countInicioFinCaja == 0)
                                <div class="col-lg-3 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3>URGENTE</h3>

                                            <p>No existe monto para iniciar la caja</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-pie-graph"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @isset($datoInicioFinCaja->fecha_hora)
                                @if (!$datoInicioFinCaja->fecha_cierre)
                                    @if (isset($datoInicioFinCaja->fecha_hora))
                                        <div class="col-lg-3 col-xs-6">
                                            <!-- small box -->
                                            <div class="small-box bg-aqua">
                                                <div class="inner">
                                                    <h3>Inicio Caja</h3>

                                                    <p>{{ $datoInicioFinCaja->fecha_hora }}</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-bag"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-green">
                                            <div class="inner">
                                                <h3>CAJA CERRADA<sup style="font-size: 20px"></sup></h3>

                                                <p>{{ $datoInicioFinCaja->fecha_cierre }}</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-stats-bars"></i>
                                            </div>
                                            {{-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
                                        </div>
                                    </div>
                                @endif
                            @endisset

                            <!-- ./col -->

                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>44</h3>

                                        <p>User Registrations</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- ./col -->

                            <!-- ./col -->
                        </div>
                        <!-- /.row -->
                    @endif
                @endisset
                <div class="loader" data-text="Cargando"></div>
                @yield('main-content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; {{ date('Y') }}</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-user bg-yellow"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                    <p>New phone +1(800)555-1234</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                    <p>nora@example.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-file-code-o bg-green"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                    <p>Execution time 5 seconds</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Update Resume
                                    <span class="label label-success pull-right">95%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Laravel Integration
                                    <span class="label label-warning pull-right">50%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Back End Framework
                                    <span class="label label-primary pull-right">68%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Allow mail redirect
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Other sets of options are available
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Expose author name in posts
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Allow the user to show his name in blog posts
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <h3 class="control-sidebar-heading">Chat Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Show me as online
                                <input type="checkbox" class="pull-right" checked>
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Turn off notifications
                                <input type="checkbox" class="pull-right">
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Delete chat history
                                <a href="javascript:void(0)" class="text-red pull-right"><i
                                        class="fa fa-trash-o"></i></a>
                            </label>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{ asset('template/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('template/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('template/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Morris.js charts -->
    <script src="{{ asset('template/bower_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('template/bower_components/morris.js/morris.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('template/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
    <!-- jvectormap -->
    <script src="{{ asset('template/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('template/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('template/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('template/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- datepicker -->
    <script src="{{ asset('template/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <!-- CAMARA -->
    <script src="{{ asset('template/dist/js/camara.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('template/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- Slimscroll -->
    <script src="{{ asset('template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('template/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <!-- InputMask -->
    <script src="{{ asset('template/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('template/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('template/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    {{-- JAVA SCRIPR SOLO NUMEROS --}}
    <script type="text/javascript" src="{{ asset('template/dist/js/jquery.numeric.min.js') }}"></script>
    {{-- JAVA SCRIPT VALIDAR FORMULARIOS --}}
    <script type="text/javascript" src="{{ asset('template/dist/js/bootstrapValidator.js') }}"></script>
    {{-- JAVA SCRIPT SWEET ALERT --}}
    <script type="text/javascript" src="{{ asset('template/bower_components/sweetalert2/dist/sweetalert2.min.js') }}">
    </script>
    <!-- Select2 -->
    <script src="{{ asset('template/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>

    {{-- AJUSTE DECIMAL --}}
    <script>
        function ajustaDecimal(numero) {
            let aux = parseFloat(numero).toFixed(2);
            numero = aux;
            numero = numero.toString();
            // console.log(numero);
            let array_numero = numero.split(".");
            let array_decimales = [0, 0];
            if (array_numero[1]) {
                array_decimales = array_numero[1].split("");
            }
            let numero_ajustado = "";
            if (parseFloat(array_decimales[1]) > 0) {
                array_decimales[0] = parseInt(array_decimales[0]) + 1;
                array_decimales[1] = 0;
                if (array_decimales[0] >= 10) {
                    array_numero[0] = parseInt(array_numero[0]) + 1;
                    array_decimales[0] = 0;
                    array_decimales[1] = 0;
                }
            }
            numero_ajustado = `${array_numero[0]}.${array_decimales[0]}${array_decimales[1]}`;
            return numero_ajustado;
        }
        // PRUEBAS
        // console.log(ajustaDecimal("99.93"));//100
        // console.log(ajustaDecimal("99.43"));//99.50
        // console.log(ajustaDecimal("99.13"));//99.20
        // console.log(ajustaDecimal("99.03"));//99.10
        // console.log(ajustaDecimal("99.00"));//99.00
        // console.log(ajustaDecimal("247.85"));//99.00
        // console.log(ajustaDecimal("247.43"));//99.00
    </script>
    @yield('scripts')

    {{-- <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('template/dist/js/pages/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('template/dist/js/demo.js') }}"></script> --}}
    {{-- <script type="text/javascript">    
    $(document).ready(function() {
      //alert("Principal");
      $('form').keypress(function(e){   
        if(e == 13){
          return false;
        }
      });
    })
</script> --}}
</body>

</html>
