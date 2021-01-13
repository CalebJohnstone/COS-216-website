COS 216 - Practical Assignment 2
Name: Caleb Johnstone

======================================================================================================================================================================================
CORS plug-in: Moesif CORS -> needed as some pages source from Deezer => https://chrome.google.com/webstore/detail/moesif-orign-cors-changer/digfbfaphojjndkpccljibejjbppifbc
=> keep on while demoing => Spotify API requests are not effected by this

The pages with searching, sorting and filtering(trending and top rated) : searching is done as you type
                                                    sort by either artist, title or album -> order alphabetically according to this attribiute
													filter by either genre, year OR both
													can do any combination of these operations --> eg: filter by genre and year, while sorting by artist and searching
													
													=> some combinations will match no results: for example if the filter is for genre set to rap and year set to 2018, if there are
													no songs that match these restrictions on the current page an error message is displayed to the user to tell them this.
													
														--> another reason for this error message being displayed could be because the user has searched a search term that does not
														match any of the songs on the current page.
													
													=> click the "clear" button to the right of an input element to remove the effect of that input.
														--> if next to a filter drop-down list: removes that filter and clears the filter option that was selected.
														--> if next to the search edit box: clears the search edit box text and as a result the appropriate songs to show,
															which is dependent on what filters and sorting are applied, are shown.
															
													=> once an option is cleared the filtering, sorting and searching that is still being applied is used to determine the new "state" of
													the restriction operations, where these restriction operations are filtering, sorting and searching. In other words, once a change is made to
													the filtering, sorting or searching: JavaScript is used to re-evaluate what songs should be displayed based on the filtering, sorting and searching
													still active on the page.
													
													=> a song is only added, from a stored array of songs, if its details match the filtering and searching applied to the page. An array is used to store just the 
													details of each song without the labels (for example: "Album: "). These details are stored in a semi-colon seperated list. This is to avoid songs being displayed when
													one of the labels mathes the search term. For example: lets say that the user enters "Album" into the search bar. If none of the songs actual information contain
													this as a substring, then the "no songs matched your search and filter criteria" message will be displayed.
													
													=> the search operation is only done on the songs that match the filtering applied to the page. So searching for a song will not result in a result or
													results that match the search term (in terms of containing the search term as a substring in the song's information) will not be displayed unless they are
													already part of the "active songs" (the songs matching the filtering applied).
													
													=> the values that can be selected from the drop-down lists are sourced from the genres and years of the songs for that page. So each filter when applied by itself will return at
													least one song as a result of this fact. When applying both filters at once, it is possible for no songs to be returned as not all genres will have necessarily have a song from every
													year that can be selected from the year filter and vice versa.

For every page: the song details are displayed in the middle of the song section with the artist's image on the left and the album image on the right. For some artists this are the same 
				for some songs.


New Releases page: hover your mouse over the red review text to show the top comment from Deezer for that song. If a song does not have a comment then "no reviews yet" is displayed as the
				   tooltip text instead.


Featured page: click on of the play button for the song previews to play it, you don't have to pause one to play another one, the currently playing one will be paused automatically
			   (used JavaScript for that as this is not a bulit-in functionality of an audio element in HTML). All of the other audio elements will be paused when an audio element is
			   selected to play.

Calendar page: today button -> brings user back to today's month and highlights the current day
               next button -> not visible when on the current month as there is no next month songs to look at since those would be future dates
			   previous button -> not visible when the minimum month has been reached, i.e the song with the earliest release date will determine which month will be the furthest
			   back that the calendar will go
			   
			   current month (the month of demoing or any month that can be viewed that is before the month of demoing) could have no songs on it due to no songs from the 
			   trending page being released during this month. Select the back button until you see a month with release songs.
