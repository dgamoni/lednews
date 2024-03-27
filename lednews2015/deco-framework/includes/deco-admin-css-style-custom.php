<?php
add_action( 'admin_head', 'deco_custom_from_icons_reforms' );
function deco_custom_from_icons_reforms() {
	$post = $_GET['post'];
	if ( get_post_type( $post ) == 'reforms' ) {
		?>
		<style>
			@font-face {
				font-family: "icomoon";
				src:         url("/wp-content/themes/samopomich/assets/font/icomoon.eot?v66x1g");
				src:         url("/wp-content/themes/samopomich/assets/font/icomoon.eot?#iefixv66x1g") format("embedded-opentype"), url("/wp-content/themes/samopomich/assets/font/icomoon.woff?v66x1g") format("woff"), url("/wp-content/themes/samopomich/assets/font/icomoon.ttf?v66x1g") format("truetype"), url("/wp-content/themes/samopomich/assets/font/icomoon.svg?v66x1g#icomoon") format("svg");
				font-weight: normal;
				font-style:  normal;
			}

			[class^="icon-"], [class*=" icon-"] {
				font-family:             "icomoon";
				speak:                   none;
				font-style:              normal;
				font-weight:             normal;
				font-variant:            normal;
				text-transform:          none;
				line-height:             1;
				-webkit-font-smoothing:  antialiased;
				-moz-osx-font-smoothing: grayscale;
			}

			.service__icon {
				width:        70px;
				height:       70px;
				float:        left;
				margin-right: 25px;
				position:     relative;
			}

			.service__icon:before {
				content:               "";
				position:              absolute;
				top:                   1.5px;
				left:                  1.5px;
				right:                 1.5px;
				bottom:                1.5px;
				border:                1px solid #dedede;
				-moz-border-radius:    50%;
				-webkit-border-radius: 50%;
				border-radius:         50%;
			}

			.service__icon:after {
				position:                absolute;
				top:                     50%;
				left:                    50%;
				-webkit-transform:       translate(-50%, -50%);
				-moz-transform:          translate(-50%, -50%);
				-ms-transform:           translate(-50%, -50%);
				-o-transform:            translate(-50%, -50%);
				transform:               translate(-50%, -50%);
				font-family:             "icomoon";
				speak:                   none;
				font-style:              normal;
				font-weight:             normal;
				font-variant:            normal;
				text-transform:          none;
				line-height:             1;
				-webkit-font-smoothing:  antialiased;
				-moz-osx-font-smoothing: grayscale;
				width:                   auto;
				color:                   #3d774c;
				margin-top:              3px;
			}

			.service__icon .circle {
				position:          absolute;
				top:               -6px;
				left:              -7px;
				-moz-transform:    scaleX(-1);
				-ms-transform:     scaleX(-1);
				-webkit-transform: scaleX(-1);
				transform:         scaleX(-1);
			}

			.service__icon .circle-small {
				position:           absolute;
				top:                1.5px;
				left:               1.5px;
				right:              1.5px;
				bottom:             1.5px;
				-moz-transition:    -moz-transform 1s;
				-o-transition:      -o-transform 1s;
				-webkit-transition: -webkit-transform 1s;
				transition:         transform 1s;
			}

			.service__icon .circle-small:before {
				content:               "";
				position:              absolute;
				top:                   -6px;
				left:                  50%;
				margin-left:           -6px;
				width:                 8px;
				height:                8px;
				border:                2px solid #ffd700;
				background:            #fff;
				-moz-border-radius:    50%;
				-webkit-border-radius: 50%;
				border-radius:         50%;
			}

			.service__icon.is-comp:after {
				font-size: 30px;
				content:   "";
			}

			.service__icon.is-zhkg:after {
				font-size: 34px;
				content:   "";
			}

			.service__icon.is-corruption:after {
				font-size: 32px;
				content:   "";
			}

			.service__icon.is-ppl:after {
				font-size:  26px;
				margin-top: 0;
				content:    "";
			}

			.service__icon.is-education:after {
				font-size: 25px;
				content:   "";
			}

			.service__icon.is-budget:after {
				font-size: 30px;
				content:   "";
			}

			.service__icon.is-hands:after {
				font-size: 25px;
				content:   "";
			}

			#acf-deco_reforms_icon li label input {
				display:     block;
				width:       16px;
				height:      16px;
				position:    absolute;
				top:         50%;
				left:        50%;
				margin-top:  -8px;
				margin-left: -8px;
				margin:      -8px 0 0 -8px;
				visibility:  hidden;
			}

			#acf-deco_reforms_icon li label input:checked + span:before {
				background-color: #C7FD75;
				border-radius:    50%;
			}

		</style>
		<?php
	}
}

add_action( 'admin_head', 'deco_terms_thumbnails' );
function deco_terms_thumbnails() {
	?>
	<style>
		.term-thumbnail,
		.term-thumbnail img,
		.tags .column-thumbnail img {
			width:  100% !important;
			height: 100% !important;
		}
	</style>

	<?php
}