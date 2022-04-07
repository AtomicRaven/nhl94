
var app = angular.module('myApp',['ui.sortable']);

app.controller("PostsCtrl", function($scope, $http) {

    $scope.loading = true;
    $scope.defaultSortType = "Overall";
    $scope.sortType = $scope.defaultSortType; // set the default sort type
    $scope.prevSortType = $scope.defaultSortType;
    $scope.sortReverse  = true;  // set the default sort order
    $scope.searchPlayer   = '';     // set the default search/filter term

    $scope.selectedLeagues = null;
    $scope.leagues = [];
    $scope.teams = [];
   
    $scope.Filter = {
        forwards: "F",
        defense: "D",
        goalies: "G"
    };

    $scope.sortableOptions = {

        stop: function(e, ui) {
            $scope.ChangeSortOrder();        
        }
    };

    $scope.toggleAll = function() {

       angular.forEach($scope.posts, function( post, idx) {

           post.selected = !post.selected;
            //if (post.selected) {
              //  qString += "player" + idx + "=" + post.ID + "&";
            //}
            
        });    
    }

    $scope.ChangeSortOrder = function(){

        $scope.sortReverse = false;
        var sortOrder = [];
        angular.forEach($scope.list, function(value, key) {
            if(value.selected)
                sortOrder.push(value.dir + value.name);
        });             

        //make sure a checkbox is ticked
        if(sortOrder.length > 0)
            $scope.sortType = sortOrder;
    }

    $scope.ReverseAttrSort = function(index, attr){
        
        if($scope.list[index].dir == "+") 
            $scope.list[index].dir = "-";
        else 
            $scope.list[index].dir = "+";

        $scope.ChangeSortOrder();
    }

    $scope.filterPlayers = function(player){
        return player.Pos === $scope.Filter.forwards || 
            player.Pos === $scope.Filter.defense ||
            player.Pos === $scope.Filter.goalies;
    };

    $scope.ReverseSort = function(){
        
        if($scope.prevSortType == $scope.sortType){
            $scope.sortReverse = !$scope.sortReverse;
        }   

        $scope.prevSortType = $scope.sortType;

        angular.forEach($scope.list, function(value, key) {
            value.selected = false;                
        }); 

        //alert('SortType:' + $scope.sortType + " SortDir:" + $scope.sortReverse);
    }

    $scope.ComparePlayers = function(){

        var qString = "";
        angular.forEach($scope.posts, function( post, idx) {

            if (post.selected) {
                qString += "player" + idx + "=" + post.ID + "&";
            }
            
        });

        $scope.GetPlayers("?" + qString.substring(0, qString.length-1));
    } 

    $scope.Reset = function(){

        $scope.GetPlayers();
        $scope.selectedTeams = "Select Team";
        $scope.searchPlayer = '';
        $scope.sortType = $scope.defaultSortType;
    }

    $scope.GetPlayers = function(params){

        var uri = "./getPlayersJson.php";

        if(params == undefined){
            params = "";
        }

        $http({
            method: 'GET',
            url: uri + params
            }).then(function successCallback(response) {
                // this callback will be called asynchronously
                // when the response is available        
                $scope.posts = response.data;

                if(params == ""){
                //Set Up the sorting
                    $scope.list = [];
                    for (var key in $scope.posts[0]) {                    

                        if(key != 'ID' && key != 'Name' && key != 'Handed' && key!='Team' && key !='Pos'){
                        //if(key != 'ID' && key != 'Name' && key != 'Handed'){
                            $scope.list.push({
                                name: key,
                                dir: "-",
                                selected: false
                            });
                        }
                    }
                }

                $scope.loading = false;

            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                $scope.loading = false;
        });
        
    }

//Leagues

    $scope.ChangeLeague = function(item) {
        $scope.GetPlayers("?leagueType=" + item.LeagueID);        
        $scope.GetTeams("?leagueType=" + item.LeagueID);
        //$scope.sel
    }

    $scope.ChangeTeams = function(item) {
        $scope.GetPlayers("?leagueType=" + $scope.selectedLeagues.LeagueID + "&teamId=" + item.TeamID);                
    }

    $scope.GetLeagues = function(params){

        var uri = "./getBinsJson.php";

        if(params == undefined){
            params = "";
        }

        $http({
            method: 'GET',
            url: uri + params
            }).then(function successCallback(response) {
                // this callback will be called asynchronously
                // when the response is available        
                
               // var result = jsObjects.filter(obj => {
                 //   return obj.b === 6
                  //})
				
                $scope.leagues = response.data;                                
                $scope.selectedLeagues = $scope.leagues.filter(obj => {return obj.LeagueID === leagueType})[0];
                $scope.loading = false;
                

            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                $scope.loading = false;
        });
        
    }

    $scope.GetTeams = function(params){

        var uri = "./getTeamsJson.php";

        if(params == undefined){
            params = "";
        }

        $http({
            method: 'GET',
            url: uri + params
            }).then(function successCallback(response) {
                // this callback will be called asynchronously
                // when the response is available        
                $scope.teams = response.data;                                
                $scope.selectedTeams = "Select Team";
                $scope.loading = false;

            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                $scope.loading = false;
        });
        
    }

    
    $scope.GetPlayers();
    $scope.GetTeams();
    $scope.GetLeagues();
});

