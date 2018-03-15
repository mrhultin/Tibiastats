<div class="ui segment">
    <h3>Top 500 <?php echo $toplistName; ?></h3>
    <div class="ui buttons fluid">
        <a href="/toplist/" class="ui <?php if($toplistName == "Experience"){ echo 'active '; }; ?>button primary">Experience</a>
        <a href="/toplist/magic/" class="ui  <?php if($toplistName == "Magic"){ echo 'active '; }; ?>button primary">Magic</a>
        <a href="/toplist/axe/" class="ui  <?php if($toplistName == "Axe"){ echo 'active '; }; ?>button primary">Axe</a>
        <a href="/toplist/sword/" class="ui  <?php if($toplistName == "Sword"){ echo 'active '; }; ?>button primary">Sword</a>
        <a href="/toplist/club/" class="ui  <?php if($toplistName == "Club"){ echo 'active '; }; ?>button primary">Club</a>
        <a href="/toplist/distance/" class="ui  <?php if($toplistName == "Distance"){ echo 'active '; }; ?>button primary">Distance</a>
        <a href="/toplist/shielding/" class="ui  <?php if($toplistName == "Shielding"){ echo 'active '; }; ?>button primary">Shielding</a>
    </div>
    <?php
    echo $toplisttable;
    ?>
</div>