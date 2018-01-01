<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="shortcut icon" href="<?php echo $base_url; ?>favicon.ico" />
<link rel="apple-touch-startup-image" href="<?php echo $base_url; ?>favicon.ico">
<link rel="apple-touch-icon" href="<?php echo $base_url; ?>favicon.ico">
<link rel="apple-touch-precomposed" href="<?php echo $base_url; ?>favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $css_path; ?>global.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $css_path; ?>styles.css" />
<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" href="<?php echo $css_path; ?>ie8.css" media="screen" />
<![endif]-->
<?php echo $required_js; ?>
<title><?php echo $game_title; ?></title>
<script type="text/javascript">$(document).ready(function(){servertime = parseFloat( <?php echo time(); ?> ) * 1000;$("span#clock").clock({"timestamp":servertime,"langSet":"<?php echo $this->lang->line ( 'ge_clock_lang' ); ?>"});$(".ajax").colorbox();});</script>
</head>
