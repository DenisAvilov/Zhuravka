<?php
/**
 * Template name: лендинг для главной
 * Template Post Type: page
 * @package zhuravka
 */


get_header();?> 
<!-- sakura shader -->
 <script id="sakura_point_vsh" type="x-shader/x_vertex">
 
  uniform mat4 uProjection;
  uniform mat4 uModelview;
  uniform vec3 uResolution;
  uniform vec3 uOffset;
  uniform vec3 uDOF;  //x:focus distance, y:focus radius, z:max radius
  uniform vec3 uFade; //x:start distance, y:half distance, z:near fade start

  attribute vec3 aPosition;
  attribute vec3 aEuler;
  attribute vec2 aMisc; //x:size, y:fade

  varying vec3 pposition;
  varying float psize;
  varying float palpha;
  varying float pdist;

  //varying mat3 rotMat;
  varying vec3 normX;
  varying vec3 normY;
  varying vec3 normZ;
  varying vec3 normal;

  varying float diffuse;
  varying float specular;
  varying float rstop;
  varying float distancefade;

void main(void) {
    // Projection is based on vertical angle
    vec4 pos = uModelview * vec4(aPosition + uOffset, 1.0);
    gl_Position = uProjection * pos;
    gl_PointSize = aMisc.x * uProjection[1][1] / -pos.z * uResolution.y * 0.5;
    
    pposition = pos.xyz;
    psize = aMisc.x;
    pdist = length(pos.xyz);
    palpha = smoothstep(0.0, 1.0, (pdist - 0.1) / uFade.z);
    
    vec3 elrsn = sin(aEuler);
    vec3 elrcs = cos(aEuler);
    mat3 rotx = mat3(
        1.0, 0.0, 0.0,
        0.0, elrcs.x, elrsn.x,
        0.0, -elrsn.x, elrcs.x
    );
    mat3 roty = mat3(
        elrcs.y, 0.0, -elrsn.y,
        0.0, 1.0, 0.0,
        elrsn.y, 0.0, elrcs.y
    );
    mat3 rotz = mat3(
        elrcs.z, elrsn.z, 0.0, 
        -elrsn.z, elrcs.z, 0.0,
        0.0, 0.0, 1.0
    );
    mat3 rotmat = rotx * roty * rotz;
    normal = rotmat[2];
    
    mat3 trrotm = mat3(
        rotmat[0][0], rotmat[1][0], rotmat[2][0],
        rotmat[0][1], rotmat[1][1], rotmat[2][1],
        rotmat[0][2], rotmat[1][2], rotmat[2][2]
    );
    normX = trrotm[0];
    normY = trrotm[1];
    normZ = trrotm[2];
    
    const vec3 lit = vec3(0.6917144638660746, 0.6917144638660746, -0.20751433915982237);
    
    float tmpdfs = dot(lit, normal);
    if(tmpdfs < 0.0) {
        normal = -normal;
        tmpdfs = dot(lit, normal);
    }
    diffuse = 0.4 + tmpdfs;
    
    vec3 eyev = normalize(-pos.xyz);
    if(dot(eyev, normal) > 0.0) {
        vec3 hv = normalize(eyev + lit);
        specular = pow(max(dot(hv, normal), 0.0), 20.0);
    }
    else {
        specular = 0.0;
    }
    
    rstop = clamp((abs(pdist - uDOF.x) - uDOF.y) / uDOF.z, 0.0, 1.0);
    rstop = pow(rstop, 0.5);
    //-0.69315 = ln(0.5)
    distancefade = min(1.0, exp((uFade.x - pdist) * 0.69315 / uFade.y));
}
</script>
<script id="sakura_point_fsh" type="x-shader/x_fragment">

  #ifdef GL_ES
  //precision mediump float;
  precision highp float;
  #endif

  uniform vec3 uDOF;  //x:focus distance, y:focus radius, z:max radius
  uniform vec3 uFade; //x:start distance, y:half distance, z:near fade start

  const vec3 fadeCol = vec3(0.08, 0.03, 0.06);

  varying vec3 pposition;
  varying float psize;
  varying float palpha;
  varying float pdist;

  //varying mat3 rotMat;
  varying vec3 normX;
  varying vec3 normY;
  varying vec3 normZ;
  varying vec3 normal;

  varying float diffuse;
  varying float specular;
  varying float rstop;
  varying float distancefade;

  float ellipse(vec2 p, vec2 o, vec2 r) {
      vec2 lp = (p - o) / r;
      return length(lp) - 1.0;
  }

