--
-- Base de données :  `mezabi-mvc`
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `ID_ARTICLE` int(11) NOT NULL,
  `CODE_ARTICLE` varchar(15) NOT NULL,
  `DESIGNATION` varchar(30) NOT NULL,
  `CATEGORIE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `articles`
--

INSERT INTO `articles` (`ID_ARTICLE`, `CODE_ARTICLE`, `DESIGNATION`, `CATEGORIE`) VALUES
(1, 'PL001', 'Pantalon Sport', 1),
(2, 'JL001', 'Jean', 1),
(3, 'P001', 'Pull Col roulé', 4),
(4, 'P002', 'Pull Col V', 4),
(5, 'R003', 'Robe Printemps', 5),
(6, 'PZIZGAG', 'Pull Col Rond', 4),
(7, 'PV', 'Pull Col V', 4),
(8, 'VSTCOS', 'Veste costume', 2),
(9, 'JPE', 'Jupe Tube', 6),
(10, 'VSPORT', 'Veste Sportswear', 2),
(11, 'TSBATMAN', 'Tee-shirt Batman', 3),
(12, 'TSUPER', 'Tee-shirt Superman', 3);

-- --------------------------------------------------------

--
-- Structure de la table `a_categories`
--

CREATE TABLE `a_categories` (
  `CODE_CATEGORIE` int(11) NOT NULL,
  `DESIGNATION` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `a_categories`
--

INSERT INTO `a_categories` (`CODE_CATEGORIE`, `DESIGNATION`) VALUES
(1, 'Pantalons'),
(2, 'Vestes'),
(3, 'Tee-Shirt'),
(4, 'Pull'),
(5, 'Robe'),
(6, 'Jupe');

-- --------------------------------------------------------

--
-- Structure de la table `a_couleurs`
--

CREATE TABLE `a_couleurs` (
  `CODE_COULEUR` int(11) NOT NULL,
  `DESIGNATION` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `a_couleurs`
--

INSERT INTO `a_couleurs` (`CODE_COULEUR`, `DESIGNATION`) VALUES
(1, 'Rouge'),
(2, 'Vert'),
(3, 'Bleu'),
(4, 'Noir'),
(5, 'Jaune'),
(6, 'Rose'),
(7, 'Marron'),
(8, 'Blanc');

-- --------------------------------------------------------

--
-- Structure de la table `a_tailles`
--

CREATE TABLE `a_tailles` (
  `CODE_TAILLE` int(11) NOT NULL,
  `DESIGNATION` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `a_tailles`
--

INSERT INTO `a_tailles` (`CODE_TAILLE`, `DESIGNATION`) VALUES
(1, 'XS'),
(2, 'S'),
(3, 'M'),
(4, 'L'),
(5, 'XL'),
(6, '2XL');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `ID_CLIENT` int(11) NOT NULL,
  `CODE_CLIENT` varchar(15) NOT NULL,
  `NOM_MAGASIN` varchar(35) NOT NULL,
  `ADRESSE_1` varchar(35) NOT NULL,
  `ADRESSE_2` varchar(35) NOT NULL,
  `CODE_POSTAL` varchar(5) NOT NULL,
  `VILLE` varchar(35) NOT NULL,
  `RESPONSABLE` varchar(35) NOT NULL,
  `TELEPHONE` varchar(10) NOT NULL,
  `EMAIL` varchar(35) NOT NULL,
  `TYPE_CLIENT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`ID_CLIENT`, `CODE_CLIENT`, `NOM_MAGASIN`, `ADRESSE_1`, `ADRESSE_2`, `CODE_POSTAL`, `VILLE`, `RESPONSABLE`, `TELEPHONE`, `EMAIL`, `TYPE_CLIENT`) VALUES
(1, 'CPV', 'VET ONLINE', 'ZA Bel Air', 'Impasse de la tour', '12000', 'Rodez', 'M. Vetman', '0565656565', 'vetonline@vet.com', 4),
(2, 'DZ', 'DumontZhabits', '33 Rue de bonald', '', '12000', 'Rodez', 'M. Dumont Pierre', '0565656565', 'dumont@orange.fr', 1),
(3, 'SUPERU12', 'SUPER U ONET', 'Route de Rodez', '', '12850', 'Onet Le Chateau', 'M. le directeur', '0565656565', 'superu@orange.com', 2),
(4, 'AMBU12', 'Le Marché qui va bien', '125 Rue des coquelicots', '', '12850', 'Sainte Radegonde', 'M. Durant Paul', '0565656565', 'vendeurZ@orange.com', 3);

-- --------------------------------------------------------

--
-- Structure de la table `c_types`
--

