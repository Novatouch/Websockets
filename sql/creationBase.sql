--********Création de la base de données*******


REVOKE ALL ON SCHEMA public FROM public;
DROP SCHEMA IF EXISTS webapp CASCADE;
DROP DATABASE webappbd;


-- DROP DATABASE "BD_PROJET";
CREATE DATABASE webappbd
WITH OWNER = postgres
ENCODING = 'UTF8'
LC_COLLATE = 'fr_FR.UTF-8'
LC_CTYPE = 'fr_FR.UTF-8';

\c webappbd postgres



CREATE SCHEMA webapp;



DROP TABLE IF EXISTS role CASCADE;
DROP TABLE IF EXISTS utilisateur CASCADE;
DROP TABLE IF EXISTS proposition CASCADE;
DROP TABLE IF EXISTS repondre CASCADE;
DROP TABLE IF EXISTS statut CASCADE;
DROP TABLE IF EXISTS sondage CASCADE;
DROP TABLE IF EXISTS question CASCADE;
DROP TABLE IF EXISTS questionProposition CASCADE;


CREATE TABLE IF NOT EXISTS webapp.role
(
    rol_id SERIAL CONSTRAINT pk_rol_id PRIMARY KEY,
    rol_nom VARCHAR(30)
);

--***********INSERTION TABLE role ******************************************
INSERT INTO webapp.role (rol_nom) VALUES ('administrateur');
INSERT INTO webapp.role (rol_nom) VALUES ('utilisateur');




CREATE TABLE IF NOT EXISTS webapp.utilisateur
(
    util_id SERIAL CONSTRAINT pk_util_id PRIMARY KEY,
    util_identifiant VARCHAR(30),
    util_prenom VARCHAR(30),
    util_nom VARCHAR(30),
    util_pass CHAR(128),
    util_session_token CHAR(64),
    util_role_id INT,
    CONSTRAINT un_util_identifiant UNIQUE(util_identifiant),
    CONSTRAINT fk_util_role_id FOREIGN KEY(util_role_id) REFERENCES webapp.role(rol_id)
);


--***********INSERTION TABLE utilisateurs******************************************
INSERT INTO webapp.utilisateur (util_identifiant, util_prenom, util_nom, util_pass, util_session_token, util_role_id) VALUES ('philippe','philippe','gautier','54c4e215116d756def14fddeb8ce375130d226881b3a9469940c1a247fdae38d893162094d33535d328f7dbf1498cce6ce2c0c00e65723722ce783e72494ba0a', '6e7ce41be600319bad04d877facc33a4c5dba6ef5a51e73d82544cd9eda0da6e', 2);
INSERT INTO webapp.utilisateur (util_identifiant, util_prenom, util_nom, util_pass, util_role_id) VALUES ('rémi','rémi','maison','ef0fb9eeaab088c45cbf18ccb3d1e7695a8ed022f6bd0b218d4df7cee6e8ed90bb4723d11c6a5c6bf49eb3e20bdfbff16427151dbc0c27c29866eb9cdd39c226', 2);
INSERT INTO webapp.utilisateur (util_identifiant, util_prenom, util_nom, util_pass, util_role_id) VALUES ('admin','jésus','christ','15ad61d711ecf80166a3e2a1226e18d813cb71f7d7ce25f090c27383a74946a2883ef22dd1b4a7896117fc0e9ca8a0413a6620fdfb529a656ef43bccfb22ca6d', 1);



CREATE TABLE IF NOT EXISTS webapp.statut
(
    sta_id SERIAL CONSTRAINT pk_sta_id PRIMARY KEY,
    sta_nom VARCHAR(100)
);

--***********INSERTION TABLE statut******************************************
INSERT INTO webapp.statut (sta_nom) VALUES ('à ouvrir');
INSERT INTO webapp.statut (sta_nom) VALUES ('en cours');
INSERT INTO webapp.statut (sta_nom) VALUES ('fermé');





