<div id="shortstats" class="ui horizontal segments top attached segment">
    <div class="ui segment statistic">
        <div class="label">
            Druids
        </div>
        <div class="value">
           <?php echo $druidCount; ?>
        </div>
        <div class="label">
            <?php echo $druidPercent; ?>%
        </div>
    </div>
    <div class="ui segment statistic">
        <div class="label">
            Sorcerers
        </div>
        <div class="value">
            <?php echo $sorcCount; ?>
        </div>
        <div class="label">
            <?php echo $sorcererPercent; ?>%
        </div>
    </div>
    <div class="ui segment statistic">
        <div class="label">
            Paladins
        </div>
        <div class="value">
            <?php echo $pallyCount; ?>
        </div>
        <div class="label">
            <?php echo $pallyPercent; ?>%
        </div>
    </div>
    <div class="ui segment statistic">
        <div class="label">
           Knights
        </div>
        <div class="value">
            <?php echo $knightCount; ?>
        </div>
        <div class="label">
            <?php echo $knightPercent; ?>%
        </div>
    </div>
</div>
<div class="ui bottom attached warning message">
    <i class="warning icon"></i>
   These numbers are not exact representations of characters in existance. Only characters our system knows about that are level 10 or higher!
</div>

<div class="ui segment">
    <div class="ui segment">
        <h3>Highest daily gain</h3>
        <table class="ui striped table">
            <?php
            foreach($topgain as $row){
                echo '<tr>
    <td><a href="/character/view/'.$row["charname"].'">'.$row["charname"].'</a></td>
    <td>'.formatExpChange($row["experiencechange"], 0).'</td>
</tr>';
            } ?>
        </table>
    </div>
    <div class="ui segment">
        <h3>Highest daily loss</h3>
        <table class="ui striped table">
            <?php
                foreach($toploss as $row){
                    #print_r($row);
                    echo '<tr>
    <td><a href="/character/view/'.$row["charname"].'">'.$row["charname"].'</a></td>
    <td>'.formatExpChange($row["experiencechange"], 0).'</td>
</tr>';
                }
                ?>
        </table>
    </div>
</div>
