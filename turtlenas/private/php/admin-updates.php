<?php
$printUpdates = $control->printUpdateList();
$printUpdatesCount = $control->printUpdateList(TRUE);
?>

<!--Account Management section.-->
<div class='pageContents'>
    <div id='updateMenuDiv'>
        <!--Button to switch to the browser view.-->
        <label for="refresh" id="refreshTxt" class="buttonTxt">Refresh</label>
        <input class="buttons" id="refresh" type="#">

        <!--Button to sign out of account.-->
        <label for="upgrade" id="upgradeTxt" class="buttonTxt">Upgrade</label>
        <button class="buttons" id="upgrade" onclick="#"></button>
    </div>

    <div>
        <text><br>Upgradable packages(<?php echo($printUpdatesCount);?>).<br><br></text>
        <?php echo($printUpdates);?>
    </div>

</div>