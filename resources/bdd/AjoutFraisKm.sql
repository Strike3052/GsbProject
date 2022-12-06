use gsb_frais;

-- on drop, on sait jamais
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