<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Duranto Shop Management System</title>
        <link rel="shortcut icon" type="image/x-icon" href="icons/favicon.png" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <style type="text/css">
            .mt20{
                margin-top:20px;
            }
            .bold{
                font-weight:bold;
            }

            /* chart style*/
            #legend ul {
                list-style: none;
            }

            #legend ul li {
                display: inline;
                padding-left: 30px;
                position: relative;
                margin-bottom: 4px;
                border-radius: 5px;
                padding: 2px 8px 2px 28px;
                font-size: 14px;
                cursor: default;
                -webkit-transition: background-color 200ms ease-in-out;
                -moz-transition: background-color 200ms ease-in-out;
                -o-transition: background-color 200ms ease-in-out;
                transition: background-color 200ms ease-in-out;
            }

            #legend li span {
                display: block;
                position: absolute;
                left: 0;
                top: 0;
                width: 20px;
                height: 100%;
                border-radius: 5px;
            }
            .table tr td ul, .table tr td ul li{
                margin:0;
                padding:0;
                margin-left:5px;
            }
            
            #loading {
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                position: fixed;
                display: block;
                opacity: 0.7;
                background-color: #fff;
                z-index: 1450;
                text-align: center;
            }
            
            #loading-image {
                position: absolute;
                top: 48%;
                left: 48%;
                z-index: 1500;
            }
            .list-group-flush > .list-group-item {
            border-width: 0 0 1px;
            }
            .list-group-itemNotice {
                position: relative;
                display: block;
                padding: .5rem 1rem;
                text-decoration: none;
                margin-bottom: -1px;
                background-color: #fff;
               /* width: 150%;*/
                border: 1px solid rgba(0,0,0,.125);
                    border-top-width: 1px;
                    border-right-width: 1px;
                    border-bottom-width: 1px;
                    border-left-width: 1px;
            }
        </style>
    </head>