CREATE TABLE `c_types` (
  `CODE_TYPE` int(11) NOT NULL,
  `DESIGNATION` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `c_types`
--

INSERT INTO `c_types` (`CODE_TYPE`, `DESIGNATION`) VALUES
(1, 'Centre Ville'),
(2, 'Supermarché'),
(3, 'Vendeur Marché'),
(4, 'VPC');

-- --------------------------------------------------------

--
-- Structure de la table `devis_entetes`
--

CREATE TABLE `devis_entetes` (
  `ID_ENT_DEV` int(11) NOT NULL,
  `NO_DEVIS` int(11) NOT NULL,
  `CLIENT` int(11) NOT NULL,
  `DATE_DEVIS` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `devis_entetes`
--

INSERT INTO `devis_entetes` (`ID_ENT_DEV`, `NO_DEVIS`, `CLIENT`, `DATE_DEVIS`) VALUES
(1, 202209001, 1, '2022-09-01'),
(2, 202209002, 2, '2022-09-02');

-- --------------------------------------------------------

--
-- Structure de la table `devis_lignes`
--

CREATE TABLE `devis_lignes` (
  `ID_LIG_DEV` int(11) NOT NULL,
  `ID_DEVIS` int(11) NOT NULL,
  `ARTICLE` int(11) NOT NULL,
  `COULEUR` int(11) NOT NULL,
  `TAILLE` int(11) NOT NULL,
  `QUANTITE` int(11) NOT NULL,
  `PRIX` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `devis_lignes`
--

INSERT INTO `devis_lignes` (`ID_LIG_DEV`, `ID_DEVIS`, `ARTICLE`, `COULEUR`, `TAILLE`, `QUANTITE`, `PRIX`) VALUES
(1, 1, 2, 2, 4, 25, '45.00'),
(2, 1, 2, 3, 4, 32, '45.00');

-- --------------------------------------------------------

--
-- Structure de la table `factures_entetes`
--

CREATE TABLE `factures_entetes` (
  `ID_ENT_FCT` int(11) NOT NULL,
  `NO_FCT` int(11) NOT NULL,
  `CLIENT` int(11) NOT NULL,
  `DATE_FCT` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `factures_entetes`
--

INSERT INTO `factures_entetes` (`ID_ENT_FCT`, `NO_FCT`, `CLIENT`, `DATE_FCT`) VALUES
(1, 202208001, 4, '2022-08-23'),
(2, 202208002, 3, '2022-08-31');

-- --------------------------------------------------------

--
-- Structure de la table `factures_lignes`
--

CREATE TABLE `factures_lignes` (
  `ID_LIG_FCT` int(11) NOT NULL,
  `ID_FCT` int(11) NOT NULL,
  `ARTICLE` int(11) NOT NULL,
  `COULEUR` int(11) NOT NULL,
  `TAILLE` int(11) NOT NULL,
  `QUANTITE` int(11) NOT NULL,
  `PRIX` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `factures_lignes`
--

INSERT INTO `factures_lignes` (`ID_LIG_FCT`, `ID_FCT`, `ARTICLE`, `COULEUR`, `TAILLE`, `QUANTITE`, `PRIX`) VALUES
(1, 1, 6, 5, 3, 25, '28.99'),
(2, 1, 5, 2, 5, 43, '34.90'),
(3, 2, 3, 5, 1, 2, '32.00'),
(4, 2, 7, 3, 4, 23, '22.55'),
(5, 2, 7, 2, 4, 1, '45.00'),
(6, 2, 2, 7, 3, 12, '26.00');

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

CREATE TABLE `parametres` (
  `ID` varchar(25) NOT NULL,
  `CONTENU_A` varchar(30) DEFAULT NULL,
  `CONTENU_N` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `parametres`
--

INSERT INTO `parametres` (`ID`, `CONTENU_A`, `CONTENU_N`) VALUES
('DEB_GENCODE', '3795567', NULL),
('LAST_GENCODE', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `stockprix`
--

CREATE TABLE `stockprix` (
  `ARTICLE` int(11) NOT NULL,
  `COULEUR` int(11) NOT NULL,
  `TAILLE` int(11) NOT NULL,
  `PRIX` decimal(10,2) NOT NULL,
  `CODE_BARRE` varchar(13) DEFAULT NULL,
  `STOCK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `stockprix`
--

INSERT INTO `stockprix` (`ARTICLE`, `COULEUR`, `TAILLE`, `PRIX`, `CODE_BARRE`, `STOCK`) VALUES
(1, 1, 1, '27.00', '3795567005816', 245),
(1, 1, 2, '28.00', '3795567005823', 61),
(1, 1, 3, '28.00', '3795567005830', 406),
(1, 1, 4, '28.00', '3795567005847', 690),
(1, 1, 5, '31.00', '3795567005854', 246),
(1, 1, 6, '31.00', '3795567005861', 401),
(1, 2, 1, '27.00', '3795567005878', 228),
(1, 2, 2, '27.00', '3795567005885', 236),
(1, 2, 3, '29.00', '3795567005892', 617),
(1, 2, 4, '30.00', '3795567005908', 603),
(1, 2, 5, '30.00', '3795567005915', 256),
(1, 2, 6, '29.00', '3795567005922', 49),
(1, 3, 1, '31.00', '3795567005939', 243),
(1, 3, 2, '30.00', '3795567005946', 338),
(1, 3, 3, '28.00', '3795567005953', 601),
(1, 3, 4, '32.00', '3795567005960', 325),
(1, 3, 5, '26.00', '3795567005977', 458),
(1, 3, 6, '26.00', '3795567005984', 330),
(1, 4, 1, '34.00', '3795567005991', 320),
(1, 4, 2, '32.00', '3795567006004', 212),
(1, 4, 3, '34.00', '3795567006011', 146),
(1, 4, 4, '33.00', '3795567006028', 248),
(1, 4, 5, '26.00', '3795567006035', 224),
(1, 4, 6, '29.00', '3795567006042', 606),
(1, 5, 1, '31.00', '3795567006059', 644),
(1, 5, 2, '29.00', '3795567006066', 116),
(1, 5, 3, '30.00', '3795567006073', 72),
(1, 5, 4, '29.00', '3795567006080', 654),
(1, 5, 5, '30.00', '3795567006097', 132),
(1, 5, 6, '25.00', '3795567006103', 306),
(1, 6, 1, '25.00', '3795567006110', 674),
(1, 6, 2, '32.00', '3795567006127', 553),
(1, 6, 3, '27.00', '3795567006134', 649),
(1, 6, 4, '30.00', '3795567006141', 138),
(1, 6, 5, '34.00', '3795567006158', 388),
(1, 6, 6, '34.00', '3795567006165', 149),
(1, 7, 1, '34.00', '3795567006172', 375),
(1, 7, 2, '27.00', '3795567006189', 687),
(1, 7, 3, '26.00', '3795567006196', 23),
(1, 7, 4, '34.00', '3795567006202', 341),
(1, 7, 5, '26.00', '3795567006219', 648),
(1, 7, 6, '33.00', '3795567006226', 571),
(1, 8, 1, '25.00', '3795567006233', 41),
(1, 8, 2, '28.00', '3795567006240', 274),
(1, 8, 3, '27.00', '3795567006257', 350),
(1, 8, 4, '32.00', '3795567006264', 164),
(1, 8, 5, '31.00', '3795567006271', 550),
(1, 8, 6, '27.00', '3795567006288', 145),
(2, 1, 1, '74.00', '3795567006295', 395),
(2, 1, 2, '69.00', '3795567006301', 262),
(2, 1, 3, '68.00', '3795567006318', 14),
(2, 1, 4, '72.00', '3795567006325', 464),
(2, 1, 5, '68.00', '3795567006332', 491),
(2, 1, 6, '73.00', '3795567006349', 12),
(2, 2, 1, '71.00', '3795567006356', 569),
(2, 2, 2, '75.00', '3795567006363', 638),
(2, 2, 3, '75.00', '3795567006370', 635),
(2, 2, 4, '71.00', '3795567006387', 23),
(2, 2, 5, '71.00', '3795567006394', 88),
(2, 2, 6, '66.00', '3795567006400', 383),
(2, 3, 1, '75.00', '3795567006417', 323),
(2, 3, 2, '70.00', '3795567006424', 231),
(2, 3, 3, '75.00', '3795567006431', 696),
(2, 3, 4, '70.00', '3795567006448', 160),
(2, 3, 5, '74.00', '3795567006455', 103),
(2, 3, 6, '71.00', '3795567006462', 217),
(2, 4, 1, '66.00', '3795567006479', 661),
(2, 4, 2, '75.00', '3795567006486', 77),
(2, 4, 3, '73.00', '3795567006493', 171),
(2, 4, 4, '72.00', '3795567006509', 619),
(2, 4, 5, '72.00', '3795567006516', 6),
(2, 4, 6, '66.00', '3795567006523', 596),
(2, 5, 1, '67.00', '3795567006530', 375),
(2, 5, 2, '74.00', '3795567006547', 318),
(2, 5, 3, '67.00', '3795567006554', 505),
(2, 5, 4, '72.00', '3795567006561', 423),
(2, 5, 5, '75.00', '3795567006578', 211),
(2, 5, 6, '73.00', '3795567006585', 651),
(2, 6, 1, '66.00', '3795567006592', 77),
(2, 6, 2, '66.00', '3795567006608', 19),
(2, 6, 3, '73.00', '3795567006615', 226),
(2, 6, 4, '71.00', '3795567006622', 501),
(2, 6, 5, '75.00', '3795567006639', 497),
(2, 6, 6, '68.00', '3795567006646', 194),
(2, 7, 1, '72.00', '3795567006653', 618),
(2, 7, 2, '74.00', '3795567006660', 503),
(2, 7, 3, '70.00', '3795567006677', 384),
(2, 7, 4, '72.00', '3795567006684', 6),
(2, 7, 5, '67.00', '3795567006691', 532),
(2, 7, 6, '75.00', '3795567006707', 240),
(2, 8, 1, '73.00', '3795567006714', 111),
(2, 8, 2, '67.00', '3795567006721', 266),
(2, 8, 3, '68.00', '3795567006738', 165),
(2, 8, 4, '69.00', '3795567006745', 349),
(2, 8, 5, '74.00', '3795567006752', 401),
(2, 8, 6, '66.00', '3795567006769', 32),
(3, 1, 1, '58.00', '3795567006776', 170),
(3, 1, 2, '56.00', '3795567006783', 631),
(3, 1, 3, '52.00', '3795567006790', 321),
(3, 1, 4, '58.00', '3795567006806', 640),
(3, 1, 5, '56.00', '3795567006813', 226),
(3, 1, 6, '60.00', '3795567006820', 493),
(3, 2, 1, '51.00', '3795567006837', 456),
(3, 2, 2, '60.00', '3795567006844', 30),
(3, 2, 3, '57.00', '3795567006851', 510),
(3, 2, 4, '59.00', '3795567006868', 381),
(3, 2, 5, '51.00', '3795567006875', 392),
(3, 2, 6, '52.00', '3795567006882', 640),
(3, 3, 1, '55.00', '3795567006899', 81),
(3, 3, 2, '59.00', '3795567006905', 141),
(3, 3, 3, '53.00', '3795567006912', 414),
(3, 3, 4, '59.00', '3795567006929', 38),
(3, 3, 5, '59.00', '3795567006936', 451),
(3, 3, 6, '60.00', '3795567006943', 673),
(3, 4, 1, '54.00', '3795567006950', 457),
(3, 4, 2, '57.00', '3795567006967', 350),
(3, 4, 3, '53.00', '3795567006974', 677),
(3, 4, 4, '58.00', '3795567006981', 420),
(3, 4, 5, '54.00', '3795567006998', 104),
(3, 4, 6, '51.00', '3795567007001', 545),
(3, 5, 1, '55.00', '3795567007018', 167),
(3, 5, 2, '53.00', '3795567007025', 280),
(3, 5, 3, '52.00', '3795567007032', 692),
(3, 5, 4, '56.00', '3795567007049', 646),
(3, 5, 5, '51.00', '3795567007056', 83),
(3, 5, 6, '59.00', '3795567007063', 101),
(3, 6, 1, '57.00', '3795567007070', 402),
(3, 6, 2, '58.00', '3795567007087', 323),
(3, 6, 3, '60.00', '3795567007094', 535),
(3, 6, 4, '58.00', '3795567007100', 79),
(3, 6, 5, '58.00', '3795567007117', 571),
(3, 6, 6, '52.00', '3795567007124', 301),
(3, 7, 1, '54.00', '3795567007131', 580),
(3, 7, 2, '55.00', '3795567007148', 61),
(3, 7, 3, '55.00', '3795567007155', 81),
(3, 7, 4, '52.00', '3795567007162', 250),
(3, 7, 5, '54.00', '3795567007179', 328),
(3, 7, 6, '60.00', '3795567007186', 245),
(3, 8, 1, '54.00', '3795567007193', 269),
(3, 8, 2, '57.00', '3795567007209', 192),
(3, 8, 3, '53.00', '3795567007216', 238),
(3, 8, 4, '58.00', '3795567007223', 476),
(3, 8, 5, '59.00', '3795567007230', 18),
(3, 8, 6, '57.00', '3795567007247', 499),
(4, 1, 1, '106.00', '3795567007254', 639),
(4, 1, 2, '97.00', '3795567007261', 246),
(4, 1, 3, '99.00', '3795567007278', 149),
(4, 1, 4, '98.00', '3795567007285', 664),
(4, 1, 5, '100.00', '3795567007292', 689),
(4, 1, 6, '104.00', '3795567007308', 106),
(4, 2, 1, '98.00', '3795567007315', 324),
(4, 2, 2, '105.00', '3795567007322', 66),
(4, 2, 3, '102.00', '3795567007339', 688),
(4, 2, 4, '102.00', '3795567007346', 611),
(4, 2, 5, '100.00', '3795567007353', 153),
(4, 2, 6, '103.00', '3795567007360', 562),
(4, 3, 1, '106.00', '3795567007377', 309),
(4, 3, 2, '101.00', '3795567007384', 469),
(4, 3, 3, '105.00', '3795567007391', 534),
(4, 3, 4, '100.00', '3795567007407', 684),
(4, 3, 5, '98.00', '3795567007414', 272),
(4, 3, 6, '104.00', '3795567007421', 138),
(4, 4, 1, '98.00', '3795567007438', 612),
(4, 4, 2, '105.00', '3795567007445', 17),
(4, 4, 3, '101.00', '3795567007452', 251),
(4, 4, 4, '101.00', '3795567007469', 431),
(4, 4, 5, '101.00', '3795567007476', 584),
(4, 4, 6, '97.00', '3795567007483', 596),
(4, 5, 1, '104.00', '3795567007490', 267),
(4, 5, 2, '105.00', '3795567007506', 405),
(4, 5, 3, '97.00', '3795567007513', 298),
(4, 5, 4, '97.00', '3795567007520', 605),
(4, 5, 5, '100.00', '3795567007537', 692),
(4, 5, 6, '105.00', '3795567007544', 169),
(4, 6, 1, '97.00', '3795567007551', 652),
(4, 6, 2, '101.00', '3795567007568', 678),
(4, 6, 3, '105.00', '3795567007575', 147),
(4, 6, 4, '106.00', '3795567007582', 184),
(4, 6, 5, '97.00', '3795567007599', 89),
(4, 6, 6, '100.00', '3795567007605', 399),
(4, 7, 1, '103.00', '3795567007612', 405),
(4, 7, 2, '97.00', '3795567007629', 559),
(4, 7, 3, '97.00', '3795567007636', 210),
(4, 7, 4, '102.00', '3795567007643', 403),
(4, 7, 5, '102.00', '3795567007650', 87),
(4, 7, 6, '102.00', '3795567007667', 352),
(4, 8, 1, '102.00', '3795567007674', 159),
(4, 8, 2, '98.00', '3795567007681', 275),
(4, 8, 3, '105.00', '3795567007698', 265),
(4, 8, 4, '99.00', '3795567007704', 373),
(4, 8, 5, '102.00', '3795567007711', 135),
(4, 8, 6, '104.00', '3795567007728', 649),
(5, 1, 1, '24.00', '3795567007735', 472),
(5, 1, 2, '26.00', '3795567007742', 488),
(5, 1, 3, '21.00', '3795567007759', 398),
(5, 1, 4, '25.00', '3795567007766', 678),
(5, 1, 5, '24.00', '3795567007773', 479),
(5, 1, 6, '29.00', '3795567007780', 666),
(5, 2, 1, '26.00', '3795567007797', 172),
(5, 2, 2, '21.00', '3795567007803', 663),
(5, 2, 3, '25.00', '3795567007810', 485),
(5, 2, 4, '20.00', '3795567007827', 595),
(5, 2, 5, '26.00', '3795567007834', 656),
(5, 2, 6, '21.00', '3795567007841', 279),
(5, 3, 1, '24.00', '3795567007858', 424),
(5, 3, 2, '27.00', '3795567007865', 299),
(5, 3, 3, '25.00', '3795567007872', 208),
(5, 3, 4, '21.00', '3795567007889', 76),
(5, 3, 5, '21.00', '3795567007896', 580),
(5, 3, 6, '22.00', '3795567007902', 149),
(5, 4, 1, '21.00', '3795567007919', 339),
(5, 4, 2, '22.00', '3795567007926', 248),
(5, 4, 3, '24.00', '3795567007933', 188),
(5, 4, 4, '24.00', '3795567007940', 168),
(5, 4, 5, '29.00', '3795567007957', 108),
(5, 4, 6, '29.00', '3795567007964', 644),
(5, 5, 1, '23.00', '3795567007971', 232),
(5, 5, 2, '25.00', '3795567007988', 26),
(5, 5, 3, '29.00', '3795567007995', 324),
(5, 5, 4, '21.00', '3795567008008', 58),
(5, 5, 5, '27.00', '3795567008015', 647),
(5, 5, 6, '22.00', '3795567008022', 447),
(5, 6, 1, '23.00', '3795567008039', 45),
(5, 6, 2, '26.00', '3795567008046', 440),
(5, 6, 3, '25.00', '3795567008053', 317),
(5, 6, 4, '29.00', '3795567008060', 401),
(5, 6, 5, '21.00', '3795567008077', 83),
(5, 6, 6, '22.00', '3795567008084', 585),
(5, 7, 1, '25.00', '3795567008091', 576),
(5, 7, 2, '24.00', '3795567008107', 612),
(5, 7, 3, '27.00', '3795567008114', 197),
(5, 7, 4, '25.00', '3795567008121', 340),
(5, 7, 5, '29.00', '3795567008138', 308),
(5, 7, 6, '20.00', '3795567008145', 48),
(5, 8, 1, '21.00', '3795567008152', 495),
(5, 8, 2, '26.00', '3795567008169', 603),
(5, 8, 3, '22.00', '3795567008176', 148),
(5, 8, 4, '23.00', '3795567008183', 135),
(5, 8, 5, '25.00', '3795567008190', 36),
(5, 8, 6, '29.00', '3795567008206', 520),
(6, 1, 1, '83.00', '3795567008213', 566),
(6, 1, 2, '81.00', '3795567008220', 55),
(6, 1, 3, '74.00', '3795567008237', 396),
(6, 1, 4, '78.00', '3795567008244', 690),
(6, 1, 5, '82.00', '3795567008251', 94),
(6, 1, 6, '81.00', '3795567008268', 8),
(6, 2, 1, '74.00', '3795567008275', 22),
(6, 2, 2, '75.00', '3795567008282', 137),
(6, 2, 3, '79.00', '3795567008299', 436),
(6, 2, 4, '77.00', '3795567008305', 202),
(6, 2, 5, '77.00', '3795567008312', 254),
(6, 2, 6, '75.00', '3795567008329', 9),
(6, 3, 1, '77.00', '3795567008336', 38),
(6, 3, 2, '82.00', '3795567008343', 219),
(6, 3, 3, '75.00', '3795567008350', 63),
(6, 3, 4, '78.00', '3795567008367', 417),
(6, 3, 5, '78.00', '3795567008374', 498),
(6, 3, 6, '82.00', '3795567008381', 694),
(6, 4, 1, '77.00', '3795567008398', 172),
(6, 4, 2, '77.00', '3795567008404', 418),
(6, 4, 3, '77.00', '3795567008411', 261),
(6, 4, 4, '78.00', '3795567008428', 511),
(6, 4, 5, '74.00', '3795567008435', 252),
(6, 4, 6, '81.00', '3795567008442', 122),
(6, 5, 1, '83.00', '3795567008459', 226),
(6, 5, 2, '78.00', '3795567008466', 305),
(6, 5, 3, '75.00', '3795567008473', 326),
(6, 5, 4, '74.00', '3795567008480', 690),
(6, 5, 5, '83.00', '3795567008497', 238),
(6, 5, 6, '81.00', '3795567008503', 360),
(6, 6, 1, '83.00', '3795567008510', 64),
(6, 6, 2, '76.00', '3795567008527', 128),
(6, 6, 3, '77.00', '3795567008534', 495),
(6, 6, 4, '76.00', '3795567008541', 611),
(6, 6, 5, '75.00', '3795567008558', 189),
(6, 6, 6, '80.00', '3795567008565', 299),
(6, 7, 1, '80.00', '3795567008572', 144),
(6, 7, 2, '83.00', '3795567008589', 642),
(6, 7, 3, '74.00', '3795567008596', 373),
(6, 7, 4, '76.00', '3795567008602', 611),
(6, 7, 5, '81.00', '3795567008619', 81),
(6, 7, 6, '80.00', '3795567008626', 386),
(6, 8, 1, '79.00', '3795567008633', 638),
(6, 8, 2, '78.00', '3795567008640', 122),
(6, 8, 3, '78.00', '3795567008657', 153),
(6, 8, 4, '77.00', '3795567008664', 129),
(6, 8, 5, '81.00', '3795567008671', 655),
(6, 8, 6, '74.00', '3795567008688', 344),
(7, 1, 1, '66.00', '3795567008695', 590),
(7, 1, 2, '64.00', '3795567008701', 374),
(7, 1, 3, '68.00', '3795567008718', 359),
(7, 1, 4, '66.00', '3795567008725', 517),
(7, 1, 5, '67.00', '3795567008732', 138),
(7, 1, 6, '68.00', '3795567008749', 127),
(7, 2, 1, '69.00', '3795567008756', 544),
(7, 2, 2, '66.00', '3795567008763', 664),
(7, 2, 3, '65.00', '3795567008770', 68),
(7, 2, 4, '66.00', '3795567008787', 323),
(7, 2, 5, '68.00', '3795567008794', 158),
(7, 2, 6, '69.00', '3795567008800', 520),
(7, 3, 1, '60.00', '3795567008817', 489),
(7, 3, 2, '68.00', '3795567008824', 457),
(7, 3, 3, '61.00', '3795567008831', 538),
(7, 3, 4, '61.00', '3795567008848', 106),
(7, 3, 5, '66.00', '3795567008855', 460),
(7, 3, 6, '60.00', '3795567008862', 377),
(7, 4, 1, '60.00', '3795567008879', 566),
(7, 4, 2, '66.00', '3795567008886', 495),
(7, 4, 3, '61.00', '3795567008893', 90),
(7, 4, 4, '61.00', '3795567008909', 278),
(7, 4, 5, '61.00', '3795567008916', 176),
(7, 4, 6, '62.00', '3795567008923', 308),
(7, 5, 1, '60.00', '3795567008930', 541),
(7, 5, 2, '68.00', '3795567008947', 8),
(7, 5, 3, '67.00', '3795567008954', 355),
(7, 5, 4, '61.00', '3795567008961', 619),
(7, 5, 5, '61.00', '3795567008978', 519),
(7, 5, 6, '61.00', '3795567008985', 466),
(7, 6, 1, '62.00', '3795567008992', 334),
(7, 6, 2, '62.00', '3795567009005', 46),
(7, 6, 3, '60.00', '3795567009012', 484),
(7, 6, 4, '64.00', '3795567009029', 95),
(7, 6, 5, '60.00', '3795567009036', 401),
(7, 6, 6, '66.00', '3795567009043', 364),
(7, 7, 1, '64.00', '3795567009050', 547),
(7, 7, 2, '68.00', '3795567009067', 495),
(7, 7, 3, '66.00', '3795567009074', 487),
(7, 7, 4, '63.00', '3795567009081', 194),
(7, 7, 5, '69.00', '3795567009098', 466),
(7, 7, 6, '69.00', '3795567009104', 110),
(7, 8, 1, '62.00', '3795567009111', 178),
(7, 8, 2, '64.00', '3795567009128', 608),
(7, 8, 3, '62.00', '3795567009135', 350),
(7, 8, 4, '67.00', '3795567009142', 540),
(7, 8, 5, '65.00', '3795567009159', 109),
(7, 8, 6, '68.00', '3795567009166', 227),
(8, 1, 1, '102.00', '3795567009173', 502),
(8, 1, 2, '96.00', '3795567009180', 484),
(8, 1, 3, '104.00', '3795567009197', 580),
(8, 1, 4, '105.00', '3795567009203', 574),
(8, 1, 5, '100.00', '3795567009210', 652),
(8, 1, 6, '99.00', '3795567009227', 209),
(8, 2, 1, '102.00', '3795567009234', 55),
(8, 2, 2, '104.00', '3795567009241', 224),
(8, 2, 3, '98.00', '3795567009258', 642),
(8, 2, 4, '103.00', '3795567009265', 381),
(8, 2, 5, '97.00', '3795567009272', 652),
(8, 2, 6, '97.00', '3795567009289', 245),
(8, 3, 1, '102.00', '3795567009296', 494),
(8, 3, 2, '101.00', '3795567009302', 59),
(8, 3, 3, '103.00', '3795567009319', 114),
(8, 3, 4, '105.00', '3795567009326', 221),
(8, 3, 5, '96.00', '3795567009333', 579),
(8, 3, 6, '104.00', '3795567009340', 53),
(8, 4, 1, '101.00', '3795567009357', 40),
(8, 4, 2, '104.00', '3795567009364', 86),
(8, 4, 3, '98.00', '3795567009371', 220),
(8, 4, 4, '104.00', '3795567009388', 123),
(8, 4, 5, '96.00', '3795567009395', 277),
(8, 4, 6, '97.00', '3795567009401', 124),
(8, 5, 1, '100.00', '3795567009418', 489),
(8, 5, 2, '102.00', '3795567009425', 377),
(8, 5, 3, '98.00', '3795567009432', 321),
(8, 5, 4, '100.00', '3795567009449', 271),
(8, 5, 5, '101.00', '3795567009456', 7),
(8, 5, 6, '101.00', '3795567009463', 576),
(8, 6, 1, '104.00', '3795567009470', 19),
(8, 6, 2, '104.00', '3795567009487', 110),
(8, 6, 3, '102.00', '3795567009494', 547),
(8, 6, 4, '101.00', '3795567009500', 126),
(8, 6, 5, '102.00', '3795567009517', 268),
(8, 6, 6, '96.00', '3795567009524', 276),
(8, 7, 1, '102.00', '3795567009531', 29),
(8, 7, 2, '104.00', '3795567009548', 190),
(8, 7, 3, '98.00', '3795567009555', 215),
(8, 7, 4, '97.00', '3795567009562', 353),
(8, 7, 5, '104.00', '3795567009579', 227),
(8, 7, 6, '101.00', '3795567009586', 176),
(8, 8, 1, '104.00', '3795567009593', 56),
(8, 8, 2, '105.00', '3795567009609', 276),
(8, 8, 3, '105.00', '3795567009616', 461),
(8, 8, 4, '96.00', '3795567009623', 544),
(8, 8, 5, '103.00', '3795567009630', 339),
(8, 8, 6, '100.00', '3795567009647', 328),
(9, 1, 1, '32.00', '3795567009654', 117),
(9, 1, 2, '36.00', '3795567009661', 44),
(9, 1, 3, '35.00', '3795567009678', 257),
(9, 1, 4, '34.00', '3795567009685', 461),
(9, 1, 5, '37.00', '3795567009692', 240),
(9, 1, 6, '40.00', '3795567009708', 255),
(9, 2, 1, '33.00', '3795567009715', 618),
(9, 2, 2, '32.00', '3795567009722', 206),
(9, 2, 3, '36.00', '3795567009739', 341),
(9, 2, 4, '38.00', '3795567009746', 460),
(9, 2, 5, '41.00', '3795567009753', 441),
(9, 2, 6, '34.00', '3795567009760', 0),
(9, 3, 1, '39.00', '3795567009777', 1),
(9, 3, 2, '33.00', '3795567009784', 329),
(9, 3, 3, '37.00', '3795567009791', 536),
(9, 3, 4, '39.00', '3795567009807', 38),
(9, 3, 5, '33.00', '3795567009814', 559),
(9, 3, 6, '32.00', '3795567009821', 586),
(9, 4, 1, '36.00', '3795567009838', 664),
(9, 4, 2, '34.00', '3795567009845', 168),
(9, 4, 3, '40.00', '3795567009852', 195),
(9, 4, 4, '38.00', '3795567009869', 201),
(9, 4, 5, '36.00', '3795567009876', 231),
(9, 4, 6, '38.00', '3795567009883', 587),
(9, 5, 1, '36.00', '3795567009890', 246),
(9, 5, 2, '39.00', '3795567009906', 597),
(9, 5, 3, '32.00', '3795567009913', 399),
(9, 5, 4, '36.00', '3795567009920', 441),
(9, 5, 5, '32.00', '3795567009937', 296),
(9, 5, 6, '39.00', '3795567009944', 45),
(9, 6, 1, '32.00', '3795567009951', 577),
(9, 6, 2, '39.00', '3795567009968', 627),
(9, 6, 3, '36.00', '3795567009975', 279),
(9, 6, 4, '33.00', '3795567009982', 669),
(9, 6, 5, '32.00', '3795567009999', 523),
(9, 6, 6, '33.00', '3795567010001', 292),
(9, 7, 1, '39.00', '3795567010018', 479),
(9, 7, 2, '37.00', '3795567010025', 37),
(9, 7, 3, '39.00', '3795567010032', 347),
(9, 7, 4, '37.00', '3795567010049', 333),
(9, 7, 5, '38.00', '3795567010056', 130),
(9, 7, 6, '36.00', '3795567010063', 274),
(9, 8, 1, '37.00', '3795567010070', 510),
(9, 8, 2, '36.00', '3795567010087', 597),
(9, 8, 3, '33.00', '3795567010094', 218),
(9, 8, 4, '35.00', '3795567010100', 352),
(9, 8, 5, '37.00', '3795567010117', 419),
(9, 8, 6, '32.00', '3795567010124', 581),
(10, 1, 1, '7.00', '3795567010131', 664),
(10, 1, 2, '14.00', '3795567010148', 32),
(10, 1, 3, '13.00', '3795567010155', 586),
(10, 1, 4, '8.00', '3795567010162', 323),
(10, 1, 5, '10.00', '3795567010179', 347),
(10, 1, 6, '15.00', '3795567010186', 650),
(10, 2, 1, '15.00', '3795567010193', 530),
(10, 2, 2, '11.00', '3795567010209', 29),
(10, 2, 3, '9.00', '3795567010216', 559),
(10, 2, 4, '9.00', '3795567010223', 434),
(10, 2, 5, '12.00', '3795567010230', 271),
(10, 2, 6, '8.00', '3795567010247', 664),
(10, 3, 1, '13.00', '3795567010254', 96),
(10, 3, 2, '14.00', '3795567010261', 20),
(10, 3, 3, '9.00', '3795567010278', 530),
(10, 3, 4, '11.00', '3795567010285', 585),
(10, 3, 5, '9.00', '3795567010292', 207),
(10, 3, 6, '11.00', '3795567010308', 392),
(10, 4, 1, '11.00', '3795567010315', 279),
(10, 4, 2, '9.00', '3795567010322', 604),
(10, 4, 3, '15.00', '3795567010339', 388),
(10, 4, 4, '7.00', '3795567010346', 85),
(10, 4, 5, '14.00', '3795567010353', 240),
(10, 4, 6, '8.00', '3795567010360', 479),
(10, 5, 1, '8.00', '3795567010377', 485),
(10, 5, 2, '13.00', '3795567010384', 82),
(10, 5, 3, '12.00', '3795567010391', 85),
(10, 5, 4, '16.00', '3795567010407', 320),
(10, 5, 5, '7.00', '3795567010414', 696),
(10, 5, 6, '15.00', '3795567010421', 320),
(10, 6, 1, '13.00', '3795567010438', 536),
(10, 6, 2, '7.00', '3795567010445', 587),
(10, 6, 3, '9.00', '3795567010452', 166),
(10, 6, 4, '15.00', '3795567010469', 380),
(10, 6, 5, '10.00', '3795567010476', 564),
(10, 6, 6, '10.00', '3795567010483', 688),
(10, 7, 1, '13.00', '3795567010490', 495),
(10, 7, 2, '11.00', '3795567010506', 199),
(10, 7, 3, '9.00', '3795567010513', 191),
(10, 7, 4, '10.00', '3795567010520', 383),
(10, 7, 5, '14.00', '3795567010537', 127),
(10, 7, 6, '13.00', '3795567010544', 59),
(10, 8, 1, '14.00', '3795567010551', 491),
(10, 8, 2, '14.00', '3795567010568', 481),
(10, 8, 3, '14.00', '3795567010575', 661),
(10, 8, 4, '8.00', '3795567010582', 598),
(10, 8, 5, '13.00', '3795567010599', 82),
(10, 8, 6, '8.00', '3795567010605', 41),
(11, 1, 1, '14.00', '3795567010612', 23),
(11, 1, 2, '18.00', '3795567010629', 196),
(11, 1, 3, '11.00', '3795567010636', 296),
(11, 1, 4, '10.00', '3795567010643', 529),
(11, 1, 5, '19.00', '3795567010650', 49),
(11, 1, 6, '15.00', '3795567010667', 214),
(11, 2, 1, '13.00', '3795567010674', 642),
(11, 2, 2, '19.00', '3795567010681', 229),
(11, 2, 3, '12.00', '3795567010698', 260),
(11, 2, 4, '19.00', '3795567010704', 39),
(11, 2, 5, '15.00', '3795567010711', 394),
(11, 2, 6, '16.00', '3795567010728', 579),
(11, 3, 1, '15.00', '3795567010735', 414),
(11, 3, 2, '16.00', '3795567010742', 219),
(11, 3, 3, '18.00', '3795567010759', 540),
(11, 3, 4, '15.00', '3795567010766', 550),
(11, 3, 5, '19.00', '3795567010773', 622),
(11, 3, 6, '16.00', '3795567010780', 249),
(11, 4, 1, '10.00', '3795567010797', 312),
(11, 4, 2, '12.00', '3795567010803', 163),
(11, 4, 3, '17.00', '3795567010810', 642),
(11, 4, 4, '15.00', '3795567010827', 677),
(11, 4, 5, '11.00', '3795567010834', 696),
(11, 4, 6, '10.00', '3795567010841', 492),
(11, 5, 1, '18.00', '3795567010858', 584),
(11, 5, 2, '12.00', '3795567010865', 300),
(11, 5, 3, '18.00', '3795567010872', 224),
(11, 5, 4, '18.00', '3795567010889', 158),
(11, 5, 5, '17.00', '3795567010896', 208),
(11, 5, 6, '13.00', '3795567010902', 364),
(11, 6, 1, '12.00', '3795567010919', 583),
(11, 6, 2, '19.00', '3795567010926', 232),
(11, 6, 3, '13.00', '3795567010933', 322),
(11, 6, 4, '18.00', '3795567010940', 367),
(11, 6, 5, '11.00', '3795567010957', 305),
(11, 6, 6, '19.00', '3795567010964', 37),
(11, 7, 1, '19.00', '3795567010971', 688),
(11, 7, 2, '13.00', '3795567010988', 155),
(11, 7, 3, '15.00', '3795567010995', 377),
(11, 7, 4, '12.00', '3795567011008', 646),
(11, 7, 5, '15.00', '3795567011015', 11),
(11, 7, 6, '18.00', '3795567011022', 626),
(11, 8, 1, '12.00', '3795567011039', 31),
(11, 8, 2, '16.00', '3795567011046', 678),
(11, 8, 3, '14.00', '3795567011053', 155),
(11, 8, 4, '18.00', '3795567011060', 225),
(11, 8, 5, '13.00', '3795567011077', 253),
(11, 8, 6, '14.00', '3795567011084', 506),
(12, 1, 1, '83.00', '3795567011091', 282),
(12, 1, 2, '75.00', '3795567011107', 327),
(12, 1, 3, '82.00', '3795567011114', 193),
(12, 1, 4, '80.00', '3795567011121', 450),
(12, 1, 5, '76.00', '3795567011138', 28),
(12, 1, 6, '80.00', '3795567011145', 551),
(12, 2, 1, '78.00', '3795567011152', 393),
(12, 2, 2, '75.00', '3795567011169', 107),
(12, 2, 3, '74.00', '3795567011176', 556),
(12, 2, 4, '80.00', '3795567011183', 675),
(12, 2, 5, '78.00', '3795567011190', 27),
(12, 2, 6, '81.00', '3795567011206', 463),
(12, 3, 1, '80.00', '3795567011213', 135),
(12, 3, 2, '75.00', '3795567011220', 108),
(12, 3, 3, '83.00', '3795567011237', 696),
(12, 3, 4, '75.00', '3795567011244', 52),
(12, 3, 5, '82.00', '3795567011251', 189),
(12, 3, 6, '78.00', '3795567011268', 11),
(12, 4, 1, '78.00', '3795567011275', 141),
(12, 4, 2, '77.00', '3795567011282', 559),
(12, 4, 3, '82.00', '3795567011299', 390),
(12, 4, 4, '80.00', '3795567011305', 492),
(12, 4, 5, '74.00', '3795567011312', 664),
(12, 4, 6, '75.00', '3795567011329', 517),
(12, 5, 1, '79.00', '3795567011336', 58),
(12, 5, 2, '83.00', '3795567011343', 611),
(12, 5, 3, '76.00', '3795567011350', 9),
(12, 5, 4, '80.00', '3795567011367', 210),
(12, 5, 5, '74.00', '3795567011374', 267),
(12, 5, 6, '77.00', '3795567011381', 477),
(12, 6, 1, '74.00', '3795567011398', 474),
(12, 6, 2, '78.00', '3795567011404', 275),
(12, 6, 3, '77.00', '3795567011411', 338),
(12, 6, 4, '76.00', '3795567011428', 549),
(12, 6, 5, '83.00', '3795567011435', 506),
(12, 6, 6, '81.00', '3795567011442', 295),
(12, 7, 1, '79.00', '3795567011459', 642),
(12, 7, 2, '80.00', '3795567011466', 237),
(12, 7, 3, '74.00', '3795567011473', 301),
(12, 7, 4, '77.00', '3795567011480', 203),
(12, 7, 5, '79.00', '3795567011497', 502),
(12, 7, 6, '81.00', '3795567011503', 0),
(12, 8, 1, '82.00', '3795567011510', 406),
(12, 8, 2, '75.00', '3795567011527', 286),
(12, 8, 3, '80.00', '3795567011534', 289),
(12, 8, 4, '79.00', '3795567011541', 648),
(12, 8, 5, '78.00', '3795567011558', 319),
(12, 8, 6, '83.00', '3795567011565', 477);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`ID_ARTICLE`),
  ADD KEY `fk_categorie` (`CATEGORIE`);

--
-- Index pour la table `a_categories`
--
ALTER TABLE `a_categories`
  ADD PRIMARY KEY (`CODE_CATEGORIE`);

--
-- Index pour la table `a_couleurs`
--
ALTER TABLE `a_couleurs`
  ADD PRIMARY KEY (`CODE_COULEUR`);

--
-- Index pour la table `a_tailles`
--
ALTER TABLE `a_tailles`
  ADD PRIMARY KEY (`CODE_TAILLE`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`ID_CLIENT`),
  ADD KEY `fk_typeClient` (`TYPE_CLIENT`);

--
-- Index pour la table `c_types`
--
ALTER TABLE `c_types`
  ADD PRIMARY KEY (`CODE_TYPE`);

--
-- Index pour la table `devis_entetes`
--
ALTER TABLE `devis_entetes`
  ADD PRIMARY KEY (`ID_ENT_DEV`),
  ADD KEY `fk_Client` (`CLIENT`);

--
-- Index pour la table `devis_lignes`
--
ALTER TABLE `devis_lignes`
  ADD PRIMARY KEY (`ID_LIG_DEV`),
  ADD KEY `fk_idDevis` (`ID_DEVIS`),
  ADD KEY `fk_articleLig` (`ARTICLE`),
  ADD KEY `fk_couleurLig` (`COULEUR`),
  ADD KEY `fk_tailleLig` (`TAILLE`);

--
-- Index pour la table `factures_entetes`
--
ALTER TABLE `factures_entetes`
  ADD PRIMARY KEY (`ID_ENT_FCT`),
  ADD KEY `fk_ClientFCT` (`CLIENT`);

--
-- Index pour la table `factures_lignes`
--
ALTER TABLE `factures_lignes`
  ADD PRIMARY KEY (`ID_LIG_FCT`),
  ADD KEY `fk_idFCT` (`ID_FCT`),
  ADD KEY `fk_articleLigF` (`ARTICLE`),
  ADD KEY `fk_couleurLigF` (`COULEUR`),
  ADD KEY `fk_tailleLigF` (`TAILLE`);

--
-- Index pour la table `parametres`
--
ALTER TABLE `parametres`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `stockprix`
--
ALTER TABLE `stockprix`
  ADD PRIMARY KEY (`ARTICLE`,`COULEUR`,`TAILLE`),
  ADD KEY `fk_couleur` (`COULEUR`),
  ADD KEY `fk_taille` (`TAILLE`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `ID_ARTICLE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `a_categories`
--
ALTER TABLE `a_categories`
  MODIFY `CODE_CATEGORIE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `a_couleurs`
--
ALTER TABLE `a_couleurs`
  MODIFY `CODE_COULEUR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `a_tailles`
--
ALTER TABLE `a_tailles`
  MODIFY `CODE_TAILLE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `ID_CLIENT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `c_types`
--
ALTER TABLE `c_types`
  MODIFY `CODE_TYPE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `devis_entetes`
--
ALTER TABLE `devis_entetes`
  MODIFY `ID_ENT_DEV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `devis_lignes`
--
ALTER TABLE `devis_lignes`
  MODIFY `ID_LIG_DEV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `factures_entetes`
--
ALTER TABLE `factures_entetes`
  MODIFY `ID_ENT_FCT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `factures_lignes`
--
ALTER TABLE `factures_lignes`
  MODIFY `ID_LIG_FCT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_categorie` FOREIGN KEY (`CATEGORIE`) REFERENCES `a_categories` (`CODE_CATEGORIE`);

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_typeClient` FOREIGN KEY (`TYPE_CLIENT`) REFERENCES `c_types` (`CODE_TYPE`);

--
-- Contraintes pour la table `devis_entetes`
--
ALTER TABLE `devis_entetes`
  ADD CONSTRAINT `fk_Client` FOREIGN KEY (`CLIENT`) REFERENCES `clients` (`ID_CLIENT`);

--
-- Contraintes pour la table `devis_lignes`
--
ALTER TABLE `devis_lignes`
  ADD CONSTRAINT `fk_articleLig` FOREIGN KEY (`ARTICLE`) REFERENCES `articles` (`ID_ARTICLE`),
  ADD CONSTRAINT `fk_couleurLig` FOREIGN KEY (`COULEUR`) REFERENCES `a_couleurs` (`CODE_COULEUR`),
  ADD CONSTRAINT `fk_idDevis` FOREIGN KEY (`ID_DEVIS`) REFERENCES `devis_entetes` (`ID_ENT_DEV`),
  ADD CONSTRAINT `fk_tailleLig` FOREIGN KEY (`TAILLE`) REFERENCES `a_tailles` (`CODE_TAILLE`);

--
-- Contraintes pour la table `factures_entetes`
--
ALTER TABLE `factures_entetes`
  ADD CONSTRAINT `fk_ClientFCT` FOREIGN KEY (`CLIENT`) REFERENCES `clients` (`ID_CLIENT`);

--
-- Contraintes pour la table `factures_lignes`
--
ALTER TABLE `factures_lignes`
  ADD CONSTRAINT `fk_articleLigF` FOREIGN KEY (`ARTICLE`) REFERENCES `articles` (`ID_ARTICLE`),
  ADD CONSTRAINT `fk_couleurLigF` FOREIGN KEY (`COULEUR`) REFERENCES `a_couleurs` (`CODE_COULEUR`),
  ADD CONSTRAINT `fk_idFCT` FOREIGN KEY (`ID_FCT`) REFERENCES `factures_entetes` (`ID_ENT_FCT`),
  ADD CONSTRAINT `fk_tailleLigF` FOREIGN KEY (`TAILLE`) REFERENCES `a_tailles` (`CODE_TAILLE`);

--
-- Contraintes pour la table `stockprix`
--
ALTER TABLE `stockprix`
  ADD CONSTRAINT `fk_article` FOREIGN KEY (`ARTICLE`) REFERENCES `articles` (`ID_ARTICLE`),
  ADD CONSTRAINT `fk_couleur` FOREIGN KEY (`COULEUR`) REFERENCES `a_couleurs` (`CODE_COULEUR`),
  ADD CONSTRAINT `fk_taille` FOREIGN KEY (`TAILLE`) REFERENCES `a_tailles` (`CODE_TAILLE`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
