<html>
<meta charset="UTF-8">
<body style="padding: 0; margin: 0; background: #fff;">
	<table cellspacing="0" cellpadding="0" border="0" align="center" width="690" style="font-family: Arial, sans-serif; font-size: 12px; color: #333333; border-collapse: collapse;">
		<thead>
			<tr>
				<td height="65" style="font-size: 20px;">
					<p>Esta é uma mensagem enviada através de seu site no dia <?php echo $data ?></p>
				</td>
			</tr>
		</thead>
		<tbody>
			
			<tr>
				<td>
					<p>por favor, não responda para o mesmo remetente, encaminhe seu email para <?php echo $email ?></p>
				</td>
			</tr>
			<tr>
				<td style="padding: 20px 0">
					<p style="padding-bottom: 20px; margin-right: 35px; border-bottom: 1px dashed #fff; font-style: italic; ">
						<strong>
						<?php echo $message ?>
						</strong> 
					</p>
				</td>
			</tr>
			
		</tbody>
	</table>
</body>
</html>