<?php
/**
 * Error Page for TigerLaundry (2011 Recode Version)
 * 
 * This file is the page that loads when fatal errors are detected. It prints
 * a nice looking error message for the user.
 * 
 * @author Ben Russell (benrr101@csh.rit.edu) 
 */
?>
<div id="error">
Fatal Error: <?= $errorTitle ?><br />
<?= $errorMessage ?>
</div>