<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2006 by the INDEPNET Development Team.
 
 http://indepnet.net/   http://glpi.indepnet.org
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

/** \file functions_search.php
* Generic functions for Search Engine
*/

/**
* Completion of the URL $_GET values with the $_SESSION values or define default values
*
*
* @param $type item type to manage
* @return nothing
*
*/
function manageGetValuesInSearch($type=0){
global $_GET;
$tab=array();

if (isset($_GET["reset_before"])){
	unset($_SESSION['glpisearch'][$type]);
	unset($_SESSION['glpisearchcount'][$type]);
	unset($_SESSION['glpisearchcount2'][$type]);
	if (isset($_GET["glpisearchcount"])) 
		$_SESSION["glpisearchcount"][$type]=$_GET["glpisearchcount"];
	}

if (is_array($_GET))
foreach ($_GET as $key => $val)
		$_SESSION['glpisearch'][$type][$key]=$val;

if (!isset($_GET["start"]))
	if (isset($_SESSION['glpisearch'][$type]["start"])) $_GET["start"]=$_SESSION['glpisearch'][$type]["start"];
	else $_GET["start"] = 0;

if (!isset($_GET["order"]))
	if (isset($_SESSION['glpisearch'][$type]["order"])) $_GET["order"]=$_SESSION['glpisearch'][$type]["order"];
	else $_GET["order"] = "ASC";

if (!isset($_GET["deleted"]))
if (isset($_SESSION['glpisearch'][$type]["deleted"])) $_GET["deleted"]=$_SESSION['glpisearch'][$type]["deleted"];
else $_GET["deleted"] = "N";

if (!isset($_GET["distinct"]))
if (isset($_SESSION['glpisearch'][$type]["distinct"])) $_GET["distinct"]=$_SESSION['glpisearch'][$type]["distinct"];
else $_GET["distinct"] = "N";
	

if (!isset($_GET["link"]))
	if (isset($_SESSION['glpisearch'][$type]["link"])) $_GET["link"]=$_SESSION['glpisearch'][$type]["link"];
	else $_GET["link"] = array();

if (!isset($_GET["field"]))
	if (isset($_SESSION['glpisearch'][$type]["field"])) $_GET["field"]=$_SESSION['glpisearch'][$type]["field"];
	else $_GET["field"] = array(0 => "view");

if (!isset($_GET["contains"]))
	if (isset($_SESSION['glpisearch'][$type]["contains"])) $_GET["contains"]=$_SESSION['glpisearch'][$type]["contains"];
	else $_GET["contains"] = array(0 => "");

if (!isset($_GET["link2"]))
	if (isset($_SESSION['glpisearch'][$type]["link2"])) $_GET["link2"]=$_SESSION['glpisearch'][$type]["link2"];
	else $_GET["link2"] = array();

if (!isset($_GET["field2"]))
	if (isset($_SESSION['glpisearch'][$type]["field2"])) $_GET["field2"]=$_SESSION['glpisearch'][$type]["field2"];
	else $_GET["field2"] = array(0 => "view");

if (!isset($_GET["contains2"]))
	if (isset($_SESSION['glpisearch'][$type]["contains2"])) $_GET["contains2"]=$_SESSION['glpisearch'][$type]["contains2"];
	else $_GET["contains2"] = array(0 => "");

if (!isset($_GET["type2"]))
	if (isset($_SESSION['glpisearch'][$type]["type2"])) $_GET["type2"]=$_SESSION['glpisearch'][$type]["type2"];
	else $_GET["type2"] = "";


if (!isset($_GET["sort"]))
	if (isset($_SESSION['glpisearch'][$type]["sort"])) $_GET["sort"]=$_SESSION['glpisearch'][$type]["sort"];
	else $_GET["sort"] = 1;

if (!isset($_SESSION["glpisearchcount"][$type])) $_SESSION["glpisearchcount"][$type]=1;
if (!isset($_SESSION["glpisearchcount2"][$type])) $_SESSION["glpisearchcount2"][$type]=0;

}

