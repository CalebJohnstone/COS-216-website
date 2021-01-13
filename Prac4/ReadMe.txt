COS 216 - Practical Assignment 4
Name: Caleb Johnstone

The ReadMe.txt details for practical 3 has been added to in this ReadMe.txt to ensure that marker does not have to go back to that one to check some detail
of practical 3.

-----------------------------------------------------------------I WANT TO BE MARKED ON WHEATLEY--------------------------------------------------------------------------------------

======================================================================================================================================================================================
CORS plug-in: Moesif CORS 
-> link to download it => https://chrome.google.com/webstore/detail/moesif-orign-cors-changer/digfbfaphojjndkpccljibejjbppifbc

The pages with searching, sorting and filtering(trending and top rated) : searching is done as you type
                                                    sort by either artist, title or album -> order alphabetically according to this attribute
													filter by either genre, year OR both
													can do any combination of these operations --> eg: filter by genre and year, while sorting by 
													artist and searching
													
													=> some combinations will match no results: for example if the filter is for genre set to rap and 
													year set to 2018, if there are no songs that match these restrictions on the current page an
													error message is displayed to the user to tell them this.
													
														--> another reason for this error message being displayed could be because the user has 
														searched a search term that does not match any of the songs on the current page.
													
													=> click the "clear" button to the right of an input element to remove the effect of that input.
														--> if next to a filter drop-down list: removes that filter and clears the filter option that 
														was selected.
														--> if next to the search edit box: clears the search edit box text and as a result the 
														appropriate songs to show, which is dependent on what filters and sorting are applied, 
														are shown.
															
													=> once an option is cleared the filtering, sorting and searching that is still being applied is 
													used to determine the new "state" of the restriction operations, where these restriction 
													operations are filtering, sorting and searching. In other words, once a change is made to
													the filtering, sorting or searching: JavaScript is used to re-evaluate what songs should be 
													displayed based on the filtering, sorting and searching still active on the page.
													
													=> a song is only added, from a stored array of songs, if its details match the filtering and 
													searching applied to the page. An array is used to store just the details of each song without the
													labels (for example: "Album: "). These details are stored in a semi-colon seperated list. This is
													to avoid songs being displayed when one of the labels mathes the search term. 
													
													For example: lets say that the user enters "Album" into the search bar. If none of the songs
													actual information contain this as a substring, then the "no songs matched your search and 
													filter criteria" message will be displayed.
													
													=> the search operation is only done on the songs that match the filtering applied to the page.
													So searching for a song will not result in a result or results that match the search term
													(in terms of containing the search term as a substring in the song's information) will not be 
													displayed unless they are already part of the "active songs" (the songs matching the
													filtering applied).
													
													=> the values that can be selected from the drop-down lists are sourced from the genres and years
													of the songs for that page. So each filter when applied by itself will return at least one song as
													a result of this fact. When applying both filters at once, it is possible for no songs to be 
													returned as not all genres will have necessarily have a song from every	year that can be selected
													from the year filter and vice versa.

For every page: the song details are displayed in the middle of the song section with the artist's image on the left and the album image on the right.
				For some artists this are the same for some songs.


New Releases page: hover your mouse over the red review text to show the top comment from Deezer for that song. If a song does not have a comment then
					"no reviews yet" is displayed as the tooltip text instead.


Featured page: click on of the play button for the song previews to play it, you don't have to pause one to play another one, the currently playing
				one will be paused automatically (used JavaScript for that as this is not a bulit-in functionality of an audio element in HTML).
				All of the other audio elements will be paused when an audio element is selected to play.

Calendar page: today button -> brings user back to today's month and highlights the current day
               next button -> not visible when on the current month as there is no next month songs to look at since those would be future dates
			   previous button -> not visible when the minimum month has been reached, i.e the song with the earliest release date will determine
			   which month will be the furthest back that the calendar will go.
			   
			   current month -> the month of demoing or any month that can be viewed that is before the month of demoing, could have no songs on it
			   due to no songs from the trending page being released during this month. Select the back button until you see a month with songs
			   released in that month.
			   
Sign Up Page: each user needs a unique email
              once the user has signed up, their API key is displayed to them and they are redirected to the launch page.

Log In Page: example of user details to use when demoing ==> Email: timj@gmail.com, Password: CalebJ#12

======================================================================================================================================================================================
Database: the database for this practical is called u19030119_MUSIC_P4. u19030119_MUSIC_P3 was used for practical 3. Two seperate databases were
		  made so that the fields and tables that are removed or added in this practical did not effect practical 3.

		The Entity Relationship Diagram for this database has been included as an image in the zip folder. There is also a SQL query script that can
		be used when demoing.

--> query to show the User table before signing up inorder to show that the user was not in the database before using the sign up page to create an
	user account:
	
	SELECT * FROM User;
	
Table:        	Page

User		  	Sign Up - store user details
user_preference Store the sorting and filtering preferences of the user --> theme and language are stored in local storage
user_rating		Store the ratings of the songs that the users make
trending_song	Trending page and Calendar page
newRelease_song	New Releases page
featured_song	Featured page

======================================================================================================================================================================================
The Top Rated has its data aggregated from the 3 song tables. The songs in the top 20 of the average user ratings from the trending_song, newRelease_song and featured_song tables are
displayed on this page with their details - including the average rating.

-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Song data is sourced from the database unless a certain time period has passed since the user last visited the launchPage, either after signing up or
clicked to go to that page.

If this time period has passed, a request is made to the API and any new songs found during that request are added to the appropriate table in the 
database. The top songs in the table are kept. Therefore any old songs are deleted from the database, inorder to keep the actual currently relevant
songs in the database.

Featured page has a time period of 20 minutes since it makes more requests than the other pages and is therefore slow to load all of the data. 
The other pages have a time period of 5 minutes. The time difference variable that determines whether the API or the database is sourced from is set
to 20 minutes for the Featured page so that it can be seen during demoing that the API does work for this page. This is so that the marker does not
have to wait the 20 minutes that would of been required without this line of code.

When demoing: to test for the other pages that the API actually returns the song data properly - wait 5 minutes and then test the pages so that the
data is sourced from the API rather than from just the database. I use the word "just", because the API will check if the song is already stored in
the relevant table and use this information as part of the returned data rather than extracting it from the external API multiple times unnecessarily.
Any songs in the list of songs for a page that are not stored yet, have their informtaion extracted from the external API and then stored in the
database to avoid repeatedly sourcing from the external API.

----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

API key creation: used the MD5 function with a random number concatenated with the current microtime as the input. None of the user's details where
				  used to determine the API key for the user as this would mean that the resultant key would be dependent on the user's details.
				  If someone else knows these details or has somehow acquired the detials of the user or multiple users then they would have a better
				  chance at being able to generate their API key. This would mean that they could make requests to the API and pretend that they are
				  this user. The random number generator used by PHP could have a predictable pattern over an extended time period.
				  
				  To avoid this - the current microtime was concatenated with the generated random number. The microtime at which the user singed up
				  is extremely difficult to guess as there are many decimal places. The fact that the resultant input is difficult to guess makes
				  it a good candidate for creating the API key with.

Hashing algorithm used for passwords: the salt is comprised of the user's email concatenate with either their name or their surname.

									  If the user's name has an even number of characters then the name is used. If the name has an odd number of
									  characters then the surname is used. The GOST hash function was used to hash the password concatenated with the
									  salt.
									  
SECURITY: the bind_param function was used to guard against SQL injection attacks - this function will help prevent malicious attacks on the database
		  by ensuring these inputs are escaped and sanitised.
									  
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
A copy of api_v2.php, which is the second iteration of the API (since Practical Assignment 3 was the first), has been included in the zip folder 
uploaded to clickUP.

I named this api_v2.php so that if the marker wants to look at the code in api.php for Practical 3 that there is only the code for Practical 3 
there and not also code for Practical 4 and 5.
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
SEPERATE SETTINGS SIDEBAR REASON: to avoid the top of the searchable pages (trending and top rated) from becoming cluttered and to allow the user
								  to save their genre and year filters from any page.
								  
user_rating table: 

	userID: the id of the user who makes the rating --> their details are stored in the User table
	table_name: the name of the table that the rated song is in --> trending_song, newRelease_song, featured_song
	id: the id of the song that the rating is for
	rating: the integer value out of ten that the user rates the song
	
	AVOIDANCE OF "STALE DATA": 
	
	(A) --> potential problem: when the table for a page, lets say the trending page so trending_song, no longer has a song because it was deleted as it is no longer in the in the list
	provided by Spotify (the external API used to populate this page and table). The user ratings for that song will still be in the user_rating table. These songs could have highlight
	enough average user ratings to be included as a song in the list of songs to be displayed on the Top Rated page.
	
	The problem arises in the fact that the table_name="trending_song" and id=<the id of the song in question> values will be used to look for a song that is no longer in the
	trending_song table. To avoid this and also because we no longer needed to keep user ratings for songs that are no longer recent or popular enough to be in the top songs lists
	for the various pages, I delete any user ratings for songs that are no longer in the table they were in - trending_song in this case.
	
user_preference table:

	userID: the id of the user that the preference is for --> their details are stored in the User table
	type: the type of preference --> this can be one of "filterGenre", "filterYear", "sort"
	value: the value of the preference --> if type="sort" this is the attribute to sort by. If type="filterGenre" or type="filterYear" then this is the genre or year to filter by
		   respectively.

	AVOIDANCE OF "STALE DATA":

	(A) -->potential problem: This is similar to the above potential problem. Users have their preferences for sorting and filtering stored in this table.
	
								  
STRUCTURE OF THE REQUESTS TO THE API FOR EACH PAGE'S INFO:

	--> POST PARAMETERS: type=info&title=<title of the page>&return[]=*
	
						where the values of <title of the page> are (provided with the capitalisation shown here): trending, newReleases, topRated,
						featured, calendar

--> the full details for all of the API requests are given in API_documentation.pdf under the "API documentation" folder.
						
====================================================================================================================================================
CLASSES:

	RedPillAPI (api.php): singleton used to implement all of the API functionality.
	Database (config.php): singleton used to implement all of the database functionality.

FILES INCLUDED IN MULTIPLE OTHER FILES:

	header.php: contains the top navbar to be used on each page
	header.js: highlight the page currently being viewed and showing the user their API key once they have logged in
	footer.php: contains the footer to be used on a page
	searchPage.js: search, filter and sort operations functionality needed for the trending and topRated pages - these are the searchable pages
	password.js: toggle between making the password text visible and not when using the sign up page
	
LOCAL STORAGE: 

	API_key: the user's API key
	seen_message: whether the user has seen the message telling them what their API key is
	language: the language that the user has selected as their preference for when using the site
	theme: the theme that the the user has selected as their preference for when using the site - either one of the dark or light modes
	
	Since these values are stored in local storage thay can be used each time the user visits the site, so they do not need to login each time and they also do not have to set 
	their preference for language and theme.
