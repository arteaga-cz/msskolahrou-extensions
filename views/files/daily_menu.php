<?php
if ( ! defined( 'ABSPATH' ) ) {
	/** Set up WordPress environment */
	require_once( dirname( __FILE__ ) . '../../../../../wp-load.php' );
}
?><!DOCTYPE html>
<html>
	<head>
		<title>Jídelníček</title>
		<meta charset="UTF-8">
		<meta name="description" content="">
		<meta name="keywords" content="">

		<style>
			* {
				margin: 0;
				padding: 0;
				max-width: 100%;
			}
			html, body {
				height: 100%;
				min-height: 100%;
				max-height: 100%;
			}
			body {
				color: #1b1b1b;
				font-family: 'myriadprobold', sans-serif;
				font-size: 11pt;
				line-height: 12pt;
			}

			p {
				padding: 0;
				margin: 0 0 5mm 0;
			}

			img {
				max-width: 100%;
				height: auto;
			}

			small {
				display: block;
				font-size: 8pt;
				line-height: 9pt;
			}

			/*table, tr, th {
				font-size: 16pt
			}*/

			th {
				border-bottom: 1px solid #000;
			}

			.movie-name {
				font-size: 33pt;
				line-height: 33pt;
			}

			.text-left {
				text-align: left;
			}

			.text-center {
				text-align: center;
			}

			.text-right {
				text-align: right;
			}

			.float-left {
				float: left;
			}

			.float-right {
				float: right;
			}

			/*.wrapper {
				position: absolute;
				left: 0;
				top: 0;
				overflow: auto;
				width: 297mm;
				height: 210mm;
			}*/

			.logo {
				padding: 10mm;
			}

			.full-width {
				width: 100%;
				max-width: 100%;
			}

			.full-height {
				height: 100%;
				max-height: 100%;
			}

			.valign-top {
				vertical-align: top;
			}

			.valign-bottom {
				vertical-align: bottom;
			}

			.bg-red {
				background-color: red;
			}

			.bg-coral {
				background-color: coral;
			}

			.bg-green {
				background-color: green;
			}

			.bg-blue {
				background-color: blue;
			}

			.bg-skyblue {
				background-color: skyblue;
			}

			.relative {
				position: relative;
			}

			.absolute {
				position: absolute;
				left: 0;
				top: 0;
				overflow: auto;
			}

			.d-inline-block {
				display: inline-block;
			}

			.header-logo {
				position: absolute;
				top: 0;
				left: 0;
			}
			.header-image {
				position: absolute;
				top: 0;
				right: 0;
			}
		</style>
	</head>

	<body class="">
		<div class="wrapper" style="width:<?php echo $view_params['menu_dimensions'][0]; ?>mm; margin-left:auto; margin-right:auto;">

			<div id="header" class="header" style="width:100%;">
				<div class="header-logo" style="width:30%; float:left;">
					<img class="logo header-logo" src="<?php echo $view_params['header_logo_path']; ?>" />
				</div>

				<div class="header-text" style="width:40%; float:left; padding-top:10mm; padding-bottom:5mm;">
					<?php echo $view_params['header_content']; ?>
					<p class="text-center">Od <?php echo $view_params['date_from']; ?> do <?php echo $view_params['date_to']; ?></p>
				</div>

				<div class="header-image" style="width:30%; float:left;">
					<img class="header-image" src="<?php echo $view_params['header_image_path']; ?>" />
				</div>
			</div>

			<div id="content" class="content" style="padding-left:10mm;padding-right:10mm">
				<table width="100%">
					<tr>
						<th class="text-left" style="padding-bottom:4mm">Den</th>
						<th class="text-left" style="padding-bottom:4mm">Druh jídla</th>
						<th class="text-left" style="padding-bottom:4mm">Alergeny</th>
						<th class="text-left" style="padding-bottom:4mm">Strava</th>
					</tr>

					<?php foreach ( $view_params['menus'] as $menu ) :	?>

					<tr>
						<td rowspan="5" class="valign-top" style="padding-top:4mm; padding-right:4mm;">
							<?php
							echo '<strong>' . $menu['day_name'] . '</strong><br/>';
							echo $menu['date_display'];
							?>
						</td>
						<td style="padding-top:4mm">
							<?php _e( 'Přesnídávka:', 'msshext' ); ?>
						</td>
						<td style="padding-top:4mm">
							<?php echo $menu['food']['snack_1_allergens'] ?>
						</td>
						<td style="padding-top:4mm">
							<?php echo $menu['food']['snack_1']; ?>
						</td>
					</tr>

					<tr>
						<td rowspan="2" class="valign-top">
							<?php _e( 'Oběd:', 'msshext' ); ?>
						</td>
						<td>
							<?php echo $menu['food']['soup_allergens']; ?>
						</td>
						<td>
							<?php echo $menu['food']['soup']; ?>
						</td>
					</tr>

					<tr>
						<td>
							<?php echo $menu['food']['lunch_allergens']; ?>
						</td>
						<td>
							<?php echo $menu['food']['lunch']; ?>
						</td>
					</tr>

					<tr>
						<td>
							<?php _e( 'Svačina:', 'msshext' ); ?>
						</td>
						<td>
							<?php echo $menu['food']['snack_2_allergens']; ?>
						</td>
						<td>
							<?php echo $menu['food']['snack_2']; ?>
						</td>
					</tr>

					<tr>
						<td>
							<?php _e( 'Pitný režim:', 'msshext' ); ?>
						</td>
						<td>

						</td>
						<td>
							<?php echo $menu['food']['drinks']; ?>
						</td>
					</tr>

						<?php
					endforeach;
					?>
				</table>
			</div>

			<div id="right" class="footer" style="padding-top:8mm; padding-bottom:10mm;">
				<?php echo $view_params['footer_content']; ?>
			</div>

		</div>
	</body>
</html>

