<!DOCTYPE html>
<html>
    <body>
        <div id="mySidebar" class="sidebar">
            <script type="text/javascript">
                function clearSortSave(){
                    $("input[name='sortRadio']").prop("checked", false);
                }

                function clearGenreSave(){
                    $("#genreSave").prop("selectedIndex", $("#genreSave").find("option[selected]").index());
                }

                function clearYearSave(){
                    $("#yearSave").prop("selectedIndex", $("#yearSave").find("option[selected]").index());
                }

                function saveSettings() {
                    //save the settings that the user has selected
                    var API_key = localStorage.getItem("API_key");
                    var parameters = "key=" + API_key + "&type=update&sort=";
                    var sort = "NULL";
                    var filter = "[genre=";

                    //SORTING
                    if($('input[name="sortRadio"]:checked').length > 0){
                        sort = $('input[name="sortRadio"]:checked').attr("id");
                    }

                    parameters += sort;

                    //FILTERING

                    //genre
                    if($("#genreSave").find(":selected").text() != "select a genre"){
                        var genreText = $("#genreSave").find(":selected").text();
                        var finalGenreText = genreText.replace("&", ";");

                        filter += finalGenreText;
                    }
                    else{
                        filter += "NULL";
                    }

                    filter += ", year=";

                    //
                    if($("#yearSave").find(":selected").text() != "select a year"){
                        filter += $("#yearSave").find(":selected").text();
                    }  
                    else{
                        filter += "NULL";
                    }                  

                    filter += "]";                    
                    parameters += "&filter=" + filter + "&return[]=NULL";

                   //make request to RedPillAPI
                   var RedPillRequest = new XMLHttpRequest();

                   RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api_v2.php", false);
                   RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                   RedPillRequest.send(parameters);

                   //change the searchPage (either trending OR topRated) input options

                   //check if this is the trending page
                   var title = $(document).find("title").text().toLowerCase();

                   if(title !== "trending"){
                       return;
                   }

                   //SORTING
                   if($('input[name="sortRadio"]:checked').length > 0){
                        var sort = $('input[name="sortRadio"]:checked').attr("id");
                        $("input[value='" + sort + "']").prop("checked", true);
                    }
                    else{
                        $("input[name='searchFilter']").prop("checked", false);
                    }

                   //FILTERING

                   var filterGenre = $("#genreSave").find(":selected").text();

                   if(filterGenre != "select a genre"){
                        $("#genreSelect").val(filterGenre); 
                   }
                   else{
                       $("#genreSelect").val(" X");
                   }

                   var filterYear = $("#yearSave").find(":selected").text();

                   if(filterYear != "select a year"){
                        $("#yearSelect").val(filterYear); 
                   }
                   else{
                       $("#yearSelect").val("Y");
                   }

                   //call determineOperations
                   determineOperations(1,2);
                }
            </script>

            <button id="saveSettings" onclick="saveSettings();">Save</button>

            <h4>(For Trending Page)</h4>

            <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
            
            <div id="settingsHeading">
                <h1>Settings</h1>
            </div>

            <div class="settingsBlock">
                <div id="filterOptions">
                    <h2>Filter </h2>

                    <label for="genreSave">Genre</label>
                    <select id="genreSave" onchange="">
                        <option disabled selected value selected='selected'>select a genre</option>
                    </select>

                    <button id="clearGenreSave" onclick="clearGenreSave()">Clear</button>
                    <br>

                    <label for="yearSave">Year</label>
                    <select id="yearSave" onchange="">
                        <option disabled selected value selected='selected'>select a year</option>
                    </select>

                    <button id="clearYearSave" onclick="clearYearSave()">Clear</button>                    
                </div>
            </div>

            <div id="sortBlock">
                <h2>Sort</h2>

                <button id="clearSort" onclick="clearSortSave()">Clear</button>

                <div id="sortOptions">
                    <div id="artistSort">
                        <input type="radio" name="sortRadio" id="artist" value="artist"><label for="artist">Artist</label><br>
                    </div>

                    <div id="titleSort">
                        <input type="radio" name="sortRadio" id="title" value="title"><label for="title">Title</label>
                    </div><br>
                   
                    <div id="albumSort">
                        <input type="radio" name="sortRadio" id="album" value="album"><label for="album">Album</label>
                    </div><br>
                </div>
            </div>
        </div>
    </body>

    <?php
        require_once('config.php');
        populateSelects();

        echo '<script src="../javascript/sidebar.js"></script>';

        function populateSelects(){
            populateGenres();
            populateYears();
        }

        function populateGenres(){
            //have an array to store the genres in
            $genres = array();

            $db = Database::instance();
            $genreLists = $db->getTrendingGenres();
            
            while($currentGenreList = $genreLists->fetch_assoc()["genre"]){                
                $currentGenres = explode(", ", $currentGenreList);

                foreach($currentGenres as $genre){
                    if(!in_array($genre, $genres)){
                        array_push($genres, $genre);
                    }
                }//for each genre in the current list
            }//while genreLists set

            //sort the genres
            sort($genres);

            foreach($genres as $genre){
                echo "<div class='genre'>".$genre."</div>";
            }
        }

        function populateYears(){
            $years = array();

            $db = Database::instance();
            $yearsList = $db->getTrendingYears();
            
            while($currentYear = $yearsList->fetch_assoc()["year"]){              
                array_push($years, $currentYear);
            }//while genreLists set

            foreach($years as $year){
                echo "<div class='year'>".$year."</div>";
            }
        }
    ?>
</html>