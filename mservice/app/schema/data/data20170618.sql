SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";



INSERT IGNORE INTO `mcountry` (`id`, `country_zh`, `country_fr`, `country_en`, `internal_id`, `slug`) VALUES
(1, '法国', 'France', 'France', 'D5eT9FbxU5mwrzmL8RzNwicGHdQlwute', 'france'),
(2, '德国', 'Allemagne', 'germany', 'Q425MnhBi6I94N1pVzwwGA8RcoGs9hoB', 'germany'),
(3, '英国', 'Angleterre', 'England', 'GseYXbVBiHVAlFbqb5Ur5RSlXKc9iAcF', 'england'),
(4, '意大利', 'Italie', 'Italy', 'Ci7r1moBqGi381LGjuK7fdY7jNNP8JHl', 'italy'),
(5, '西班牙', 'Espagne', 'Spain', '3zy9yUxT02CLTgT2fG4kc9bnsn0jD7wu', 'spain');
(6, '荷兰', 'Pays-Bas', 'Hollande', '4OjdgOfuYLiooCcsMvK74bjiK2LIINKW', 'hollande'),
(7, '比利时', 'Belgique', 'Belgium', 'SCEW3iq0aOnA99Z8txQb5y9ijo5AhDEy', 'belgium'),
(8, '瑞士', 'Suisse', 'Swiss', 'bnE9DxpXUDsTEK9wBT9uaHfs8hyraDe0', 'swiss');
COMMIT;

ALTER TABLE `mcountry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;