<div class="ui segment">
<h3>World overview</h3>
<table class="ui celled striped table compact">
	<thead>
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Location</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($worlds as $world){
				echo '<tr></tr><td><a href="/worlds/view/'.$world["name"].'/">'.$world["name"].'</a></td><td>'.$world["type"].'</td><td>'.$world["location"].'</td></tr>';
			}
		?>
	</tbody>
</table>
</div>