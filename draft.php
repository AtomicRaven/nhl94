<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

        $sBy = "Sorted By: Overall";  
        $sOrder = "DESC";
        $nSortOrder = "DESC";
        $s = "";
        $leagueid = 1;
        $teamid = -1;
        $draftSheet = "";

        $pFilter = array(
            'forwards' => "checked",
            'defense' => "checked",
            'goalies' => "checked"
        );       

        if (isset($_GET["binId"])){
            $leagueid = $_GET["binId"];
        }

        if (isset($_GET["teamId"])){
            $teamid = $_GET["teamId"];
        }

        if(isset($_GET["draftSheet"])){
            $draftSheet = $_GET["draftSheet"];
            AddDraftSheet($draftSheet, $leagueid, true);
        }

        $selectedLg = GetLeague($leagueid);
        $leagueTypes = GetLeagueTypes();
        $leagueTypeSelectBox = CreateSelectBox("binId", "Select League", $leagueTypes, "LeagueID", "Name", "this.form.submit()", $leagueid);
        
        //Need to massivley simplfy this logic
        if (isset($_GET["sOrder"]) && !empty($_GET["sOrder"])) {
            
            if (isset($_GET["forwards"])){
                $pFilter['forwards'] = 'checked';                
            }else{
                $pFilter['forwards'] = '';
            }

            if (isset($_GET["defense"])){
                $pFilter['defense'] = 'checked';                
            }else{
                $pFilter['defense'] = '';
            }

            if (isset($_GET["goalies"])){
                $pFilter['goalies'] = 'checked';                
            }else{
                $pFilter['goalies'] = '';
            }

            //print_r($pFilter);            
            
            if (isset($_GET["s"]) && !empty($_GET["s"])) {

                $s =  $_GET["s"];
                $sOrder = $_GET["sOrder"]; 

                //echo "Session: " . $_SESSION['s'] . "<br/>";
                //echo "SortBy: " . $s . "<br/>";

                if(isset($_SESSION['s'])){                

                    if($_SESSION['s'] == $s){                                       

                        if($sOrder == "DESC"){
                            $nSortOrder = "ASC";
                            $sOrder = "ASC";
                        }else{
                            $nSortOrder = "DESC";               
                            $sOrder = "DESC";
                        }

                        //echo "Session equals s so flipping" . "<br/>";    

                    }else{

                        //echo "Session did not equal s" . "<br/>";
                        //Do Nothing with sortOrder
                        $nSortOrder = $sOrder;
                        
                    }
                }

                $_SESSION['s'] = $s;
            }

        }

        if (isset($_GET["s"]) && !empty($_GET["s"])) {

            $s =  $_GET["s"];
            $sBy = "Sorted By: " . $s . " " . $sOrder;              
        
        }else{

            $sBy = "Sorted By: Overall". " " . $sOrder;  
        }            

        //Get all the player comparisons
        $str = $_SERVER['QUERY_STRING'];
        $compare = false;
        parse_str($str, $playerArray);

        foreach($playerArray as $key => $value){            

            if (strpos($key, 'player') !== false) {
                $compare = true;
            }else{
                unset($playerArray[$key]);
            }            
        }       

         if(!$compare){
            
            $rosters = GetRosters($pFilter, $leagueid, $teamid);
            $vis = "show";

        }else{

            $rosters = ComparePlayers($playerArray, $leagueid);
            $vis = "hidden";
        }
        
        
        //echo "TeamId: " . $teamid;
        $teamSelectBox = CreateSelectBox("teamId", "Select Team", GetTeams($leagueid), "TeamID", "Team", "this.form.submit()", $teamid);

        $draftLinkSheet = "";

        if($selectedLg["DraftSheet"] != null){
            $dArray = explode("/export",$selectedLg["DraftSheet"]); 
            $draftLinkSheet = $dArray[0];
        }

?><!DOCTYPE HTML
<html>
<head>
<title>Player Drafterer</title>
<?php include_once './_INCLUDES/01_HEAD.php';

    if($s != ""){
        print_r("<script>var s='" . $s . "';</script>");
    }else{
        print_r("<script>var s='';</script>");
    }
 ?>

</head>

