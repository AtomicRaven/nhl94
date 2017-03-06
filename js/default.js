//OnLoad


	function RosterSubmit(){

		if(s!="" && s!='Off'){

			var theForm = document.forms['rosterForm'];
			var sOrder = document.getElementById("sOrder");

			if(sOrder.value == 'DESC'){

				sOrder.value = 'ASC';

			}else{
				
				sOrder.value = 'DESC';
			}
			addHidden(theForm, 's', s);
		}

		document.rosterForm.submit();
	}

	function addHidden(theForm, key, value) {
		// Create a hidden input element, and append it to the form:
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = key;'name-as-seen-at-the-server';
		input.value = value;
		theForm.appendChild(input);
	}

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


	//Register Form
	function SubmitRegisterForm(){
		
		var userName = document.getElementById("username"),
			name = document.getElementById("name"),
			email = document.getElementById("email"),
			password = document.getElementById("password"),
			confirmPassword = document.getElementById("confirmPassword"),
			submit = true,
			msgBox = $("#msg"),
			msgBox2 = $("#msg2"),
			msgHtml = "",
			msgHtml2 = "";
				
		if(userName.value == ""){
			submit = false;
			msgHtml += "username, ";
		}

		if(name.value == ""){
			submit = false;
			msgHtml += "name, ";
		}

		if(email.value == ""){

			submit = false;
			msgHtml += "email, ";

		}else{

			if(!validateEmail(email.value)){
				submit = false;
				msgHtml2 += " Invalid email address.";
			}
		}

		if(password.value == ""){
			submit = false;
			msgHtml += "password, ";

		}else{

			if(password.value != confirmPassword.value){
				submit = false;
				msgHtml2 += " Passwords do not match.";
			}

		}

		if(submit){

			document.register.submit();

		} else{

			if(msgHtml != ""){
				msgBox.html("Please enter the following: " + msgHtml.substring(0, msgHtml.length - 2) + "</br>");
			}else{
				msgBox.html("");
			}

			if(msgHtml2 != ""){
				msgBox2.html("Please correct the following: " + msgHtml2 + "</br></br>");
			}else{
				msgBox2.html("");
			}
		}
	}

	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);

	}
	
	function SubmitChangePWDForm(){

			var oldpwd = document.getElementById("oldpwd"),
			newpwd = document.getElementById("newpwd");
			submit = true,
			msgBox = $("#msg"),
			msgHtml = "";
				
		if(oldpwd.value == ""){
			submit = false;
			msgHtml += "Old Password, ";
		}

		if(newpwd.value == ""){
			submit = false;
			msgHtml += "New Password, ";
		}

		

		if(submit){

			document.changepwd.submit();

		} else{

			if(msgHtml != ""){
				msgBox.html("Please enter the following: " + msgHtml.substring(0, msgHtml.length - 2) + "</br>");
			}else{
				msgBox.html("");
			}
		}

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

		if(homeUserSelect.options[homeUserSelect.selectedIndex].value == -1){
			
			msgHtml += "Home User, ";
			submit = false;
		}

		if(awayUserSelect.options[awayUserSelect.selectedIndex].value == -1){
			
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

	function DeleteTable(){

		var tblSelect = document.getElementById("leagueType");

		var tableId = tblSelect.options[tblSelect.selectedIndex].value;
		var tableName = tblSelect.options[tblSelect.selectedIndex].innerHTML;

		if(tableId != -1){

			var deleteSeries = confirm("Are you sure you want to delete table #" + tableId  + " | " + tableName + "??");

			if (deleteSeries == true) {

				var deleteSeries2 = confirm("Are you really really sure you want to delete table #" + tableId  + " | " + tableName + "??");

				if (deleteSeries2 == true) {
					window.location.href="deleteTable.php?leagueType=" + tableId;	
				}
				
			} else {
				//txt = "You pressed Cancel!";
			}
		}

	}

	function DeleteGame(gameId, seriesId, page, tId){


		var deleteGame = confirm("Are you sure you want to delete game #" + gameId + "??");

		if (deleteGame == true) {
			location.href="processGameDelete.php?gameId=" + gameId + "&seriesId=" + seriesId + "&redirect=" + page + "&tId=" + tId;
		} else {
			//txt = "You pressed Cancel!";
		}		
	}

	//resultsSeries Page
	function showGameDetails(obj, x, gameId, gameNum, leagueid) {
		if ( obj.innerHTML === '+ Details' ) {
			// fetch game stats
			$('#fetch_' + x).load('fragment_game_stats_template.php?gameId=' + gameId + "&gameNum=" + gameNum + "&leagueId=" + leagueid);
			// dipslay table row beneath button	
			$('#' + x).fadeIn();
			// toggle button	
			obj.innerHTML = '- Details'
		}
		else {
			// hide table row beneath button	
			$('#' + x).fadeOut();	
			// toggle button	
			obj.innerHTML = '+ Details'
		}	

		return;
	}

	function showAllGames(obj) {

		var gameId, leagueId;

		if (obj.innerHTML === '+ All') {
			obj.innerHTML = '- All';
			$('button.details').html('- Details');
			// show all $('.detail_row').css('- Details')
			$( ".detail_row" ).each(function( index ) {
					//

					gameId = $(this).attr("data-game-id");
					leagueId = $(this).attr("data-league-id");

					console.log('#fetch_detail_' + parseInt(index+1));
					
					$('#fetch_detail_' + parseInt(index+1)).load('fragment_game_stats_template.php?gameId=' + gameId + "&gameNum=" + (index+1) + "&leagueId=" + leagueId);
					$('#detail_' + parseInt(index+1)).fadeIn();
			});			
		}
		else {
			obj.innerHTML = '+ All'	
			$('button.details').html('+ Details')
			$('.detail_row').css('display','none')
		}
	}

	function UploadFile(scheduleNum, chk){

		var fileInput = "Choose file: <input type='file' name='uploadfile' />";

		if(chk)
			fileInput += "<input type='button' onclick='SubmitForm()' value='Upload' />";			
		else
			fileInput += "<input type='submit' name='submit' value='Upload' />";			

		var fileInputBox = fileInput + "<input type='hidden' name='scheduleid' value='" + scheduleNum + "' />";
		var fileInputDiv = $("#fileInput" + scheduleNum);

		fileInputDiv.html(fileInputBox);
		fileInputDiv.show();					

	}

/// End of Create.js functions