void main(void) {
    vec3 p = vec3(gl_PointCoord - vec2(0.5, 0.5), 0.0) * 2.0;
    vec3 d = vec3(0.0, 0.0, -1.0);
    float nd = normZ.z; //dot(-normZ, d);
    if(abs(nd) < 0.0001) discard;
    
    float np = dot(normZ, p);
    vec3 tp = p + d * np / nd;
    vec2 coord = vec2(dot(normX, tp), dot(normY, tp));
    
    //angle = 15 degree
    const float flwrsn = 0.258819045102521;
    const float flwrcs = 0.965925826289068;
    mat2 flwrm = mat2(flwrcs, -flwrsn, flwrsn, flwrcs);
    vec2 flwrp = vec2(abs(coord.x), coord.y) * flwrm;
    
    float r;
    if(flwrp.x < 0.0) {
        r = ellipse(flwrp, vec2(0.065, 0.024) * 0.5, vec2(0.36, 0.96) * 0.5);
    }
    else {
        r = ellipse(flwrp, vec2(0.065, 0.024) * 0.5, vec2(0.58, 0.96) * 0.5);
    }
    
    if(r > rstop) discard;
    
    vec3 col = mix(vec3(1.0, 0.8, 0.75), vec3(1.0, 0.9, 0.87), r);
    float grady = mix(0.0, 1.0, pow(coord.y * 0.5 + 0.5, 0.35));
    col *= vec3(1.0, grady, grady);
    col *= mix(0.8, 1.0, pow(abs(coord.x), 0.3));
    col = col * diffuse + specular;
    
    col = mix(fadeCol, col, distancefade);
    
    float alpha = (rstop > 0.001)? (0.5 - r / (rstop * 2.0)) : 1.0;
    alpha = smoothstep(0.0, 1.0, alpha) * palpha;
    
    gl_FragColor = vec4(col * 0.5, alpha);
}
</script>
<!-- effects -->
<script id="fx_common_vsh" type="x-shader/x_vertex">
  uniform vec3 uResolution;
  attribute vec2 aPosition;

  varying vec2 texCoord;
  varying vec2 screenCoord;

void main(void) {
    gl_Position = vec4(aPosition, 0.0, 1.0);
    texCoord = aPosition.xy * 0.5 + vec2(0.5, 0.5);
    screenCoord = aPosition.xy * vec2(uResolution.z, 1.0);
}
</script>
<script id="bg_fsh" type="x-shader/x_fragment">
  #ifdef GL_ES
  //precision mediump float;
  precision highp float;
  #endif

  uniform vec2 uTimes;

  varying vec2 texCoord;
  varying vec2 screenCoord;

void main(void) {
    vec3 col;
    float c;
    vec2 tmpv = texCoord * vec2(0.8, 1.0) - vec2(0.95, 1.0);
    c = exp(-pow(length(tmpv) * 1.8, 2.0));
    col = mix(vec3(0.02, 0.0, 0.03), vec3(0.96, 0.98, 1.0) * 1.5, c);
    gl_FragColor = vec4(col * 0.5, 1.0);
}
</script>
<script id="fx_brightbuf_fsh" type="x-shader/x_fragment">
    #ifdef GL_ES
    //precision mediump float;
    precision highp float;
    #endif
    uniform sampler2D uSrc;
    uniform vec2 uDelta;

    varying vec2 texCoord;
    varying vec2 screenCoord;

