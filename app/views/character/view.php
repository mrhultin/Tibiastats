<?php
if($deleted == 1 or $deleted === 1){
	/* Character is deleted, the player must be informed of this! */
	echo '<div class="ui negative message">
  		<i class="warning sign icon"></i> This character has been deleted. This profile is only for historic use.
 </div>';
}?>
<div class="ui segment">
<?php
echo '<h4>Profile of <span style="color:#EC7A2E;">'.$name.'</span></h4>
<p>'.$gender.' is an '.$vocation.'  from <a href="/worlds/view/'.$worldname.'/">'.$worldname.'</a></p>
<a target="_blank" href="https://secure.tibia.com/community/?subtopic=characters&name='.$name.'"  class="ui primary button"><i class="external icon"></i> Tibia.com</a>

';
?>


<div class="ui attached horizontal segments">
	<div class="ui segment statistic">
		<div class="label">
			Highest daily gain
		</div>
		<div class="value popup" data-content="Due to the way Tibia.com works, this could be over a 48 hour period."  data-variation="inverted">
			<?php echo $bestDay; ?>
		</div>
	</div>
	<div class="ui segment statistic">
		<div class="label">
			Weekly Gain (7 days)
		</div>
		<div class="value">
			<?php echo $weeklyGain; ?>
		</div>
		<div class="label">
			Daily average of <?php echo $weeklyAvg; ?>
		</div>
	</div>
	<div class="ui segment statistic">
		<div class="label">
			Monthly Gain (30 days)
		</div>
		<div class="value">
			<?php echo $monthlyGain; ?>
		</div>
		<div class="label">
			Daily average of <?php echo $monthlyAvg; ?>
		</div>
	</div>
</div>
	<?php

	if(isset($highscores) and count($highscores) > 0) {
	echo '<div class="ui segment">
	<h4> Highscore entries </h4>
	<table class="ui striped table compact">
		<thead>
			<tr><th> Skill</th>
				<th> Rank</th>

				<th> Value</th>
			</tr>
		</thead>
	';
		foreach($highscores as $row){
			echo '<tr>
			<td>'.$row["skillname"].'</td>
			<td>#'.$row["skillrank"].'</td>
			<td>'.$row["skillvalue"].'</td>
</tr>';
		}
echo '<tfoot>
<tr>
<th colspan="3"><small><i class="warning icon"></i>It is possible a character is no longer in highscores, if that is the case the most recent skill/rank will still be shown.</small></th>
</tr>
</tfoot></table>
</div>';
	} ?>

<?php
if(isset($experience) and count($experience) > 0) {
	echo '<table class="ui celled striped table compact">
<thead>
	<tr>
		<th>Date</th>
		<th>Rank</th>
		<th>Level</th>
		<th>Experience</th>
		<th>Change</th>
		<th>Left</th>
	</tr>
</thead>';

	foreach ($experience as $row) {
		echo '<tr>
			<td>' . date("d/m/Y", $row["date"]) . '</td>
			<td>' . $row["rank"] . $row["rankchange"] . '</td>
			<td>' . $row["level"] . '</td>
			<td>' . number_format($row["experience"]) . '</td>
			<td>' . $row["experiencechange"] . '</td>
			<td>' . number_format($row["tnl"]) . ' (' . $row["tnlperc"] . '%)</td>
		</tr>';
	}
	echo '</table>';
} else {

	echo '<div class="ui info message">Character has no experience entries yet! We can only get accurate information if the character has been in the top 300 of his/her world.</div>';
}
/* player deaths */
echo '<h4>Player deaths</h4>';
if(isset($deaths) and count($deaths) > 0){
	echo '
<table class="ui striped table compact">
		<thead>
			<tr><th> Date</th>
				<th> Info</th>
			</tr>
		</thead>';
	foreach($deaths as $death){
		echo '<tr>
			<td>'.date("d/m/Y h:m:s", $death["date"]).'</td>
			<td>At level '.$death["level"].' by '. $death["reason"].'</td>
			</tr>
';
	}
	echo '</table>';
} else {
	echo '<div class="ui success message">We have no deaths on record for this character</div>';
}
?>
</div>