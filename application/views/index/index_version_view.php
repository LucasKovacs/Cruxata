<body>
	<table width="800px" border="0" cellspacing="0" cellpadding="0" style="color:#000;">
		<tbody>
			<tr>
				<td><h1><?php echo $this->lang->line ( 've_title' ); ?></h1></td>
			</tr>
			<?php
			
				foreach ( $ve_versions as $version => $detail  )
				{
					$description 		= '<span style="font-weight:bold;">' . $version . '</span>';
					$description   	   .= '<br />';
		
					foreach ( $detail as $subversion => $changes )
					{
						$description   .= '<span style="font-weight:bold;padding:0 0 0 15px;">' . $subversion . '</span>';
						$description   .= '<br />';
						$description   .= '<ul style="list-style-type:square;list-style-position:outside;padding:0px 20px;margin:15px;">' . $changes . '</ul>';
					}
		
					$description   	   .= '<br />';
			?>
			<tr>
				<td style="text-align:left;"><?php echo $description; ?></td>
			</tr>
			<?php
				}
			
			?>
		<tbody>
	</table>
</body>