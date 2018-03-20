    <div id="footerlinks" style="text-align:center; display:block;">
        <div class="center">
            <a<?php if($page == "about"){ echo ' class="ui orange label" ';} else { echo ' class="ui label"'; } ?> href="/about/">About <?php echo $siteName; ?></a><a <?php if($page == "logfile"){ echo ' class="ui orange label" ';} else { echo ' class="ui label"'; } ?> href="/logfile/">Update logs</a><a<?php if($page == "contact"){ echo ' class="ui orange label" ';} else { echo ' class="ui label"'; } ?> href="/contact/">Contact</a>
        </div>
            <div >
                &copy; 2016 - <?php if(date("Y") != "2016"){ echo date("Y").' - ';} echo $siteName;?>
            </div>
            <div>
                <a href="http://tibia.com/?ref=TibiaScanner" target="_blank">Tibia</a> is a registered trademark of <a href="https://www.cipsoft.com/" target="_blank">CipSoft GmbH</a>. Tibia and all products related to Tibia belong to CipSoft GmBH.
            </div>
    </div>
</div>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-79172218-1', 'auto');
        ga('send', 'pageview');

    </script>
</body>
</html>