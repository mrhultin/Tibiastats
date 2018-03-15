<div class="ui segment">
    <h3>Deleted players</h3>
    <p>Before you start witch hunting, it's important to know that some of these characters might simply have been namelocked, it's impossible for our system to distinguish between deleted and namelocked characters</p>
</div>
<!--<div class="ui segment">
    <h4>Stats</h4>
</div>-->
<div class="ui segment">
    <form class="ui form segment" method="post" action="/deleted/">
        <h4>Filter results</h4>
        <div class="fields">
            <div class="two wide field">
                <label>Level range</label>
                <input type="text" name="minlevel" maxlength="4" placeholder="Min level" value="<?php if(isset($minlevel)){ echo $minlevel; }?>">
            </div>
            <div class="two wide field">
                <label>&nbsp;</label>
                <input type="text" name="maxlevel" maxlength="4" placeholder="Max level" value="<?php if(isset($maxlevel)){ echo $maxlevel; }?>">
            </div>
            <div class="two wide field">
                <label>World</label>

                    <div class="field">
                        <select class="ui fluid search dropdown" name="world">
                            <option value="">All worlds</option>
                            <?php
                            if(isset($worlds)){
                                foreach($worlds as $row){
                                    echo '<option value="'.$row["name"].'"';
                                    if(isset($searchWorld) and $row["name"] == $searchWorld){
                                        echo ' selected';
                                    }
                                    echo '>'.$row["name"].'</option>';
                                }
                            }
                            ?>
                        </select>
                </div>
            </div>
            <div class="two wide field">
                <label>Vocation</label>
                <select class="ui fluid search dropdown" name="vocation">
                    <option value="">All vocations</option>
                    <option value="druid" <?php if(isset($selectedVoc) and $selectedVoc == "druid"){ echo 'selected';} ?>>Druids</option>
                    <option value="sorcerer" <?php if(isset($selectedVoc) and $selectedVoc == "sorcerer"){ echo 'selected';} ?>>Sorcerers</option>
                    <option value="paladin" <?php if(isset($selectedVoc) and $selectedVoc == "paladin"){ echo 'selected';} ?>>Paladins</option>
                    <option value="knight" <?php if(isset($selectedVoc) and $selectedVoc == "knight"){ echo 'selected';} ?>>Knights</option>
                </select>

            </div>
            <div class="four wide field">
                <label>&nbsp;</label>
                <input type="submit" class="ui button primary fluid" value="Apply filter">
            </div>
        </div>
        </form>
    <div class="ui pagination menu">
        <?php
            $firstUrl = sprintf($baseUrl, 1);
            $lastUrl = sprintf($baseUrl, $totalpages);
            for($i = 1; $i <= $totalpages; $i++){
                if($i == 1){
                    echo '<a class="item" ref="'.$firstUrl.'">&laquo;</a>';
                }
                $targetUrl = sprintf($baseUrl, $i);
                if($i == $currentPage){
                    echo '<a class="active item">'.$i.'</a>';
                } else {
                    if($i == $currentPage+3 or $i == $currentPage-3){
                        /* Check if we should print start or end pages */
                        if($i < $currentPage){
                            echo '<a class="item" href="'.$firstUrl.'">1</a>';
                        }
                        echo '<a class="item">...</a>';
                        if($i > $currentPage){
                            echo '<a class="item" href="'.$lastUrl.'">'.$totalpages.'</a>';
                        }
                    } elseif($i <= $currentPage+2 and $i > $currentPage-3){
                        echo '<a class="item" href="'.$targetUrl.'">'.$i.'</a>';
                    } elseif($i >= $currentPage-2 and $i < $currentPage+3){
                        echo '<a class="item" href="'.$targetUrl.'">'.$i.'</a>';
                    }
                }
                if($i == $totalpages){
                    echo '<a class="item" href="'.$lastUrl.'">&raquo;</a>';
                }
            }

        ?>
    </div>
    <table class="ui striped table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Level</th>
            <th>World</th>
            <th>Vocation</th>
            <th>Deletion date</th>
        </tr>
        </thead>
        <?php
        foreach($deleted as $row){
            $level = $row["level"];
            if($row["level"] == 0){
                $level = $row["level"].'<span class="info-popup" data-content="This means we had not yet scanned this characters profile"  data-variation="inverted">
				<i style="color: #EC7A2E;" class="help circle icon"></i>';
            }
            echo '<tr>
            <td><a href="/character/view/'.$row["name"].'/">'.$row["name"].'</a></td>
            <td>'.$level.'</td>
            <td>'.$row["worldname"].'</td>
            <td>'.getVocationName($row["vocation"]).'</td>
            <td>'.date("jS \of F Y", $row["deleteddate"]).'</td>
</tr>';
        }
        ?>
    </table>
    <div class="ui pagination menu">
        <?php
            $firstUrl = sprintf($baseUrl, 1);
            $lastUrl  = sprintf($baseUrl, $totalpages);
            for($i = 1; $i <= $totalpages; $i++){
                if($i == 1){
                    echo '<a class="item" ref="'.$firstUrl.'">&laquo;</a>';
                }
                $targetUrl = sprintf($baseUrl, $i);
                if($i == $currentPage){
                    echo '<a class="active item">'.$i.'</a>';
                } else {
                    if($i == $currentPage+3 or $i == $currentPage-3){
                        /* Check if we should print start or end pages */
                        if($i < $currentPage){
                            echo '<a class="item" href="'.$firstUrl.'">1</a>';
                        }
                        echo '<a class="item">...</a>';
                        if($i > $currentPage){
                            echo '<a class="item" href="'.$lastUrl.'">'.$totalpages.'</a>';
                        }
                    } elseif($i <= $currentPage+2 and $i > $currentPage-3){
                        echo '<a class="item" href="'.$targetUrl.'">'.$i.'</a>';
                    } elseif($i >= $currentPage-2 and $i < $currentPage+3){
                        echo '<a class="item" href="'.$targetUrl.'">'.$i.'</a>';
                    }
                }
                if($i == $totalpages){
                    echo '<a class="item" href="'.$lastUrl.'">&raquo;</a>';
                }
            }

        ?>
    </div>
</div>