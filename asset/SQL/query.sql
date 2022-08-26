-- récupération de tous les films classés par date
SELECT id, title, image_url, release_date FROM movies ORDER BY release_date;

-- récupération de tous les prénoms + noms du casting
SELECT id, firstname, lastname FROM `castings`;

-- récupération de tous les prénoms + noms du castings par date de naissance
SELECT id, firstname, lastname, birthdate FROM `castings` ORDER BY birthdate;

-- récupération de tous les prénoms + noms du castings avec le nom du type de casting
SELECT castings.id, firstname, lastname, type, casting_types.name
FROM `castings`
INNER JOIN casting_types ON castings.type = casting_types.id;

-- récupération de tous les prénoms + noms du castings avec le nom du type de casting, ordonné par nom de type de casting
SELECT castings.id, firstname, lastname, casting_types.name
FROM `castings`
INNER JOIN casting_types ON castings.type = casting_types.id
ORDER BY casting_types.name;

-- récupération de tous les prénoms + noms du castings avec le nom du type de casting, ordonné par nom de type de casting et de nom, dans le sens décroissant pour les noms
SELECT castings.id, firstname, lastname, casting_types.name
FROM `castings`
INNER JOIN casting_types ON castings.type = casting_types.id
ORDER BY casting_types.name, lastname DESC;

-- récupération de tous les prénoms + noms du castings avec le nom du type de casting, ordonné par nom de type de casting et de nom, dans le sens décroissant pour les noms et les nom de type de casting 
SELECT castings.id, firstname, lastname, casting_types.name
FROM `castings`
INNER JOIN casting_types ON castings.type = casting_types.id
ORDER BY casting_types.name DESC, lastname DESC;

-- récupération de tous les prénoms + noms, nom du type de casting avec seulement les réalisateurs 
SELECT castings.firstname, castings.lastname, casting_types.name
FROM castings INNER JOIN casting_types ON castings.type = casting_types.id
WHERE casting_types.id = 1;

-- récupération de tous les prénoms + noms, nom du type de casting avec seulement les réalisateurs, ordonné par le nom du casting
SELECT castings.firstname, castings.lastname, casting_types.name
FROM castings INNER JOIN casting_types ON castings.type = casting_types.id
WHERE casting_types.id = 1
ORDER BY castings.lastname;

-- récupération de tous les prénoms + noms, nom du type de casting avec seulement les réalisateurs mais avec le name du casting_types
SELECT castings.firstname, castings.lastname, casting_types.name
FROM castings INNER JOIN casting_types ON castings.type = casting_types.id
WHERE casting_types.name = 'réalisateur';

-- récupération du casting de tous les films
SELECT movies.id, movies.title, castings.firstname, castings.lastname
FROM castings
INNER JOIN movies_castings ON movies_castings.casting_id = castings.id  
INNER JOIN movies ON movies_castings.movie_id = movies.id;

-- récupération du casting d'un film en particulier (le gendarme de st-tropez dans notre exemple)
SELECT movies.id, movies.title, castings.firstname, castings.lastname FROM castings
INNER JOIN movies_castings ON movies_castings.casting_id = castings.id  
INNER JOIN movies ON movies_castings.movie_id = movies.id
WHERE movies.id = 3;

-- récupération des notes de tous les utilisateurs par titre de film
SELECT movies_users_ratings.id, movies_users_ratings.rate, users.firstname, users.lastname, movies.title
FROM movies_users_ratings
INNER JOIN users ON movies_users_ratings.user_id = users.id
INNER JOIN movies ON movies_users_ratings.movie_id = movies.id;

-- récupération des notes de tous les utilisateurs par titre de film, classé par note
SELECT movies_users_ratings.id, movies_users_ratings.rate, users.firstname, users.lastname, movies.title
FROM movies_users_ratings
INNER JOIN users ON movies_users_ratings.user_id = users.id
INNER JOIN movies ON movies_users_ratings.movie_id = movies.id
ORDER BY movies_users_ratings.rate;

-- récupération des notes de tous les utilisateurs par titre de film, classé par note, ordre inversé (du plus grand au plus petit)
SELECT movies_users_ratings.id, movies_users_ratings.rate, users.firstname, users.lastname, movies.title
FROM movies_users_ratings
INNER JOIN users ON movies_users_ratings.user_id = users.id
INNER JOIN movies ON movies_users_ratings.movie_id = movies.id
ORDER BY movies_users_ratings.rate DESC;

-- récupération des notes de tous les utilisateurs par titre de film, classé par note, dont la note est supérieure à 50
SELECT movies_users_ratings.id, movies_users_ratings.rate, users.firstname, users.lastname, movies.title
FROM movies_users_ratings
INNER JOIN users ON movies_users_ratings.user_id = users.id
INNER JOIN movies ON movies_users_ratings.movie_id = movies.id
WHERE movies_users_ratings.rate > 50
ORDER BY movies_users_ratings.rate;

-- note moyenne de tout le site
SELECT AVG(movies_users_ratings.rate) as average_rate
FROM movies_users_ratings INNER JOIN movies;

-- note moyenne de chaque film
SELECT movies_users_ratings.movie_id, movies.title, AVG(movies_users_ratings.rate) as average_rate
FROM movies_users_ratings
    INNER JOIN movies
    ON movies_users_ratings.movie_id = movies.id
GROUP BY movies.id;

-- note moyenne d'un film dont l'id est 8
SELECT movies_users_ratings.movie_id, movies.title, AVG(movies_users_ratings.rate) as average_rate
FROM movies_users_ratings
    INNER JOIN movies
    ON movies_users_ratings.movie_id = movies.id
    WHERE movies.id = 8;

-- note moyenne de chaque film, ordonné par note du plus grand au plus petit
SELECT movies_users_ratings.movie_id, movies.title, AVG(movies_users_ratings.rate) as average_rate
FROM movies_users_ratings
    INNER JOIN movies
    ON movies_users_ratings.movie_id = movies.id
GROUP BY movies.id
ORDER BY movies_users_ratings.rate DESC;

-- note moyenne de chaque film, ordonné par note du plus grand au plus petit avec seulement ceux dont la note est supérieure à 60
SELECT movies_users_ratings.movie_id, movies.title, AVG(movies_users_ratings.rate) as average_rate
FROM movies_users_ratings
    INNER JOIN movies
    ON movies_users_ratings.movie_id = movies.id
GROUP BY movies.id
HAVING average_rate > 60;

--------------------------
-- note moyenne de chaque film pour le tri par classement
SELECT movies.*, AVG(movies_users_ratings.rate) as average_rate
FROM movies_users_ratings
    RIGHT JOIN movies
    ON movies_users_ratings.movie_id = movies.id
GROUP BY movies.id;

-- récupération de tout le castings d'un film dont l'id est 3, ordonné par type de casting
SELECT castings.*, casting_types.name
FROM castings
    INNER JOIN movies_castings ON movies_castings.casting_id = castings.id  
    INNER JOIN movies ON movies_castings.movie_id = movies.id
    INNER JOIN casting_types ON castings.type = casting_types.id
WHERE movies.id = 3
ORDER BY casting_types.id;

-- récupération des genres d'un film dont l'id est 8
SELECT movies.id, movies.title, movies_genres.genre_id as movie_genre_id, genres.name FROM movies
    INNER JOIN movies_genres ON movies_genres.movie_id = movies.id
    INNER JOIN genres ON genres.id = movies_genres.genre_id
WHERE movies.id = 8;