CREATE TABLE Image(
   url_image VARCHAR(100) ,
   PRIMARY KEY(url_image)
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
   id_compte INT AUTO_INCREMENT,
   login VARCHAR(50)  NOT NULL,
   email VARCHAR(100) ,
   telephone CHAR(11) ,
   password CHAR(64)  NOT NULL,
   nom VARCHAR(50) ,
   prenom VARCHAR(50) ,
   nonce_email CHAR(20) ,
   nonce_telephone CHAR(20) ,
   admin BOOLEAN NOT NULL,
   url_image VARCHAR(100)  NOT NULL,
   PRIMARY KEY(id_compte),
   UNIQUE(login),
   UNIQUE(email),
   UNIQUE(telephone),
   FOREIGN KEY(url_image) REFERENCES Image(url_image)
);

CREATE TABLE Article(
   id_article INT AUTO_INCREMENT,
   nom VARCHAR(50)  NOT NULL,
   description VARCHAR(500) ,
   prix DECIMAL(15,2)   NOT NULL,
   quantite INT NOT NULL,
   id_compte INT NOT NULL,
   PRIMARY KEY(id_article),
   FOREIGN KEY(id_compte) REFERENCES Utilisateur(id_compte)
);

CREATE TABLE Commenter(
   id_compte INT,
   id_article INT,
   titre VARCHAR(50)  NOT NULL,
   texte VARCHAR(500)  NOT NULL,
   note DOUBLE NOT NULL,
   PRIMARY KEY(id_compte, id_article),
   FOREIGN KEY(id_compte) REFERENCES Utilisateur(id_compte),
   FOREIGN KEY(id_article) REFERENCES Article(id_article)
);

CREATE TABLE illustrer(
   id_article INT,
   url_image VARCHAR(100) ,
   PRIMARY KEY(id_article, url_image),
   FOREIGN KEY(id_article) REFERENCES Article(id_article),
   FOREIGN KEY(url_image) REFERENCES Image(url_image)
);

CREATE TABLE posseder(
   id_compte INT,
   id_wishlist INT,
   PRIMARY KEY(id_compte, id_wishlist),
   FOREIGN KEY(id_compte) REFERENCES Utilisateur(id_compte),
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
   id_compte INT,
   id_article INT,
   PRIMARY KEY(id_compte, id_article),
   FOREIGN KEY(id_compte) REFERENCES Utilisateur(id_compte),
   FOREIGN KEY(id_article) REFERENCES Article(id_article)
);
