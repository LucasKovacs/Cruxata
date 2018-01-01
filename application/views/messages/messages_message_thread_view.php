<html>
<head>
</head>
<body>
<table border="0" width="600px" cellpadding="10" cellspacing="11">
<?php

	foreach ( $data as $key => $messages_data )
	{
		$align		= ( $user_id == $messages_data['message_user_id'] ) ? 'right' : 'left';
		$messages	= ( $user_id == $messages_data['message_user_id'] ) ? '<a href="'.$base_url.'private-message/'.$messages_data['sender'].'"><img src="' . $img_path . 'icons/message.gif" /></a>' : '';
?>
		<tr>
			<td colspan="2" style="border:1px solid #000;background-color:#990f1f;color:#fff;text-align:<?php echo $align; ?>;">
				<?php echo '<span style="font-weight:bold">' . $messages_data['sender'] . '</span> ' . $messages . ' <span style="font-style:italic;">(' . $this->functions->format_hour ( $messages_data['message_date'] ) . ')</span>:' ?>
			</td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:#000;" width="50px">
				<?php echo ($key + 1) . ' |'; ?>
			</td>
			<td style="text-align:<?php echo $align; ?>;font-style:italic;color:#000;">
				<?php echo $messages_data['message_text']; ?>
			</td>
		</tr>
<?php
	}

?>
</table>
</body>
</html>