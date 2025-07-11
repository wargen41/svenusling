CREATE TABLE site(var TEXT PRIMARY KEY, val TEXT);
INSERT INTO site VALUES('root', '/svenusling-site/');
INSERT INTO site VALUES('name', 'Sven Usling');

CREATE TABLE site_text(id TEXT PRIMARY KEY, category TEXT, sv TEXT, en TEXT);
INSERT INTO site_text VALUES('RATED_MOVIES', 'navigation', 'Betygsatta filmer', 'Rated movies');
INSERT INTO site_text VALUES('VIDEO_REVIEWS', 'navigation', 'Recensioner', 'Reviews');
INSERT INTO site_text VALUES('MISC_LISTS', 'navigation', 'Listor', 'Lists');
