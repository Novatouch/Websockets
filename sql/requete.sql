--SELECT identifiant FROM authentification;

--*********** requête ******************************************
--EXPLAIN ANALYSE SELECT util_identifiant, util_prenom, util_nom, util_pass, rol_nom
--	FROM utilisateur u, role r
--	WHERE u.util_role_id = r.rol_id;

--EXPLAIN ANALYSE SELECT util_identifiant, util_prenom, util_nom, util_pass, rol_nom
--	FROM utilisateur u, role r;


--EXPLAIN ANALYSE SELECT util_identifiant, util_prenom, util_nom, util_pass, rol_nom
--	FROM utilisateur u INNER JOIN role r ON (u.util_role_id = r.rol_id);

--EXPLAIN ANALYSE SELECT identifiant FROM authentification;

-- requete SQL  
SELECT pass FROM webapp.authentification WHERE identifiant='philippe';


-- authentification.php

SELECT id, pass, role FROM webapp.authentification WHERE identifiant='$identifiant'


UPDATE webapp.authentification_insertion SET (token)  VALUES ('$token') WHERE id='$id';


-- sondage.php

-- authentification utilisateur

SELECT token FROM webapp.authentification WHERE id='id';

--lister_sondage_en_cours


SELECT json FROM webapp.vue_sondage;


UPDATE 

SELECT row_to_json(row(id)) FROM webapp.authentification;


INSERT INTO webapp.vue_repondre_insertion (utilisateur_id, question_id, proposition_id) VALUES ('3','1','1');
INSERT INTO webapp.repondre (rep_utilisateur_id, rep_question_id, rep_proposition_id) VALUES ('1','1','1');

CREATE OR REPLACE VIEW webapp.vue_repondre_insertion AS
SELECT reponse_utilisateur_id, reponse_proposition_id
FROM webapp.repondre;






-- AFFICHER QUESTIONNAIRE
-- CREATE OR REPLACE VIEW webapp.vue_questionnaire (json) AS


SELECT row_to_json(t) AS json
FROM(
    -- recupération id, theme et presentation sondage
    SELECT son_id as id, son_theme AS theme, son_presentation AS presentation,
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
    FROM webapp.sondage
    WHERE son_id=1
) t;

SELECT row_to_json(t) AS json
FROM(
    -- recupération id, theme et presentation sondage
    (
        SELECT son_id as id, son_theme AS theme, son_presentation AS presentation,
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
        FROM webapp.sondage
        WHERE son_id=1
    ) 

) t;


create or replace function lister_sondage(_id INT)
returns varchar as $BODY$
declare 
stats varchar;
enregistrement_json record;
myrec record;
begin

SELECT row_to_json(t) AS json INTO enregistrement_json
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
    AND son_id=_id
) t;

-- rechercher dans la table utilisateurs si l'utilisateur existe
SELECT q.ques_sondage_id INTO myrec  
FROM webapp.repondre r, webapp.question q
WHERE q.ques_id=r.rep_question_id
AND r.rep_utilisateur_id=_id;

-- test si un résultat à été trouvé
IF FOUND THEN
-- la procédure retourne une erreur
stats := '{"participer":"oui","sondage": [' || enregistrement_json.json ;

ELSE 
stats := '{"participer":"non","sondage": [' || enregistrement_json.json ;
END IF;
stats := stats || ']}';

return stats;
end;
$BODY$ language plpgsql;




SELECT array_to_json(array_agg(row_to_json(t))) AS json
FROM (
    SELECT so.son_id AS id , so.son_theme AS theme
    FROM webapp.sondage so, webapp.statut st
    WHERE so.son_statut_id = st.sta_id
) t;