/**
* Print generic search form
*
* 
*
*@param $type type to display the form
*@param $target url to post the form
*@param $field array of the fields selected in the search form
*@param $contains array of the search strings
*@param $sort the "sort by" field value
*@param $deleted the deleted value 
*@param $link array of the link between each search.
*@param $distinct only display distinct items
*@param $contains2 array of the search strings for meta items
*@param $field2 array of the fields selected in the search form for meta items
*@param $type2 type to display the form for meta items
*@param $link2 array of the link between each search. for meta items
*
*@return nothing (diplays)
*
**/
function searchForm($type,$target,$field="",$contains="",$sort= "",$deleted= "",$link="",$distinct="Y",$link2="",$contains2="",$field2="",$type2=""){
	global $lang,$HTMLRel,$SEARCH_OPTION,$cfg_glpi,$LINK_ID_TABLE;
	$options=$SEARCH_OPTION[$type];


	// Mete search names
	$names=array(
		COMPUTER_TYPE => $lang["Menu"][0],
//		NETWORKING_TYPE => $lang["Menu"][1],
		PRINTER_TYPE => $lang["Menu"][2],
		MONITOR_TYPE => $lang["Menu"][3],
		PERIPHERAL_TYPE => $lang["Menu"][16],
		SOFTWARE_TYPE => $lang["Menu"][4],
		PHONE_TYPE => $lang["Menu"][34],	
	);
	
	echo "<form method=get action=\"$target\">";
	echo "<div align='center'><table border='0'  class='tab_cadre_fixe'>";
	echo "<tr><th colspan='5'><b>".$lang["search"][0].":</b></th></tr>";
	echo "<tr class='tab_bg_1'>";
	echo "<td align='center'>";
	echo "<table>";
	
	// Display normal search parameters
	for ($i=0;$i<$_SESSION["glpisearchcount"][$type];$i++){
		echo "<tr><td align='right'>";
		// First line display add / delete images for normal and meta search items
		if ($i==0){
			echo "<a href='".$cfg_glpi["root_doc"]."/computers/index.php?add_search_count=1&amp;type=$type'><img src=\"".$HTMLRel."pics/plus.png\" alt='+' title='".$lang["search"][17]."'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($_SESSION["glpisearchcount"][$type]>1)
			echo "<a href='".$cfg_glpi["root_doc"]."/computers/index.php?delete_search_count=1&amp;type=$type'><img src=\"".$HTMLRel."pics/moins.png\" alt='-' title='".$lang["search"][18]."'></a>&nbsp;&nbsp;&nbsp;&nbsp;";

			if (isset($names[$type])){
				echo "<a href='".$cfg_glpi["root_doc"]."/computers/index.php?add_search_count2=1&amp;type=$type'><img src=\"".$HTMLRel."pics/meta_plus.png\" alt='+' title='".$lang["search"][19]."'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
				if ($_SESSION["glpisearchcount2"][$type]>0)
				echo "<a href='".$cfg_glpi["root_doc"]."/computers/index.php?delete_search_count2=1&amp;type=$type'><img src=\"".$HTMLRel."pics/meta_moins.png\" alt='-' title='".$lang["search"][20]."'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
			}
		}
		// Display link item
		if ($i>0) {
			echo "<select name='link[$i]'>";
			
			echo "<option value='AND' ";
			if(is_array($link)&&isset($link[$i]) && $link[$i] == "AND") echo "selected";
			echo ">AND</option>";
			
			echo "<option value='OR' ";
			if(is_array($link)&&isset($link[$i]) && $link[$i] == "OR") echo "selected";
			echo ">OR</option>";		

			echo "<option value='AND NOT' ";
			if(is_array($link)&&isset($link[$i]) && $link[$i] == "AND NOT") echo "selected";
			echo ">AND NOT</option>";
			
			echo "<option value='OR NOT' ";
			if(is_array($link)&&isset($link[$i]) && $link[$i] == "OR NOT") echo "selected";
			echo ">OR NOT</option>";
			
			echo "</select>";
		}
		// display search field
		echo "<input type='text' size='15' name=\"contains[$i]\" value=\"". (is_array($contains)&&isset($contains[$i])?stripslashes($contains[$i]):"" )."\" >";
		echo "&nbsp;";
		echo $lang["search"][10]."&nbsp;";
		
		// display select box to define serach item
		echo "<select name=\"field[$i]\" size='1'>";
    		echo "<option value='view' ";
		if(is_array($field)&&isset($field[$i]) && $field[$i] == "view") echo "selected";
		echo ">".$lang["search"][11]."</option>";

        	reset($options);
		foreach ($options as $key => $val) {
			echo "<option value=\"".$key."\""; 
			if(is_array($field)&&isset($field[$i]) && $key == $field[$i]) echo "selected";
			echo ">". substr($val["name"],0,30) ."</option>\n";
		}

    	echo "<option value='all' ";
		if(is_array($field)&&isset($field[$i]) && $field[$i] == "all") echo "selected";
		echo ">".$lang["search"][7]."</option>";

		echo "</select>&nbsp;";

		
		echo "</td></tr>";
	}

	// Display meta search items
	$linked=array();
	if ($_SESSION["glpisearchcount2"][$type]>0){
		// Define meta search items to linked
		switch ($type){
			case COMPUTER_TYPE :
				$linked=array(PRINTER_TYPE,MONITOR_TYPE,PERIPHERAL_TYPE,SOFTWARE_TYPE,PHONE_TYPE);
				break;
/*			case NETWORKING_TYPE :
				$linked=array(COMPUTER_TYPE,PRINTER_TYPE,PERIPHERAL_TYPE);
				break;
*/			case PRINTER_TYPE :
				$linked=array(COMPUTER_TYPE);
				break;
			case MONITOR_TYPE :
				$linked=array(COMPUTER_TYPE);
				break;
			case PERIPHERAL_TYPE :
				$linked=array(COMPUTER_TYPE);
				break;
			case SOFTWARE_TYPE :
				$linked=array(COMPUTER_TYPE);
				break;
			case PHONE_TYPE :
				$linked=array(COMPUTER_TYPE);
				break;
		}
	}

	if (is_array($linked)&&count($linked)>0)
	for ($i=0;$i<$_SESSION["glpisearchcount2"][$type];$i++){
		echo "<tr><td align='left'>";
		$rand=mt_rand();
		
		// Display link item (not for the first item)
		//if ($i>0) {
			echo "<select name='link2[$i]'>";
			
			echo "<option value='AND' ";
			if(is_array($link2)&&isset($link2[$i]) && $link2[$i] == "AND") echo "selected";
			echo ">AND</option>";
			
			echo "<option value='OR' ";
			if(is_array($link2)&&isset($link2[$i]) && $link2[$i] == "OR") echo "selected";
			echo ">OR</option>";		

			echo "<option value='AND NOT' ";
			if(is_array($link2)&&isset($link2[$i]) && $link2[$i] == "AND NOT") echo "selected";
			echo ">AND NOT</option>";
			
			echo "<option value='OR NOT' ";
			if(is_array($link2)&&isset($link2[$i]) && $link2[$i] == "OR NOT") echo "selected";
			echo ">OR NOT</option>";
			
			echo "</select>";
		//}
		// Display select of the linked item type available
		echo "<select name='type2[$i]' id='type2_".$type."_".$i."_$rand'>";
		echo "<option value='-1'>-----</option>";
		foreach ($linked as $key)
			echo "<option value='$key'>".substr($names[$key],0,20)."</option>";
		echo "</select>";

		// Ajax script for display search meat item
		echo "<script type='text/javascript' >\n";
		echo "   new Form.Element.Observer('type2_".$type."_".$i."_$rand', 1, \n";
		echo "      function(element, value) {\n";
		echo "      	new Ajax.Updater('show_".$type."_".$i."_$rand','".$cfg_glpi["root_doc"]."/ajax/updateSearch.php',{asynchronous:true, evalScripts:true, \n";	
		echo "           method:'post', parameters:'type='+value+'&num=$i&field=".(is_array($field2)&&isset($field2[$i])?$field2[$i]:"")."&val=".(is_array($contains2)&&isset($contains2[$i])?$contains2[$i]:"")."'\n";
		echo "})})\n";
		echo "</script>\n";
		
		echo "<span id='show_".$type."_".$i."_$rand'>&nbsp;</span>\n";

		// Display already selected values
		if (is_array($type2)&&isset($type2[$i])&&$type2[$i]>0){
			echo "<script type='text/javascript' >\n";
			echo "document.getElementById('type2_".$type."_".$i."_$rand').value='".$type2[$i]."';";
			echo "</script>\n";
		}
		
		echo "</td></tr>";
	}

	echo "</table>";
	echo "</td>";

	// Display sort selection
	echo "<td>";
	echo $lang["search"][4];
	echo "&nbsp;<select name='sort' size='1'>";
	reset($options);
	foreach ($options as $key => $val) {
		echo "<option value=\"".$key."\"";
		if($key == $sort) echo " selected";
		echo ">".substr($val["name"],0,20)."</option>\n";
	}
	echo "</select> ";
	echo "</td>";
	
	// Display deleted selection
	echo "<td>";
//	echo "<table>";
	if (in_array($LINK_ID_TABLE[$type],$cfg_glpi["deleted_tables"])){
		//echo "<tr><td>";
		echo "<select name='deleted'>";
		echo "<option value='Y' ".($deleted=='Y'?" selected ":"").">".$lang["choice"][1]."</option>";
		echo "<option value='N' ".($deleted=='N'?" selected ":"").">".$lang["choice"][0]."</option>";
		echo "</select>";
		echo "<img src=\"".$HTMLRel."pics/showdeleted.png\" alt='".$lang["common"][3]."' title='".$lang["common"][3]."'>";
		//echo "</td></tr>";
	}
	
/*	echo "<tr><td><select name='distinct'>";
	echo "<option value='Y' ".($distinct=='Y'?" selected ":"").">".$lang["choice"][1]."</option>";
	echo "<option value='N' ".($distinct=='N'?" selected ":"").">".$lang["choice"][0]."</option>";
	echo "</select>";
	echo "<img src=\"".$HTMLRel."pics/doublons.png\" alt='".$lang["common"][12]."' title='".$lang["common"][12]."'>";
	echo "</td></tr></table>";
*/
	echo "</td>";
	// Display Reset search
	echo "<td>";
	echo "<a href='".$HTMLRel."/computers/index.php?reset_search=reset_search&amp;type=$type' ><img title=\"".$lang["buttons"][16]."\" alt=\"".$lang["buttons"][16]."\" src='".$HTMLRel."pics/reset.png' class='calendrier'</a>";
	echo "</td>";
	// Display submit button
	echo "<td width='80' align='center' class='tab_bg_2'>";
	echo "<input type='submit' value=\"".$lang["buttons"][0]."\" class='submit' >";
	echo "</td></tr></table></div>";
	// Reset to start when submit new search
	echo "<input type='hidden' name='start' value='0'>";
	echo "</form>";
	
}
/**
* Generic Search and list function
*
*
* Build the query, make the search and list items after a search.
*
*@param $target filename where to go when done.
*@param $field array of fields in witch the search would be done
*@param $type type to display the form
*@param $contains array of the search strings
*@param $distinct display only distinct items
*@param $sort the "sort by" field value
*@param $order ASC or DSC (for mysql query)
*@param $start row number from witch we start the query (limit $start,xxx)
*@param $deleted Query on deleted items or not.
*@param $link array of the link between each search.
*@param $contains2 array of the search strings for meta items
*@param $field2 array of the fields selected in the search form for meta items
*@param $type2 type to display the form for meta items
*@param $link2 array of the link between each search. for meta items
*
*
*@return Nothing (display)
*
**/
function showList ($type,$target,$field,$contains,$sort,$order,$start,$deleted,$link,$distinct,$link2="",$contains2="",$field2="",$type2=""){
	global $db,$INFOFORM_PAGES,$SEARCH_OPTION,$LINK_ID_TABLE,$HTMLRel,$cfg_glpi,$lang;

	// Define meta table where search must be done in HAVING clause
	$META_SPECIF_TABLE=array("glpi_device_ram","glpi_device_hdd","glpi_device_processor");
	$names=array(
		COMPUTER_TYPE => $lang["Menu"][0],
//		NETWORKING_TYPE => $lang["Menu"][1],
		PRINTER_TYPE => $lang["Menu"][2],
		MONITOR_TYPE => $lang["Menu"][3],
		PERIPHERAL_TYPE => $lang["Menu"][16],
		SOFTWARE_TYPE => $lang["Menu"][4],
	);	
	
	// Get the items to display
	$toview=array();
	// Add first element (name)
	array_push($toview,1);
	// Add default items
	$query="SELECT * FROM glpi_display WHERE type='$type' ORDER by rank";
	$result=$db->query($query);
	if ($db->numrows($result)>0){
		while ($data=$db->fetch_array($result))
			array_push($toview,$data["num"]);
	}

	// Add searched items
	if (count($field)>0)
		foreach($field as $key => $val)
			if (!in_array($val,$toview)&&$val!="all"&&$val!="view")
			array_push($toview,$val);

	// Add order item
	if (!in_array($sort,$toview))
		array_push($toview,$sort);

	// Manage search on all item
	$SEARCH_ALL=array();
	if (in_array("all",$field)){
		foreach ($field as $key => $val)
		if ($val=="all"){
			array_push($SEARCH_ALL,array("contains"=>$contains[$key]));
		}
	}

		
	// Clean toview array
	$toview=array_unique($toview);
	$toview_count=count($toview);
	
	// Construct the request 
	//// 1 - SELECT
	$SELECT ="SELECT ";
	
	// Add select for all toview item
	for ($i=0;$i<$toview_count;$i++){
		$SELECT.=addSelect($type,$SEARCH_OPTION[$type][$toview[$i]]["table"],$SEARCH_OPTION[$type][$toview[$i]]["field"],$i,0);
	}

	// Get specific item for extra column
	if ($LINK_ID_TABLE[$type]=="glpi_cartridges_type"||$LINK_ID_TABLE[$type]=="glpi_consumables_type")
		$SELECT.=$LINK_ID_TABLE[$type].".alarm as ALARM, ";

	//// 2 - FROM AND LEFT JOIN
	// Set reference table
	$FROM = " FROM ".$LINK_ID_TABLE[$type];
	// Init already linked tables array in order not to link a table several times
	$already_link_tables=array();
	// Put reference table
	array_push($already_link_tables,$LINK_ID_TABLE[$type]);

	// Add all table for toview items
	for ($i=1;$i<$toview_count;$i++)
		$FROM.=addLeftJoin($type,$LINK_ID_TABLE[$type],$already_link_tables,$SEARCH_OPTION[$type][$toview[$i]]["table"]);


	// Search all case :
	if (count($SEARCH_ALL)>0)
	foreach ($SEARCH_OPTION[$type] as $key => $val)
			$FROM.=addLeftJoin($type,$LINK_ID_TABLE[$type],$already_link_tables,$SEARCH_OPTION[$type][$key]["table"]);


	//// 3 - WHERE

	$first=true;
	// default string
	$WHERE = " WHERE ";
	// Add deleted if item have it
	if (in_array($LINK_ID_TABLE[$type],$cfg_glpi["deleted_tables"])){
		$LINK= " AND " ;
		if ($first) {$LINK=" ";$first=false;}
		$WHERE.= $LINK.$LINK_ID_TABLE[$type].".deleted='$deleted' ";
	}
	// Remove template items
	if (in_array($LINK_ID_TABLE[$type],$cfg_glpi["template_tables"])){
		$LINK= " AND " ;
		if ($first) {$LINK=" ";$first=false;}
		$WHERE.= $LINK.$LINK_ID_TABLE[$type].".is_template='0' ";
	}

	// Add search conditions
	// If there is search items
	if ($_SESSION["glpisearchcount"][$type]>0&&count($contains)>0) {
		$i=0;

		//foreach($contains as $key => $val)
		for ($key=0;$key<$_SESSION["glpisearchcount"][$type];$key++)
		// if real search (strlen >0) and not all and view search
		if (isset($contains[$key])&&strlen($contains[$key])>0&&$field[$key]!="all"&&$field[$key]!="view"){
			$LINK=" ";
			$NOT=0;
			// Manage Link if not first item
			if (!$first||$i>0) {
				if (is_array($link)&&isset($link[$key])&&ereg("NOT",$link[$key])){
				$LINK=" ".ereg_replace(" NOT","",$link[$key]);
				$NOT=1;
				}
				else if (is_array($link)&&isset($link[$key]))
					$LINK=" ".$link[$key];
				else $LINK=" AND ";
			}
			// Add Where clause if not to be done ine HAVING CLAUSE
			if (!in_array($SEARCH_OPTION[$type][$field[$key]]["table"],$META_SPECIF_TABLE)){
				$WHERE.= $LINK.addWhere($NOT,$type,$SEARCH_OPTION[$type][$field[$key]]["table"],$SEARCH_OPTION[$type][$field[$key]]["field"],$contains[$key]);
                        	$i++;
			}
		// if real search (strlen >0) and view search
		} else if (isset($contains[$key])&&strlen($contains[$key])>0&&$field[$key]=="view"){

			$NOT=0;
			// Manage Link if not first item
			if (!$first||$i>0) {
				if (is_array($link)&&isset($link[$key])&&ereg("NOT",$link[$key])){
					$WHERE.=" ".ereg_replace(" NOT","",$link[$key]);
					$NOT=1;
				} else if (is_array($link)&&isset($link[$key]))
					$WHERE.=" ".$link[$key];
				else 
					$WHERE.=" AND ";
				
			}

			 $WHERE.= " ( ";
			$first2=true;
			foreach ($toview as $key2 => $val2)
			// Add Where clause if not to be done ine HAVING CLAUSE
			if (!in_array($SEARCH_OPTION[$type][$val2]["table"],$META_SPECIF_TABLE)){
				$LINK=" OR ";
				if ($first2) {$LINK=" ";$first2=false;}
				$WHERE.= $LINK.addWhere($NOT,$type,$SEARCH_OPTION[$type][$val2]["table"],$SEARCH_OPTION[$type][$val2]["field"],$contains[$key]);
			}
			$WHERE.=" ) ";
			$i++;
		// if real search (strlen >0) and all search
		} else if (isset($contains[$key])&&strlen($contains[$key])>0&&$field[$key]=="all"){

			$NOT=0;
			// Manage Link if not first item
			if (isset($link[$key])&&(!$first||$i>0)) {
				if (ereg("NOT",$link[$key])){
				$WHERE.=" ".ereg_replace(" NOT","",$link[$key]);
				$NOT=1;
				}
				else 
				$WHERE.=" ".$link[$key];
			} else $WHERE.=" AND ";
			 $WHERE.= " ( ";
			$first2=true;

   		        foreach ($SEARCH_OPTION[$type] as $key2 => $val2)
			// Add Where clause if not to be done ine HAVING CLAUSE
			if (!in_array($val2["table"],$META_SPECIF_TABLE)){
                                $LINK=" OR ";
                                if ($first2) {$LINK=" ";$first2=false;}
                                
                                $WHERE.= $LINK.addWhere($NOT,$type,$val2["table"],$val2["field"],$contains[$key]);
			}

		        $WHERE.=")";
		        $i++;
                } 

	}


	//// 4 - ORDER
	// Add order by if order item is a normal item
	if (!in_array($SEARCH_OPTION[$type][$sort]["table"],$META_SPECIF_TABLE))	
		$ORDER= addOrderBy($SEARCH_OPTION[$type][$sort]["table"].".".$SEARCH_OPTION[$type][$sort]["field"],$order);
	// Add order by if order item must to be treated by the GROUP BY HAVING clause
	else {
		foreach($toview as $key => $val)
		if ($sort==$val)
			$ORDER= addOrderBy($SEARCH_OPTION[$type][$sort]["table"].".".$SEARCH_OPTION[$type][$sort]["field"],$order,$key);	
	}


	
	//// 5 - META SEARCH
	// Preprocessing
	if ($_SESSION["glpisearchcount2"][$type]>0&&is_array($type2)){
		
		// a - SELECT 
		for ($i=0;$i<$_SESSION["glpisearchcount2"][$type];$i++)
		if (isset($type2[$i])&&$type2[$i]>0&&isset($contains2[$i])&&strlen($contains2[$i]))	{
			$SELECT.=addSelect($type2[$i],$SEARCH_OPTION[$type2[$i]][$field2[$i]]["table"],$SEARCH_OPTION[$type2[$i]][$field2[$i]]["field"],$i,1,$type2[$i]);		
		}

		// b - ADD LEFT JOIN 
		// Already link meta table in order not to linked a table several times
		$already_link_tables2=array();
		// Link reference tables
		for ($i=0;$i<$_SESSION["glpisearchcount2"][$type];$i++)
		if (isset($type2[$i])&&$type2[$i]>0&&isset($contains2[$i])&&strlen($contains2[$i])) {
			if (!in_array($LINK_ID_TABLE[$type2[$i]],$already_link_tables2))
				$FROM.=addMetaLeftJoin($type,$type2[$i],$already_link_tables2,$i,($contains2[$i]=="NULL"));	
		}
		// Link items tables
		for ($i=0;$i<$_SESSION["glpisearchcount2"][$type];$i++)
		if (isset($type2[$i])&&$type2[$i]>0&&isset($contains2[$i])&&strlen($contains2[$i])) {
			if (!in_array($SEARCH_OPTION[$type2[$i]][$field2[$i]]["table"]."_".$type2[$i],$already_link_tables2)){
				$FROM.=addLeftJoin($type2[$i],$LINK_ID_TABLE[$type2[$i]],$already_link_tables2,$SEARCH_OPTION[$type2[$i]][$field2[$i]]["table"],0,1,$type2[$i]);				
			}
			
		}
	
	}
	
	
	//// 6 - Add item ID
	
	// Add ID to the select
	$SELECT.=$LINK_ID_TABLE[$type].".ID AS ID ";

	//// 7 - Manage GROUP BY
	$GROUPBY="";
	if ($_SESSION["glpisearchcount2"][$type]>0||count($SEARCH_ALL)>0)	
		$GROUPBY=" GROUP BY ID";

	// Specific case of group by : multiple links with the reference table
	if (empty($GROUPBY))
	foreach ($toview as $key2 => $val2){
		if (empty($GROUPBY)&&(($val2=="all")
			||($type==COMPUTER_TYPE&&ereg("glpi_device",$SEARCH_OPTION[$type][$val2]["table"]))
			||(ereg("glpi_contracts",$SEARCH_OPTION[$type][$val2]["table"]))
			||($SEARCH_OPTION[$type][$val2]["table"].".".$SEARCH_OPTION[$type][$val2]["field"]=="glpi_licenses.serial")
			||($SEARCH_OPTION[$type][$val2]["table"]=="glpi_networking_ports")
			||($SEARCH_OPTION[$type][$val2]["table"]=="glpi_dropdown_netpoint")
		)) 
	
		$GROUPBY=" GROUP BY ID ";
	}

	// Specific search define in META_SPECIF_TABLE : only for computer search (not meta search)
	if ($type==COMPUTER_TYPE){
		// For each real search item 
		foreach($contains as $key => $val)
		if (strlen($val)>0){
		// If not all and view search
		if ($field[$key]!="all"&&$field[$key]!="view"){
			foreach ($toview as $key2 => $val2){
				
				if (($val2==$field[$key])&&in_array($SEARCH_OPTION[$type][$val2]["table"],$META_SPECIF_TABLE)){
				if (!isset($link[$key])) $link[$key]="AND";
				
					$GROUPBY=addGroupByHaving($GROUPBY,$SEARCH_OPTION[$type][$field[$key]]["table"].".".$SEARCH_OPTION[$type][$field[$key]]["field"],strtolower($contains[$key]),$key2,0,$link[$key]);
				}
			}
		}
		}
	} 

	 // Specific search for others item linked  (META search)
	if (is_array($type2))
		for ($key=0;$key<$_SESSION["glpisearchcount2"][$type];$key++)
		if (isset($type2[$key])&&isset($contains2[$key])&&strlen($contains2[$key]))
		{
			$LINK="";
			if (isset($link2[$key])) $LINK=$link2[$key];
			
			if ($SEARCH_OPTION[$type2[$key]][$field2[$key]]["meta"]==1)			
				$GROUPBY=addGroupByHaving($GROUPBY,$SEARCH_OPTION[$type2[$key]][$field2[$key]]["table"].".".$SEARCH_OPTION[$type2[$key]][$field2[$key]]["field"],strtolower($contains2[$key]),$key,1,$LINK);
			else { // Meta Where Search
				$LINK=" ";
				$NOT=0;
				// Manage Link if not first item
				if (!$first) {
					if (is_array($link2)&&isset($link2[$key])&&ereg("NOT",$link2[$key])){
						$LINK=" ".ereg_replace(" NOT","",$link2[$key]);
						$NOT=1;
						}
					else if (is_array($link2)&&isset($link2[$key]))
						$LINK=" ".$link2[$key];
					else $LINK=" AND ";
				}

				$WHERE.= $LINK.addWhere($NOT,$type2[$key],$SEARCH_OPTION[$type2[$key]][$field2[$key]]["table"],$SEARCH_OPTION[$type2[$key]][$field2[$key]]["field"],$contains2[$key],1);
			}
		}

	// If no research limit research to display item and compute number of item using simple request
	$nosearch=true;
	for ($i=0;$i<$_SESSION["glpisearchcount"][$type];$i++)
	if (isset($contains[$i])&&strlen($contains[$i])>0) $nosearch=false;
	
	if ($_SESSION["glpisearchcount2"][$type]>0)	
		$nosearch=false;

	$LIMIT="";
	$numrows=0;
	//No search : count number of items using a simple count(ID) request and LIMIT search
	if ($nosearch) {
		$LIMIT= " LIMIT $start, ".$cfg_glpi["list_limit"];
		$query_num="SELECT count(ID) FROM ".$LINK_ID_TABLE[$type];
	
		$first=true;
		if (in_array($LINK_ID_TABLE[$type],$cfg_glpi["deleted_tables"])){
			$LINK= " AND " ;
			if ($first) {$LINK=" WHERE ";$first=false;}
			$query_num.= $LINK.$LINK_ID_TABLE[$type].".deleted='$deleted' ";
		}
		if (in_array($LINK_ID_TABLE[$type],$cfg_glpi["template_tables"])){
			$LINK= " AND " ;
			if ($first) {$LINK=" WHERE ";$first=false;}
			$query_num.= $LINK.$LINK_ID_TABLE[$type].".is_template='0' ";
		}
		$result_num = $db->query($query_num);
		$numrows= $db->result($result_num,0,0);
	}

	// If export_all reset LIMIT condition
	if (isset($_GET['export_all'])) $LIMIT="";

	// Reset WHERE if empty
	if ($WHERE == " WHERE ") $WHERE="";


	$db->query("SET SESSION group_concat_max_len = 9999999;");
	$QUERY=$SELECT.$FROM.$WHERE.$GROUPBY.$ORDER.$LIMIT;

	//echo $QUERY."<br>\n";
	
	// Set display type for export if define
	$output_type=0;
	if (isset($_GET["display_type"]))
		$output_type=$_GET["display_type"];
		

	// Get it from database and DISPLAY
	if ($result = $db->query($QUERY)) {
		// if real search or complet eexport : get numrows from request 
		if (!$nosearch||isset($_GET['export_all'])) 
			$numrows= $db->numrows($result);
		// If the begin of the view is before the number of items
		if ($start<$numrows) {

			// Contruct Pager parameters
			$parameters="sort=$sort&amp;order=$order".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains).getMultiSearchItemForLink("field2",$field2).getMultiSearchItemForLink("contains2",$contains2).getMultiSearchItemForLink("type2",$type2).getMultiSearchItemForLink("link2",$link2);
			
			// Display pager only for HTML
			if ($output_type==0) 
				printPager($start,$numrows,$target,$parameters,$type);

			// Form to delete old item
			$isadmin=isAdmin($_SESSION['glpitype']);
			if ($isadmin&&$output_type==0){
				echo "<form method='post' name='massiveaction_form' id='massiveaction_form' action=\"".$cfg_glpi["root_doc"]."/common/massiveaction.php\">";
			}
			
			// Compute number of columns to display
			// Add toview elements
			$nbcols=$toview_count;
			// Add meta search elements if real search (strlen>0) or only NOT search
			if ($_SESSION["glpisearchcount2"][$type]>0&&is_array($type2))
			for ($i=0;$i<$_SESSION["glpisearchcount2"][$type];$i++)
			if (isset($type2[$i])&&isset($contains2[$i])&&strlen($contains2[$i])>0&&$type2[$i]>0&&(!isset($link2[$i])||!ereg("NOT",$link2[$i]))) {
				$nbcols++;
			}
			
			if ($output_type==0)// HTML display - massive modif
				$nbcols++;

			// Display List Header
			echo displaySearchHeader($output_type,$cfg_glpi["list_limit"]+1,$nbcols);
			// New Line for Header Items Line
			echo displaySearchNewLine($output_type);
			$header_num=1;
			
			if ($output_type==0)// HTML display - massive modif
				echo displaySearchHeaderItem($output_type,"",$header_num,"",0,$order);

			// Display column Headers for toview items
			for ($i=0;$i<$toview_count;$i++){

				$linkto="$target?sort=".$toview[$i]."&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains).getMultiSearchItemForLink("field2",$field2).getMultiSearchItemForLink("contains2",$contains2).getMultiSearchItemForLink("type2",$type2).getMultiSearchItemForLink("link2",$link2);

				echo displaySearchHeaderItem($output_type,$SEARCH_OPTION[$type][$toview[$i]]["name"],$header_num,$linkto,$sort==$toview[$i],$order);
			}
		
			// Display columns Headers for meta items
			if ($_SESSION["glpisearchcount2"][$type]>0&&is_array($type2))
			for ($i=0;$i<$_SESSION["glpisearchcount2"][$type];$i++)
			if (isset($type2[$i])&&$type2[$i]>0&&isset($contains2[$i])&&strlen($contains2[$i])&&(!isset($link2[$i])
				||(!ereg("NOT",$link2[$i]) || $contains2[$i]=="NULL"))) {
				echo displaySearchHeaderItem($output_type,$names[$type2[$i]]." - ".$SEARCH_OPTION[$type2[$i]][$field2[$i]]["name"],$header_num);
			}
			// Add specific column Header
			if ($type==SOFTWARE_TYPE)
				echo displaySearchHeaderItem($output_type,$lang["software"][11],$header_num);
			if ($type==CARTRIDGE_TYPE)
				echo displaySearchHeaderItem($output_type,$lang["cartridges"][0],$header_num);	
			if ($type==CONSUMABLE_TYPE)
				echo displaySearchHeaderItem($output_type,$lang["consumables"][0],$header_num);
			// End Line for column headers		
			echo displaySearchEndLine($output_type);
			
			// if real search seek to begin of items to display (because of complete search)
			if (!$nosearch)
				$db->data_seek($result,$start);

			// Define begin and end var for loop
			// Search case
			$i=$start;
			$end_display=$start+$cfg_glpi["list_limit"];
			// No search Case
			if ($nosearch){
				$i=0;
				$end_display=min($numrows-$start,$cfg_glpi["list_limit"]);
				}
			// Export All case
			if (isset($_GET['export_all'])) {
				$i=0;
				$end_display=$numrows;
			}
			
			

			// Num of the row (1=header_line)
			$row_num=1;
			// Display Loop
			while ($i < $numrows && $i<($end_display)){
				// Column num
				$item_num=1;
				// Get data and increment loop variables
				$data=$db->fetch_assoc($result);
				$i++;
				$row_num++;
				// New line
				echo displaySearchNewLine($output_type);
					

				if ($output_type==0){// HTML display - massive modif
					$sel="";
					if (isset($_GET["select"])&&$_GET["select"]=="all") $sel="checked";
					echo displaySearchItem($output_type,"<input type='checkbox' name='item[".$data["ID"]."]' value='1' $sel>",$item_num,$row_num,0,"width='10'");
				}

				// Print first element - specific case for user 
				if ($SEARCH_OPTION[$type][1]["table"].".".$SEARCH_OPTION[$type][1]["field"]=="glpi_users.name")
					echo displaySearchItem($output_type,giveItem($type,"glpi_users.name.brut",$data,0),$item_num,$row_num);
				else 
					echo displaySearchItem($output_type,giveItem($type,$SEARCH_OPTION[$type][1]["table"].".".$SEARCH_OPTION[$type][1]["field"],$data,0),$item_num,$row_num);

				// Print other toview items
				for ($j=1;$j<$toview_count;$j++){
					// Specific case of enterprises
					if ($SEARCH_OPTION[$type][$toview[$j]]["table"].".".$SEARCH_OPTION[$type][$toview[$j]]["field"]=="glpi_enterprises.name")
						echo displaySearchItem($output_type,giveItem($type,"glpi_enterprises.name.brut",$data,$j),$item_num,$row_num);
					// Specific case of contracts
					else if ($SEARCH_OPTION[$type][$toview[$j]]["table"].".".$SEARCH_OPTION[$type][$toview[$j]]["field"]=="glpi_contracts.name")
						echo  displaySearchItem($output_type,giveItem($type,"glpi_contracts.name.brut",$data,$j),$item_num,$row_num);
					// General case
					else  
						echo displaySearchItem($output_type,giveItem($type,$SEARCH_OPTION[$type][$toview[$j]]["table"].".".$SEARCH_OPTION[$type][$toview[$j]]["field"],$data,$j),$item_num,$row_num);

				}
				
				// Print Meta Item
				if ($_SESSION["glpisearchcount2"][$type]>0&&is_array($type2))
				for ($j=0;$j<$_SESSION["glpisearchcount2"][$type];$j++)
				if (isset($type2[$j])&&$type2[$j]>0&&isset($contains2[$j])&&strlen($contains2[$j])&&(!isset($link2[$j])
					||(!ereg("NOT",$link2[$j]) || $contains2[$j]=="NULL"))){
					
					// General case
					if (!strpos($data["META_$j"],"$$$$"))
						echo displaySearchItem($output_type,$data["META_$j"],$item_num,$row_num);
					// Case of GROUP_CONCAT item : split item and multilline display
					else {
						$split=explode("$$$$",$data["META_$j"]);
						$count_display=0;
						$out="";
						for ($k=0;$k<count($split);$k++)
						if ($contains2[$j]=="NULL"||(strlen($contains2[$j])==0||eregi($contains2[$j],$split[$k]))){
						
							if ($count_display) $out.= "<br>";
							$count_display++;
							$out.= $split[$k];
						}
						echo displaySearchItem($output_type,$out,$item_num,$row_num);
					
					}
				}
				// Specific column display
				if ($type==CARTRIDGE_TYPE){
		   			echo displaySearchItem($output_type,countCartridges($data["ID"],$data["ALARM"],$output_type),$item_num,$row_num);
				}
				if ($type==SOFTWARE_TYPE){
		   			echo displaySearchItem($output_type,countInstallations($data["ID"],$output_type),$item_num,$row_num);
					}		
				if ($type==CONSUMABLE_TYPE){
		   			echo displaySearchItem($output_type,countConsumables($data["ID"],$data["ALARM"],$output_type),$item_num,$row_num);
				}		
		   	// End Line
		        echo displaySearchEndLine($output_type);
			}
			// Display footer
			echo displaySearchFooter($output_type);


			// Delete selected item
			if ($isadmin&&$output_type==0){
				echo "<div align='center'>";
				echo "<table cellpadding='5' width='80%'>";
				echo "<tr><td><img src=\"".$HTMLRel."pics/arrow-left.png\" alt=''></td><td><a onclick= \"if ( markAllRows('massiveaction_form') ) return false;\" href='".$_SERVER["PHP_SELF"]."?select=all'>".$lang["buttons"][18]."</a></td>";
			
				echo "<td>/</td><td><a onclick= \"if ( unMarkAllRows('massiveaction_form') ) return false;\" href='".$_SERVER["PHP_SELF"]."?select=none'>".$lang["buttons"][19]."</a>";
				echo "</td><td align='left' width='80%'>";
				dropdownMassiveAction($type,$deleted);
				echo "</td>";
				echo "</table>";
				
				echo "</div>";
				// End form for delete item
				echo "</form>";
			}
			
			if ($output_type==0) // In case of HTML display
				printPager($start,$numrows,$target,$parameters);

		} else {
			echo displaySearchError($output_type);
			
		}
	}
	else echo $db->error();

}



