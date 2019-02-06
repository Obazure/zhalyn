<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="/assets/css/main.css" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300italic,600italic,300,600">
			
		<script src="https://use.fontawesome.com/3ed9e3f52b.js"></script>
			
		<!--[if lte IE 9]><link rel="stylesheet" href="/assets/css/ie9.css" /><![endif]-->
		<noscript><link rel="stylesheet" href="/assets/css/noscript.css" /></noscript>
	
		<title>ОБЕД | <?=$data['title']?></title>

	    <meta name="description" content="<?=$data['description']?>" />
	    <meta name="keywords" content="<?=$data['keywords']?>" />
	
	    <meta name="author" content="conprefor@gmail.com"> 
	    
	    <style type="text/css">
		    #bg:after { background-image: url("/assets/images/bg<?=rand(1,7)?>.jpg");}
	    </style>
	    
    	<link rel="shortcut icon" href="data:image/png;base64,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">
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="content" style="margin-top: 25px;margin-bottom: 25px;">
								<nav>
									<ul>
										<?
											if(!empty($data['navbar'])){
												for ($i=0,$size=sizeof($data['navbar']);$i<$size;$i++)
													echo '<li><a href="'.$data['navbar'][$i]['link'].'">'.$data['navbar'][$i]['text'].'</a></li>';
											}
										?>
									</ul>
								</nav>
						
							
						</div>
					
						<? 
						if($data['showlogo']==true)
							echo '<div class="logo"><span class="icon fa-ravelry"></span></div>';
						?>
						
						<?=Route::MessageShow()?>
						
						
						
					</header> 



					<?
						if ($data['navbar'][0]['link']=='#signin')
						{
					?>
					<div id=main>
						
						<article id="signin">
							<h1 class="align-center">Войти</h1>
							<form method="post" action="/user/signinhandler">
							<div class="field half">
								<input type="text" name="name" required	 placeholder="Имя" />
							</div>
							<div class="field half">
								<input type="password" name="password" required	 placeholder="Пароль" />
							</div>
							<div class="field align-center">
								<input type="submit" value="Войти" class="" /></li>
							</div>	
							
							</form>		
							
						</article>
					</div>
					<?
						}
					?>
								
					<? include 'app/views/'.$content_view; ?>
							
					
				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; <a href="//cdx.kz"><span class="markit">CDX</span>.kz</a> - 2017</p>
					</footer>

			</div>

		<!-- BG -->
			<div id="bg"></div>

		<!-- Scripts -->
			<script src="/assets/js/jquery.min.js"></script>
			<script src="/assets/js/skel.min.js"></script>
			<script src="/assets/js/util.js"></script>
			<script src="/assets/js/main.js"></script>

	</body>
</html>