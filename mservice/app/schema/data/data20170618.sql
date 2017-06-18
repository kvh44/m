SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `massage`
--

--
-- 转存表中的数据 `mcountry`
--

INSERT IGNORE INTO `mcountry` (`id`, `country_zh`, `country_fr`, `country_en`, `internal_id`, `slug`) VALUES
(1, '法国', 'France', 'France', 'D5eT9FbxU5mwrzmL8RzNwicGHdQlwute', 'france'),
(2, '德国', 'Allemagne', 'germany', 'Q425MnhBi6I94N1pVzwwGA8RcoGs9hoB', 'germany'),
(3, '英国', 'Angleterre', 'England', 'GseYXbVBiHVAlFbqb5Ur5RSlXKc9iAcF', 'england'),
(4, '意大利', 'Italie', 'Italy', 'Ci7r1moBqGi381LGjuK7fdY7jNNP8JHl', 'italy'),
(5, '西班牙', 'Espagne', 'Spain', '3zy9yUxT02CLTgT2fG4kc9bnsn0jD7wu', 'spain');
COMMIT;