/**
* Generic Function to add GROUP BY to a request
*
*
*@param $field field to add
*@param $GROUPBY group by strign to complete
*@param $val value search
*@param $num item number 
*@param $meta is it a meta item ?
*@param $link link to use 
*
*
*@return select string
*
**/
function addGroupByHaving($GROUPBY,$field,$val,$num,$meta=0,$link=""){

$NOT="";
if (ereg("NOT",$link)){
 $NOT=" NOT";
 $link=ereg_replace(" NOT","",$link);
}

if (empty($link)) $link="AND";

$NAME="ITEM_";
if ($meta) $NAME="META_";

if (!ereg("GROUP BY ID",$GROUPBY)) $GROUPBY=" GROUP BY ID ";


if (!ereg("$NAME$num",$GROUPBY)) {
	if (ereg("HAVING",$GROUPBY)) $GROUPBY.=" ".$link." ";
	else $GROUPBY.=" HAVING ";

	switch ($field){

		case "glpi_device_ram.specif_default" :
			$larg=100;
			if (empty($NOT))
				$GROUPBY.=" ( $NAME$num < ".(intval($val)+$larg)." AND $NAME$num > ".(intval($val)-$larg)." ) ";
			else 
				$GROUPBY.=" ( $NAME$num > ".(intval($val)+$larg)." OR $NAME$num < ".(intval($val)-$larg)." ) ";
		break;
		case "glpi_device_processor.specif_default" :
			$larg=100;
			if (empty($NOT))
				$GROUPBY.=" ( $NAME$num < ".(intval($val)+$larg)." AND $NAME$num > ".(intval($val)-$larg)." ) ";
			else 
				$GROUPBY.=" ( $NAME$num > ".(intval($val)+$larg)." OR $NAME$num < ".(intval($val)-$larg)." ) ";
		break;
		case "glpi_device_hdd.specif_default" :
			$larg=1000;
			if (empty($NOT))
				$GROUPBY.=" ( $NAME$num < ".(intval($val)+$larg)." AND $NAME$num > ".(intval($val)-$larg)." ) ";
			else 
				$GROUPBY.=" ( $NAME$num > ".(intval($val)+$larg)." OR $NAME$num < ".(intval($val)-$larg)." ) ";
		break;
		default :
			
			if ($val!="null")
				$GROUPBY.=" ( $NAME$num $NOT LIKE '%$val%') ";
			else $GROUPBY.=" ( $NAME$num IS $NOT NULL) ";
		break;
	}
}
return $GROUPBY;
}

