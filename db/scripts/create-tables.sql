PRAGMA foreign_keys = ON;
-- CREATE TABLES
-- and set up primary keys
CREATE TABLE media(
    id INTEGER PRIMARY KEY,
    file_name TEXT NOT NULL,
    media_type TEXT NOT NULL,
    file_directory TEXT NOT NULL,
    caption TEXT,
    attribution TEXT
);
CREATE TABLE persons(
    id INTEGER PRIMARY KEY,
    category TEXT NOT NULL,
    name TEXT NOT NULL,
    birth_date TEXT,
    death_date TEXT,
    poster_image_id INTEGER,
    FOREIGN KEY(poster_image_id) REFERENCES media(id)
);
CREATE TABLE genres(
    id INTEGER PRIMARY KEY,
    common INTEGER NOT NULL,
    sv TEXT NOT NULL,
    en TEXT NOT NULL
);
-- Not using foreign key for created_by, because the user might have been deleted
CREATE TABLE movies(
    id INTEGER PRIMARY KEY,
    hidden INTEGER NOT NULL,
    added_date TEXT,
    type TEXT,
    series_id INTEGER,
    season_id INTEGER,
    sequence_number INTEGER,
    sequence_number_2 INTEGER,
    title TEXT NOT NULL,
    original_title TEXT,
    sorting_title TEXT NOT NULL,
    year TEXT,
    year_2 TEXT,
    rating INTEGER NOT NULL,
    ViewDate TEXT,
    poster_image_id INTEGER,
    large_image_id INTEGER,
    IMDbID TEXT,
    description TEXT,
    created_by INTEGER,
    created_at TEXT,
    updated_at TEXT,
    FOREIGN KEY(series_id) REFERENCES movies(id),
    FOREIGN KEY(season_id) REFERENCES movies(id),
    FOREIGN KEY(poster_image_id) REFERENCES media(id),
    FOREIGN KEY(large_image_id) REFERENCES media(id)
);
CREATE TABLE movies_genres(
    movie_id INTEGER NOT NULL,
    genre_id INTEGER NOT NULL,
    FOREIGN KEY(movie_id) REFERENCES movies(id),
    FOREIGN KEY(genre_id) REFERENCES genres(id)
);
CREATE TABLE movies_persons(
    movie_id INTEGER NOT NULL,
    person_id INTEGER NOT NULL,
    person_name TEXT NOT NULL,
    category TEXT NOT NULL,
    sequence_order INTEGER NOT NULL,
    role_name TEXT,
    note TEXT,
    FOREIGN KEY(movie_id) REFERENCES movies(id),
    FOREIGN KEY(person_id) REFERENCES persons(id)
);
CREATE TABLE movies_trivia(
    movie_id INTEGER NOT NULL,
    sv TEXT,
    en TEXT,
    FOREIGN KEY(movie_id) REFERENCES movies(id)
);
CREATE TABLE persons_trivia(
    person_id INTEGER NOT NULL,
    sv TEXT,
    en TEXT,
    FOREIGN KEY(person_id) REFERENCES persons(id)
);
CREATE TABLE media_persons(
    media_id INTEGER NOT NULL,
    person_id INTEGER NOT NULL,
    FOREIGN KEY(media_id) REFERENCES media(id),
    FOREIGN KEY(person_id) REFERENCES persons(id)
);
CREATE TABLE media_movies(
    media_id INTEGER NOT NULL,
    movie_id INTEGER NOT NULL,
    FOREIGN KEY(media_id) REFERENCES media(id),
    FOREIGN KEY(movie_id) REFERENCES movies(id)
);
CREATE TABLE movies_quotes(
    movie_id INTEGER NOT NULL,
    quote TEXT NOT NULL,
    FOREIGN KEY(movie_id) REFERENCES movies(id)
);
CREATE TABLE awards(
    id INTEGER PRIMARY KEY,
    award TEXT NOT NULL,
    category TEXT
);
CREATE TABLE awards_persons(
    award_id INTEGER NOT NULL,
    year TEXT,
    movie_id INTEGER,
    person_id INTEGER,
    person_name TEXT NOT NULL,
    won INTEGER NOT NULL,
    note TEXT,
    FOREIGN KEY(award_id) REFERENCES awards(id),
    FOREIGN KEY(movie_id) REFERENCES movies(id),
    FOREIGN KEY(person_id) REFERENCES persons(id)
);
CREATE TABLE awards_movies(
    award_id INTEGER NOT NULL,
    year TEXT,
    movie_id INTEGER,
    won INTEGER NOT NULL,
    note TEXT,
    FOREIGN KEY(award_id) REFERENCES awards(id),
    FOREIGN KEY(movie_id) REFERENCES movies(id)
);
CREATE TABLE relations(
    id INTEGER PRIMARY KEY,
    sv TEXT,
    en TEXT
);
CREATE TABLE relations_persons(
    person_id INTEGER NOT NULL,
    person_2_id INTEGER,
    person_2_name TEXT,
    relation_id INTEGER NOT NULL,
    date_1 TEXT,
    date_2 TEXT,
    FOREIGN KEY(person_id) REFERENCES persons(id),
    FOREIGN KEY(person_2_id) REFERENCES persons(id),
    FOREIGN KEY(relation_id) REFERENCES relations(id)
);
CREATE TABLE users(
    id INTEGER PRIMARY KEY,
    username TEXT,
    email TEXT,
    password_hash TEXT,
    role TEXT,
    created_at TEXT,
    updated_at TEXT
);
CREATE TABLE reviews(
    id INTEGER PRIMARY KEY,
    movie_id INTEGER,
    user_id INTEGER,
    rating INTEGER,
    comment TEXT,
    created_at TEXT,
    updated_at TEXT,
    FOREIGN KEY(movie_id) REFERENCES movies(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
);
