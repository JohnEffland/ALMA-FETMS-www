<?php

require_once(dirname(__FILE__) . '/../SiteConfig.php');
require_once($site_dbConnect);

include('header_js.php');
echo '<div class="article">';

echo 'To search for a WCA, enter the Band followed by serial number,<br>
      separated by a space.
      <br>For example, to see Band 9 SN 34, enter "9 34"';

if (isset($_REQUEST['band_sn'])){
    $band_sn = explode(" ",$_REQUEST['band_sn']);
    $Band = $band_sn[0];
    $SN = $band_sn[1];

    $q="SELECT keyId FROM FE_Components
    WHERE band=$Band AND SN = $SN
    AND fkFE_ComponentType = 11;";
    $r = @mysql_query($q,$db);
    $keyId = @mysql_result($r,0);

    if ($keyId == ""){
        echo "<br><br><font size = '+2' color = '#ff0000'>No WCA record found for band $Band, SN $SN.</font><br>";
    }
    if ($keyId != ""){
        echo '<meta http-equiv="Refresh" content="1;url=wca.php?fc='.$fc.'&keyId='. $keyId . '">';
    }
}

echo '</div>';

include('footer.php');
?>