/**
* Generic Function to add ORDER BY to a request
*
*
*@param $field field to add
*@param $order order define
*@param $key item number
*
*
*@return select string
*
**/
function addOrderBy($field,$order,$key=0){
	switch($field){
	case "glpi_device_hdd.specif_default" :
	case "glpi_device_ram.specif_default" :
	case "glpi_device_processor.specif_default" :
		return " ORDER BY ITEM_$key $order ";
		break;
	case "glpi_contracts.end_date":
		return " ORDER BY ADDDATE(glpi_contracts.begin_date, INTERVAL glpi_contracts.duration MONTH) $order ";
	break;
	default:
		return " ORDER BY $field $order ";
		break;
	}

}

/**
* Generic Function to add select to a request
*
*
*@param $field field of the item to add
*@param $num item num in the request
*@param $type device type
*@param $table table of the item to add
*@param $meta is it a meta item ?
*@param $meta_type meta type table ID
*
*
*@return select string
*
**/
function addSelect ($type,$table,$field,$num,$meta=0,$meta_type=0){
global $LINK_ID_TABLE;

$addtable="";
$pretable="";
$NAME="ITEM";
if ($meta) {
	//$pretable="META_";
	$NAME="META";
	if ($LINK_ID_TABLE[$meta_type]!=$table)
		$addtable="_".$meta_type;
}

switch ($table.".".$field){
case "glpi_enterprises.name" :
case "glpi_enterprises_infocoms.name" :
	return $pretable.$table.$addtable.".".$field." AS ".$NAME."_$num, ".$pretable.$table.$addtable.".website AS ".$NAME."_".$num."_2, ".$pretable.$table.$addtable.".ID AS ".$NAME."_".$num."_3, ";
	break;
case "glpi_users.name" :
	return $pretable.$table.$addtable.".".$field." AS ITEM_$num, glpi_users.realname AS ".$NAME."_".$num."_2, ";
	break;
case "glpi_contracts.end_date" :
	return $pretable.$table.$addtable.".begin_date AS ITEM_$num, ".$pretable.$table.$addtable.".duration AS ".$NAME."_".$num."_2, ";
	break;
case "glpi_contracts.echeance_preavis" : // ajout jmd
	return $pretable.$table.$addtable.".begin_date AS ITEM_$num, ".$pretable.$table.$addtable.".duration AS ".$NAME."_".$num."_2, ".$pretable.$table.$addtable.".notice AS ".$NAME."_".$num."_3, ";
	break;
case "glpi_contracts.echeance" : // ajout jmd
	return $pretable.$table.$addtable.".begin_date AS ITEM_$num, ".$pretable.$table.$addtable.".duration AS ".$NAME."_".$num."_2, ";
	break;

case "glpi_device_hdd.specif_default" :
	return " SUM(DEVICE_".HDD_DEVICE.".specificity) / COUNT( DEVICE_".HDD_DEVICE.".ID) * COUNT( DISTINCT DEVICE_".HDD_DEVICE.".ID) AS ".$NAME."_".$num.", ";
	break;
case "glpi_device_ram.specif_default" :
	return " SUM(DEVICE_".RAM_DEVICE.".specificity) / COUNT( DEVICE_".RAM_DEVICE.".ID) * COUNT( DISTINCT DEVICE_".RAM_DEVICE.".ID) AS ".$NAME."_".$num.", ";
	break;
case "glpi_device_processor.specif_default" :
	return " SUM(DEVICE_".PROCESSOR_DEVICE.".specificity) / COUNT( DEVICE_".PROCESSOR_DEVICE.".ID) AS ".$NAME."_".$num.", ";
	break;
case "glpi_networking_ports.ifmac" :
	if ($type==COMPUTER_TYPE)
		return " GROUP_CONCAT( DISTINCT ".$pretable.$table.$addtable.".".$field." SEPARATOR '$$$$') AS ITEM_$num, GROUP_CONCAT( DISTINCT DEVICE_".NETWORK_DEVICE.".specificity  SEPARATOR '$$$$') AS ".$NAME."_".$num."_2, ";
	else return " GROUP_CONCAT( DISTINCT ".$pretable.$table.$addtable.".".$field." SEPARATOR '$$$$') AS ".$NAME."_$num, ";
	break;
case "glpi_licenses.serial" :
case "glpi_networking_ports.ifaddr" :
case "glpi_dropdown_netpoint.name" :
	return " GROUP_CONCAT( DISTINCT ".$pretable.$table.$addtable.".".$field." SEPARATOR '$$$$') AS ".$NAME."_".$num.", ";
	break;
default:
	if ($meta){
		
		if ($table!=$LINK_ID_TABLE[$type])
			return " GROUP_CONCAT( DISTINCT LCASE(".$pretable.$table.$addtable.".".$field.") SEPARATOR '$$$$') AS META_$num, ";
		else return " GROUP_CONCAT( DISTINCT LCASE(".$table.$addtable.".".$field.") SEPARATOR '$$$$') AS META_$num, ";

	}
	else 
		return $table.$addtable.".".$field." AS ITEM_$num, ";
	break;
}

}