void main(void) {
    vec4 col = texture2D(uSrc, texCoord);
    gl_FragColor = vec4(col.rgb * 2.0 - vec3(0.5), 1.0);
}
</script>
<script id="fx_dirblur_r4_fsh" type="x-shader/x_fragment">
  #ifdef GL_ES
  //precision mediump float;
  precision highp float;
  #endif
  uniform sampler2D uSrc;
  uniform vec2 uDelta;
  uniform vec4 uBlurDir; //dir(x, y), stride(z, w)

  varying vec2 texCoord;
  varying vec2 screenCoord;

void main(void) {
    vec4 col = texture2D(uSrc, texCoord);
    col = col + texture2D(uSrc, texCoord + uBlurDir.xy * uDelta);
    col = col + texture2D(uSrc, texCoord - uBlurDir.xy * uDelta);
    col = col + texture2D(uSrc, texCoord + (uBlurDir.xy + uBlurDir.zw) * uDelta);
    col = col + texture2D(uSrc, texCoord - (uBlurDir.xy + uBlurDir.zw) * uDelta);
    gl_FragColor = col / 5.0;
}
</script>
<!-- effect fragment shader template -->
<script id="fx_common_fsh" type="x-shader/x_fragment">
  #ifdef GL_ES
  //precision mediump float;
  precision highp float;
  #endif
  uniform sampler2D uSrc;
  uniform vec2 uDelta;

  varying vec2 texCoord;
  varying vec2 screenCoord;

void main(void) {
    gl_FragColor = texture2D(uSrc, texCoord);
}
</script>
<!-- post processing -->
<script id="pp_final_vsh" type="x-shader/x_vertex">
  uniform vec3 uResolution;
  attribute vec2 aPosition;
  varying vec2 texCoord;
  varying vec2 screenCoord;
  void main(void) {
      gl_Position = vec4(aPosition, 0.0, 1.0);
      texCoord = aPosition.xy * 0.5 + vec2(0.5, 0.5);
      screenCoord = aPosition.xy * vec2(uResolution.z, 1.0);
  }
</script>
 <script id="pp_final_fsh" type="x-shader/x_fragment">
  #ifdef GL_ES
  //precision mediump float;
  precision highp float;
  #endif
  uniform sampler2D uSrc;
  uniform sampler2D uBloom;
  uniform vec2 uDelta;
  varying vec2 texCoord;
  varying vec2 screenCoord;
 void main(void) {
    vec4 srccol = texture2D(uSrc, texCoord) * 2.0;
    vec4 bloomcol = texture2D(uBloom, texCoord);
    vec4 col;
    col = srccol + bloomcol * (vec4(1.0) + srccol);
    col *= smoothstep(1.0, 0.0, pow(length((texCoord - vec2(0.5)) * 2.0), 1.2) * 0.5);
    col = pow(col, vec4(0.45454545454545)); //(1.0 / 2.2)
    
    gl_FragColor = vec4(col.rgb, 1.0);
    gl_FragColor.a = 1.0;
 }
</script>

<?php $count_posts = show_zhuravka_card_product();         ?>

