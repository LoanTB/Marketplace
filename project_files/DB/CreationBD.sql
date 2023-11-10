CREATE TABLE Image(
                      url VARCHAR(100) ,
                      PRIMARY KEY(url)
);

CREATE TABLE Wishlist(
                         id_wishlist INT AUTO_INCREMENT,
                         nom VARCHAR(50)  NOT NULL,
                         PRIMARY KEY(id_wishlist)
);

CREATE TABLE Type(
                     nom_type VARCHAR(50) ,
                     PRIMARY KEY(nom_type)
);

CREATE TABLE Utilisateur(
                            id_utilisateur INT AUTO_INCREMENT,
                            login VARCHAR(50)  NOT NULL,
                            email VARCHAR(100) ,
                            telephone CHAR(11) ,
                            password CHAR(64)  NOT NULL,
                            nom VARCHAR(50) ,
                            prenom VARCHAR(50) ,
                            nonce_email CHAR(20) ,
                            nonce_telephone CHAR(20) ,
                            admin BOOLEAN NOT NULL,
                            url VARCHAR(100)  NOT NULL,
                            PRIMARY KEY(id_utilisateur),
                            UNIQUE(login),
                            UNIQUE(email),
                            UNIQUE(telephone),
                            FOREIGN KEY(url) REFERENCES Image(url)
);

CREATE TABLE Article(
                        id_article INT AUTO_INCREMENT,
                        nom VARCHAR(50)  NOT NULL,
                        description VARCHAR(500) ,
                        prix DECIMAL(15,2)   NOT NULL,
                        quantite INT NOT NULL,
                        id_utilisateur INT NOT NULL,
                        PRIMARY KEY(id_article),
                        FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Commenter(
                          id_utilisateur INT,
                          id_article INT,
                          titre VARCHAR(50)  NOT NULL,
                          texte VARCHAR(500)  NOT NULL,
                          note DOUBLE NOT NULL,
                          PRIMARY KEY(id_utilisateur, id_article),
                          FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
                          FOREIGN KEY(id_article) REFERENCES Article(id_article)
);

CREATE TABLE illustrer(
                          id_article INT,
                          url VARCHAR(100) ,
                          PRIMARY KEY(id_article, url),
                          FOREIGN KEY(id_article) REFERENCES Article(id_article),
                          FOREIGN KEY(url) REFERENCES Image(url)
);

CREATE TABLE posseder(
                         id_utilisateur INT,
                         id_wishlist INT,
                         PRIMARY KEY(id_utilisateur, id_wishlist),
                         FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
                         FOREIGN KEY(id_wishlist) REFERENCES Wishlist(id_wishlist)
);

CREATE TABLE souhaiter(
                          id_article INT,
                          id_wishlist INT,
                          PRIMARY KEY(id_article, id_wishlist),
                          FOREIGN KEY(id_article) REFERENCES Article(id_article),
                          FOREIGN KEY(id_wishlist) REFERENCES Wishlist(id_wishlist)
);

CREATE TABLE type_produit(
                             id_article INT,
                             nom_type VARCHAR(50) ,
                             PRIMARY KEY(id_article, nom_type),
                             FOREIGN KEY(id_article) REFERENCES Article(id_article),
                             FOREIGN KEY(nom_type) REFERENCES Type(nom_type)
);

CREATE TABLE dansPanier(
                           id_utilisateur INT,
                           id_article INT,
                           PRIMARY KEY(id_utilisateur, id_article),
                           FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
                           FOREIGN KEY(id_article) REFERENCES Article(id_article)
);
