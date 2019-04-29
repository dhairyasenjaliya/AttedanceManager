<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Welcome Back | {{ Auth::user()->name }}</title>
  <link rel="shortcut icon" href="./image/favicon.png">
  <link rel="stylesheet" href="/css/app.css">

  <link rel="stylesheet" href="/css/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/css/daterangepicker-bs3.css">
<style>
 @import "https://cdn.jsdelivr.net/npm/animate.css@3.5.1";
.defaultImage{
  height:50px;
  width:50px; 
 

  #app {
    background: #fff;
    width: 50%;
    padding: 30px;
    border-radius: 10px;
    margin: 50px auto 0 auto;
    height: calc(60% - 50px);
  }

  .page {
    position: fixed;
    width: inherit;
  } 
}

</style>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars red"></i></a>
      </li>
      
    </ul>
    @can('isAdmin') 
    <!-- SEARCH FORM -->    
      <div class="input-group input-group-sm">
      <div class="input-group-append">
        <input class="form-control form-control-navbar" @keyup="searchIt" v-model="search" type="search" placeholder="Search" aria-label="Search">        
          <button class="btn btn-navbar" @click="searchIt">
            <i class="fas fa-search red"></i>
          </button>
        </div>     
      </div> 
      @endcan 

      @can('isUser') 
    <!-- SEARCH FORM -->
    <div class="input-group input-group-sm">
      <div class="input-group-append">    
        <h3>Keep your Attedance UpToDate</h3> 
      </div>
    </div>
      @endcan   
      <i class="fas fa-power-off red"></i>
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
           {{ __('Logout') }}   
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>  
        </a> 

        
        
  </nav>
  <!-- /.navbar -->
  
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link to='/dashboard' class="brand-link">
      <img src="./image/9s.png" alt="main" class="brand-image "
           style="opacity: .8">
      <span class="brand-text font-weight-light">9'$tack</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="<?php echo asset('./image/profile') . '/' . Auth::user()->photo  ?>" alt="Upload">
        </div>
        <div class="info">
        <!-- <router-link to='/profile' class="d-block" >                            -->
                <a href="#" class="d-block" >   {{ Auth::user()->name }} </a>
        <!-- </router-link> -->
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <router-link to='/Dashboard' class="nav-link " >
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <span class="right badge badge-danger">New</span>
              </p>
            </router-link>
          </li>     
               
          <li class="nav-item">
            <router-link to='/Timesheet' class="nav-link " >
            <i class="nav-icon fas fa-calendar-check"></i>
              <p>
                Timesheet 
              </p>
              
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to='/ActiveDeveloper' class="nav-link " >
            <i class="nav-icon fas fa-bomb"></i>
              <p>
                Active Developer 
              </p>
            </router-link>
          </li>
          @can('isAdmin')
            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon  fas fa-cogs"></i>
              <p>
                 Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <router-link to='/users' class="nav-link">
                <i class="fas fa-users"></i>
                  <p>Users </p>
                </router-link>
              </li>             
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <router-link to='/timesheetmanager' class="nav-link">
              <i class="fas fa-tasks"></i>
                  <p>Timesheet Manager </p>
                </router-link>
              </li>             
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <router-link to='/leavemanager' class="nav-link">
              <i class="fas fa-glass-cheers"></i>
                  <p>Leave Manager </p>
                </router-link>
              </li>             
            </ul>
          </li>   
          @endcan     
          <li class="nav-item">
            <router-link to='/profile' class="nav-link ">
              <i class="nav-icon  fas fa-user"></i>
              <p>
                Profile
              </p>
            </router-link>
          </li>
          <!-- @can('isAdmin')
          <li class="nav-item">
            <router-link to='/developer' class="nav-link ">
              <i class="nav-icon fab fa-connectdevelop"></i>
              <p>
                Developers
              </p>
            </router-link>
          </li>
          @endcan   -->
                       
          </li>          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="content">  

        <transition name="router-anim" enter-active-class="animated fadeInDown" leave-active-class="animated fadeOutDown">
            <router-view>  </router-view> 
        </transition>
        
        <vue-progress-bar></vue-progress-bar> 
      </div>
</div>
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
     Contact US
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2018-2019 <a href="#">Dhairya</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->


<!--  for authentic user -->
@auth
<script>
    window.user = @json(auth()->user())
</script>
@endauth


<!-- REQUIRED SCRIPTS --> 
<script src="/js/app.js"></script> 
<script src="/js/daterangepicker.js"></script>
<!-- datepicker -->
<script src="/js/bootstrap-datepicker.js"></script>
 

</body>
</html>