<section class="s-king inverse sectionTest" id="card-product">
      <canvas id="sakura"></canvas>
         <div class="container  btnbg">                     
           <div class="row card-product-wrap">                        
                  
             <?php foreach( $count_posts as $post) : $ID = $post->ID ?>                             
                 <?php   if($ID == 117) : ?>
                <h2 class="h2"><?php echo the_title(); ?></h2>
             <p> <?php echo  $post->post_content; ?> </p>
             <?php  endif;  endforeach; ?>

             <div class="col-md-4">
              <article class="card-product-item">
              <?php foreach( $count_posts as $post) : $ID = $post->ID ?> 
                    <?php   if($ID == 114) : ?>          
                      <span class="card-product-img" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');"> </span>
                      <h3 class="h3"><?php echo the_title(); ?></h3>                     
                      <span class="card-count"><?php echo $post->post_excerpt;?></span>
                      <p> <?php echo  $post->post_content; ?> </p>
                      <a class="button" href="#">Записаться</a>
                      <?php  endif;  endforeach; ?> 
               </article>               
             </div>
             <div class="col-md-4">
              <article class="card-product-item">
              <?php foreach( $count_posts as $post) : $ID = $post->ID ?> 
                    <?php   if($ID == 115) : ?>                      
                      <span class="card-product-img" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');"> </span>
                      <h3 class="h3"><?php echo the_title(); ?></h3>                     
                      <span class="card-count"><?php echo $post->post_excerpt;?></span>
                      <p> <?php echo  $post->post_content; ?> </p>
                      <a class="button" href="#">Записаться</a>
                      <?php  endif;  endforeach; ?> 
               </article>               
             </div>
             <div class="col-md-4">
              <article class="card-product-item">
              <?php foreach( $count_posts as $post) : $ID = $post->ID ?> 
                    <?php   if($ID == 116) : ?>                      
                      <span class="card-product-img" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');"> </span>
                      <h3 class="h3"><?php echo the_title(); ?></h3>                     
                      <span class="card-count"><?php echo $post->post_excerpt;?></span>
                      <p> <?php echo  $post->post_content; ?> </p>
                      <a class="button" href="#">Записаться</a>
                      <?php  endif;  endforeach; ?> 
               </article>               
             </div>                         
            </div>
         </div>  
         
</section>
<section class="lid-magnit">
 <?php if ( have_posts() ) : query_posts('p=118');
      while (have_posts()) : the_post(); ?>
    <div class="lid-magnit-img" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">
    
</div>
    <?php endwhile; endif; wp_reset_query(); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                  <article class="lid-magnit-content">
                      <header>
                      <?php if ( have_posts() ) : query_posts('p=118');
      while (have_posts()) : the_post(); ?>
                          <h1><?php echo the_title(); ?></h1>
                          
                          <span class="lid-magnit-title" ><? the_excerpt(); ?></span>
                      </header> 
                       <?php  the_content(); ?>
                       <?php endwhile; endif; wp_reset_query(); ?>
                       <a class="button" href="#">Записаться</a>  
                  </article>
            </div>
        </div>
    </div>
</section>
<?php if ( have_posts() ) : query_posts('p=15');
      while (have_posts()) : the_post(); ?>
<section class="s-king inverse section-overly" id="skil-slide-background" style="background-image: url('<?php echo get_the_post_thumbnail_url();?>')">
     <div class="container">           
                <div class="row">
                    <div class="col-md-5">                      
                       <h2 class="h2"> <?php the_title();?> </h2>
                       <?php the_content();?>
                       <?php endwhile; endif; wp_reset_query(); ?>
                    </div> 
                <?php $posts = show_zhuravka_slider_job(); ?>
                    <div class="col-md-7">
                           <div class="section-gallery fotorama" id="slider-job" data-nav="thumbs" data-thumbwidth="110.5" 
                                    data-thumbheights="70" data-thumbborderwidth="5" data-thumbmargin="10"  data-arrows="true" data-width="100%">
                                  <?php foreach( $posts as $post) :  ?> 
                                    <a href="<?php echo get_the_post_thumbnail_url( );?>"><img  src="<?php echo CFS()->get('img_mini');?>" alt="макияж без макияжа">
                                    </a>
                                  <?php endforeach;  ?>
                                    
                           </div>   
                    </div>  
                </div> 
                
     </div>
</section>

