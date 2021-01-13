USE u19030119_MUSIC_P4;

SELECT * FROM User WHERE id in (39, 43);

SELECT * FROM user_rating; /* 464fb6a8b26f3d46cdddf54edc682df5 */
SELECT * FROM user_rating WHERE id='0TrPqhAMoaKUFLR7iYDokf';

SELECT DISTINCT id FROM user_rating WHERE id NOT IN (
	SELECT id FROM trending_song 
    UNION SELECT id FROM newRelease_song 
    UNION SELECT id FROM featured_song 
);

insert into user_preference values (42, 'filterGenre', 'noel'), (42, 'filterYear', 'cody');

SELECT * FROM trending_song WHERE id='0TrPqhAMoaKUFLR7iYDokf'; 

SELECT table_name, id, AVG(rating) AS avg_rating FROM user_rating GROUP BY table_name, id ORDER BY avg_rating DESC LIMIT 20;

UPDATE user_rating SET table_name = "newRelease_song" WHERE id REGEXP '^[0-9]+$';

/* Practical 4 and 5 */
SELECT * FROM user_preference WHERE userID=43;

DELETE FROM user_preference WHERE userID=39;

INSERT INTO user_preference VALUES (1, 'sort', 'artist');

SELECT genre FROM trending_song;

SELECT * FROM trending_song;

/*Doran Greg (User that I am testing with (logged in as them))*/
INSERT INTO user_rating VALUES (43, "trending_song", "24Yi9hE78yPEbZ4kxyoXAI", 7); /* top song on the trending page */

SELECT * FROM trending_song ORDER BY ranking;

SELECT * FROM newRelease_song ORDER BY ranking;

/* delete from newRelease_song;
delete from featured_song;

these deletes: added newRelease_song<releaseDate, album> and featured_song<album>
*/

select * from newRelease_song where artistImageURL like '../images%';
select * from newRelease_song where albumImageURL like '../images%';

SELECT * FROM topRated_song ORDER BY ranking;

SELECT * FROM featured_song ORDER BY ranking;

SELECT AVG(rating) FROM user_rating GROUP BY table_name, id;