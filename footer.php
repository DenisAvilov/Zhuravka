<?php
/**
 * @package zhuravka
 */
?>

</div><!-- #content -->

<div id="zhuravka-footer">
<?php $posts = show_zhuravka_header();?>  
    <?php foreach( $posts as $post ): ?>
        <div class="main-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-sm-4 col-xs-12">
                        <div class="footer-column">
                           <?php echo get_custom_logo( );?> 
                           <p><?php echo CFS()->get('title_logo_footer');?></p>
                            <div class="footer-social">          
                                
                                <?php
	                              if ( function_exists('dynamic_sidebar') )
	                                 	dynamic_sidebar('icons-1');
                        	     ?>   
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-xs-12">
                        <div class="footer-column">
                            <nav class="footer-menu">
                            <?php wp_nav_menu( [
                               'меню на главной' => 'main-menu', 
                               'container'       => 'ul', 
                           ] ); ?>  
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-xs-12">
                        <div class="footer-column">
                            <p class="footer-addr" ><i class="fa fa-location-arrow"></i> <?php echo CFS()->get('citi');?><br> </p>
                               <p class="footer-addr"><i class="fa fa-clock-o"></i> <?php echo CFS()->get('business_hours');?></p>
                               <p class="footer-addr"><span class="footer-phone"><i class="fa fa-mobile"></i> <a href="tel:<?php echo CFS()->get('telefon');?>"><?php echo CFS()->get('telefon');?></a></span></p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <div class="footer-column">
                       <div class="map" id="contack">
                           
                       </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xs-12">
                        <div class="copyright" style="text-align: center">Site completed &#xA9; <a href="https://www.facebook.com/avilovd.a">Denis Avilov</a> 2017</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    
</div>


<?php wp_footer(); ?>
</body>
</html>
