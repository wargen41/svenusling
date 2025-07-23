-- TABLES
CREATE TABLE site(
    var TEXT PRIMARY KEY,
    val TEXT
);
CREATE TABLE site_text(
    id TEXT PRIMARY KEY,
    category TEXT,
    sv TEXT,
    en TEXT
);
CREATE TABLE site_articles(
    id TEXT,
    lang TEXT,
    title TEXT,
    ingress TEXT,
    body TEXT,
    author TEXT,
    date TEXT,
    PRIMARY KEY (id, lang)
);
-- CONTENT
-- general site variables
INSERT INTO site VALUES('name', 'Sven Usling');
INSERT INTO site VALUES('default_language', 'sv');
INSERT INTO site VALUES('icon', '/assets/siluett.jpeg');
INSERT INTO site VALUES('logo', '/assets/siluett.jpeg');
-- text strings
INSERT INTO site_text VALUES('SKIP_TO_MAIN', 'accessibility', 'Gå till primärt innehåll', 'Skip to main content');
INSERT INTO site_text VALUES('MAIN_MENU_TITLE', 'accessibility', 'Meny', 'Menu');
INSERT INTO site_text VALUES('START_TITLE', 'titles', 'Välkommen', 'Welcome');
INSERT INTO site_text VALUES('RATED_MOVIES_TITLE', 'titles', 'Betygsatta filmer', 'Rated movies');
INSERT INTO site_text VALUES('FILMS_AND_SERIES_LIST_TITLE', 'titles', 'Filmer och serier', 'Movies and series');
INSERT INTO site_text VALUES('FILMS_LIST_TITLE', 'titles', 'Filmer', 'Movies');
INSERT INTO site_text VALUES('SERIES_LIST_TITLE', 'titles', 'Serier', 'Series');
INSERT INTO site_text VALUES('VIDEO_REVIEWS_TITLE', 'titles', 'Videorecensioner', 'Video
reviews');
INSERT INTO site_text VALUES('MISC_LISTS_TITLE', 'titles', 'Listor', 'Lists');
-- articles
INSERT INTO site_articles VALUES(
    'INTRODUCTION',
    'sv',
    'Välkommen',
    'Lorem ipsum dolor sit amet, **consectetur adipiscing** elit. Nunc ornare magna sit amet lectus lacinia rhoncus vestibulum sit amet velit.',
    'Cras eget accumsan massa. Suspendisse ex velit, imperdiet a urna quis, mattis consequat nulla. Sed a magna eu tellus tempor sodales nec sit amet felis. In non orci nec elit sodales porttitor.',
    'Författarens namn',
    '2025-07-12'
);
INSERT INTO site_articles VALUES(
    'THE_MOVIES',
    'sv',
    'I <3 FILM',
    'Att uttrycka sin kärlek till filmer kan vara en kreativ och intressant process. Det handlar om att utforska och dela sin passion för detta fantastiska medium.',
    'Jag har hunnit se några olika filmer och den här sidan ger en liten inblick i vad jag tyckte om dem. Tycker du likadant, eller finns det något att diskutera? Vilket som är trevligt. ;)',
    'Sven Usling',
    '2025-07-22'
);
