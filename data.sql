CREATE DATABASE IF NOT EXISTS tickets;

USE tickets;
CREATE TABLE IF NOT EXISTS Ticket (
    num_abo INT NOT NULL,
    Date varchar(20) NOT NULL,
    heure TIME NOT NULL,
    duree_reel TIME NULL,
    volume_fact FLOAT NULL,
    type_datas VARCHAR(50) NOT NULL)
;
