CREATE TABLE Image(
                      url_image VARCHAR(500) ,
                      PRIMARY KEY(url_image)
);

CREATE TABLE Wishlist(
                         id_wishlist INT AUTO_INCREMENT,
                         nom VARCHAR(100)  NOT NULL,
                         PRIMARY KEY(id_wishlist)
);

CREATE TABLE Type(
                     nom_type VARCHAR(100) ,
                     PRIMARY KEY(nom_type)
);

CREATE TABLE Utilisateur(
                            id_utilisateur INT AUTO_INCREMENT,
                            login VARCHAR(100)  NOT NULL,
                            email VARCHAR(200) ,
                            telephone CHAR(12) ,
                            password CHAR(64)  NOT NULL,
                            nom VARCHAR(200) ,
                            prenom VARCHAR(200) ,
                            nonce_email CHAR(20) ,
                            nonce_telephone CHAR(20) ,
                            admin BOOLEAN NOT NULL,
                            jour DATE NOT NULL,
                            url_image VARCHAR(500)  NOT NULL,
                            PRIMARY KEY(id_utilisateur),
                            UNIQUE(login),
                            UNIQUE(email),
                            UNIQUE(telephone),
                            FOREIGN KEY(url_image) REFERENCES Image(url_image) ON DELETE CASCADE
);

CREATE TABLE Article(
                        id_article INT AUTO_INCREMENT,
                        nom VARCHAR(200)  NOT NULL,
                        description VARCHAR(1000) ,
                        prix DECIMAL(15,2)   NOT NULL,
                        quantite INT NOT NULL,
                        jourModification DATE NOT NULL,
                        jour DATE NOT NULL,
                        id_utilisateur INT NOT NULL,
                        PRIMARY KEY(id_article),
                        FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
);

CREATE TABLE Commenter(
                          id_utilisateur INT,
                          id_article INT,
                          titre VARCHAR(200)  NOT NULL,
                          texte VARCHAR(1000)  NOT NULL,
                          note DOUBLE NOT NULL,
                          jourModification DATE NOT NULL,
                          jour DATE NOT NULL,
                          PRIMARY KEY(id_utilisateur, id_article),
                          FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
                          FOREIGN KEY(id_article) REFERENCES Article(id_article) ON DELETE CASCADE
);

CREATE TABLE illustrer(
                          id_article INT,
                          url_image VARCHAR(500) ,
                          ordre TINYINT,
                          PRIMARY KEY(id_article, url_image),
                          FOREIGN KEY(id_article) REFERENCES Article(id_article) ON DELETE CASCADE,
                          FOREIGN KEY(url_image) REFERENCES Image(url_image) ON DELETE CASCADE
);

CREATE TABLE enregistrer(
                            id_utilisateur INT,
                            id_wishlist INT,
                            PRIMARY KEY(id_utilisateur, id_wishlist),
                            FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
                            FOREIGN KEY(id_wishlist) REFERENCES Wishlist(id_wishlist) ON DELETE CASCADE
);

CREATE TABLE contenir(
                         id_article INT,
                         id_wishlist INT,
                         jour DATE NOT NULL,
                         PRIMARY KEY(id_article, id_wishlist),
                         FOREIGN KEY(id_article) REFERENCES Article(id_article) ON DELETE CASCADE,
                         FOREIGN KEY(id_wishlist) REFERENCES Wishlist(id_wishlist) ON DELETE CASCADE
);

CREATE TABLE deType(
                       id_article INT,
                       nom_type VARCHAR(100) ,
                       PRIMARY KEY(id_article, nom_type),
                       FOREIGN KEY(id_article) REFERENCES Article(id_article) ON DELETE CASCADE,
                       FOREIGN KEY(nom_type) REFERENCES Type(nom_type) ON DELETE CASCADE
);

CREATE TABLE dansPanier(
                           id_utilisateur INT,
                           id_article INT,
                           quantite INT,
                           jour DATE NOT NULL,
                           PRIMARY KEY(id_utilisateur, id_article),
                           FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
                           FOREIGN KEY(id_article) REFERENCES Article(id_article) ON DELETE CASCADE
);

CREATE TABLE Acheter(
                        id_utilisateur INT,
                        id_article INT,
                        quantite INT,
                        prix DECIMAL(15,2)  ,
                        jour DATE NOT NULL,
                        PRIMARY KEY(id_utilisateur, id_article),
                        FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
                        FOREIGN KEY(id_article) REFERENCES Article(id_article) ON DELETE SET NULL
);
