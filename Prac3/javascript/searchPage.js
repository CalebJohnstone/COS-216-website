$(document).ready(function (){
    window.songArray = new Array();
    window.infoArray = new Array();
    window.activeSongs = new Array();

   populateArrays();
   populateSelects();

    //allow dynamic searching
    $("#searchTerm").on("keyup", function(e){
        var charTyped = String.fromCharCode(e.which);

        if (/[a-z\d]/i.test(charTyped) || e.code == "Space" || e.code == "Delete" || e.code == "Backspace") {
            searchSongs();
        }
    });
});//document is ready

function clearSort(){
    $("input[name='searchFilter']").prop("checked", false);
    determineOperations();
}

function loadScreen(){
    $("#loadScreen").delay(1000).fadeOut();
}

//activeSongs functions
function clearActiveSongs(){
    window.activeSongs.length = 0;
}

function addActiveSong(index){
    window.activeSongs.push(index);
}

function isActiveSong(index){
    return window.activeSongs.includes(index);
}
//

//indexValue class
function IndexedValue(value, index){
    this.value = value;
    this.index = index;
}

function compare(a, b){
    if(a.value < b.value){
        return -1;
    }

    if(a.value > b.value){
        return 1;
    }

    return 0;
}
//

function showTop(){
    try{
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
    catch(e){
        document.body.scrollTop = 0; // For Safari
    }    
}

function populateArrays(){   
    var songSection = $(".songSection").children();
    var pArray = document.querySelectorAll(".song > p");

    for(var i = 0; i < songSection.length; i++){
        window.songArray.push($(".song:eq(" + i + ")"));
        var p = pArray[i];

        //store the information in an array without the headings
        var info = p.innerText;
        var rawText = info.replace(/<br>/g, " ");

        //check for ":" in the album name
        if(rawText.lastIndexOf(":") !== rawText.indexOf("Album:") + 5){
            var album = rawText.substring(rawText.indexOf("Album:") + 6);
            var newAlbum = album.replace(/:/g, "");

            var newText = rawText.substring(0, rawText.indexOf("Album:") + 6) + newAlbum;
            rawText = newText;
        }
        
        var finalString = rawText.replace(/([a-z])+: /gi, ";").replace("Release ", "");
        var finalInfo = finalString.substring(1).toLowerCase().replace(/\n|\r/g, "");
        window.infoArray.push(finalInfo);
    }

    //for each song: remove any fields that don't have a value
    window.songArray.forEach(function(song, i){
        if(song.html().indexOf(": <br>") > -1){
            var index = song.html().indexOf(": <br>");
            var songHTML = song.html();

            while(songHTML[index-1] != ">"){index--;}

            var afterIndex = song.html().indexOf(": <br>")+6;
            var newSongHTML = songHTML.substring(0, index) + songHTML.substring(afterIndex);
            song.html(newSongHTML);
        }
        
        //add the song index to activeSongs
        addActiveSong(i);
    });

    loadScreen();
}

function populateSelects(){
    populateGenres();
    populateYears();
}

function populateGenres(){
    //have an array to store the genres in
    var genres = new Array();

    window.songArray.forEach(function(song, i){
        //move to next song if not have a genre
        if(song.text().indexOf("Genre") == -1){
            return;
        }

        var text = window.infoArray[i].split(";");
        //get rid of unicode(10) character
        var currentGenres = text[4].trim().split(", ");
        
        currentGenres.forEach(function(genre){
            if(!genres.includes(genre)){
                genres.push(genre);
            }
        });//for each genre of the current song
    });

    //sort the array
    genres.sort();

    $("#genreSelect").append("<option disabled selected value selected='selected'>select a genre</option>");

    genres.forEach(function(genre){
        $("#genreSelect").append("<option value='" + genre + "'>" + genre + "</option>");
    });
}

function populateYears(){
    //have an array to store the genres in
    var years = new Array();

    window.songArray.forEach(function(song, i){
        if(song.text().indexOf("Release Date") == -1){
            return;
        }

        var text = window.infoArray[i].split(";");
        var year = text[2].substring(0, text[2].indexOf("-"));

        if(!years.includes(year)){
            years.push(year);
        }
    });

    //sort the array
    years.sort();

    $("#yearSelect").append("<option disabled selected value selected='selected'>select a year</option>");

    years.forEach(function(year){
        $("#yearSelect").append("<option value='" + year + "'>" + year + "</option>");
    });
}

function determineOperations(){//here
    clearActiveSongs();
    //console.log("determineOperations();");

    var sorting = $('input[name=searchFilter]:checked').length > 0;
    var filtering;

    if($("#genreSelect").find(":selected").text() == "select a genre" && $("#yearSelect").find(":selected").text() == "select a year"){//1 case
        filtering = false;
        //console.log("NOT filtering");
    }
    else{//3 cases
        filtering = true;
        //console.log("FILTERING");
    }

    if(sorting){
        //console.log("SORTING");
        var sortAttribute = $('input[name=searchFilter]:checked').val().toUpperCase();

        if(!filtering){
            //console.log("sorting by: " + sortAttribute);
            sortSongs(sortAttribute);
        }
        else if($("#genreSelect").find(":selected").text() != "select a genre" && $("#yearSelect").find(":selected").text() == "select a year"){//only genre
            var genre = $("#genreSelect").find(":selected").text();

            //console.log("sorting by: " + sortAttribute + ", filtering by: genre=" + genre);
            sortAndFilter(sortAttribute, "genre", genre);
        }
        else if($("#genreSelect").find(":selected").text() == "select a genre" && $("#yearSelect").find(":selected").text() != "select a year"){//only year
            var year = $("#yearSelect").find(":selected").text();

            //console.log("sorting by: " + sortAttribute + ", filtering by year=" + year);
            sortAndFilter(sortAttribute, "year", year);
        }
        else{//both genre and year
            var genre = $("#genreSelect").find(":selected").text();
            var year = $("#yearSelect").find(":selected").text();

            //console.log("sorting by: " + sortAttribute + ", filtering by genre=" + genre + " AND year=" + year);
            sortAndFilter(sortAttribute, "genre", genre, "year", year);
        }
    }//sorting
    else{//not sorting
        //console.log("NOT sorting");

        if(!filtering){
            //not possible since this function would not be called -> ALSO: do nth
            //console.log("CHECK IF ERROR: NOT SORTING OR FILTERING");

            //add all of the songs
            window.songArray.forEach(function(song, index){
                addActiveSong(index);
            });
        }
        else if($("#genreSelect").find(":selected").text() != "select a genre" && $("#yearSelect").find(":selected").text() == "select a year"){
            filterGenre();
        }
        else if($("#genreSelect").find(":selected").text() == "select a genre" && $("#yearSelect").find(":selected").text() != "select a year"){
            filterYear();
        }
        else{//both genre and year
            filterBoth();
        }
    }//not sorting

    if(arguments.length == 0){
        searchSongs();
    }
}

function filterBoth(){
    //console.log("filterBoth();");

    var filterGenre = $("#genreSelect").find(":selected").text();
    //console.log("filterGenre: " + filterGenre);

    var filterYear = $("#yearSelect").find(":selected").text();
    //console.log("filterYear: " + filterYear);

    //only show songs that have this as one of the genres AND was released in the correct year
    window.songArray.forEach(function(song, index){
        if(song.text().indexOf("Genre") > -1 && song.text().indexOf("Release Date") > -1){
            var text = window.infoArray[index].split(";");
            var genres = text[4];
            var year = text[2].substring(0, text[2].indexOf("-"));

            if(genres.indexOf(filterGenre) > -1 && filterYear == year){
                //add to activeSongs
                addActiveSong(index);
            }
        }
    });
}

function sortAndFilter(){
    var numArguments = arguments.length;

    //have an array to store the attribute values in
    var indexedValues = new Array();
    var index;
    var sortAttribute = arguments[0];

    //sorting by 1st argument and filtering by 2nd with value of 3rd argument
    var filterAttribute = arguments[1];
    var filterValue = arguments[2];

    if(sortAttribute == "ARTIST"){
        index = 1;
    }
    else if(sortAttribute == "TITLE"){
        index = 0;
    }
    else if(sortAttribute == "ALBUM"){
        index = -1;//test for in loop
    }

    var targetYear = -1;

    if(numArguments == 5){
        targetYear = arguments[4];
        //console.log("targetYear = " + targetYear);
    }

    window.infoArray.forEach(function(info, i){//here
        var text = info.split(";");

        if(filterAttribute == "genre"){
            if(window.songArray[i].text().indexOf("Genre") > -1){
                var genreText = text[text.length-2];

                //console.log(genres);
                //console.log(filterValue + "|");
                    
                if(genreText.indexOf(filterValue) === -1){
                    //console.log("Rejected: genre=" + filterValue + " not one of this song's genres");
                    return;
                }
            }
            else{
                //console.log("Song has no Genre");
                return;
            }

            if(numArguments == 5){
                if(window.songArray[i].text().indexOf("Release Date") > -1){
                    var year = text[2].substring(0, text[2].indexOf("-"));
    
                    if(year != targetYear){
                        //console.log("Rejected: NOT the correct year --> " + year);
                        return;
                    }

                    //console.log("correct year: " + year);
                }
                else{
                    //console.log("Song has no Release Date");
                    return;
                }
            }
        }
        else if(filterAttribute == "year"){
            if(window.songArray[i].text().indexOf("Release Date") > -1){
                var year = text[2].substring(0, text[2].indexOf("-"));

                if(year != filterValue){
                    //console.log("Rejected: NOT the correct year --> " + year);
                    return;
                }
            }
            else{
                //console.log("Song has no Release Date");
                return;
            }
        }

        var value;

        if(index != -1){//ARTIST OR TITLE
            value = text[index];
        }
        else{//ALBUM
            value = text[text.length-1];
        }

        //use IndexValue object instead
        var currentIndexedValue = new IndexedValue(value.toLowerCase().trim(), i);
        indexedValues.push(currentIndexedValue);
    });//for each song

    //sort the array
    indexedValues.sort(compare);

    indexedValues.forEach(function(current){
        addActiveSong(current.index);
    });

}

function clearGenreFilter(){
    //go back to "select a genre" option
    $("#genreSelect").prop("selectedIndex", $("#genreSelect").find("option[selected]").index());

    determineOperations();
} 

function filterGenre(){
    var filterGenre = $("#genreSelect").find(":selected").text();
    //console.log("filterGenre: " + filterGenre);

    //only show songs that have this as one of the genres
    window.songArray.forEach(function(song, index){
        if(song.text().indexOf("Genre") > -1){
            var text = window.infoArray[index].split(";");
            var genres = text[4];
            
            if(genres.indexOf(filterGenre) > -1){
                //add to activeSongs
                addActiveSong(index);
            }
        }
    });
}

function clearYearFilter(){
    //go back to "select a genre" option
    $("#yearSelect").prop("selectedIndex", $("#yearSelect").find("option[selected]").index());

    determineOperations();
}

function filterYear(){
    var filterYear = $("#yearSelect").find(":selected").text();

    //only show songs that have this as one of the genres
    window.songArray.forEach(function(song, index){
        if(song.text().indexOf("Release Date") > -1){
            var text = window.infoArray[index].split(";");    
            var year = text[2].substring(0, text[2].indexOf("-"));

            if(filterYear == year){
                //add to activeSongs
                addActiveSong(index);
            }
        }
    });
}

function searchSongs(){
    //remove all of the songs
    $(".songSection").empty();

    var searchTerm = $("#searchTerm").val();    
    var searchTermTrimmed = searchTerm.trim();

    if(searchTermTrimmed.length == 0){
        determineOperations(0);
    }

    //display a message to say that no songs matched the filter criteria
    if(window.activeSongs.length == 0){
        var errorDiv = "<div id='noSongMessage'><p>No songs matched your search and filter criteria<p></div>";
        $(".songSection").append(errorDiv);
        return;
    }
    else{
        $("#noSongMessage").remove();
    }

    var atLeastOne = false;

    //window.activeSongs stores the indexes of the songs that are currently selected by the sorting and filtering applied to the songs
    window.activeSongs.forEach(function(activeSong){
        var songText = window.infoArray[activeSong];
        var text = songText.toUpperCase();
        var term = searchTermTrimmed.toUpperCase();

        if(text.indexOf(term) > -1){
            //add the song back to the screen
            $(".songSection").append(window.songArray[activeSong]);
            atLeastOne = true;
        }
    });

    //display a message to say that no songs matched the search term
    if(!atLeastOne){
        var errorDiv = "<div id='noSongMessage'><p>No songs matched your search and filter criteria<p></div>";
        $(".songSection").append(errorDiv);
    }
    else{
        $("#noSongMessage").remove();
    }

    showTop();
}

function clearSearch(){
    $("#searchTerm").val("");
    determineOperations();
}

function sortSongs(category){
    var indexedValues = new Array();

    window.infoArray.forEach(function(info, i){
        var text = info.split(";");
        var index;

        if(category == "ARTIST"){
            index = 1;
        }
        else if(category == "TITLE"){
            index = 0;
        }
        else if(category == "ALBUM"){
            index = text.length-1;
        }

        var indexedValue = new IndexedValue(text[index], i);
        indexedValues.push(indexedValue);
    });

    indexedValues.sort(compare);

    indexedValues.forEach(function(current){
        addActiveSong(current.index);
    });
}