SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `messages` (
  `id_message` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED DEFAULT NULL,
  `id_salon` int(11) UNSIGNED NOT NULL,
  `date_message` datetime NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `salons` (
  `id_salon` int(11) UNSIGNED NOT NULL,
  `nom` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `pseudo` varchar(30) NOT NULL,
  `mdp` varchar(50) NOT NULL,
  `last_seen` datetime DEFAULT NULL,
  `statut` enum('en ligne','deconnecte','absent') NOT NULL DEFAULT 'deconnecte'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users_salon` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_salon` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_salon` (`id_salon`);

ALTER TABLE `salons`
  ADD PRIMARY KEY (`id_salon`),
  ADD UNIQUE KEY `nom` (`nom`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

ALTER TABLE `users_salon`
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_salon` (`id_salon`);


ALTER TABLE `messages`
  MODIFY `id_message` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
ALTER TABLE `salons`
  MODIFY `id_salon` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `users`
  MODIFY `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

ALTER TABLE `messages`
  ADD CONSTRAINT `fk_message_salon` FOREIGN KEY (`id_salon`) REFERENCES `salons` (`id_salon`),
  ADD CONSTRAINT `fk_message_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users_salon`
  ADD CONSTRAINT `fk_id_salon` FOREIGN KEY (`id_salon`) REFERENCES `salons` (`id_salon`),
  ADD CONSTRAINT `fk_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
