<?php
/*
* Template Name: Код кнопки
*/
?>
	
<?php get_header(); ?>

<?php the_post(); ?>


	<div id="content" class="fourteen columns">
		
		<?php the_title( '<h1 class="entry-title center">', '</h1>' ); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			

			
				<div class="entry-content ten columns">
					<div class="dp100 ">
						<?php the_content(); ?>
					</div>
				</div>							
						

				<!-- informer view -->
				<div class="ten columns center">
					<div id="inner-informer-button">
						<script type="text/javascript"> var widget_embed = 'button'; var size_button = 999; </script>
					</div>
					<div id="base-inner-informer-button">
						<script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script>
					</div>
					<div id="embed-widget-container2"></div>
				</div>

<!-- informer setting -->


<div class="entry-content ten columns informer-setting">

	<div class="informer-title"><span class="meta-autor">Раз.</span> Настройте желаемый вид</div>

	<div class="three columns informer-col1">
	<form class="informer-form b-form" _lpchecked="1">

		<label>Тип кнопки</label>
		    <select class="change-informer-button button-size" name="button-size">
		        <option value="1" selected="selected">88х33</option>
		        <option value="2">88х15</option>
		        <option value="3">31х31</option>
		    </select>
		    <label>Цвет</label>
				<input type="text" class="change-informer-button button-color-val button-color" name="button-color"  value="263ce3">
	</div>
	<div class="seven columns informer-col2">
		<div class="dp80">

		</div>

	</form>
	</div><!-- end seven col 2-->
</div> <!-- end entry-content ten columns -->

<div class="clear"></div> 
<!-- end informer settings -->

				<!-- informer code -->
				
				<div class="entry-content ten columns">
					<div class="informer-title"><span class="meta-autor">Два.</span> Скопируйте готовый код к себе на сайт</div>
					<h6 class="main-title informer-h6">Персональный код информера</h6>
					<pre id="xmp-informer-button">
					    <xmp><!-- код информера lednews.ru --><script type="text/javascript"> var widget_embed = 'button';var size_button = 1;</script><script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script><div id="embed-widget-container2"></div><!-- end код информера lednews.ru --></xmp>
					 </pre>
				</div>


				<div class="entry-content">
					<div class="dp100 ">
						<div class="bottom-text center">
							<p class="text-strong">У Вас есть к нам вопросы или предложения?</p>
							<p>Пишите нам по адресу <a href="mailto:news@lednews.ru" target="_top">news@lednews.ru</a></p>
						</div>

					</div>
				</div>
			
		</div>


	</div> <!-- end content -->


<?php get_footer(); ?>