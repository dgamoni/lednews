<?php 
	/**
	 * The Template for displaying informer page
	 *
	 */
	
get_header(); 
?>

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

				<div class="center">
					<div id="inner-informer">
						<script type="text/javascript"> var widget_embed = 'posts'; var posttype = 'news'; var count =3; </script>
					</div>
					<div id="base-inner-informer">
						<script src="http://test.lednews.ru/widget/wp-widget.js" type="text/javascript"></script>
					</div>
					<div id="embed-widget-container"></div>
				</div>

<!-- informer setting -->


<div class="entry-content ten columns informer-setting">

	<div class="informer-title"><span class="meta-autor">Раз.</span> Настройте желаемый вид</div>

	<div class="three columns informer-col1">
	<form class="informer-form b-form" _lpchecked="1">
		<label>Тип информера</label>
			<select class="change-informer change-informer-post_type" name="post_type">
		        <option value="articles">Статьи</option>
		        <option value="news" selected="selected">Новости</option>
		    </select>
		<label>Тип отображения</label>
		    <select class="change-informer informer_type" name="type">
		        <option value="vertical">По вертикали</option>
		        <option value="horizontal" selected="selected">По горизонтали</option>
		    </select>
		<label>Количество новостей</label>
		    <select class="change-informer change-informer-count" name="count">
		        <option value="1">1</option>
		        <option value="2">2</option>
		        <option value="3" selected="selected">3</option>
		        <option value="6">6</option>
		        <option value="9">9</option>
		    </select>
		<label>Ширина</label>
		    <input type="number" class="change-informer change-informer-width" value="960" name="width" placeholder="px">
		<label>Вкл/выкл фона</label>
		    <input type="checkbox" class="change-informer change-informer-background" name="background" checked="checked">
	</div>

	

	<div class="seven columns informer-col2">
		<div class="dp80">

				<label>Шрифт</label>
			        <select class="change-informer change-font" name="font">
			            <option selected="selected" value="Arial">Arial</option>
			            <option value="Verdana">Verdana</option>
			            <option value="Tahoma">Tahoma</option>
			            <option value="Times">Times</option>
			            <option value="Georgia">Georgia</option>
			        </select>
				<label>Заголовок</label>
				    <input type="text" name="title" value="Новости Lednews" disabled >

				    <!-- col2-a -->
					<div class="informer-col2-a">
						<label>Размер заголовка</label>
						    <input type="number" name="font-size" value="15" placeholder="px" disabled>
						<label>Размер даты</label>
						    <input type="number" name="date-title" value="12" placeholder="px" disabled>
						<label>Рамка картинки</label>
						    <input type="number" class="change-informer change-informer-img-border-px" name="img-border-px" value="0" placeholder="px">
						<label>Цвет</label>
						    <input type="text" class="color-informer change-informer change-informer-img-color" name="img-color"  value="e6e6e6">
					</div><!--  end col2-a -->

					<!-- begin col2-b -->
					<div class="informer-col2-b">
						<label>Начертание</label>
				            <select name="font-weight" disabled>
				                <option value="normal" selected="selected">Нормальный</option>
				                <option value="bold">Жирный</option>
				            </select>
				        <label>Начертание</label>
						    <select name="font-weight-date" disabled>
						        <option value="normal" selected="selected">Нормальный</option>
						        <option value="bold">Жирный</option>
						    </select>

						<label>Тип рамки</label>
							    <select class="change-informer change-informer-img-border-type" name="img-border-type">
							        <option value="dotted">Точечная</option>
							        <option value="dashed" >Пунктирная</option>
							        <option value="solid" selected="selected">Сплошная</option>
							        <option value="double">Двойная</option>
							        <option value="groove">Кромка</option>
							        <option value="ridge">Толстая кромка</option>
							        <option value="inset">Внутренняя</option>
							        <option value="outset">Наружная</option>
							    </select>
					</div> <!-- end col2-b -->
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
					<pre id="xmp-informer">
					    <xmp><!-- default informer code lednews --><script type="text/javascript"> var widget_embed = 'posts';var posttype = 'news'; var count=3;</script><script src="http://test.lednews.ru/widget/wp-widget.js" type="text/javascript"></script><div id="embed-widget-container"></div><!-- end informer code lednews --></xmp>
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