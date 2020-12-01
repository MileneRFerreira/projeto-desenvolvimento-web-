<?php include_once("lib/includes.php");?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS SCRIPTS -->
    <base href="http://localhost/forum/">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="css/style.css"/>

    <!-- JS SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="js/scripts.js"></script>


    <title>AaaaaA</title>
  </head>
  <body>
		<div class="main-title">
			<div class="row">
				<div class="col-sm-8">
					<img src="images/topheaderlogo.png" class="img-fluid">
				</div>

				<div class="col-sm-4">
					<ul align="right">
						<li><a href=""><i class="fas fa-home"></i><span class="badge">10</span></a></li>
						<li><a href=""><i class="fas fa-envelope"></i><span class="badge">10</span></a></li>
						<li><a href=""><i class="fas fa-user"></i><span class="badge">10</span></a></li>
						<li><a href=""><i class="fas fa-bell"></i><span class="badge">10</span></a></li>
						<li><a href=""><i class="fas fa-power-off"></i><span class="badge">10</span></a></li>
					</ul>
				</div>
			</div>
		</div>
	    <div class="main-menu">
	      	<div class="row">
		      	<div class="col-sm-9">
			  		<nav aria-label="breadcrumb">
					  <ol class="breadcrumb">
					    <li class="breadcrumb-item"><a href="inicio/"><i class="fas fa-home"></i> Home</a></li>
					    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list-ul"></i> Library</li>
					  </ol>
					</nav>
		      	</div>
		      	<div class="col-sm-3 form-inline">
		      		<div class="form-group">
		      			<form method="POST" action="buscar/">
		      				<input type="text" class="form-control">
		      			
		      		</div>
		      		<div class="form-group">
		      				<button type="submit" class="btn btn-secoundary"><i class="fas fa-search"></i></button>
		      				<input type="hidden" name="env" value="form">
		      			</form>
		      		</div>
		      		<div class="form-group">
	      				<button type="button" class="btn btn-secoundary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btnGroupDrop1"><i class="fas fa-cog"></i></button>
	      				<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					      <a class="dropdown-item" href="#">Dropdown link</a>
					      <a class="dropdown-item" href="#">Dropdown link</a>
					    </div>
		      		</div>
		      	</div>
	      	</div>
	    </div>

<div class="chatbox-full">
	<?php $forum->get_chatbox();?>
</div>

	<div class="clearfix"></div>
	 
   <div class="row">
	<div class="col-sm-9">
	  	<?php
	        $url = (isset($_GET['pagina'])) ? $_GET['pagina'] : 'inicio';
	        $explode = explode('/', $url);
	        $dir = "pags/";
	        $ext = ".php";

	        if(file_exists($dir.$explode['0'].$ext)){
	          include($dir.$explode['0'].$ext);
	        }else{
	          echo "<div class='col-sm-5 offset-md-3 alert alert-danger'>Página não encontrada!</div>";
	        }
	      ?>
	</div>

	<div class="col-sm-3">
			
	</div>
</div>

    <!--JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  </body>
</html>