CREATE TABLE IF NOT EXISTS webapp.sondage
(
    son_id SERIAL CONSTRAINT pk_son_id PRIMARY KEY,
    son_theme VARCHAR(100),
    son_presentation VARCHAR(100),
    son_statut_id INT,
    sond_date_debut TIME with time zone,
    sond_date_fin TIME with time zone,
    CONSTRAINT fk_son_statut_id FOREIGN KEY(son_statut_id) REFERENCES webapp.statut(sta_id)
);

--***********INSERTION TABLE sondage******************************************
INSERT INTO webapp.sondage (son_theme, son_presentation, son_statut_id) VALUES ('education','L''éductation nationnale','2');





CREATE TABLE IF NOT EXISTS webapp.question
(
    ques_id SERIAL CONSTRAINT pk_ques_id PRIMARY KEY,
    ques_texte VARCHAR(200),
    ques_sondage_id INT,
    CONSTRAINT fk_ques_sondage_id FOREIGN KEY(ques_sondage_id) REFERENCES webapp.sondage(son_id)
);

--***********INSERTION TABLE question******************************************
INSERT INTO webapp.question (ques_texte, ques_sondage_id) VALUES ('Penser vous que la qualité de l''enseignement à baissé depuis l''année 1920', 1);

CREATE TABLE webapp.proposition
(
    pro_id SERIAL CONSTRAINT pk_pro_id PRIMARY KEY,
    pro_texte VARCHAR(100),
    pro_question_id INT,
    pro_nombre_votants INT DEFAULT 0,
    CONSTRAINT fk_pro_question_id FOREIGN KEY(pro_question_id) REFERENCES webapp.question(ques_id)
);


INSERT INTO webapp.proposition (pro_texte, pro_question_id, pro_nombre_votants) VALUES ('oui', 1, 1);
INSERT INTO webapp.proposition (pro_texte, pro_question_id, pro_nombre_votants) VALUES ('non', 1, 1);
INSERT INTO webapp.proposition (pro_texte, pro_question_id) VALUES ('Je ne sais pas mais je réponds oui', 1);
INSERT INTO webapp.proposition (pro_texte, pro_question_id) VALUES ('Je n''ai pas d''avis sur la question', 1);



CREATE TABLE IF NOT EXISTS webapp.repondre
(
    rep_utilisateur_id INT,
    rep_question_id INT,
    rep_proposition_id INT,
    CONSTRAINT pk_ag_rep_util_id_rep_pro_id PRIMARY KEY (rep_utilisateur_id, rep_question_id),
    CONSTRAINT fk_rep_utilisateur_id FOREIGN KEY(rep_utilisateur_id) REFERENCES webapp.utilisateur(util_id),
    CONSTRAINT fk_rep_proposition_id FOREIGN KEY(rep_proposition_id) REFERENCES webapp.proposition(pro_id),
    CONSTRAINT fk_rep_question_id FOREIGN KEY(rep_question_id) REFERENCES webapp.question(ques_id)
);


--***********INSERTION TABLE repondre******************************************

INSERT INTO webapp.repondre (rep_utilisateur_id, rep_question_id, rep_proposition_id) VALUES (1, 1, 1);
INSERT INTO webapp.repondre (rep_utilisateur_id, rep_question_id, rep_proposition_id) VALUES (2, 1, 2);




--***********Vues ******************************************
CREATE OR REPLACE VIEW webapp.authentification (id, identifiant, prenom, nom, pass, token , role) AS
SELECT u.util_id, u.util_identifiant, u.util_prenom, u.util_nom, u.util_pass, u.util_session_token, r.rol_nom
FROM webapp.utilisateur u, webapp.role r
WHERE u.util_role_id = r.rol_id;

CREATE OR REPLACE VIEW webapp.authentification_insertion (id, token) AS
SELECT util_id, util_session_token 
FROM webapp.utilisateur;


CREATE OR REPLACE VIEW webapp.vue_repondre_insertion  (utilisateur_id, question_id, proposition_id) AS
SELECT rep_utilisateur_id,  rep_question_id, rep_proposition_id
FROM webapp.repondre;

