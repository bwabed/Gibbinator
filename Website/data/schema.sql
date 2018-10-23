DROP TABLE IF EXISTS user;
CREATE TABLE  user (
  id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  firstName VARCHAR(64)  NOT NULL,
  lastName  VARCHAR(64)  NOT NULL,
  email     VARCHAR(128) NOT NULL,
  password  VARCHAR(40)  NOT NULL,
  PRIMARY KEY  (id)
);

INSERT INTO user (firstName, lastName, email, password) VALUES ('Ramon',  'Binz',  'ramon.binz@bbcag.ch',   sha1('ramon'));
INSERT INTO user (firstName, lastName, email, password) VALUES ('Samuel', 'Wicky', 'samuel.wicky@bbcag.ch', sha1('samuel'));