/**
* Generic Function to add where to a request
*
*
*@param $field field of the item to add
*@param $table table of the item to add
*@param $val item num in the request
*@param $nott is it a negative serach ?
*@param $type device type
*@param $meta is a meta search (meta=2 in includes_search.php)
*
*@return select string
*
**/
function addWhere ($nott,$type,$table,$field,$val,$meta=0){
global $LINK_ID_TABLE,$lang;

$NOT="";
if ($nott) $NOT=" NOT";

if ($meta&&$LINK_ID_TABLE[$type]!=$table) $table.="_".$type;

$SEARCH=" $NOT LIKE '%".$val."%' ";
if ($val=="NULL") $SEARCH=" IS $NOT NULL ";

switch ($table.".".$field){
case "glpi_users.name" :
	return " ( $table.$field $SEARCH OR glpi_users.realname $SEARCH ) ";
	break;
case "glpi_device_hdd.specif_default" :
//	$larg=500;
//	return " ( DEVICE_".HDD_DEVICE.".specificity < ".($val+$larg)." AND DEVICE_".HDD_DEVICE.".specificity > ".($val-$larg)." ) ";
	return " $table.$field $NOT LIKE '%%' ";
	break;
case "glpi_device_ram.specif_default" :
//	$larg=50;
//	return " ( DEVICE_".RAM_DEVICE.".specificity < ".($val+$larg)." AND DEVICE_".RAM_DEVICE.".specificity > ".($val-$larg)." ) ";
	return " $table.$field $NOT LIKE '%%' ";
	break;
case "glpi_device_processor.specif_default" :
//	$larg=50;
//	return " ( DEVICE_".RAM_DEVICE.".specificity < ".($val+$larg)." AND DEVICE_".RAM_DEVICE.".specificity > ".($val-$larg)." ) ";
	return " $table.$field $NOT LIKE '%%' ";
	break;

case "glpi_networking_ports.ifmac" :
	if ($type==COMPUTER_TYPE)
		return " (  DEVICE_".NETWORK_DEVICE.".specificity $SEARCH OR $table.$field $SEARCH ) ";
	else return " $table.$field $SEARCH ";
	break;
case "glpi_contracts.end_date" :

		$search=array("/\&lt;/","/\&gt;/");
		$replace=array("<",">");
		$val=preg_replace($search,$replace,$val);
		if (ereg("([<>])(.*)",$val,$regs)){
			return " NOW() ".$regs[1]." ADDDATE(ADDDATE($table.begin_date, INTERVAL $table.duration MONTH), INTERVAL ".$regs[2]." MONTH) ";	
		}
		else {
			return " ADDDATE($table.begin_date, INTERVAL $table.duration MONTH) $SEARCH ";		
		}

	
	break;
// ajout jmd
case "glpi_contracts.echeance" :

		$search=array("/\&lt;/","/\&gt;/");
		$replace=array("<",">");
		$val=preg_replace($search,$replace,$val);
		if (ereg("([<>])(.*)",$val,$regs)){
			return " DATEDIFF(ADDDATE($table.begin_date, INTERVAL $table.duration MONTH),CURDATE() )".$regs[1].$regs[2]." ";
		}
		else {
			return " ADDDATE($table.begin_date, INTERVAL $table.duration MONTH) $SEARCH ";		
		}

	
	break;
// ajout jmd
case "glpi_contracts.echeance_preavis" :

		$search=array("/\&lt;/","/\&gt;/");
		$replace=array("<",">");
		$val=preg_replace($search,$replace,$val);
		if (ereg("([<>])(.*)",$val,$regs)){
			
<<<<<<< .mine
			return " $table.notice<>0 AND DATEDIFF(ADDDATE($table.begin_date, INTERVAL ($table.duration - $table.notice) MONTH),CURDATE() )".$regs[1].$regs[2]." ";
=======
			return " $table.notice<>0 AND DATEDIFF(ADDDATE($table.begin_date, INTERVAL ($table.duration-$table.notice) MONTH),CURDATE() )".$regs[1].$regs[2]." ";
>>>>>>> .r3131

		}
		else {
			return " ADDDATE($table.begin_date, INTERVAL ($table.duration - $table.notice) MONTH) $SEARCH ";		
		}

	
	break;

case "glpi_computers.date_mod":
case "glpi_printers.date_mod":
case "glpi_networking.date_mod":
case "glpi_peripherals.date_mod":
case "glpi_software.date_mod":
case "glpi_monitors.date_mod":
case "glpi_contracts.begin_date":
case "glpi_infocoms.buy_date":
case "glpi_infocoms.use_date":
		$search=array("/\&lt;/","/\&gt;/");
		$replace=array("<",">");
		$val=preg_replace($search,$replace,$val);
		if (ereg("([<>])(.*)",$val,$regs)){
			return " NOW() ".$regs[1]." ADDDATE($table.$field, INTERVAL ".$regs[2]." MONTH) ";	
		}
		else {
			$ADD="";	
			if ($nott) $ADD=" OR $table.$field IS NULL";
			return " ($table.$field $SEARCH ".$ADD." ) ";
		}
	break;
case "glpi_infocoms.value":
case "glpi_infocoms.warranty_value":
	$interval=100;
	$ADD="";
	if ($nott&&$val!="NULL") $ADD=" OR $table.$field IS NULL";
	if ($nott)
		return " ($table.$field < ".intval($val)."-$interval OR $table.$field > ".intval($val)."+$interval ".$ADD." ) ";
	else  return " (($table.$field >= ".intval($val)."-$interval AND $table.$field <= ".intval($val)."+$interval) ".$ADD." ) ";
	break;
case "glpi_infocoms.amort_time":
case "glpi_infocoms.warranty_duration":
	$ADD="";
	if ($nott&&$val!="NULL") $ADD=" OR $table.$field IS NULL";
	if ($nott)
		return " ($table.$field <> ".intval($val)." ".$ADD." ) ";
	else  return " ($table.$field = ".intval($val)."  ".$ADD." ) ";
	break;
case "glpi_infocoms.amort_type":
	$ADD="";
	if ($nott&&$val!="NULL") $ADD=" OR $table.$field IS NULL";
	if (eregi($val,getAmortTypeName(1))) $val=1;
	else if (eregi($val,getAmortTypeName(2))) $val=2;
	else $val=0;
	if ($nott)
		return " ($table.$field <> $val ".$ADD." ) ";
	else  return " ($table.$field = $val  ".$ADD." ) ";
	break;
case "glpi_users.active":
	$ADD="";
	if ($nott&&$val!="NULL") $ADD=" OR $table.$field IS NULL";

	if (eregi($val,$lang["choice"][1])) $val=1;
	else $val=0;
	if ($nott)
		return " ($table.$field <> $val ".$ADD." ) ";
	else  return " ($table.$field = $val  ".$ADD." ) ";
	break;

default:
	$ADD="";	
	if ($nott&&$val!="NULL") $ADD=" OR $table.$field IS NULL";
	return " ($table.$field $SEARCH ".$ADD." ) ";
	break;
}

}