CREATE OR REPLACE VIEW webapp.vue_sondage (json) AS
SELECT array_to_json(array_agg(row_to_json(t))) AS json
FROM (
    SELECT so.son_id AS id , so.son_theme AS theme
    FROM webapp.sondage so, webapp.statut st
    WHERE so.son_statut_id = st.sta_id
) t;


CREATE OR REPLACE VIEW webapp.resultat (identifiant_sondage, theme_sondage, presentation_sondage, identifiant_question, texte_question, identifant_proposition, texte_proposition, nombre_votants, pourcentage) AS
SELECT s.son_id, s.son_theme, s.son_presentation, q.ques_id, q.ques_texte, p.pro_id, p.pro_texte, p.pro_nombre_votants, 100*p.pro_nombre_votants/(sum(p.pro_nombre_votants) OVER (PARTITION BY p.pro_question_id))
FROM webapp.sondage s, webapp.question q, webapp.proposition p
WHERE s.son_id = q.ques_sondage_id
AND q.ques_id = p.pro_question_id;


-- recuperation dans un array json des sondages
CREATE OR REPLACE VIEW webapp.liste_sondage (json) AS
SELECT row_to_json(t) AS json
FROM(
    -- recupération id, theme et presentation sondage
    SELECT son_id as id, son_theme AS theme, son_presentation AS presentation, sta_nom AS statut,
    (
        SELECT array_to_json(array_agg(row_to_json(u)))
        FROM(
            -- recuperation des questions
            SELECT ques_id, ques_texte,
            (
                SELECT array_to_json(array_agg(row_to_json(v)))
                FROM(

                    --recuperation des propositions
                    SELECT pro_id,pro_texte,pro_nombre_votants, 100*pro_nombre_votants/(sum(pro_nombre_votants) OVER (PARTITION BY pro_question_id)) AS score
                    FROM webapp.proposition
                    WHERE pro_question_id=webapp.question.ques_id
                ) v
            ) AS propositions
            FROM webapp.question
            WHERE ques_sondage_id=webapp.sondage.son_id
        ) u
    ) AS questions
    FROM webapp.sondage, webapp.statut
    WHERE son_statut_id=sta_id
) t;



----********* TRIGGER ***********************************************
CREATE OR REPLACE FUNCTION mise_a_jour_score() RETURNS trigger AS $$
BEGIN
    UPDATE webapp.proposition SET pro_nombre_votants=pro_nombre_votants+1
    WHERE NEW.rep_proposition_id = pro_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


--********* TRIGGER ***********************************************
CREATE TRIGGER vue_repondre_insertion
AFTER INSERT ON webapp.repondre
FOR EACH ROW
EXECUTE PROCEDURE mise_a_jour_score();





--********* Role ***********************************************
DROP ROLE IF EXISTS webappbd_auth;
CREATE ROLE webappbd_auth  LOGIN  ENCRYPTED PASSWORD 'X4fzk78A';

GRANT CONNECT ON DATABASE webappbd TO webappbd_auth;
GRANT USAGE ON SCHEMA webapp TO webappbd_auth;
GRANT SELECT (id, identifiant, pass, role) ON TABLE webapp.authentification TO webappbd_auth;
GRANT SELECT,UPDATE (id, token) ON TABLE webapp.authentification_insertion TO webappbd_auth;
--GRANT SELECT ON TABLE webapp.utilisateur TO webappbd_auth; 

DROP ROLE IF EXISTS webappbd_update;
CREATE ROLE webappbd_update  LOGIN  ENCRYPTED PASSWORD 'vS6yKz64';
GRANT CONNECT ON DATABASE webappbd TO webappbd_update;
GRANT USAGE ON SCHEMA webapp TO webappbd_update;
GRANT SELECT (id, token, role) ON TABLE webapp.authentification TO webappbd_update;
GRANT SELECT,UPDATE (id, token) ON TABLE webapp.authentification_insertion TO webappbd_update;
GRANT SELECT ON TABLE webapp.vue_sondage TO webappbd_update;
--GRANT USAGE ON SCHEMA webapp TO webappbd_auth;



