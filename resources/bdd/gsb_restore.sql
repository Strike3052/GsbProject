-- Script de restauration de l'application "GSB Frais"
drop database if exists gsb_frais;
drop user if exists 'userGsb'@'localhost';
-- Administration de la base de données
CREATE DATABASE gsb_frais ;
CREATE USER 'userGsb'@'localhost' IDENTIFIED BY 'secret';
GRANT SHOW DATABASES ON *.* TO 'userGsb'@'localhost';
GRANT ALL PRIVILEGES ON `gsb_frais`.* TO 'userGsb'@'localhost';
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
USE gsb_frais ;

-- Création de la structure de la base de données
CREATE TABLE IF NOT EXISTS fraisforfait (
  id char(3) NOT NULL,
  libelle char(20) DEFAULT NULL,
  montant decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS etat (
  id char(2) NOT NULL,
  libelle varchar(30) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comptable (
  id char(4) NOT NULL,
  nom char(30) DEFAULT NULL,
  prenom char(30)  DEFAULT NULL, 
  login char(20) DEFAULT NULL,
  mdp char(20) DEFAULT NULL,
  adresse char(30) DEFAULT NULL,
  cp char(5) DEFAULT NULL,
  ville char(30) DEFAULT NULL,
  dateembauche date DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS visiteur (
  id char(4) NOT NULL,
  nom char(30) DEFAULT NULL,
  prenom char(30)  DEFAULT NULL, 
  login char(20) DEFAULT NULL,
  mdp char(20) DEFAULT NULL,
  adresse char(30) DEFAULT NULL,
  cp char(5) DEFAULT NULL,
  ville char(30) DEFAULT NULL,
  dateembauche date DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fichefrais (
  idvisiteur char(4) NOT NULL,
  mois char(6) NOT NULL,
  nbjustificatifs int(11) DEFAULT NULL,
  montantvalide decimal(10,2) DEFAULT NULL,
  datemodif date DEFAULT NULL,
  idetat char(2) DEFAULT 'CR',
  PRIMARY KEY (idvisiteur,mois),
  FOREIGN KEY (idetat) REFERENCES etat(id),
  FOREIGN KEY (idvisiteur) REFERENCES visiteur(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lignefraisforfait (
  idvisiteur char(4) NOT NULL,
  mois char(6) NOT NULL,
  idfraisforfait char(3) NOT NULL,
  quantite int(11) DEFAULT NULL,
  PRIMARY KEY (idvisiteur,mois,idfraisforfait),
  FOREIGN KEY (idvisiteur, mois) REFERENCES fichefrais(idvisiteur, mois),
  FOREIGN KEY (idfraisforfait) REFERENCES fraisforfait(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lignefraishorsforfait (
  id int(11) NOT NULL auto_increment,
  idvisiteur char(4) NOT NULL,
  mois char(6) NOT NULL,
  libelle varchar(100) DEFAULT NULL,
  date date DEFAULT NULL,
  montant decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idvisiteur, mois) REFERENCES fichefrais(idvisiteur, mois)
) ENGINE=InnoDB;

-- Alimentation des données paramètres
INSERT INTO fraisforfait (id, libelle, montant) VALUES
('ETP', 'Forfait Etape', 110.00),
('KM', 'Frais Kilométrique', 0.62),
('NUI', 'Nuitée Hôtel', 80.00),
('REP', 'Repas Restaurant', 25.00);

INSERT INTO etat (id, libelle) VALUES
('RB', 'Remboursée'),
('CL', 'Saisie clôturée'),
('CR', 'Fiche créée, saisie en cours'),
('VA', 'Validée et mise en paiement');

-- Récupération des utilisateurs
INSERT INTO visiteur (id, nom, prenom, login, mdp, adresse, cp, ville, dateembauche) VALUES
('a131', 'Villechalane', 'Louis', 'lvillachane', 'jux7g', '8 rue des Charmes', '46000', 'Cahors', '2005-12-21'),
('a17', 'Andre', 'David', 'dandre', 'oppg5', '1 rue Petit', '46200', 'Lalbenque', '1998-11-23'),
('a55', 'Bedos', 'Christian', 'cbedos', 'gmhxd', '1 rue Peranud', '46250', 'Montcuq', '1995-01-12'),
('a93', 'Tusseau', 'Louis', 'ltusseau', 'ktp3s', '22 rue des Ternes', '46123', 'Gramat', '2000-05-01'),
('b13', 'Bentot', 'Pascal', 'pbentot', 'doyw1', '11 allée des Cerises', '46512', 'Bessines', '1992-07-09'),
('b16', 'Bioret', 'Luc', 'lbioret', 'hrjfs', '1 Avenue gambetta', '46000', 'Cahors', '1998-05-11'),
('b19', 'Bunisset', 'Francis', 'fbunisset', '4vbnd', '10 rue des Perles', '93100', 'Montreuil', '1987-10-21'),
('b25', 'Bunisset', 'Denise', 'dbunisset', 's1y1r', '23 rue Manin', '75019', 'paris', '2010-12-05'),
('b28', 'Cacheux', 'Bernard', 'bcacheux', 'uf7r3', '114 rue Blanche', '75017', 'Paris', '2009-11-12'),
('b34', 'Cadic', 'Eric', 'ecadic', '6u8dc', '123 avenue de la République', '75011', 'Paris', '2008-09-23'),
('b4', 'Charoze', 'Catherine', 'ccharoze', 'u817o', '100 rue Petit', '75019', 'Paris', '2005-11-12'),
('b50', 'Clepkens', 'Christophe', 'cclepkens', 'bw1us', '12 allée des Anges', '93230', 'Romainville', '2003-08-11'),
('b59', 'Cottin', 'Vincenne', 'vcottin', '2hoh9', '36 rue Des Roches', '93100', 'Monteuil', '2001-11-18'),
('c14', 'Daburon', 'François', 'fdaburon', '7oqpv', '13 rue de Chanzy', '94000', 'Créteil', '2002-02-11'),
('c3', 'De', 'Philippe', 'pde', 'gk9kx', '13 rue Barthes', '94000', 'Créteil', '2010-12-14'),
('c54', 'Debelle', 'Michel', 'mdebelle', 'od5rt', '181 avenue Barbusse', '93210', 'Rosny', '2006-11-23'),
('d13', 'Debelle', 'Jeanne', 'jdebelle', 'nvwqq', '134 allée des Joncs', '44000', 'Nantes', '2000-05-11'),
('d51', 'Debroise', 'Michel', 'mdebroise', 'sghkb', '2 Bld Jourdain', '44000', 'Nantes', '2001-04-17'),
('e22', 'Desmarquest', 'Nathalie', 'ndesmarquest', 'f1fob', '14 Place d Arc', '45000', 'Orléans', '2005-11-12'),
('e24', 'Desnost', 'Pierre', 'pdesnost', '4k2o5', '16 avenue des Cèdres', '23200', 'Guéret', '2001-02-05'),
('e39', 'Dudouit', 'Frédéric', 'fdudouit', '44im8', '18 rue de l église', '23120', 'GrandBourg', '2000-08-01'),
('e49', 'Duncombe', 'Claude', 'cduncombe', 'qf77j', '19 rue de la tour', '23100', 'La souteraine', '1987-10-10'),
('e5', 'Enault-Pascreau', 'Céline', 'cenault', 'y2qdu', '25 place de la gare', '23200', 'Gueret', '1995-09-01'),
('e52', 'Eynde', 'Valérie', 'veynde', 'i7sn3', '3 Grand Place', '13015', 'Marseille', '1999-11-01'),
('f21', 'Finck', 'Jacques', 'jfinck', 'mpb3t', '10 avenue du Prado', '13002', 'Marseille', '2001-11-10'),
('f39', 'Frémont', 'Fernande', 'ffremont', 'xs5tq', '4 route de la mer', '13012', 'Allauh', '1998-10-01'),
('f4', 'Gest', 'Alain', 'agest', 'dywvt', '30 avenue de la mer', '13025', 'Berre', '1985-11-01');

/*
* Ajout des prix kilometrik détaillés
*/
/* on drop, on sait jamais */
drop table if exists fraiskilometrique;
-- Ajout table des frais kilométrique détaillés
create table fraiskilometrique (
	id char(5) not null,
    typeVehicule varchar(12) not null,
    libelle varchar(30) not null,
    montant decimal(5,2) not null,
    constraint PK_fraiskilometrique primary key(id)
);
-- Ajout des frais détaillés
insert into fraiskilometrique (id,typeVehicule,libelle, montant) values ('ESS4M','ESSENCE','Vehicule essence 4CV', 0.62);
insert into fraiskilometrique (id,typeVehicule,libelle, montant) values ('ESS5P','ESSENCE','Vehicule essence 5CV ou plus', 0.67);
insert into fraiskilometrique (id,typeVehicule,libelle, montant) values ('DIE4M','DIESEL','Vehicule diesel 4CV', 0.52);
insert into fraiskilometrique (id,typeVehicule,libelle, montant) values ('DIE5P','DIESEL','Vehicule diesel 5CV', 0.58);

-- Peut être ignoré
-- Mais on le fait pour détecter des problèmes futurs vu qu'on en a plus besin techniquement
update fraisforfait set montant=null where id='KM';

-- On drop, on sait jamais
drop table if exists ligneForfaitKilometrique;
-- Ajout table de jointure pour ne pas toucher l'ensemble de la BDD fonctionnel
create table ligneForfaitKilometrique(
    idVisiteur char(4) not null,
    mois char(6) not null,
    idFraisKilometrique char(5) null,
    constraint PK_ligneForfaitKilo primary key (idVisiteur,mois),
    constraint FK_LigneKilo_Visiteur foreign key (idVisiteur) references visiteur(id)
);

/*
* Création table comptable
*/

CREATE TABLE IF NOT EXISTS comptable (
  id char(4) NOT NULL,
  nom char(30) DEFAULT NULL,
  prenom char(30)  DEFAULT NULL, 
  login char(20) DEFAULT NULL,
  mdp char(20) DEFAULT NULL,
  adresse char(30) DEFAULT NULL,
  cp char(5) DEFAULT NULL,
  ville char(30) DEFAULT NULL,
  dateembauche date DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO comptable (id, nom, prenom, login, mdp, adresse, cp, ville, dateembauche) VALUES
('a198', 'Pignon', 'Aymé', 'ayme', 'P@ssw0rd', '8 rue des Charmant prince', '83220', 'Le Pradet', '2012-12-21'),
('a120', 'Simon', 'Jonathan', 'simon', 'P@ssw0rd', '836 bld', '83100', 'TOULON', '2012-12-01');
/*
* Modification des mots de passe
*/
ALTER TABLE `visiteur` CHANGE `mdp` `mdp` CHAR(255);

ALTER TABLE `comptable` CHANGE `mdp` `mdp` CHAR(255);

/*
* Facteur A2F
*/
ALTER TABLE visiteur ADD email TEXT NULL;
UPDATE visiteur SET email = CONCAT(login,"@swiss-galaxy.com");

ALTER TABLE comptable ADD email TEXT NULL;
UPDATE comptable SET email = CONCAT(login,"@swiss-galaxy.com");

ALTER TABLE visiteur ADD codea2f CHAR(4);

ALTER TABLE comptable ADD codea2f CHAR(4);