USE `Tibiascanner`;
DELETE n1 FROM Tibiascanner.player_deaths n1,Tibiascanner.player_deaths n2 WHERE n1.id > n2.id AND n1.charid = n2.charid AND n1.date = n2.date;
DELETE n1 FROM players_deleted n1, players_deleted n2 WHERE n1.id > n2.id AND n1.charid = n2.charid;