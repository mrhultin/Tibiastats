<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script type="text/javascript">
    window.onload = function(){
        $(".chartContainer").CanvasJSChart({
            title: {
                text: "Players Online"
            },
            axisY: {
                title: "Players online",
                suffix: "",
                includeZero: false
            },
            data: [
                {
                    type: "line",
                    toolTipContent: "{label}: {y} players online.",
                    dataPoints: [
                        <?php
                        if(!isset($onlineTimes)){
                           foreach($onlineTimes as $row){
                                echo '{ label: "'.date("j, n, Y G:i ", $row["date"]).'", y: '.$row["value"].' },';
                           }
                        }
                        ?>
                    ]
                }
            ]
        });

        $(".chartContainer2").CanvasJSChart({
            animationEnabled: true,
            title: {
                text: ""
            },
            data: [{
                type: "pie",
                startAngle: 240,
                yValueFormatString: "##0.00\"%\"",
                indexLabel: "{label} {y}",
                dataPoints: [
                    {y: <?php echo $druidPercent; ?>, label: "Druid"},
                    {y: <?php echo $sorcererPercent; ?>, label: "Sorcerer"},
                    {y: <?php echo $pallyPercent; ?>, label: "Paladin"},
                    {y: <?php echo $knightPercent; ?>, label: "Knight"}
                ]
            }]
        });
        chart.render();


    }
</script>
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

<style>
    #warning-icon {
        background-color: #f9ca99;

        opacity:1 !important;
    }
    #warning-icon i {
        color:#900505;
    }
</style>
</div>
<div class="ui attached segment message " style="border-top:1px solid rgba(34, 36, 38, 0.15) !important;">
    <div class="chartContainer2" style="height: 300px; width: 100%"></div>
    </div>
<div class="ui bottom attached warning message">
    <button class="circular ui icon button disabled" id="warning-icon"><i class="warning icon"></i></button>
   These numbers are not exact representations of characters in existance. CipSoft releases anual reports containing exact numbers!
</div>
<div class="ui segment">
    <div class="chartContainer" style="height: 300px; width: 100%"></div>
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
                    echo '<tr>
    <td><a href="/character/view/'.$row["charname"].'">'.$row["charname"].'</a></td>
    <td>'.formatExpChange($row["experiencechange"], 0).'</td>
</tr>';
                }
                ?>
        </table>
    </div>
</div>