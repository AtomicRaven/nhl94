
/// Create.js functions
	function UpdateSeriesName(){

		var homeTeam = "";
		var awayTeam = ""; 

		var homeSelect = document.getElementById("homeUser");
		var awaySelect = document.getElementById("awayUser");

		if(homeSelect.options[homeSelect.selectedIndex].value != 0){

			homeTeam = homeSelect.options[homeSelect.selectedIndex].text; 
		}

		if(awaySelect.options[awaySelect.selectedIndex].value != 0){

			awayTeam = awaySelect.options[awaySelect.selectedIndex].text; 
		}

		$("#seriesName").val(homeTeam + " vs " + awayTeam);
	}

	function SubmitForm(){

		var homeSelect = document.getElementById("homeTeam");
		var awaySelect = document.getElementById("awayTeam");

		var homeUserSelect = document.getElementById("homeUser");
		var awayUserSelect = document.getElementById("awayUser");

		var submit = true;
		var msgBox = $("#msg");
		var msgHtml = "Please Select the following: ";

		/* if(homeSelect.options[homeSelect.selectedIndex].value == 0){

			msgHtml += "Home Team, ";
			submit = false;
		} */

		/* if(awaySelect.options[awaySelect.selectedIndex].value == 0){
			
			msgHtml += "Away Team, ";
			submit = false;
		} */

		if(homeUserSelect.options[homeUserSelect.selectedIndex].value == 0){
			
			msgHtml += "Home User, ";
			submit = false;
		}

		if(awayUserSelect.options[awayUserSelect.selectedIndex].value == 0){
			
			msgHtml += "Away User, ";
			submit = false;
		}

		if(submit){

			document.seriesForm.submit();

		} else{

			msgBox.html(msgHtml.substring(0, msgHtml.length - 2) + "</br></br>");
		}


	}

	function DeleteSeries(seriesId, seriesName){

		var deleteSeries = confirm("Are you sure you want to delete series #" + seriesId + " | " + seriesName + "??");

		if (deleteSeries == true) {
			location.href="processSeriesDelete.php?seriesId=" + seriesId;	
		} else {
			//txt = "You pressed Cancel!";
		}

	}

	function DeleteGame(gameId, seriesId){


		var deleteGame = confirm("Are you sure you want to delete game #" + gameId + "??");

		if (deleteGame == true) {
			location.href="processGameDelete.php?gameId=" + gameId + "&seriesId=" + seriesId;
		} else {
			//txt = "You pressed Cancel!";
		}

		
	}

/// End of Create.js functions