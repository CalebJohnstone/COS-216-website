RedPillRequest();

function RedPillRequest(){
    //make request to my API
    var RedPillRequest = new XMLHttpRequest();
 
    RedPillRequest.onreadystatechange = function(){
        if(this.status == 200 && this.readyState == 4){
            var obj = JSON.parse(this.responseText);
             var data = obj["data"];
 
             if(data.length > 0){
                 //build the page
                $("#content-wrap").append("<div id='datesArray'></div>");

                 data.forEach(function(song){
                     //build the date element
                     if(song["artist"] == null || song["title"] == null || song["releaseDate"] == null){
                        return;
                     }

                     var releaseDate = song["releaseDate"];

                     var date = '<div class="date">';
                     date += '<div class="dateText">' + song["artist"] + ": " + song["title"] + '</div>';
                     date += '<div class="dateYear">' + releaseDate.substring(0, 4) + '</div>';
                     date += '<div class="dateMonth">' + releaseDate.substring(5, 7) + '</div>';
                     date += '<div class="dateDay">' + releaseDate.substring(8, releaseDate.length) + '</div>';
                     date += '</div>';
             
                     $("#datesArray").append(date);
                 });//for each song
             }//if song data was returned
        }//once ready
    }//onreadystatechange
 
    RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api.php", false);
    RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    RedPillRequest.send("type=info&title=calendar&return[]=*");
 }