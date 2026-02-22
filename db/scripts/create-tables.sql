PRAGMA foreign_keys = ON;
-- CREATE TABLES
-- and set up primary keys
CREATE TABLE media(
    MediaID INTEGER PRIMARY KEY,
    FileName TEXT NOT NULL,
    Type TEXT NOT NULL,
    Directory TEXT NOT NULL,
    Caption TEXT,
    Attribution TEXT
);
CREATE TABLE persons(
    PersonID INTEGER PRIMARY KEY,
    Category TEXT NOT NULL,
    Name TEXT NOT NULL,
    BirthDate TEXT,
    DeathDate TEXT,
    PosterImageID INTEGER
);
CREATE TABLE genres(
    GenreID INTEGER PRIMARY KEY,
    Common INTEGER NOT NULL,
    sv TEXT NOT NULL,
    en TEXT NOT NULL
);
CREATE TABLE movies(
    MovieID INTEGER PRIMARY KEY,
    Hidden INTEGER NOT NULL,
    AddDate TEXT,
    Type TEXT,
    SeriesID INTEGER,
    SeasonID INTEGER,
    SequenceNumber INTEGER,
    SequenceNumber2 INTEGER,
    Title TEXT NOT NULL,
    OriginalTitle TEXT,
    Sorting TEXT NOT NULL,
    Year TEXT,
    Year2 TEXT,
    Rating INTEGER NOT NULL,
    ViewDate TEXT,
    PosterImageID INTEGER,
    LargeImageID INTEGER,
    IMDbID TEXT
);
CREATE TABLE movies_genres(
    MovieID INTEGER NOT NULL,
    GenreID INTEGER NOT NULL,
    FOREIGN KEY(MovieID) REFERENCES movies(MovieID),
    FOREIGN KEY(GenreID) REFERENCES genres(GenreID)
);
CREATE TABLE movies_persons(
    MovieID INTEGER NOT NULL,
    PersonID INTEGER NOT NULL,
    PersonName TEXT NOT NULL,
    Category TEXT NOT NULL,
    SequenceOrder INTEGER NOT NULL,
    RoleName TEXT,
    Note TEXT,
    FOREIGN KEY(MovieID) REFERENCES movies(MovieID),
    FOREIGN KEY(PersonID) REFERENCES persons(PersonID)
);
CREATE TABLE movies_trivia(
    MovieID INTEGER NOT NULL,
    sv TEXT,
    en TEXT,
    FOREIGN KEY(MovieID) REFERENCES movies(MovieID)
);
CREATE TABLE persons_trivia(
    PersonID INTEGER NOT NULL,
    sv TEXT,
    en TEXT,
    FOREIGN KEY(PersonID) REFERENCES persons(PersonID)
);
CREATE TABLE media_persons(
    MediaID INTEGER NOT NULL,
    PersonID INTEGER NOT NULL,
    FOREIGN KEY(MediaID) REFERENCES media(MediaID),
    FOREIGN KEY(PersonID) REFERENCES persons(PersonID)
);
CREATE TABLE media_movies(
    MediaID INTEGER NOT NULL,
    MovieID INTEGER NOT NULL,
    FOREIGN KEY(MediaID) REFERENCES media(MediaID),
    FOREIGN KEY(MovieID) REFERENCES movies(MovieID)
);
CREATE TABLE movies_quotes(
    MovieID INTEGER NOT NULL,
    Quote TEXT NOT NULL,
    FOREIGN KEY(MovieID) REFERENCES movies(MovieID)
);
CREATE TABLE awards(
    AwardID INTEGER PRIMARY KEY,
    Award TEXT NOT NULL,
    Category TEXT
);
CREATE TABLE awards_persons(
    AwardID INTEGER NOT NULL,
    Year TEXT,
    MovieID INTEGER,
    PersonID INTEGER,
    PersonName TEXT NOT NULL,
    Won INTEGER NOT NULL,
    Note TEXT,
    FOREIGN KEY(AwardID) REFERENCES awards(AwardID),
    FOREIGN KEY(MovieID) REFERENCES movies(MovieID),
    FOREIGN KEY(PersonID) REFERENCES persons(PersonID)
);
CREATE TABLE awards_movies(
    AwardID INTEGER NOT NULL,
    Year TEXT,
    MovieID INTEGER,
    Won INTEGER NOT NULL,
    Note TEXT,
    FOREIGN KEY(AwardID) REFERENCES awards(AwardID),
    FOREIGN KEY(MovieID) REFERENCES movies(MovieID)
);
CREATE TABLE relations(
    RelationID INTEGER PRIMARY KEY,
    sv TEXT,
    en TEXT
);
CREATE TABLE relations_persons(
    PersonID INTEGER NOT NULL,
    Person2ID INTEGER,
    Person2Name TEXT,
    RelationID INTEGER NOT NULL,
    Date1 TEXT,
    Date2 TEXT,
    FOREIGN KEY(PersonID) REFERENCES persons(PersonID),
    FOREIGN KEY(Person2ID) REFERENCES persons(PersonID),
    FOREIGN KEY(RelationID) REFERENCES relations(RelationID)
);