<section class="offer inverse section-overly" id="offer" style="background-image: url(https://nedorogoy.site/wp-content/uploads/2017/11/kak-raskrutit-salon-krasoty.jpg)">
             <div class="container">
                 <div class="row">
                 <article class="offer-title">
                     <header><h2 class="h2">Услуги</h2> 
                     <p>Моя команда квалифицированных мастеров преследует прекрасную цель -</br>сделать как можно больше людей красивее, ярче и привлекательнее.</p>
                    </header>         
                     
                 </article>         
                     <div class="col-sm-12">  
                       <div class="offer-wrap"> 
                         <article class="offer-item" style="background-image: url('data:image/jpeg;base64,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');">
                             <h2>Макияж</h2>
                             <p>Это удивительный способ быстро изменить свою внешность и соответствовать своим видом времени и месту.</p> 
                             <a href="#" class="offer-button">300 ₴</a>      
                         </article>
                         <article class="offer-item" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRcB0uonIyOmC9YcZO36nINKhUFnKAc_CMf3WRcrVhWVVVeX3bf2Q&s');">
                             <h2>Прически</h2>
                             <p>Получи прическу любой сложности: свадебную, вечернею, небрежные пучки, голливудскую волну.</p> 
                             <a href="#" class="offer-button">300 ₴</a>      
                         </article>                    
                                        
                         <article class="offer-item" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJdBZTDUjMnROieFKHDULW0-Die1MAbATMdqdjpGZbpcq9-DdD&s');">
                             <h2>Коррекция бровей</h2>
                             <p>Брови это рамка лица. Рамка внутри котоой наше лицо меняет свою форму, размеры и характер.</p> 
                             <a href="#" class="offer-button">300 ₴</a>      
                         </article>
                         <article class="offer-item" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRcB0uonIyOmC9YcZO36nINKhUFnKAc_CMf3WRcrVhWVVVeX3bf2Q&s');">
                             <h2>Ламинирование ресниц</h2>
                             <p>Несомненный плюс - стойкость процедуры:  эффект продержится полтора-два месяца.</p> 
                             <a href="#" class="offer-button">300 ₴</a>      
                         </article>                    
                        </div>
                     </div>
                 </div>
              </div>
</section>
<?php $posts = show_zhuravka_slider_top();  ?>
<section class="home-slider-top"  id="zhuravka-content">
             <h2 class="h2"> Мои дипломы</h2>
             <div  class="owl-carousel  owl-theme carousel-services">            
                <?php foreach( $posts as $post ){ setup_postdata($post); ?>
                <div class="carousel-services-item">                                                                                                       
                      <div class="carousel-services-content  carousel-content-brow brow-test" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">
                          <div class="carousel-services-composition" >
                            <h3 class="h3"><?php //the_title(); ?></h3>                      
                              <?php  //the_content(); ?>
                          </div>
                      </div>
                      <div class="carousel-services-img" style="background-image: url('<?php echo CFS()->get('skill_background'); ?>');"></div>
                </div>
                <?php }   wp_reset_postdata();  ?>     
            </div>    
</section> 

<?php $posts_reviews = show_zhuravka_slider_reviews(); ?>
<?php foreach( $posts_reviews as $post) : $ID = $post->ID ?>                             
                 <?php   if($ID == 149) : ?>
<section class="s-reviews inverse section-overly" id="s-reviews" style="background-image: url('<?php echo get_the_post_thumbnail_url();?>')">
             <div class="container">
                 <div class="row">
                     <div class="col-sm-12">                   
                <h2 class="h2"><?php echo the_title(); ?></h2>                    
                      <?php  endif;  endforeach; ?> 
                     </div>
                             <div class="col-sm-8 col-sm-offset-2">
                                 <div class="quotes"></div>
                                 <div class="owl-carousel reviews">
                                 <?php foreach( $posts_reviews as $post) : ?>                
                                     <div class="review">
                                        <div class="review-header"> <?php echo CFS()->get( 'name');?></div>
                                        <div class="review-date"><?php echo CFS()->get( 'date');?></div>
                                        <div class="review-stars">
                                            <div class="stars">
                                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                            </div>
                                        </div>
                                        <div class="review-text">
                                            <?php  echo $post->post_content; ?>                                       
                                        </div>
                                    </div>
                                    <?php  endforeach; ?> 
                                    
                                 </div>

                     </div>
                 </div>
              </div>
</section>


      
 

        <?php get_footer(); ?>

