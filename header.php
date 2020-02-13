<?php
/**
 * @package zhuravka
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <meta name="apple-mobile-web-status-bar-style" content="#1a1a1a">
    <meta name="description" content="Описание страницы сайта.">
    <title >Красивые реснички,брови.Ваш мастер визажист</title>
   <!-- <style>body{opacity: 0; overflow-x: hidden; }html{background-color: #1a1a1a}
    </style> -->
  
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:100,300,400,500,600,700,800,900&amp;subset=cyrillic" rel="stylesheet">
  
	
	<!--[if lt IE 8]>
       <div style=' clear: both; text-align:center; position: relative;'>
         <a href="http://windows.microsoft.com/en-US/internet-explorer/Items/ie/home?ocid=ie6_countdown_bannercode">
           <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
      </div>
    <![endif]-->
    <!--[if lt IE 9]>
		<script src="js/html5.js"></script>
		<script src="js/css3-mediaqueries.js"></script>
	<![endif]-->

	<?php wp_head(); ?>
</head>
<body class="ishome" <?php get_body_class() ?> >
<?php $posts = show_zhuravka_header();?>  
<div id="my-page">
<?php foreach($posts as $post): ?> 
<div id="zhuravka-header">
        <header class="site-header" style="background-image: url('<?php echo get_the_post_thumbnail_url( $post->ID, 'large'); ?>') ;">
                     <div class="top-line">                     
                       <?php echo get_custom_logo( );?>                                                 
                                                       
                         <div class="phone hidden-xs">
                             <i class="fa fa-mobile"></i>
                             <a href="tel:<?php echo CFS()->get('telefon');?>"><?php echo CFS()->get( 'telefon');?></a>
                        </div>                  
                        
                         <a   href="#my-menu" class="hamburger hamburger--emphatic">
                              <span class="hamburger-box">
                                  <span class="hamburger-inner"> </span>
                              </span>
                         </a>
                         <nav id="my-menu"> 
                         <?php echo get_custom_logo( ); ?>                             
                            <?php wp_nav_menu( [
                               'меню на главной' => 'main-menu', 
                               'container'       => 'ul', 
                           ] ); ?>    
                        
                         </nav>
                     </div>

                     <div class="header-flex">
                         <div class="flex-center">

                             <div class="container">
                                 <div class="row">
                                     <div class="col-sm-7 col-xs-9">

                                         <div class="header-composition">
                                           <div class="welcome">                                      
                                               <p><span ><?php echo $post->post_title;?></span></p>
                                               <h1 class="h1 "><?php echo get_bloginfo('name' );?></h1>  
                                               <strong></i><?php echo CFS()->get('top_slogan');?> </strong>                    
                                           </div>
                                           <p><?php echo $post->post_content?></p>     
                                         </div>
                                     </div>
                                 </div>
                             </div>

                         </div>
                     </div>
                     <div class="header-social">                       
                         <?php
	                              if ( function_exists('dynamic_sidebar') )
	                                 	dynamic_sidebar('icons-1');
                        	?>                       
                    </div>
                     <div class="header-contacts">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12 ">
                            <i class="fa fa-location-arrow"></i><?php echo CFS()->get( 'citi');?> 
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12 ">
                           <div class="phone-h"><i class="fa fa-mobile"></i><a href="tel:<?php echo CFS()->get( 'telefon');?>"><?php echo CFS()->get( 'telefon');?></a></div>
                        </div>
                        <div class="col-md-5 hidden-sm hidden-xs">
                            <i class="fa fa fa-clock-o"></i><?php echo CFS()->get('business_hours');?>
                        </div>
                    </div>
                </div>
            </div> 
        </header>
    </div>
    <?php endforeach;?>
    <div id="content">
   