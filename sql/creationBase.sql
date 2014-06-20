--********Création de la base de données*******

-- Database: "BD_PROJET"

-- DROP DATABASE "BD_PROJET";

CREATE DATABASE "BD_PROJET"
  WITH OWNER = postgres
      ENCODING = 'UTF8'

CREATE TABLE role
(
	rol_id SERIAL CONSTRAINT pk_rol_id PRIMARY KEY,
	rol_nom VARCHAR(30)
);

--***********INSERTION TABLE role ******************************************
INSERT INTO role (rol_nom) VALUES ('administrateur');
INSERT INTO role (rol_nom) VALUES ('utilisateur');


CREATE TABLE utilisateur
(
	util_id SERIAL CONSTRAINT pk_util_id PRIMARY KEY,
	util_identifiant VARCHAR(30),
	util_prenom VARCHAR(30),
	util_nom VARCHAR(30),
	util_pass CHAR(100),
	util_session CHAR(100),
	util_role_id INT,
	CONSTRAINT un_util_identifiant UNIQUE(util_identifiant),
	CONSTRAINT fk_util_role_id FOREIGN KEY(util_role_id) REFERENCES role(rol_id)
);


--***********INSERTION TABLE utilisateurs******************************************
INSERT INTO utilisateur (util_prenom, util_nom, util_pass, util_role_id) VALUES ('philippe','philippe','gautier','password', 2);
INSERT INTO utilisateur (util_prenom, util_nom, util_pass, util_role_id) VALUES ('rémi','rémi','maison','password', 2);
INSERT INTO utilisateur (util_prenom, util_nom, util_pass, util_role_id) VALUES ('admin','jésus','christ','password', 1);


CREATE TABLE proposition
(
	pro_id SERIAL CONSTRAINT pk_pro_id PRIMARY KEY,
	pro_texte VARCHAR(100)
);


INSERT INTO proposition (pro_texte) VALUES ('oui');
INSERT INTO proposition (pro_texte) VALUES ('non');
INSERT INTO proposition (pro_texte) VALUES ('Je ne sais pas mais je réponds oui');
INSERT INTO proposition (pro_texte) VALUES ('Je n''ai pas d''avis sur la question');

CREATE TABLE repondre
(
	reponse_utilisateur_id,
	reponse_proposition_id,
	CONSTRAINT pk_ag_rep_util_id_rep_pro_id PRIMARY KEY (rep_util_id, rep_pro_id),
	CONSTRAINT fk_reponse_utilisateur_id FOREIGN KEY(reponse_utilisateur_id) REFERENCES utilisateur(util_id),
	CONSTRAINT fk_reponse_proposition_id FOREIGN KEY(reponse_proposition_id) REFERENCES proposition(pro_id)
);


--***********INSERTION TABLE repondre******************************************

INSERT INTO repondre (reponse_utilisateur_id,reponse_proposition_id) VALUES (1, 1);
INSERT INTO repondre (reponse_utilisateur_id,reponse_proposition_id) VALUES (1, 2);


CREATE TABLE statut
(
	sta_id SERIAL CONSTRAINT pk_sta_id PRIMARY KEY,
	sta_nom VARCHAR(100)
);

--***********INSERTION TABLE statut******************************************
INSERT INTO statut (sta_nom) VALUES ('à ouvrir');
INSERT INTO statut (sta_nom) VALUES ('en cours');
INSERT INTO statut (sta_nom) VALUES ('fermé');


CREATE TABLE sondage
(
	son_id SERIAL CONSTRAINT pk_son_id PRIMARY KEY,
	son_theme VARCHAR(100),
	son_texte VARCHAR(100),
	son_statut_id VARCHAR(100),
	CONSTRAINT fk_son_statut_id FOREIGN KEY(son_statut_id) REFERENCES statut(sta_id)
);

--***********INSERTION TABLE statut******************************************
INSERT INTO sondage (son_theme, son_texte, son_statut_id) VALUES ('education','L''éductation nationnale','1');



--***********INSERTION TABLE sondage******************************************
INSERT INTO role (sta_nom) VALUES ('à ouvrir');
INSERT INTO role (sta_nom) VALUES ('en cours');
INSERT INTO role (sta_nom) VALUES ('fermé');


CREATE TABLE question
(
	ques_id SERIAL CONSTRAINT pk_ques_id PRIMARY KEY,
	ques_texte VARCHAR(200),
	ques_sondage_id INT,
	CONSTRAINT fk_ques_sondage_id FOREIGN KEY(ques_sondage_id) REFERENCES sondage(son_id)
);

--***********INSERTION TABLE question******************************************
INSERT INTO sondage (ques_texte, ques_sondage_id) VALUES ('Penser vous que la qualité de l''enseignement à baissé depuis l''année 1920', 1);

CREATE TABLE questionProposition
(
	quepro_question_id INT,
	quepro_propostion_id INT,
	quepro_score INT,
	CONSTRAINT pk_ag_quepro_question_id_quepro_propostion_id PRIMARY KEY (quepro_question_id, quepro_propostion_id),
	CONSTRAINT fk_quepro_question_id FOREIGN KEY(quepro_question_id) REFERENCES question(ques_id)
	CONSTRAINT fk_quepro_propostion_id FOREIGN KEY(quepro_propostion_id) REFERENCES question(ques_id)
);

--***********INSERTION TABLE questionProposition******************************************
INSERT INTO questionProposition (quepro_question_id, quepro_propostion_id) VALUES (1, 1);
INSERT INTO questionProposition (quepro_question_id, quepro_propostion_id) VALUES (1, 2);
INSERT INTO questionProposition (quepro_question_id, quepro_propostion_id) VALUES (1, 3);
INSERT INTO questionProposition (quepro_question_id, quepro_propostion_id) VALUES (1, 4);


