<div class="ui segment">
<h3>Contact <?php echo $siteName; ?></h3>
    <?php
    if(isset($error)){
        print_r($data);
        echo '<div class="ui negative message">
    <div class="header">Error!</div>
    <ul class="list">';
    foreach($error as $e){
        echo '<li>'.ucfirst($e).'</li>';
    }
    echo '</ul>
  </div>';
    }
    ?>
    <div class="ui warning message">
        <div class="header">Important!</div>
        <p>To stop spam we will be logging your IP-adress, your IP will never be sold or used for any other purpose than blocking potential spam!</p>
    </div>
    <form class="ui form" action="/contact/send/" method="post">
        <div class="field">
            <label>Contact information</label>
            <div class="fields">
                <div class="six wide field">
                    <input type="text" name="name" placeholder="Your name" <?php if(isset($error) and isset($data["name"])){ echo 'value="'.$data["name"].'"'; }?>>
                </div>
                <div class="six wide field">
                    <input type="text" name="email" placeholder="E-mail adress" <?php if(isset($error) and isset($data["email"])){ echo 'value="'.$data["email"].'"'; }?>>
                </div>
                <div class="four wide field">
                    <select class="ui fluid dropdown" name="reason">
                        <option value="">Reason</option>
                        <option value="bug">Bug report</option>
                        <option value="support">Support</option>
                        <option value="suggestion">Suggestion</option>
                    </select>

                </div>

            </div>
        </div>
        <div class="field">
            <label>Message</label>
            <textarea name="message" placeholder="If you're reporting a bug, please try to be as thorough as possible!"><?php if(isset($error) and isset($data["message"])){ echo $data["message"]; }?></textarea>
        </div>
        <button type="submit" class="ui submit primary button">Submit</button>

    </form>
</div>