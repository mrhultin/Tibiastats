<div class="ui segment">
<?php
echo '<h3>'.$name.' experience overview</h3>';
echo '<p>'.$name.' is an '.$type.' server located in '.$location.'.<br>Experience was last updated '.$expupdated.' and worlddata were last updated '.$updated.'</p>';
?>
    <div class="ui buttons fluid">
        <a href="/worlds/view/<?php echo strtolower($name); ?>/" class="ui <?php if($toplistName == "Experience"){ echo 'active '; }; ?>button primary">Experience</a>
        <a href="/worlds/view/<?php echo strtolower($name); ?>/magic/" class="ui  <?php if($toplistName == "Magic"){ echo 'active '; }; ?>button primary">Magic</a>
        <a href="/worlds/view/<?php echo strtolower($name); ?>/axe/" class="ui  <?php if($toplistName == "Axe"){ echo 'active '; }; ?>button primary">Axe</a>
        <a href="/worlds/view/<?php echo strtolower($name); ?>/sword/" class="ui  <?php if($toplistName == "Sword"){ echo 'active '; }; ?>button primary">Sword</a>
        <a href="/worlds/view/<?php echo strtolower($name); ?>/club/" class="ui  <?php if($toplistName == "Club"){ echo 'active '; }; ?>button primary">Club</a>
        <a href="/worlds/view/<?php echo strtolower($name); ?>/distance/" class="ui  <?php if($toplistName == "Distance"){ echo 'active '; }; ?>button primary">Distance</a>
        <a href="/worlds/view/<?php echo strtolower($name); ?>/shielding/" class="ui  <?php if($toplistName == "Shielding"){ echo 'active '; }; ?>button primary">Shielding</a>
    </div>
<table class="ui celled striped table compact">
    <thead>
        <th width="6%">Rank</th>
        <th>Name</th>
        <th>Level</th>
        <th>Experience</th>

    <th>1 day change</th>
    <th>Weekly change</th>
    <th>Monthly change</th>
    </thead>
    <tbody>
    <?php
        echo $toplist;    ?>
    </tbody>
</table>
</div>
