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
INSERT INTO site VALUES('root', '/svenusling-site/');
INSERT INTO site VALUES('name', 'Sven Usling');
-- text strings
INSERT INTO site_text VALUES('SKIP_TO_MAIN', 'accessibility', 'Gå till primärt innehåll', 'Skip to main content');
INSERT INTO site_text VALUES('RATED_MOVIES', 'navigation', 'Betygsatta filmer', 'Rated movies');
INSERT INTO site_text VALUES('VIDEO_REVIEWS', 'navigation', 'Recensioner', 'Reviews');
INSERT INTO site_text VALUES('MISC_LISTS', 'navigation', 'Listor', 'Lists');
-- articles
INSERT INTO site_articles VALUES(
    'INTRODUCTION',
    'sv',
    'Välkommen',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ornare magna sit amet lectus lacinia rhoncus vestibulum sit amet velit.',
    'Cras eget accumsan massa. Suspendisse ex velit, imperdiet a urna quis, mattis consequat nulla. Sed a magna eu tellus tempor sodales nec sit amet felis. In non orci nec elit sodales porttitor. Cras enim purus, egestas ac ex eget, sodales laoreet lacus. Pellentesque fermentum, ex in molestie elementum, erat orci commodo felis, vel pharetra erat tellus at tortor. Donec ut lectus vitae massa scelerisque euismod. Mauris nisl massa, interdum luctus nunc et, aliquam laoreet metus. Maecenas fermentum eget tellus non tempor. Nulla suscipit velit dui, sed luctus massa fermentum vitae. Maecenas vehicula sodales vulputate. Quisque vel leo risus. Maecenas enim sem, porttitor vitae magna nec, elementum maximus est.\nDonec nulla urna, pellentesque in arcu a, varius porttitor est. Curabitur ut mi ut tortor ultrices mattis. Proin scelerisque lacinia risus nec rutrum. Quisque accumsan orci ac dictum varius. Aenean at eros at ex vestibulum auctor. Integer lacinia at nisl id vehicula. Proin in finibus nisl. Integer aliquet a metus a porttitor. Quisque sed eros a purus porta egestas. Integer non sagittis tortor. In hac habitasse platea dictumst. Mauris consectetur mi quis lorem lobortis gravida. Morbi sed leo sem. Pellentesque tincidunt erat in arcu ornare, quis tincidunt est ornare.',
    'Författarens namn',
    '2025-07-12'
);
