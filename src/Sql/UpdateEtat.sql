BEGIN

DECLARE done INT DEFAULT 0;
DECLARE var_id int(11);
DECLARE var_etat_id int(11);
DECLARE var_started_date_time datetime;
DECLARE var_duration int(11);
DECLARE var_deadline datetime;
DECLARE var_max_nb_of_registration int(11);

DECLARE cursor1 CURSOR FOR SELECT id, etat_id, started_date_time, duration, deadline, max_nb_of_registration FROM sortie;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

OPEN cursor1;

etat_loop: LOOP

FETCH cursor1 INTO var_id, var_etat_id, var_started_date_time, var_duration, var_deadline, var_max_nb_of_registration;

IF done = 1 THEN
LEAVE etat_loop;
END IF;

-- etat 3 / cloturée
-- Si la date limite d'inscription est dépassée sauf pour les sorties non publiées
IF NOW() > var_deadline AND var_etat_id not in (1,6) THEN
UPDATE sortie SET etat_id = 3 WHERE id = var_id;
SELECT id FROM sortie WHERE id = var_id;


-- etat 4 / en cours
-- Si a sortie est commencée mais pas terminée sauf pour les sorties non publiées
ELSEIF NOW() > var_started_date_time AND NOW() < ADDDATE(var_started_date_time, INTERVAL var_duration HOUR) AND var_etat_id not in (1,6) THEN
UPDATE sortie SET etat_id = 4 WHERE id = var_id;


-- état 5 / terminée
-- Si la sortie est terminée sauf pour les sorties non publiées
ELSEIF NOW() > ADDDATE(var_started_date_time, INTERVAL var_duration HOUR) AND var_etat_id not in (1,6) THEN
UPDATE sortie SET etat_id = 5 WHERE id = var_id;


-- etat 7 / Archivée
-- Si la date de début de la sortie est dépassée depuis plus de 31 jours sauf pour les sorties non publiées
ELSEIF ADDDATE(var_started_date_time, INTERVAL 1 MONTH) < NOW() AND var_etat_id not in (1,6) THEN
UPDATE sortie SET etat_id = 6 WHERE id = var_id;

ELSE
UPDATE sortie SET etat_id = 2 WHERE id = var_id AND etat_id in (3,4,5,7);


END IF;

END LOOP;

CLOSE cursor1;

END


/////////////////////////////////

BEGIN

DECLARE done INT DEFAULT 0;
DECLARE var_id int(11);
DECLARE var_etat_id int(11);
DECLARE var_started_date_time datetime;
DECLARE var_duration int(11);
DECLARE var_deadline datetime;
DECLARE var_max_nb_of_registration int(11);

DECLARE var_user_id int(11);
DECLARE var_sortie_id int(11);

DECLARE cursor1 CURSOR FOR SELECT id, etat_id, started_date_time, duration, deadline, max_nb_of_registration FROM sortie;


DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

OPEN cursor1;

etat_loop: LOOP

FETCH cursor1 INTO var_id, var_etat_id, var_started_date_time, var_duration, var_deadline, var_max_nb_of_registration;

IF done = 1 THEN
LEAVE etat_loop;
END IF;

-- etat 3 / cloturée
-- Si la date limite d'inscription est dépassée ou si la sortie est sold out sauf pour les sorties non publiées ou annulées
IF NOW() > var_deadline OR (SELECT count(user_id) FROM user_sortie WHERE sortie_id = var_id) = var_max_nb_of_registration AND var_etat_id not in (1,6) THEN
UPDATE sortie SET etat_id = 3 WHERE id = var_id;
SELECT id FROM sortie WHERE id = var_id;


-- etat 4 / en cours
-- Si a sortie est commencée mais pas terminée sauf pour les sorties non publiées ou annulées
ELSEIF NOW() > var_started_date_time AND NOW() < ADDDATE(var_started_date_time, INTERVAL var_duration HOUR) AND var_etat_id not in (1,6) THEN
UPDATE sortie SET etat_id = 4 WHERE id = var_id;


-- état 5 / terminée
-- Si la sortie est terminée sauf pour les sorties non publiées ou annulées
ELSEIF NOW() > ADDDATE(var_started_date_time, INTERVAL var_duration HOUR) AND var_etat_id not in (1,6) THEN
UPDATE sortie SET etat_id = 5 WHERE id = var_id;

-- etat / 2
-- (LIEE AU FIXTURE ALEATOIRE, A SUPPRIMER EN PROD)
-- Si aucun des cas precedents correspond, la sortie doit etre ouverte
ELSE
UPDATE sortie SET etat_id = 2 WHERE id = var_id AND etat_id in ( 3,4,5,7);

END IF;

-- etat 7 / Archivée
-- Si la date de début de la sortie est dépassée depuis plus de 31 jours ( meme pour les sorties non publiées )
IF ADDDATE(var_started_date_time, INTERVAL 1 MONTH) < NOW() AND var_etat_id not in (6) THEN
UPDATE sortie SET etat_id = 7 WHERE id = var_id;

END IF;

END LOOP;

CLOSE cursor1;

END

