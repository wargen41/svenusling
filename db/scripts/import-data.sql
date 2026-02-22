-- IMPORT CSV DATA in SQLite
-- Place the csv files in a folder named "import-data"
.mode csv
.separator ","
.import 'import-data/table-genres.csv' genres
.import 'import-data/table-relations.csv' relations
.import 'import-data/table-media.csv' media
.import 'import-data/table-awards.csv' awards
.import 'import-data/table-movies.csv' movies
.import 'import-data/table-persons.csv' persons
.import 'import-data/table-awards_movies.csv' awards_movies
.import 'import-data/table-awards_persons.csv' awards_persons
.import 'import-data/table-media_movies.csv' media_movies
.import 'import-data/table-media_persons.csv' media_persons
.import 'import-data/table-movies_genres.csv' movies_genres
.import 'import-data/table-movies_persons.csv' movies_persons
.import 'import-data/table-movies_quotes.csv' movies_quotes
.import 'import-data/table-movies_trivia.csv' movies_trivia
.import 'import-data/table-persons_trivia.csv' persons_trivia
.import 'import-data/table-relations_persons.csv' relations_persons

-- Remove null values

-- Display some imported data
.mode table
SELECT * FROM genres;
SELECT * FROM relations;
SELECT * FROM media LIMIT 10;
SELECT * FROM awards LIMIT 10;
SELECT * FROM movies LIMIT 10;
SELECT * FROM persons LIMIT 10;
SELECT * FROM awards_movies LIMIT 10;
SELECT * FROM awards_persons LIMIT 10;
SELECT * FROM media_movies LIMIT 10;
SELECT * FROM media_persons LIMIT 10;
SELECT * FROM movies_genres LIMIT 10;
SELECT * FROM movies_persons LIMIT 10;
SELECT * FROM movies_quotes LIMIT 5;
SELECT * FROM movies_trivia LIMIT 5;
SELECT * FROM persons_trivia LIMIT 5;
SELECT * FROM relations_persons LIMIT 10;