/**
* Generic Function to display Items
*
*
*@param $field field to add
*@param $data array containing data results
*@param $num item num in the request
*@param $type device type
*
*
*@return string to print
*
**/
function giveItem ($type,$field,$data,$num){
global $cfg_glpi,$INFOFORM_PAGES,$HTMLRel,$cfg_glpi,$lang;

switch ($field){
	case "glpi_licenses.serial" :
	case "glpi_networking_ports.ifaddr" :
	case "glpi_dropdown_netpoint.name" :
		$out="";
		$split=explode("$$$$",$data["ITEM_$num"]);
		
		$count_display=0;
		for ($k=0;$k<count($split);$k++)
		if (strlen(trim($split[$k]))>0){
			if ($count_display) $out.= "<br>";
			$count_display++;
			$out.= $split[$k];
		}
		return $out;
	
		break;
	case "glpi_users.active" :
			return $lang["choice"][$data["ITEM_$num"]];
		break;

	case "glpi_users.name" :
		// print realname or login name
		if (!empty($data["ITEM_".$num."_2"]))
			return $data["ITEM_".$num."_2"];
		else return $data["ITEM_$num"];
		break;
	case "glpi_users.name.brut" :		
		$type=USER_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;
	case "glpi_computers.name" :
		$type=COMPUTER_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;
	case "glpi_printers.name" :
		$type=PRINTER_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;
	case "glpi_networking.name" :
		$type=NETWORKING_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;
	case "glpi_phones.name" :
		$type=PHONE_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;
	case "glpi_monitors.name" :
		$type=MONITOR_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;
	case "glpi_software.name" :
		$type=SOFTWARE_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
 		return $out;
		break;
	case "glpi_peripherals.name" :
		$type=PERIPHERAL_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;	
	case "glpi_cartridges_type.name" :
		$type=CARTRIDGE_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;
	case "glpi_consumables_type.name" :
		$type=CONSUMABLE_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;	
	case "glpi_contacts.name" :
		$type=CONTACT_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;	
	case "glpi_contracts.name" :
		$type=CONTRACT_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;	
	case "glpi_contracts.name.brut" :
		$type=CONTRACT_TYPE;
		$out= $data["ITEM_$num"];
		return $out;
		break;			

	case "glpi_enterprises.name" :
		$type=ENTERPRISE_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		if (!empty($data["ITEM_".$num."_2"]))
			$out.= "<a href='".$data["ITEM_".$num."_2"]."' target='_blank'><img src='".$HTMLRel."/pics/web.png' alt='website'></a>";
		return $out;
		break;	
	case "glpi_enterprises_infocoms.name" :
	case "glpi_enterprises.name.brut" :
		$type=ENTERPRISE_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data["ITEM_".$num."_3"]."\">";
		$out.= $data["ITEM_$num"];
		if (!empty($data["ITEM_".$num."_3"])&&($cfg_glpi["view_ID"]||(empty($data["ITEM_$num"])))) $out.= " (".$data["ITEM_".$num."_3"].")";
		$out.= "</a>";
		if (!empty($data["ITEM_".$num."_2"]))
			$out.= "<a href='".$data["ITEM_".$num."_2"]."' target='_blank'><img src='".$HTMLRel."/pics/web.png' alt='website'></a>";
		return $out;
		break;			
	case "glpi_docs.name" :		
		$type=DOCUMENT_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;
	case "glpi_type_docs.name" :		
		$type=TYPEDOC_TYPE;
		$isadmin=isNormal($_SESSION["glpitype"]);
		if ($isadmin)
			$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		else $out="";
		$out.= $data["ITEM_$num"];
		if ($isadmin && ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"]))) $out.= " (".$data["ID"].")";
		if ($isadmin)
			$out.= "</a>";
		return $out;
		break;
	case "glpi_links.name" :		
		$type=LINK_TYPE;
		$out= "<a href=\"".$cfg_glpi["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
		$out.= $data["ITEM_$num"];
		if ($cfg_glpi["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
		$out.= "</a>";
		return $out;
		break;		
	case "glpi_type_docs.icon" :
		if (!empty($data["ITEM_$num"]))
			return "<img style='vertical-align:middle;' alt='' src='".$HTMLRel.$cfg_glpi["typedoc_icon_dir"]."/".$data["ITEM_$num"]."'>";
		else return "&nbsp;";
	break;	

	case "glpi_docs.filename" :		
		return getDocumentLink($data["ITEM_$num"]);
	break;		
	case "glpi_docs.link" :
	case "glpi_enterprises.website" :
		if (!empty($data["ITEM_$num"]))
			return "<a href=\"".$data["ITEM_$num"]."\" target='_blank'>".$data["ITEM_$num"]."</a>";
		else return "&nbsp;";
	break;	
	case "glpi_enterprises.email" :
	case "glpi_contacts.email" :
	case "glpi_users.email" :
		if (!empty($data["ITEM_$num"]))
			return "<a href='mailto:".$data["ITEM_$num"]."'>".$data["ITEM_$num"]."</a>";
		else return "&nbsp;";
	break;	
	case "glpi_device_hdd.specif_default" :
	case "glpi_device_ram.specif_default" :
	case "glpi_device_processor.specif_default" :
			return $data["ITEM_".$num];
	break;
	case "glpi_networking_ports.ifmac" :
		$out="";
		if ($type==COMPUTER_TYPE){
			if (!empty($data["ITEM_".$num."_2"])){
				$split=explode("$$$$",$data["ITEM_".$num."_2"]);
				$count_display=0;
				for ($k=0;$k<count($split);$k++)
				if (strlen(trim($split[$k]))>0){	
					if ($count_display) $out.= "<br>";
					else $out.= "hw=";
					$count_display++;
					$out.= $split[$k];
				}
				
				if (!empty($data["ITEM_".$num])) $out.= "<br>";
			}
		
			if (!empty($data["ITEM_".$num])){
				$split=explode("$$$$",$data["ITEM_".$num]);
				$count_display=0;
				for ($k=0;$k<count($split);$k++)
				if (strlen(trim($split[$k]))>0){	
					if ($count_display) $out.= "<br>";
					else $out.= "port=";
					$count_display++;
					$out.= $split[$k];
				}

			}
		} else {
			$split=explode("$$$$",$data["ITEM_".$num]);
			$count_display=0;
			for ($k=0;$k<count($split);$k++)
			if (strlen(trim($split[$k]))>0){	
				if ($count_display) $out.= "<br>";
				$count_display++;
				$out.= $split[$k];
			}
		}
		return $out;
	break;
	case "glpi_contracts.duration":
	case "glpi_contracts.notice":
		return $data["ITEM_$num"]." ".$lang["financial"][57];
		break;
	case "glpi_computers.date_mod":
	case "glpi_printers.date_mod":
	case "glpi_networking.date_mod":
	case "glpi_peripherals.date_mod":
	case "glpi_phones.date_mod":
	case "glpi_software.date_mod":
	case "glpi_monitors.date_mod":
		return convDateTime($data["ITEM_$num"]);
		break;
	case "glpi_contracts.end_date":
		if ($data["ITEM_$num"]!=''&&$data["ITEM_$num"]!="0000-00-00")
			return getWarrantyExpir($data["ITEM_$num"],$data["ITEM_".$num."_2"]);
		break;
	case "glpi_contracts.echeance_preavis": // ajout jmd
		return "toto";
	case "glpi_contracts.echeance": // ajout jmd
		return "toto";
	case "glpi_contracts.begin_date":
	case "glpi_infocoms.buy_date":
	case "glpi_infocoms.use_date":
		return convDate($data["ITEM_$num"]);
		break;
	case "glpi_infocoms.amort_time":
		return $data["ITEM_$num"]." ".$lang["financial"][9];
		break;
	case "glpi_infocoms.warranty_duration":
		return $data["ITEM_$num"]." ".$lang["financial"][57];
		break;
	case "glpi_infocoms.amort_type":
		return getAmortTypeName($data["ITEM_$num"]);
		break;

	default:
		return $data["ITEM_$num"];
		break;
}

}


/**
* Generic Function to get transcript table name
*
*
*@param $table reference table
*@param $device_type device type ID
*@param $meta_type meta table type ID
*
*@return Left join string
*
**/
function translate_table($table,$device_type=0,$meta_type=0){

$ADD="";
if ($meta_type) $ADD="_".$meta_type;

switch ($table){
	case "glpi_computer_device":
		if ($device_type==0)
			return $table.$ADD;
		else return "DEVICE_".$device_type.$ADD;
		break;
	default :
		return $table.$ADD;
		break;
}

}

/**
* Generic Function to add left join to a request
*
*
*@param $type reference ID
*@param $ref_table reference table
*@param $already_link_tables array of tables already joined
*@param $new_table new table to join
*@param $device_type device_type for search on computer device
*@param $meta is it a meta item ?
*@param $meta_type meta type table
*
*
*@return Left join string
*
**/
function addLeftJoin ($type,$ref_table,&$already_link_tables,$new_table,$device_type=0,$meta=0,$meta_type=0){


// Rename table for meta left join
$AS="";
$nt=$new_table;
$addmetanum="";
$rt=$ref_table;
if ($meta) {
	$AS= " AS ".$new_table."_".$meta_type;
	$nt=$new_table."_".$meta_type;
	//$rt.="_".$meta_type;
}

if (in_array(translate_table($new_table,$device_type,$meta_type),$already_link_tables)) return "";
else array_push($already_link_tables,translate_table($new_table,$device_type,$meta_type));

switch ($new_table){
	case "glpi_dropdown_locations":
		return " LEFT JOIN $new_table $AS ON ($rt.location = $nt.ID) ";
		break;
	case "glpi_dropdown_contract_type":
		return " LEFT JOIN $new_table $AS ON ($rt.contract_type = $nt.ID) ";
		break;
	case "glpi_type_computers":
	case "glpi_type_networking":
	case "glpi_type_printers":
	case "glpi_type_monitors":
	case "glpi_type_phones":
	case "glpi_dropdown_contact_type":
	case "glpi_dropdown_consumable_type":
	case "glpi_dropdown_cartridge_type":
	case "glpi_dropdown_enttype":
	case "glpi_type_peripherals":
		return " LEFT JOIN $new_table $AS ON ($rt.type = $nt.ID) ";
		break;
	case "glpi_dropdown_phone_power":
		return " LEFT JOIN $new_table $AS ON ($rt.power = $nt.ID) ";
		break;
	case "glpi_dropdown_model":
	case "glpi_dropdown_model_printers":
	case "glpi_dropdown_model_monitors":
	case "glpi_dropdown_model_peripherals":
	case "glpi_dropdown_model_phones":
	case "glpi_dropdown_model_networking":
		return " LEFT JOIN $new_table $AS ON ($rt.model = $nt.ID) ";
		break;
	case "glpi_dropdown_os":
	if ($type==SOFTWARE_TYPE)
		return " LEFT JOIN $new_table $AS ON ($rt.platform = $nt.ID) ";
	else 
		return " LEFT JOIN $new_table $AS ON ($rt.os = $nt.ID) ";
		break;
	case "glpi_dropdown_os_version":
		return " LEFT JOIN $new_table $AS ON ($rt.os_version = $nt.ID) ";
		break;
	case "glpi_dropdown_os_sp":
		return " LEFT JOIN $new_table $AS ON ($rt.os_sp = $nt.ID) ";
		break;
	case "glpi_networking_ports":
		$out="";
		// Add networking device for computers
		if ($type==COMPUTER_TYPE)
			$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_computer_device",NETWORK_DEVICE,$meta,$meta_type);
		
		return $out." LEFT JOIN $new_table $AS ON ($rt.ID = $nt.on_device AND $nt.device_type='$type') ";
		break;
	case "glpi_dropdown_netpoint":
		// Link to glpi_networking_ports before
		$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_networking_ports");
		
		return $out." LEFT JOIN $new_table $AS ON (glpi_networking_ports.netpoint = $nt.ID) ";
		break;
	case "glpi_users":
		return " LEFT JOIN $new_table $AS ON ($rt.tech_num = $nt.ID) ";
		break;
	case "glpi_enterprises":
		return " LEFT JOIN $new_table $AS ON ($rt.FK_glpi_enterprise = $nt.ID) ";
		break;
	case "glpi_enterprises_infocoms":
			$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_infocoms");
		return $out." LEFT JOIN glpi_enterprises AS glpi_enterprises_infocoms ON (glpi_infocoms.FK_enterprise = $nt.ID) ";
		break;
	case "glpi_dropdown_budget":
			$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_infocoms");
		return $out." LEFT JOIN $new_table $AS ON (glpi_infocoms.budget = $nt.ID) ";
		break;
	case "glpi_infocoms":
		return " LEFT JOIN $new_table $AS ON ($rt.ID = $nt.FK_device AND $nt.device_type='$type') ";
		break;
	case "glpi_contract_device":
		return " LEFT JOIN $new_table $AS ON ($rt.ID = $nt.FK_device AND $nt.device_type='$type') ";
		break;
	case "glpi_state_item":
		return " LEFT JOIN $new_table $AS ON ($rt.ID = $nt.id_device AND $nt.device_type='$type') ";
		break;
	case "glpi_dropdown_state":
		// Link to glpi_state_item before
		$out=addLeftJoin($type,$rt,$already_link_tables,"glpi_state_item");
		
		return $out." LEFT JOIN $new_table $AS ON (glpi_state_item.state = $nt.ID) ";
		break;
	
	case "glpi_contracts":
		// Link to glpi_networking_ports before
		$out=addLeftJoin($type,$rt,$already_link_tables,"glpi_contract_device");
		
		return $out." LEFT JOIN $new_table $AS ON (glpi_contract_device.FK_contract = $nt.ID) ";
		break;
	case "glpi_dropdown_network":
		return " LEFT JOIN $new_table $AS ON ($rt.network = $nt.ID) ";
		break;			
	case "glpi_dropdown_domain":
		return " LEFT JOIN $new_table $AS ON ($rt.domain = $nt.ID) ";
		break;			
	case "glpi_dropdown_firmware":
		return " LEFT JOIN $new_table $AS ON ($rt.firmware = $nt.ID) ";
		break;			
	case "glpi_dropdown_rubdocs":
		return " LEFT JOIN $new_table $AS ON ($rt.rubrique = $nt.ID) ";
		break;
	case "glpi_licenses":
		if (!$meta)
			return " LEFT JOIN $new_table $AS ON ($rt.ID = $nt.sID) ";
		else return "";
		break;	
	case "glpi_computer_device":
		if ($device_type==0)
			return " LEFT JOIN $new_table $AS ON ($rt.ID = $nt.FK_computers ) ";
		else return " LEFT JOIN $new_table AS DEVICE_".$device_type." ON ($rt.ID = DEVICE_".$device_type.".FK_computers AND DEVICE_".$device_type.".device_type='$device_type') ";
		break;	
	case "glpi_device_processor":

		// Link to glpi_networking_ports before
		$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_computer_device",PROCESSOR_DEVICE,$meta,$meta_type);
		
		return $out." LEFT JOIN $new_table $AS ON (DEVICE_".PROCESSOR_DEVICE.".FK_device = $nt.ID) ";
		break;		
	case "glpi_device_ram":
		$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_computer_device",RAM_DEVICE,$meta,$meta_type);
		
		return $out." LEFT JOIN $new_table $AS ON (DEVICE_".RAM_DEVICE.".FK_device = $nt.ID) ";
		break;		
	case "glpi_device_iface":
		// Link to glpi_networking_ports before
		$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_computer_device",NETWORK_DEVICE,$meta,$meta_type);
		
		return $out." LEFT JOIN $new_table $AS ON (DEVICE_".NETWORK_DEVICE.".FK_device = $nt.ID) ";
		break;	
	case "glpi_device_sndcard":
		// Link to glpi_networking_ports before
		$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_computer_device",SND_DEVICE,$meta,$meta_type);
		
		return $out." LEFT JOIN $new_table $AS ON (DEVICE_".SND_DEVICE.".FK_device = $nt.ID) ";
		break;		
	case "glpi_device_gfxcard":
		// Link to glpi_networking_ports before
		$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_computer_device",GFX_DEVICE,$meta,$meta_type);
		
		return $out." LEFT JOIN $new_table $AS ON (DEVICE_".GFX_DEVICE.".FK_device = $nt.ID) ";
		break;	
	case "glpi_device_moboard":
		// Link to glpi_networking_ports before
		$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_computer_device",MOBOARD_DEVICE,$meta,$meta_type);
		
		return $out." LEFT JOIN $new_table $AS ON (DEVICE_".MOBOARD_DEVICE.".FK_device = $nt.ID) ";
		break;	
	case "glpi_device_hdd":
		// Link to glpi_networking_ports before
		$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_computer_device",HDD_DEVICE,$meta,$meta_type);
		
		return $out." LEFT JOIN $new_table $AS ON (DEVICE_".HDD_DEVICE.".FK_device = $nt.ID) ";
		break;

	default :
		return "";
		break;
}
}


/**
* Generic Function to add left join for meta items
*
*
*@param $reference_type reference item type ID 
*@param $to_type item type to add
*@param $already_link_tables2 array of tables already joined
*@param $num meta number
*
*
*@return Meta Left join string
*
**/
function addMetaLeftJoin($from_type,$to_type,&$already_link_tables2,$num,$null){
global $LINK_ID_TABLE;
	
$LINK=" INNER JOIN ";
if ($null)
	$LINK=" LEFT JOIN ";

	switch ($from_type){
		case COMPUTER_TYPE :
			switch ($to_type){
/*				case NETWORKING_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[NETWORKING_TYPE]."_$num");
					return " $LINK glpi_networking_ports as META_ports ON (glpi_computers.ID = META_ports.on_device AND META_ports.device_type='".COMPUTER_TYPE."') ".
						   " $LINK glpi_networking_wire as META_wire1 ON (META_ports.ID = META_wire1.end1) ".
						   " $LINK glpi_networking_ports as META_ports21 ON (META_ports21.device_type='".NETWORKING_TYPE."' AND META_wire1.end2 = META_ports21.ID ) ".
						   " $LINK glpi_networking_wire as META_wire2 ON (META_ports.ID = META_wire2.end2) ".
						   " $LINK glpi_networking_ports as META_ports22 ON (META_ports22.device_type='".NETWORKING_TYPE."' AND META_wire2.end1 = META_ports22.ID ) ".
						   " $LINK glpi_networking$num ON (glpi_networking$num.ID = META_ports22.on_device OR glpi_networking.ID = META_ports21.on_device)";
				break;
*/				
				case PRINTER_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[PRINTER_TYPE]);
					return " $LINK glpi_connect_wire AS META_conn_print_$num ON (META_conn_print_$num.end2=glpi_computers.ID  AND META_conn_print_$num.type='".PRINTER_TYPE."') ".
						   " $LINK glpi_printers ON (META_conn_print_$num.end1=glpi_printers.ID) ";
				break;				
				case MONITOR_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[MONITOR_TYPE]);
					return " $LINK glpi_connect_wire AS META_conn_mon_$num ON (META_conn_mon_$num.end2=glpi_computers.ID  AND META_conn_mon_$num.type='".MONITOR_TYPE."') ".
						   " $LINK glpi_monitors ON (META_conn_mon_$num.end1=glpi_monitors.ID) ";
				break;				
				case PERIPHERAL_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[PERIPHERAL_TYPE]);
					return " $LINK glpi_connect_wire AS META_conn_periph_$num ON (META_conn_periph_$num.end2=glpi_computers.ID  AND META_conn_periph_$num.type='".PERIPHERAL_TYPE."') ".
						   " $LINK glpi_peripherals ON (META_conn_periph_$num.end1=glpi_peripherals.ID) ";
				break;				
				case PHONE_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[PHONE_TYPE]);
					return " $LINK glpi_connect_wire AS META_conn_phones_$num ON (META_conn_phones_$num.end2=glpi_computers.ID  AND META_conn_phones_$num.type='".PHONE_TYPE."') ".
						   " $LINK glpi_phones ON (META_conn_phones_$num.end1=glpi_phones.ID) ";
				break;			

				case SOFTWARE_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[SOFTWARE_TYPE]);
					return " $LINK glpi_inst_software as META_inst_$num ON (META_inst_$num.cID = glpi_computers.ID) ".
						   " $LINK glpi_licenses as META_glpi_licenses_$num ON ( META_inst_$num.license=META_glpi_licenses_$num.ID ) ".
						   " $LINK glpi_software ON (META_glpi_licenses_$num.sID = glpi_software.ID) "; 
				break;
				}
			break;
		case MONITOR_TYPE :
			switch ($to_type){
				case COMPUTER_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[COMPUTER_TYPE]);
					return " $LINK glpi_connect_wire AS META_conn_mon_$num ON (META_conn_mon_$num.end1=glpi_monitors.ID  AND META_conn_mon_$num.type='".MONITOR_TYPE."') ".
						   " $LINK glpi_computers ON (META_conn_mon_$num.end2=glpi_computers.ID) ";
				
				break;
			}
			break;		
		case PRINTER_TYPE :
			switch ($to_type){
				case COMPUTER_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[COMPUTER_TYPE]);
					return " $LINK glpi_connect_wire AS META_conn_mon_$num ON (META_conn_mon_$num.end1=glpi_printers.ID  AND META_conn_mon_$num.type='".PRINTER_TYPE."') ".
						   " $LINK glpi_computers ON (META_conn_mon_$num.end2=glpi_computers.ID) ";
				
				break;
			}
			break;		
		case PERIPHERAL_TYPE :
			switch ($to_type){
				case COMPUTER_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[COMPUTER_TYPE]);
					return " $LINK glpi_connect_wire AS META_conn_mon_$num ON (META_conn_mon_$num.end1=glpi_peripherals.ID  AND META_conn_mon_$num.type='".PERIPHERAL_TYPE."') ".
						   " $LINK glpi_computers ON (META_conn_mon_$num.end2=glpi_computers.ID) ";
				
				break;
			}
			break;		
		case PHONE_TYPE :
			switch ($to_type){
				case COMPUTER_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[COMPUTER_TYPE]);
					return " $LINK glpi_connect_wire AS META_conn_mon_$num ON (META_conn_mon_$num.end1=glpi_phones.ID  AND META_conn_mon_$num.type='".PHONE_TYPE."') ".
						   " $LINK glpi_computers ON (META_conn_mon_$num.end2=glpi_computers.ID) ";
				
				break;
			}
			break;
		case SOFTWARE_TYPE :
			switch ($to_type){
				case COMPUTER_TYPE :
					array_push($already_link_tables2,$LINK_ID_TABLE[COMPUTER_TYPE]);
				return " $LINK glpi_licenses as META_glpi_licenses_$num ON ( META_glpi_licenses_$num.sID = glpi_software.ID ) ".
					   " $LINK glpi_inst_software as META_inst_$num ON (META_inst_$num.license = META_glpi_licenses_$num.ID) ".
					   " $LINK glpi_computers ON (META_inst_$num.cID = glpi_computers.ID) ";
					
				break;
			}
			break;		
		
		
		}
	
}


/**
* Convert an array to be add in url
*
*
* @param $name name of array
* @param $array array to be added
* @return string to add
*
*/
function getMultiSearchItemForLink($name,$array){
	
	$out="";
	if (is_array($array)&&count($array)>0)
	foreach($array as $key => $val){
//		if ($name!="link"||$key!=0)
			$out.="&amp;".$name."[$key]=".$val;
	}
	return $out;
	
}

?>
