CREATE TABLE Image(
                      id_image INT AUTO_INCREMENT,
                      PRIMARY KEY(id_image)
);

CREATE TABLE Wishlist(
                         id_wishlist INT AUTO_INCREMENT,
                         nom VARCHAR(50)  NOT NULL,
                         PRIMARY KEY(id_wishlist)
);

CREATE TABLE Utilisateur(
                            id_utilisateur INT AUTO_INCREMENT,
                            login VARCHAR(50)  NOT NULL,
                            email VARCHAR(100) ,
                            password CHAR(64)  NOT NULL,
                            PRIMARY KEY(id_utilisateur),
                            UNIQUE(login),
                            UNIQUE(email)
);

CREATE TABLE Admin(
                      id_utilisateur INT,
                      PRIMARY KEY(id_utilisateur),
                      FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Type(
                     nom_type VARCHAR(50) ,
                     PRIMARY KEY(nom_type)
);

CREATE TABLE Client(
                       id_utilisateur INT,
                       telephone CHAR(11) ,
                       nom VARCHAR(50) ,
                       prenom VARCHAR(50) ,
                       nonce_email CHAR(20) ,
                       nonce_telephone CHAR(20) ,
                       id_image INT NOT NULL,
                       PRIMARY KEY(id_utilisateur),
                       UNIQUE(telephone),
                       FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
                       FOREIGN KEY(id_image) REFERENCES Image(id_image)
);

CREATE TABLE Article(
                        id_article INT AUTO_INCREMENT,
                        prix DECIMAL(15,2)   NOT NULL,
                        nom VARCHAR(50)  NOT NULL,
                        description VARCHAR(500) ,
                        quantite INT NOT NULL,
                        id_utilisateur INT NOT NULL,
                        id_utilisateur_1 INT NOT NULL,
                        PRIMARY KEY(id_article),
                        FOREIGN KEY(id_utilisateur) REFERENCES Admin(id_utilisateur),
                        FOREIGN KEY(id_utilisateur_1) REFERENCES Client(id_utilisateur)
);

CREATE TABLE Commenter(
                          id_utilisateur INT,
                          id_article INT,
                          titre VARCHAR(50)  NOT NULL,
                          texte VARCHAR(500)  NOT NULL,
                          note DOUBLE NOT NULL,
                          PRIMARY KEY(id_utilisateur, id_article),
                          FOREIGN KEY(id_utilisateur) REFERENCES Client(id_utilisateur),
                          FOREIGN KEY(id_article) REFERENCES Article(id_article)
);

CREATE TABLE illustrer(
                          id_article INT,
                          id_image INT,
                          PRIMARY KEY(id_article, id_image),
                          FOREIGN KEY(id_article) REFERENCES Article(id_article),
                          FOREIGN KEY(id_image) REFERENCES Image(id_image)
);

CREATE TABLE posseder(
                         id_utilisateur INT,
                         id_wishlist INT,
                         PRIMARY KEY(id_utilisateur, id_wishlist),
                         FOREIGN KEY(id_utilisateur) REFERENCES Client(id_utilisateur),
                         FOREIGN KEY(id_wishlist) REFERENCES Wishlist(id_wishlist)
);

CREATE TABLE souhaiter(
                          id_article INT,
                          id_wishlist INT,
                          PRIMARY KEY(id_article, id_wishlist),
                          FOREIGN KEY(id_article) REFERENCES Article(id_article),
                          FOREIGN KEY(id_wishlist) REFERENCES Wishlist(id_wishlist)
);

CREATE TABLE ban(
                    id_utilisateur INT,
                    id_utilisateur_1 INT,
                    motif VARCHAR(200) ,
                    fin DATE,
                    PRIMARY KEY(id_utilisateur, id_utilisateur_1),
                    FOREIGN KEY(id_utilisateur) REFERENCES Client(id_utilisateur),
                    FOREIGN KEY(id_utilisateur_1) REFERENCES Admin(id_utilisateur)
);

CREATE TABLE type_produit(
                             id_article INT,
                             nom_type VARCHAR(50) ,
                             PRIMARY KEY(id_article, nom_type),
                             FOREIGN KEY(id_article) REFERENCES Article(id_article),
                             FOREIGN KEY(nom_type) REFERENCES Type(nom_type)
);
