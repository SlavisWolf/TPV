INSERT INTO `client` (`id`, `name`, `surname`, `tin`, `address`, `location`, `postalCode`, `province`, `email`) VALUES
(1, 'Carlos pobre', 'López Moran pobre', '1323432443df P', 'Calle juliana 4ºE Pobre', 'Granada Pobre', '1021', 'Granada Pobre', 'pobre@gmail.com'),
(2, 'Pedro Antonio', 'Garcia Perez', '32454531G', 'Calle San Cayetano', 'Granada', '18007', 'Granada', 'panyajo@hotmail.com'),
(3, 'Luis', 'Jimenez Castillo', '212131389P', 'Calle Santa Glotilde', 'Malaga', '19012', 'Malaga', 'castillo_jimenez@gmail.com'),
(4, 'Maria del Carmen', 'Barja Carballal', '21334234B', 'Calle Santa Rita', 'Granada', '18004', 'Granada', 'gallega@hotmail.com'),
(5, 'Aurora', 'Moran Garcia', '23567122U', 'Camino de ronda 65', 'Granada', '18004', 'Granada', 'aurora_foto@gmail.com'),
(6, 'Pepe', 'Roblez Martinez', '6017562856S', 'Camino Ronda 22', 'Granada', '18004', 'Granada', 'pescaderia_88@gmail.com');



/*PASS 1234*/
INSERT INTO `member` (`id`, `login`, `password`) VALUES
(1, 'antoniojesus.ib@gmail.com', '$2y$10$9s79DMDdh/FTyC9nHNT.3uxnJpDh4g0ukMZGSn7ErT9dk...nL2ra'),
(2, 'pepe@gmail.com', '$2y$10$O7WFMuAiM6suIA9GuqLOauxzzil/jgKjUOSB/n9G81wS4lsB1ok9y'),
(3, 'juanito@yahoo.es', '$2y$10$lmTzIahz0nVzls9hsxCSR.k2VRWBHgLnKMVty/7/LSRkfjvPu8vU.'),
(4, 'jimi_chachi_guay@amazon.uk', '$2y$10$kdUQlDvDUzXENYIcQYmstuV37bJXf1.TDNZOsNhPY7fdmt5Dkvx0W'),
(5, 'carlos_fiestuqui@gmail.com', '$2y$10$CAUaT1qzgvnGhMa1ItJmBO1OJDUAvUp40bM4RFpooXw3jIjjPVBrO'),
(6, 'ricarkiko@ebay.en', '$2y$10$BrLKumWib1R75FXNiCQiOuUtlhaZDKQzB9kvnSM9jVDdAmQmIBOP6'),
(7, 'susana@hotmail.com', '$2y$10$dMCvTKAM4SgclRdn1Cz5nekBYcEITXOo.mkWoM0qjvp1lcOKL4KVa'),
(8, 'ilitri_dame_cuenta@roto2.es', '$2y$10$oxGs8OpjgrhzSqqjhgLhlOKsDQR3qG8UuDMbDjgrnpt1zd2641HSK'),
(9, 'david-1995@hotmail.com', '$2y$10$ZWy6lK5l6NMfp3CPiex7T.Bdw8PiF3wvLHkwsYld3tVamNLktiVey'),
(10, 'azofaifa@zaidinvergeles.es', '$2y$10$71utrTA3lDZGMD3Z6NySGOD6S.7zeMFy89ysh23iNgo4vvO4O85Ka'),
(11, 'pepe_cohete@gmail.com', '$2y$10$0JE/q54rDz8wB4AIoHSNIeMBdNPGjXlE5VXKKo7HNzOLQeAFtcGk2'),
(12, 'jimibuenosdias@gmail.com', '$2y$10$8ysK6.OY1ZVjVtx0X57vxuNvh4BtEVU7zZSzL9ZvQHeupSp89B63.'),
(13, 'ricardogonma@hotmail.com', '$2y$10$jTlvHMaa9KVItbW40.iz.uq1xLKhJ0FtmgvaY7jD3jvQALqaClsFS');

INSERT INTO `product` (`id`, `idFamily`, `product`, `price`, `description`) VALUES
(1, 5, 'Tarta de chocolate', '5.37', 'Tarta de chocolate blandita con nata'),
(2, 4, 'Pan Integral', '0.67', 'Pan integral de barra'),
(3, 4, 'Pan de molde', '2.00', 'Pan de molde casero'),
(4, 3, 'Palmera Kinder', '1.60', 'Palmera rellena de kinder.'),
(5, 3, 'Palmera dulce de leche', '1.50', 'Palmera de hojaldre rellena de de dulce de leche.'),
(6, 5, 'Tarta de queso', '4.63', 'Tarta de queso al estilo alemán'),
(7, 6, 'Bizcocho de mantequilla', '1.00', 'Bizchocho de limón y mantequilla'),
(8, 6, 'Bizchocho de yougur', '1.16', 'Bizchocho con horneado con yogur casero natural sin azucar.'),
(9, 3, 'Palmera de huevo', '1.30', 'Palmera con huevo!'),
(10, 5, 'Tarta de fresas', '3.85', 'Tarta con fresas y nata'),
(11, 5, 'Tarta de limón', '4.76', 'Tarta de limon y frambuesas'),
(12, 4, 'Pan blanco', '0.40', 'Pan tradicional blanco'),
(13, 4, 'Pan negro ruso', '0.80', 'Pan negro de origen ruso, al estilo tradicional.');


INSERT INTO `family` (`id`, `family`) VALUES
(6, 'Bizcochos'),
(3, 'Palmeras'),
(4, 'Pan'),
(5, 'Tartas');