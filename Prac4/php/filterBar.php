<?php
    echo '<div id="searchBar">
        <input type="text" id="searchTerm" placeholder="search for what you want">
        <button type="button" onclick="clearSearch();" id="reloadBtn">Clear</button>
      </div>
  
      <div id="searchFilter">
        <h3>Sort according to:</h3>
        <input type="radio" name="searchFilter" onclick="determineOperations()" value="artist">Artist
        <input type="radio" name="searchFilter" onclick="determineOperations()" value="title">Song Title
        <input type="radio" name="searchFilter" onclick="determineOperations()" value="album">Album
  
        <button type="button" id="clearSortBtn" onclick="clearSort()">Clear</button>
  
        <div id="filterFields">
          <h3>Filter: </h3>
          <label for="genreSelect">Genre</label>
          <select id="genreSelect" onchange="determineOperations()"></select>
          <button type="button" id="clearGenreBtn" onclick="clearGenreFilter()">Clear</button>
  
          <label for="yearSelect">Year</label>
          <select id="yearSelect" onchange="determineOperations()"></select>
          <button type="button" id="clearYearBtn" onclick="clearYearFilter()">Clear</button>
        </div>
      </div>
  
      <img id="loadScreen" alt="loading" src="../animations/Eclipse-1s-200px.svg">';
?>