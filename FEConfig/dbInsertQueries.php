<?php

$er = error_reporting();
error_reporting($er ^ E_NOTICE);

class dbInsertQueries
{
    function InsertIntoFrontEnds($FEarray)
    {
        //called from AddFrontEnd.php
        $insert_frontends=mysql_query("INSERT INTO Front_Ends(SN,ESN,Docs,keyFacility,Description)
        VALUES('$FEarray[sn]','$FEarray[esn]','$FEarray[link]','$FEarray[facility]','$FEarray[description]')")
        or die("Could not insert into FrontEnds" .mysql_error());

        return $insert_frontends;
    }
    function InsertIntoConfigLink($maxkey,$comp)
    {
        //called from AddFrontEnd.php
        $insert_configLink=mysql_query("INSERT INTO FE_ConfigLink(fkFE_Components,fkFE_Config) VALUES('$comp',
        '$maxkey')")
        or die("Could not insert into ConfigLink" .mysql_error());
        return $insert_configLink;
    }
    function insertIntoStatLocAndNotes($notes,$updatedby,$status,$location,$link_data,$maxkey,$facility)
    {
        //called from AddFrontEnd.php, function InsertNewConfig() in thie file, CupdateFE.php
        $insert_notesetc=mysql_query("INSERT INTO FE_StatusLocationAndNotes(fkFEConfig,
        fkLocationNames,fkStatusType,Notes,lnk_Data,Updated_by,keyFacility)
        VALUES('$maxkey','$location','$status','$notes','$link_data','$updatedby','$facility')")
        or die("Could not get data" .mysql_error());

        return $insert_notesetc;
    }
    function insertIntoFEConfig($maxkey, $fesn,$facility)
    {
        //called from AddFrontEnd.php
        $notes="Initial configuration FE " .$fesn;


        $insert_feconfig=mysql_query("INSERT INTO FE_Config(fkFront_Ends,Description,keyFacility)
        VALUES('$maxkey','$notes','$facility')")
        or die("could not insert into FeConfig" .mysql_error());

        return $insert_feconfig;
    }
    function InsertNewConfig($FrontEndArray,$componentArray,$keyFE)
    {
        //called from CupdateFE.php
        $getFEkey=mysql_query("SELECT fkFront_Ends FROM FE_Config WHERE keyFEConfig='$keyFE'")
        or die("Could not get feconfig" .mysql_error());
        $fe_key=mysql_result($getFEkey,0,"fkFront_Ends");

        $getFESN=mysql_query("SELECT SN FROM Front_Ends WHERE keyFrontEnds='$fe_key'")
        or die("Could not get fesn" .mysql_error());
        $fe_sn=mysql_result($getFESN,0,"SN");

        $insertNewConfig=mysql_query("INSERT INTO FE_Config(fkFront_Ends,Description)
                                      VALUES('$fe_key','Cold PAS Config for FE SN $fe_sn')")
        or die("Could not insert new config" .mysql_error());

        $NewFEConfig_query=mysql_query("SELECT MAX(keyFEConfig) as NewConf FROM FE_Config WHERE fkFront_Ends='$fe_key'")
        or die("Could not get new config" .mysql_error());
        $newconf=mysql_result($NewFEConfig_query,0,"NewConf");

        foreach($componentArray as $value)
        {
            if($value != "")
            {
                mysql_query("INSERT INTO FE_ConfigLink(fkFE_Components,fkFE_Config)
                          VALUES('$value','$newconf')")
                or die("Could not insert data" .mysql_error());
            }
        }

        $update_fetable=mysql_query("Update Front_Ends SET ESN='$FrontEndArray[cansn]' WHERE keyFrontEnds='$fe_key'");

        return $newconf;
    }
    function insertIntoComponents($component_array)
    {
        $insertIntoComponents=mysql_query("INSERT INTO FE_Components(fkFE_ComponentType,SN,ESN1,ESN2,
                                        Component_Description,Docs,Band)
                             VALUES('$component_array[type]','$component_array[sn]','$component_array[ESN1]',
                            '$component_array[ESN2]','$component_array[compdescr]','$component_array[docs]',
                           '$component_array[band]')")
        or die("Could not insert into FE_Components" .mysql_error());

        $Componentkey_query=mysql_query("SELECT keyId FROM FE_Components WHERE SN='$component_array[sn]' AND
                                        fkFE_ComponentType='$component_array[type]' AND Band='$component_array[band]' ORDER BY
                                        keyId DESC LIMIT 1")
        or die("Could not get keyId" .mysql_error());

        $Component_key=mysql_result($Componentkey_query,0,"keyId");

        $insertintoStatLoc=mysql_query("INSERT INTO FE_StatusLocationAndNotes(fkFEComponents,fkLocationNames,
                                        fkStatusType,Notes,lnk_Data,Updated_By)
                                        VALUES('$Component_key','$component_array[location]','$component_array[status]' ,
                                           '$component_array[notes]','$component_array[link]','$component_array[updatedby]')")
        or die("Could not insert into StatusLocationAndNotes" .mysql_error());

        return $insertintoStatLoc;

    }
}

error_reporting($er);
