<div class="ui segment">
    <h3>Log files</h3>
    <p>This page shows public logs of our data collection, it allows you to see when your world was last updated.</p>
    <p>For performance reasons this page only shows the most recent 100 entries. If you wish to know exactly when your specific world was updated, you can find that information on the corresponding world page of your world.</p>
    <table class="ui very compact table">
        <thead>
        <tr><th>Info</th>
            <th>Date</th>
        </tr></thead>
        <tbody>
    <?php
    if(count($logdata) > 0) {
        foreach ($logdata as $row) {
            echo '<tr><td>' . $row["text"] . '</td><td>' . date("d/m/Y", $row["date"]) . '</td>';
        }
    }else {
        echo '<tr><td colspan="2"><em>No entries could be loaded.</em></td>';
    }
    ?>
        </tbody>
        </table>
</div>