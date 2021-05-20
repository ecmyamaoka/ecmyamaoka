<?php

echo $this->Html->css('jqueryfiletree.min.css');

?>
<!-- <link rel="stylesheet/less" type="text/css" href="/css/jqueryfiletree.less"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.2.0/less.min.js"></script>  -->

<div class="filetree-path"></div>

<div class="filetree-basic"></div>

<script src="https://code.jquery.com/jquery-1.9.1.js" integrity="sha256-e9gNBsAcA0DBuRWbm0oZfbiCyhjLrI6bmqAl5o+ZjUA=" crossorigin="anonymous"></script>


<?php 

echo $this->Html->script('jqueryfiletree.js');

?>
<script>

    $('.filetree-basic').fileTree({
        script: 'https://apps.ec-masters.net/auth/jqueryFileTree.php',
        onlyFolders: false,
    }, function(file) {
        console.log(file);
        // do something with file
        $('.selected-file').text($('a[rel="' + file + '"]').text());
    })
    .on('filetreeexpanded filetreecollapsed filetreeclicked ...', function(e, data) {
        $('.filetree-path').text(data['rel']);
      console.log(data['rel']);
   });

</script>