<script type="text/javascript">
v = new Date();
var countdown = document.getElementById('<?php echo $container; ?>');
function t(){
	n  = new Date();
	ss = <?php echo $time; ?>;
	s  = ss - Math.round( (n.getTime() - v.getTime()) / 1000.);
	m  = 0;
	h  = 0;
	if ( s < 0 ) {
		countdown.innerHTML = '<?php echo $lang ?>';
		setTimeout("location.reload(true);",1000);
	} else {
		if ( s > 59 ) { m = Math.floor( s / 60 ); s = s - m * 60; }
		if ( m > 59 ) { h = Math.floor( m / 60 ); m = m - h * 60; }
		if ( s < 10 ) { s = "0" + s }
		if ( m < 10 ) { m = "0" + m }
		countdown.innerHTML = h + ':' + m + ':' + s;
	}
	window.setTimeout("t();",999);
}
window.onload=t;
</script>