<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">


</head>
<body>
<script>
//	window.close();
</script>
<style>
	h1 {
		text-align:  center;
		font-size:   22px;
		font-family: 'arial';
		color:       #8B8B8B;
	}

	.loader-box {
		position:   relative;
		width:      100%;
		height:     150px;
		top:        0;
		left:       0;
		z-index:    999;
		background: rgba(255, 255, 255, .5);

	}

	.loader-box .loader-inner {
		position:          absolute;
		top:               50%;
		left:              50%;
		-webkit-transform: translate(-50%, -50%);
		-moz-transform:    translate(-50%, -50%);
		-ms-transform:     translate(-50%, -50%);
		-o-transform:      translate(-50%, -50%);
		transform:         translate(-50%, -50%);

	}

	.loader-box .loader-inner div {
		border-radius:               100%;
		margin:                      2px;
		-webkit-animation-fill-mode: both;
		animation-fill-mode:         both;
		border:                      2px solid #D34231;
		border-bottom-color:         transparent;
		height:                      35px;
		width:                       35px;
		display:                     inline-block;
		-webkit-animation:           rotate 0.75s 0s linear infinite;
		animation:                   rotate 0.75s 0s linear infinite;
	}

	@keyframes rotate {
		0% {
			-webkit-transform: rotate(0deg);
			transform:         rotate(0deg);
		}

		50% {
			-webkit-transform: rotate(180deg);
			transform:         rotate(180deg);
		}

		100% {
			-webkit-transform: rotate(360deg);
			transform:         rotate(360deg);
		}
	}

	@-webkit-keyframes rotate {
		0% {
			-webkit-transform: rotate(0deg);
			transform:         rotate(0deg);
		}

		50% {
			-webkit-transform: rotate(180deg);
			transform:         rotate(180deg);
		}

		100% {
			-webkit-transform: rotate(360deg);
			transform:         rotate(360deg);
		}
	}

	@-moz-keyframes rotate {
		0% {
			-moz-transform: rotate(0deg);
			transform:      rotate(0deg);
		}

		50% {
			-moz-transform: rotate(180deg);
			transform:      rotate(180deg);
		}

		100% {
			-moz-transform: rotate(360deg);
			transform:      rotate(360deg);
		}
	}

	}
</style>
<?php //print_r( $_SERVER ); ?>
<div class="loader-box">
	<div class="loader-inner">
		<div></div>
	</div>
</div>
<h1><?php echo 'Вы авторизированы. Через несколько секунд окно закроется.'; ?></h1>
</body>
</html>