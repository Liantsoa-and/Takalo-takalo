-- Création de la table `membres` et insertion des 3 étudiants (MySQL)
CREATE TABLE IF NOT EXISTS `membres` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(150) NOT NULL,
  `prenom` VARCHAR(150) NOT NULL,
  `etu` VARCHAR(50) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed initial
INSERT INTO `membres` (`nom`, `prenom`, `etu`, `photo`, `bio`) VALUES
('RAKOTOARIVONY', 'Harena Natolotra Sarobidy', 'ETU-3940', 'H.jpg', 'Membre du projet.'),
('FENOHERILIANTSOA', 'Ny Aina Andreane', 'ETU-4199', 'L.jpg', 'Membre du projet.'),
('FANEVA', 'Jedidia', 'ETU-4042', 'J.jpg', 'Membre du projet.');
