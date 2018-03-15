<div class="ui segment">

<?php
if(isset($characters) and count($characters) > 0){
    echo '<h4>Results for "'.ucfirst($searchterm).'"</h4>';
    echo 'There are '.count($characters).' results matching your query.';
    echo '<table class="ui unstackable table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Level</th>
      <th>World</th>
    </tr>
  </thead>
  <tbody>';
    foreach($characters as $character){
        echo '<tr><td><a href="/character/view/'.$character["name"].'">'.$character["name"].'</a></td><td>'.$character["level"].'</td><td>'.$character["world"].'</td></tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<div class="ui warning message">
        <div class="header">
            Uhoh!
        </div>
            We did not find any characters named "<strong><em>'.ucfirst($searchterm).'</em></strong>" you searched for. Did you make a typo?
        </div>';
}
?>
</div>