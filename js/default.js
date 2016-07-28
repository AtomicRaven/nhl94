
/// Create.js functions
	function UpdateSeriesName(){

		var homeTeam = "";
		var awayTeam = ""; 

		var homeSelect = document.getElementById("homeTeam");
		var awaySelect = document.getElementById("awayTeam");

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

		if(homeSelect.options[homeSelect.selectedIndex].value == 0){

			msgHtml += "Home Team, ";
			submit = false;
		}

		if(awaySelect.options[awaySelect.selectedIndex].value == 0){
			
			msgHtml += "Away Team, ";
			submit = false;
		}

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

/// End of Create.js functions