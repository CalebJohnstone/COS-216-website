window.dates = null;
window.dateCounter = 0;
window.minimumDate = null;
window.today = new Date();

window.calendarMonth = null;
window.calendarYear = null;

$(document).ready(function(){
    window.calendarYear = window.today.getFullYear();
    window.calendarMonth = window.today.getMonth();//calendarYear and calendarMonth are global variables

    $(document.body).append("<table id='calendar'></table>");

    //add the buttons
    addButtons();

    window.dates = new Array();
    window.dateCounter = 0;

   //get the access token from Spotify
   var accessToken;
   var reqToken = new XMLHttpRequest();

   reqToken.onreadystatechange = function(){
       if(this.status == 200 && this.readyState == 4){
            var obj = JSON.parse(this.responseText);
            accessToken = obj["access_token"];

            //use the access token in subsequent API calls
            var reqTopTracks = new XMLHttpRequest();

            reqTopTracks.onreadystatechange = function(){
                if(this.status == 200 && this.readyState == 4){
                    var tracks = JSON.parse(this.responseText)["items"];

                    //add each song to the associative array
                    for(var i = 0; i < tracks.length; i++){
                        var track = tracks[i]["track"];

                        if(track == null || track["name"] == null){
                            continue;
                        }

                        var title = track["name"];
                        var trackID = track["id"];

                        //request track info
                        var reqTrack = new XMLHttpRequest();

                        reqTrack.onreadystatechange = function(){
                            if(this.status == 200 && this.readyState == 4){
                                var trackObject = JSON.parse(this.responseText);
                                var datePrecision = trackObject["album"]["release_date_precision"];
                                var date = trackObject["album"]["release_date"];

                                var dayIndex;

                                if(datePrecision != "day"){
                                    dayIndex = 0;
                                }
                                else{
                                    dayIndex = date.substring(8,date.length);
                                }

                                var monthIndex = date.substring(5, 7) - 1;
                                var yearValue = date.substring(0, 4);

                                //replace every second space with <br>
                                var formattedTitle = title.replace(/( [^ ]*) /g, "$1<br>");

                                window.dates[window.dateCounter] = {name: formattedTitle, year: yearValue, month: monthIndex, day: dayIndex};
                                window.dateCounter++; 
                            }//reqTrack status and readyState
                        }//reqTrack.onreadystatechange

                        reqTrack.open("GET" ,"https://api.spotify.com/v1/tracks/" + trackID, false);
                        reqTrack.setRequestHeader("Authorization", "Bearer " + accessToken);
                        reqTrack.send();
                    }
                }
            }//reqTopTracks status and readyState
            var queryPlaylist = "playlists/37i9dQZF1DWWW9iyuOPGds/tracks";

            reqTopTracks.open("GET", "https://api.spotify.com/v1/" + queryPlaylist, false);
            reqTopTracks.setRequestHeader("Authorization", "Bearer " + accessToken);
            reqTopTracks.send();
       }
   }//reqTopTracks.onreadystatechange

   reqToken.open("POST", "https://accounts.spotify.com/api/token", false);
   reqToken.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   reqToken.setRequestHeader("Authorization", "Basic ZjVmYjAxMzRlMDI4NDA5NzkyN2ZjYWNhNDEzZTIyNmM6ZDU3YzRlMGFkYWIyNGU5YzgwNWNkYjA4YzJlOWQyMDY=");
   reqToken.send("grant_type=client_credentials");

   $("#loadScreen").delay(2000).fadeOut();
   changeMonth(window.calendarMonth, window.calendarYear);
   determineEarliestDate();
});


function determineEarliestDate(){
   //determine the earliest release date
   window.minimumDate = window.dates[0];

   for(var i = 1; i < window.dateCounter; i++){
       var currentMonth = window.dates[i].month;
       var currentYear = window.dates[i].year;

       if(currentYear < window.minimumDate.year){
           window.minimumDate = window.dates[i];
       }
       else if(currentYear == window.minimumDate.year && currentMonth < window.minimumDate.month){
            window.minimumDate = window.dates[i];
       }
   }
}

function addToCalendar(title, year, month, day){
    if(year == window.calendarYear && month == window.calendarMonth){
        //find the appropriate day

        var weekIndex = Math.floor(day/7) + 1;
        var dayIndex = (day % 7) - 1;

        var text = $("tr:eq(" + weekIndex + ") td:eq(" + dayIndex + ")").html();
        text += "<br><br>" + title;

        $("tr:eq(" + weekIndex + ") td:eq(" + dayIndex + ")").html(text);
    }
}

function clearCalendar(){
    $("#calendar").empty();
}

function changeMonth(month, year){
    //previous has taken us to December
    if(month === -1){
        month = 11;
        year--;
    }
    else if(month === 12){
        month = 0;
        year++;
    }

    //reassign the values of the global variables
    window.calendarMonth = month;
    window.calendarYear = year;

    //clear the calendar
    clearCalendar();

    //draw the new month
    drawMonth(month, year);
    addButtons();

    if(month == window.today.getMonth() && year == window.today.getFullYear()){
        $("#next").css("visibility", "hidden");
        var week = Math.ceil(window.today.getDate()/7);
        var dayInWeek = window.today.getDate() % 7 - 1;

        $("tr:eq(" + week + ") td:eq(" + dayInWeek + ")").css("background", "rgba(255, 0, 0, 0.5)");
    }
    else if(year == window.minimumDate.year && month == window.minimumDate.month){
        $("#previous").css("visibility", "hidden");
    }
}

function addButtons(){
    //add the buttons
    $("#calendar").append("<button id='previous' onclick='changeMonth(" + (window.calendarMonth - 1) + ", " + window.calendarYear + ")'>Previous</button>");
    $("#calendar").append("<button id='next' onclick='changeMonth(" + (window.calendarMonth + 1) + ", " + window.calendarYear + ")'>Next</button>");

    $("#calendar").append("<button id='today' onclick='changeMonth(" + window.today.getMonth() + ", " + window.today.getFullYear() + ")'>Today</button>");
}

function drawMonth(month, year){
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var monthName = monthNames[month];

    var heading = "<th colspan='7'>" + monthName + " " + year + "</th>";
    $("#calendar").append("<tr>" + heading + "</tr>");

    for(var i = 0 ; i < 4; i++){
        $("#calendar").append("<tr></tr>");
    }

    var day = 1;

    $("tr").each(function(index){
        if(index > 0){
            for(var j = 0; j < 7; j++){
                $("tr:eq(" + index + ")").append("<td>"+ day + "</td>");
                day++;
            }
        }
    });

    if(month == 1){
        var isLeapYear = ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
        if(isLeapYear){
            $("#calendar").append("<tr><td>29</td></tr>");
            $("#calendar tr:eq(5) td").css("color", "white");
        }
    }
    else{
        $("#calendar").append("<tr><td>29</td><td>30</td></tr>"); 

        if((month % 2 == 0 && month <= 6) || (month % 2 != 0 && month > 6)){
            $("tr:eq(" + 5 + ")").append("<td>31</td>");        
        }

        $("#calendar tr:eq(5) td").css("color", "white");
    }

    if(window.dateCounter > 0){
        for(var i = 0; i < window.dateCounter; i++){
            addToCalendar(window.dates[i].name, window.dates[i].year, window.dates[i].month, window.dates[i].day);
        }
    }
}