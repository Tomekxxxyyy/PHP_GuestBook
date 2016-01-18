CREATE TABLE glowna_nazwiska(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
data_dodania DATETIME,
data_modyfikacji DATETIME,
imie VARCHAR(75),
nazwisko VARCHAR(75)    
);

CREATE TABLE adres(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
id_glowny INT NOT NULL,
data_dodania DATETIME,
data_modyfikacji DATETIME,
adres VARCHAR(255),
miasto VARCHAR(30),
wojewodztwo VARCHAR(20),
kod VARCHAR(10),
typ ENUM('dom', 'praca', 'inny')
);

CREATE TABLE telefon(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
id_glowny INT NOT NULL,
data_dodania DATETIME,
data_modyfikacji DATETIME,
telefon VARCHAR(25),
typ ENUM('dom', 'praca', 'inny')
);

CREATE TABLE faks(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
id_glowny INT NOT NULL,
data_dodania DATETIME,
data_modyfikacji DATETIME,
faks VARCHAR(25),
typ ENUM('dom', 'praca', 'inny')
);

CREATE TABLE email(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
id_glowny INT NOT NULL,
data_dodania DATETIME,
data_modyfikacji DATETIME,
email VARCHAR(150),
typ ENUM('dom', 'praca', 'inny')
);

CREATE TABLE notatki(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
id_glowny INT NOT NULL unique,
data_dodania DATETIME,
data_modyfikacji DATETIME,
notatka TEXT
);