<body>

		<div id="page">
		
				<?php include_once './_INCLUDES/02_NAV.php'; ?>
				
				<div id="main">
					<?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
					<h1>Player Drafterer</h1>	

                    <a href="<?=$draftLinkSheet?>" target="draftSheet"><?=$draftLinkSheet?></a><br/><br/>
					<a href="filteredPlayers.php?binId=<?=$leagueid?>" class="square-button" target="_blank">Filtered Players List</a>					
                     
                    <form name="rosterForm" method="get" action="draft.php">

                        &nbsp; <button id="clearBtn" type="button" onclick="javascript:location.href='draft.php?teamId=<?=$teamid?>&binId=<?=$leagueid?>'" style="margin-top: 10px;">Clear</button>
                        &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Compare</button>   

                        <div style="display:inline;visibility: <?=$vis?>;">
                            <input type="checkbox" value="true" name="forwards" onclick="RosterSubmit()" <?=$pFilter['forwards']?>/>Forwards
                            <input type="checkbox" value="true" name="defense" onclick="RosterSubmit()" <?=$pFilter['defense']?>/>Defense
                            <input type="checkbox" value="true" name="goalies" onclick="RosterSubmit()" <?=$pFilter['goalies']?>/>Goalies

                            <?php
                            
                                //if ($LOGGED_IN == true && $_SESSION['Admin'] == true){                                    
                                    echo $teamSelectBox;
                                    echo $leagueTypeSelectBox;                                    
                                //}
                            ?>

                        </div>

                        <div style="margin-top: 10px;"><?=$sBy?></div><br/>
                        <table class="standard smallify leader">
                                <tr class="heading">
                                    <td class="c"></td>
                                    <td class="c">Rnk</td>
                                    <td class="c">Nm</td>
                                    <td class="c">#</td>
                                    <td class="c"><button type="submit" name="s" value="Handed">Hnd</button></td>
                                    <td class="c"><button type="submit" name="s" value="Overall">Ovrll</button></td>
                                    <td class="c"><button type="submit" name="s" value="Team">Tm</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Pos">Pos</button></td>                                                                                                  
                                    <td class="c"><button type="submit" name="s" value="Weight">Wgt</button></td>
                                    <td class="c"><button type="submit" name="s" value="Checking">Chk</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="ChkAbl">ChkAbl</button></td>  
                                    <td class="c"><button type="submit" name="s" value="ShotP">ShP</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="ShotA">ShA</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Speed">Spd</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Agility">Agl</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Stick">Stk</button></td>                                    
                                    <td class="c"><button type="submit" name="s" value="Pass">Pass</button></td>
                                    <td class="c"><button type="submit" name="s" value="Off">OffA</button></td>
                                    <td class="c"><button type="submit" name="s" value="Def">DefA</button></td>
                                    <input id="sOrder" type="hidden" name="sOrder" value="<?=$nSortOrder?>"/>
                                </tr>
                                
                                <?php                                    
                                    $sortedPlayers = array();
                                    $draftedPlayers = array();
                                    $diff = false;                                    
                                    
                                    if($selectedLg["DraftSheet"] != null){

                                        $diff = true;
                                        $file = fopen($selectedLg["DraftSheet"],"r");
                                        
                                        while(!feof($file))
                                        {
                                            $draftedPlayers[] = fgetcsv($file)[3];                                    
                                        }

                                        fclose($file);
                                    }else{
                                        
                                      echo 'There is no DraftSheet for this League';

                                    }
                                    
                                    $count = 0;
                                    while($row = mysqli_fetch_array($rosters)){                                          

                                        if($row["Team"] != "ASW" && $row["Team"] != "ASE"){
                                            
                                            $hField = $row["H/F"];

                                            if ($hField & 1) {
                                                $handed = 'R';
                                            } else { 
                                                $handed = 'L';
                                            }

                                                //$handed .= " " . $hField;

                                            $overall = CalculateOverallRanking($row);
                                            $ChkAbl = round((6 * $row["Wgt"] + (10 * $row["ChK"]) -13) /8, 1);

                                            $sortedPlayers[] = array(
                                                            "ID"=>$row["PlayerID"],
                                                            "Name"=>$row["First"] . " " . $row["Last"],
                                                            "Num"=>$row["JNo"],
                                                            "Handed"=>$handed,
                                                            "Overall"=>$overall,
                                                            "Team"=>$row["Team"],
                                                            "Pos"=>$row["Pos"],
                                                            "Weight"=>$row["Wgt"],
                                                            "Checking"=>$row["ChK"],
                                                            "ChkAbl"=>$ChkAbl,
                                                            "ShotP"=>$row["ShP"],
                                                            "ShotA"=>$row["ShA"],
                                                            "Speed"=>$row["Spd"],
                                                            "Agility"=>$row["Agl"],
                                                            "Stick"=>$row["StH"],
                                                            "Pass"=>$row["Pas"],
                                                            "Off"=>$row["OfA"],
                                                            "Def"=>$row["DfA"]
                                            );
                                        }
                                    }

                                    //Filter out all the drafted players
                                    if($diff == true){                                    
                                        
                                        foreach($draftedPlayers as $player){                                                         
                                            
                                            if (($key = array_search($player, array_column($sortedPlayers, 'Name'))) !== false) {
                                                unset($sortedPlayers[$key]);
                                                $sortedPlayers = array_values($sortedPlayers);
                                                
                                                $count++;
                                                //echo $count . ") FOUND: '" . $player ."'</br>";
                                                //echo $count .")'" . $sortedPlayers[$key] ."'</br>";                                                
                                            }                                            
                                        }

                                        //print_r($sortedPlayers);

                                        echo "<h2>" . $count ." players filtered out </h2>";

                                    }

                                   

                                    //Sort by Overall
                                    if($s!=""){
                                        //usort($sortedPlayers, 'SortBy');
                                        usort($sortedPlayers, function($a, $b) use ($s, $sOrder) {
                                            //$myExtraArgument is available in this scope
                                            //perform sorting, return -1, 0, 1
                                            return SortBy($a, $b, $s, $sOrder);
                                        });
                                    }else{
                                        usort($sortedPlayers, 'SortByOverall');
                                    }                                                                       
                                    
                                   $i = 0;
                                   $array = array_values($playerArray);
                                   foreach($sortedPlayers as $p){                                         

                                       $checked = "";

                                       if($compare){                                            
                                            $checked = "checked";                                                 
                                            
                                        }

                                       $i++;
                                ?>
                        
                                 <tr class="<?php print $stripe[$i & 1]; ?>">
                                    
                                    <td class="c"><input type="checkbox" name="player<?=$i?>" value="<?=$p["ID"]?>" <?=$checked?>/></td>
                                    <td class="c"><?=$i?></td>                                    
                                    <td class="c"><?=$p["Name"]?></td>
                                    <td class="c"><?=$p["Num"]?></td>
                                    <td class="c"><?=$p["Handed"]?></td>
                                    <td class="c"><?=$p["Overall"]?></td>
                                    <td class="c"><?=$p["Team"]?></td>
                                    <td class="c"><?=$p["Pos"]?></td>
                                    <td class="c"><?=$p["Weight"]?></td>
                                    <td class="c"><?=$p["Checking"]?></td>
                                    <td class="c"><?=$p["ChkAbl"]?></td>
                                    <td class="c"><?=$p["ShotP"]?></td>
                                    <td class="c"><?=$p["ShotA"]?></td>
                                    <td class="c"><?=$p["Speed"]?></td>
                                    <td class="c"><?=$p["Agility"]?></td>
                                    <td class="c"><?=$p["Stick"]?></td>
                                    <td class="c"><?=$p["Pass"]?></td>
                                    <td class="c"><?=$p["Off"]?></td>
                                    <td class="c"><?=$p["Def"]?></td>
                                </tr>					                           
                        
                                <?php
                                    }
                                ?>
                        </table>

                        &nbsp; <button id="clearBtn" type="button" onclick="javascript:location.href='draft.php'" style="margin-top: 10px;">Clear</button>                       
                        &nbsp; <button id="submitBtn" type="submit" style="margin-top: 10px;">Compare</button> 
                        
                    </form>
                </div><!-- end: #page -->	
		
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./js/default.js"></script>
</body>
</html>                                