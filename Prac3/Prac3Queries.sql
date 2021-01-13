USE u19030119_MUSIC_P3;

SELECT * FROM User;

/* Practical 4 and 5 */
SELECT * FROM user_preferences;

SELECT * FROM trending_song ORDER BY ranking;

SELECT * FROM newRelease_song ORDER BY ranking;

delete from featured_song

SELECT * FROM topRated_song ORDER BY ranking;

SELECT * FROM featured_song ORDER BY ranking;

select * from topRated_song where artistImageURL like '../images%';
select * from topRated_song where albumImageURL like '../images%';
