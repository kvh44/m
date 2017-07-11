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
-- 转存表中的数据 `mlocation`
--

INSERT IGNORE INTO `mlocation` (`id`, `country_id`, `city_zh`, `city_fr`, `city_en`, `post_number`, `post_number_zh`, `internal_id`, `slug`) VALUES
(1, 1, '巴黎', 'Paris', 'Paris', '75001', '1区', 'UxlRkRaeQhol8PcPJsgKJCW4n5qFVOUC', 'paris75001'),
(2, 1, '巴黎', 'Paris', 'Paris', '75002', '2区', 'lyubwVnPn0An1BG2woXgCFBw63GUGQLf', 'paris75002'),
(3, 1, '巴黎', 'Paris', 'Paris', '75003', '3区', 'ucVZ0fCJuTfE5iHzJXIqajjLPUeMVILm', 'paris75003'),
(4, 1, '巴黎', 'Paris', 'Paris', '75004', '4区', 'zjbO0km5vLBSnsThP6D1wPpDrPGGTuDz', 'paris75004'),
(5, 1, '巴黎', 'Paris', 'Paris', '75005', '5区', 'hiavb52z1lHH44JjbC6May890hcnmAeS', 'paris75005'),
(6, 1, '巴黎', 'Paris', 'Paris', '75006', '6区', 'YFuvuLxkhOny8uKzzHNdZbDv1lWZ0K9N', 'paris75006'),
(7, 1, '巴黎', 'Paris', 'Paris', '75007', '7区', 's08vGKVP0smtrnSAzCRinNJpAY65l5sY', 'paris75007'),
(8, 1, '巴黎', 'Paris', 'Paris', '75008', '8区', '47AlEkYmNeEkWC1ra5RaMxBjbTl8LV30', 'paris75008'),
(9, 1, '巴黎', 'Paris', 'Paris', '75009', '9区', 'jQ9TvSq9JXir0x7cH9fQztl91hEMnoFi', 'paris75009'),
(10, 1, '巴黎', 'Paris', 'Paris', '75010', '10区', 'ILtrXSp9xVpdKR447SbO0n5rqygcFmRw', 'paris75010'),
(11, 1, '巴黎', 'Paris', 'Paris', '75011', '11区', 'TKpQe35LSrp6Xyxrcr1pxceVDyPPyjZh', 'paris75011'),
(12, 1, '巴黎', 'Paris', 'Paris', '75012', '12区', 'chI6DMjOyf2iDEFRCkEiSrFQpNi5Tz06', 'paris75012'),
(13, 1, '巴黎', 'Paris', 'Paris', '75013', '13区', 'mbEmMsr1nXP8rxfrQmW1PFCN83tidpOq', 'paris75013'),
(14, 1, '巴黎', 'Paris', 'Paris', '75014', '14区', '99Ec0NO958grjhk7abdK2mKIq7HhMuLv', 'paris75014'),
(15, 1, '巴黎', 'Paris', 'Paris', '75015', '15区', 'MBeJyEB64FlPRqiOBg1vCz0HCtHH02bW', 'paris75015'),
(16, 1, '巴黎', 'Paris', 'Paris', '75016', '16区', 'ICeLTdaZxdtTKLRZbLwZeUM1vWQb4cgy', 'paris75016'),
(17, 1, '巴黎', 'Paris', 'Paris', '75017', '17区', 'zbUS004nXlQc4GxGVdVVaSVttFPcYsLe', 'paris75017'),
(18, 1, '巴黎', 'Paris', 'Paris', '75018', '18区', 'CXhb9LPOM6J1BO4jDkJ6qJXlIHoQ5uaq', 'paris75018'),
(19, 1, '巴黎', 'Paris', 'Paris', '75019', '19区', 'j9SEeTiVxMQyxo7TCNC0ZIWHaaILdf0f', 'paris75019'),
(20, 1, '巴黎', 'Paris', 'Paris', '75020', '20区', 'zYLqdw718tVhQdEbfHXXO5VhYeCY0ohS', 'paris75020'),
(21, 1, '巴黎', 'Paris', 'Paris', '75', '小巴黎市内', 'zYLqdw718tVhQdEbfHXXO5VhYeCY0ohS', 'paris75');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
