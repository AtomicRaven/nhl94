<?php

		session_start();
		$ADMIN_PAGE = false;
		include_once './_INCLUDES/00_SETUP.php';
		include_once './_INCLUDES/dbconnect.php';	

if ($LOGGED_IN == true && $_SESSION['Admin'] == true){
    
?>


<!DOCTYPE HTML>
<html>
<head>
<title>Compare Player</title>
    <?php include_once './_INCLUDES/01_HEAD.php'; ?>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"/>

    <script> var leagueType = <?=$leagueType?>; </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>        
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>	
    <script src="./js/sortable.js"></script>        
    <script src="./js/app.js"></script>
</head>

<body>
    <div id="page">
    
        <?php include_once './_INCLUDES/02_NAV.php'; ?>
        
        <div id="main">
            <?php include_once './_INCLUDES/03_LOGIN_INFO.php'; ?>
            <h1>Compare Player</h1>			
            <a href="comparePlayer.php" class="square-button">Old Roster Page</a>			                    
            
                <div ng-app="myApp">

                    <div ng-controller="PostsCtrl">
                        &nbsp; <button id="clearBtn" type="button" ng-click="Reset()" style="margin-top: 10px;">Clear</button>
                        &nbsp; <button id="submitBtn" type="button" ng-click="ComparePlayers()" style="margin-top: 10px;">Compare</button>


                        <div style="display:inline;visibility: <?=$vis?>;">
                            <input type="text" class="form-control" placeholder="Search Player" ng-model="searchPlayer">
                            Filter:
                            <input type="checkbox" ng-model="Filter.forwards" ng-true-value="'F'" data-ng-false-value="''"/> Forwards
                            <input type="checkbox" ng-model="Filter.defense" ng-true-value="'D'" data-ng-false-value="''"/> Defense
                            <input type="checkbox" ng-model="Filter.goalies" ng-true-value="'G'" data-ng-false-value="''"/> Goalies

                            <select ng-model="selectedTeams" ng-options="item as item.Team for item in teams track by item.TeamID" ng-change="ChangeTeams(selectedTeams)">
                                <option value="">Select Team</option>
                            </select>

                            <select ng-model="selectedLeagues" ng-options="item as item.Name for item in leagues track by item.LeagueID" ng-change="ChangeLeague(selectedLeagues)">
                                <option value="">Select League</option>
                            </select>
                        </div>

                        <div style="margin-left:10px;">
                            <ul ui-sortable="sortableOptions" ng:model="list" >
                                <li ng-repeat="key in list" >                                    
                                    <input type="checkbox" ng-model="key.selected" ng-true-value="true" data-ng-false-value="false" ng-click="ChangeSortOrder()"/>
                                    {{key.name}} 
                                    <div class='link' style='display:inline;' ng-click="ReverseAttrSort($index, key.name)">
                                        <span ng-show="key.dir == '-'" class="fa fa-caret-down"></span>
                                        <span ng-show="key.dir == '+'" class="fa fa-caret-up"></span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div style="margin-top: 10px;margin-left:10px;">SortedBy: {{sortType}}</div><br/>

                        <table class="standard smallify leader">
                                <tr class="heading">
                                    <td class="c"><input type="checkbox" ng-click="toggleAll()" ng-model="isAllSelected"></td>
                                    <td class="c">Rnk</td>
                                    <td class="c">Nm</td>
                                    <td class="c">#</td>
                                    <td class="c">
                                       <div class='link' ng-click="sortType = 'Handed'; ReverseSort()">Hnd
                                            <span ng-show="sortType == 'Handed' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Handed' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div>
                                    </td>
                                    <td class="c">
                                       <div class='link' ng-click="sortType = 'Overall'; ReverseSort()">Ovrll
                                            <span ng-show="sortType == 'Overall' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Overall' && !sortReverse" class="fa fa-caret-up"></span>
                                        </div>
                                    </td>
                                    <td class="c">
                                       <div class='link' ng-click="sortType = 'Team'; ReverseSort()">Tm
                                            <span ng-show="sortType == 'Team' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Team' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div>
                                    </td>                                    
                                    <td class="c">
                                        <div class='link' ng-click="sortType = 'Pos'; ReverseSort()">Pos
                                            <span ng-show="sortType == 'Pos' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Pos' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div>
                                    </td>                                                                                                  
                                    <td class="c">
                                       <div class='link' ng-click="sortType = 'Weight'; ReverseSort()">Wgt
                                            <span ng-show="sortType == 'Weight' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Weight' && !sortReverse" class="fa fa-caret-up"></span>
                                        </div>
                                    </td>
                                    <td class="c">
                                        <div class='link' ng-click="sortType = 'Checking'; ReverseSort()">Chk
                                            <span ng-show="sortType == 'Checking' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Checking' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div>
                                    </td>                                    
                                    <td class="c">
                                        <div class='link' ng-click="sortType = 'ChkAbl'; ReverseSort()">ChkAbl
                                            <span ng-show="sortType == 'ChkAbl' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'ChkAbl' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div>
                                    </td> 
                                    <td class="c">
                                        <div class='link' ng-click="sortType = 'ShotP'; ReverseSort()">ShP
                                            <span ng-show="sortType == 'ShotP' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'ShotP' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div>
                                    </td>                                    
                                    <td class="c">
                                        <div class='link' ng-click="sortType = 'ShotA'; ReverseSort()">ShA                                    
                                            <span ng-show="sortType == 'ShotA' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'ShotA' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div>
                                    </td>                                    
                                    <td class="c">
                                        <div class='link' ng-click="sortType = 'Speed'; ReverseSort()">Spd
                                            <span ng-show="sortType == 'Speed' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Speed' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div></td>                                    
                                    <td class="c"> <div class='link' ng-click="sortType = 'Agility'; ReverseSort()">Agl
                                            <span ng-show="sortType == 'Agility' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Agility' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div></td>                                    
                                    <td class="c"> <div class='link' ng-click="sortType = 'Stick'; ReverseSort()">Stk
                                            <span ng-show="sortType == 'Stick' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Stick' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div></td>                                    
                                    <td class="c"> <div class='link' ng-click="sortType = 'Pass'; ReverseSort()">Pass
                                            <span ng-show="sortType == 'Pass' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Pass' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div></td>
                                    <td class="c"> <div class='link' ng-click="sortType = 'Off'; ReverseSort()">OffA
                                            <span ng-show="sortType == 'Off' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Off' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div></td>
                                    <td class="c"> <div class='link' ng-click="sortType = 'Def'; ReverseSort()">DefA
                                            <span ng-show="sortType == 'Def' && sortReverse" class="fa fa-caret-down"></span>
                                            <span ng-show="sortType == 'Def' && !sortReverse" class="fa fa-caret-up"></span>                                  
                                        </div></td>
                                </tr>                                                              
                                <!--<tr ng-repeat="post in posts | orderBy:[ 'Weight', '-ShotP', '-Overall' ] | filter:filterPlayers"  ng-class-odd="'stripe'">-->
                                <tr ng-repeat="post in posts | orderBy:sortType:sortReverse | filter:filterPlayers | filter:searchPlayer"  ng-class-odd="'stripe'">
                                    <td class="c"><input type="checkbox" value="{{post.ID}}" ng-model="post.selected"/></td>
                                    <td class="c">{{$index +1}}</td>                                    
                                    <td class="c">{{post.Name}}</td>
                                    <td class="c">{{post.JNo}}</td>
                                    <td class="c">{{post.Handed}}</td>
                                    <td class="c">{{post.Overall}}</td>
                                    <td class="c">{{post.Team}}</td>
                                    <td class="c">{{post.Pos}}</td>
                                    <td class="c">{{post.Weight}}</td>
                                    <td class="c">{{post.Checking}}</td>
                                    <td class="c">{{post.ChkAbl}}</td>
                                    <td class="c">{{post.ShotP}}</td>
                                    <td class="c">{{post.ShotA}}</td>
                                    <td class="c">{{post.Speed}}</td>
                                    <td class="c">{{post.Agility}}</td>
                                    <td class="c">{{post.Stick}}</td>
                                    <td class="c">{{post.Pass}}</td>
                                    <td class="c">{{post.Off}}</td>
                                    <td class="c">{{post.Def}}</td>
                                </tr>					                           
                        

                        </table>

                        &nbsp; <button id="clearBtn" type="button" ng-click="Reset()" style="margin-top: 10px;">Clear</button>
                        &nbsp; <button id="submitBtn" type="button" ng-click="ComparePlayers()" style="margin-top: 10px;">Compare</button>
                        
        </div><!-- end: #page -->	
        </div>
    </div>

<script src="./js/default.js"></script>
</body>
</html>     

<?php
		}
		else {
				header('Location: index.php');
		}	
?>