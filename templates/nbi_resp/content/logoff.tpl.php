   <?php 
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('logoff', 'top');
    // RCI code eof
    ?>  
		<h2><?php echo HEADING_TITLE; ?></h2>
		<div class="content">
			<p><?php echo TEXT_MAIN; ?></p>
			<br>
			<?php echo '<a class="button" href="' . tep_href_link(FILENAME_DEFAULT) . '">Continue</a>'; ?>
		</div>	

        <?php 
        // RCI code start
        echo $cre_RCI->get('logoff', 'menu');
        // RCI code eof
        ?>   

   <?php 
    // RCI code start
    echo $cre_RCI->get('logoff', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>