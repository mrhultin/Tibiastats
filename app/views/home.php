<div class="ui segment">

<h2>Welcome</h2>
<p><?php echo $siteName;?> is a webapplication devoted to gathering and supplying you with steaming fresh statistics related to the only game <strong>Tibia</strong>, we supply you with losses, gains and rank changes for characters on the official <a href="http://tibia.com/">Tibia.com</a> highscores. We also have statistics of things such as hometowns, which is the more popular vocation and more.</p>

</div>
<div id="shortstats" class="ui horizontal segments">
	<div class="ui segment statistic">
		<div class="label">
			Characters in Database <span class="info-popup" data-content="Keep in mind some of these characters might have been deleted, we're never gonna delete a character from our registry."  data-variation="inverted">
				<i style="color: #EC7A2E;" class="help circle icon"></i>
			</span>
		</div>
		<div class="value">
			<?php echo number_format($playerCount); ?>
		</div>
	</div>
	<div class="ui segment statistic">
		<div class="label">
			Deleted characters
		</div>
		<div class="value">
			<?php echo number_format($deletionCount); ?>
		</div>
	</div>
	<div class="ui segment statistic">
		<div class="label">
			Worlds in Database
		</div>
		<div class="value">
			<?php echo $worldCount; ?>
		</div>
	</div>
	<div class="ui segment statistic">
		<div class="label">
			Deaths seen
		</div>
		<div class="value">
			<?php echo number_format($deathCount); ?>
		</div>
	</div>
</div>
<div class="ui segment items divided">
<?php
foreach($news as $row){
	echo '
	<div class="item">
		<div class="content">
			<a class="header">'.$row["title"].'</a>
	 		<div class="description">
        		<p>'.nl2br($row["post"]).'</p>
    	    </div>
    	    <div class="extra">
    	    	Posted: '.date("d/m/Y", strtotime($row["date"])).'
    	    </div>
		</div>
	</div>';
}

?>
</div>
