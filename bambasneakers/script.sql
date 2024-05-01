CREATE DATABASE IF NOT EXISTS `proyectoIAM`;
USE `proyectoIAM`;

CREATE TABLE IF NOT EXISTS USUARIOS (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    correo VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255),
    género ENUM('Hombre', 'Mujer', 'Otro') DEFAULT 'Otro' NOT NULL,
    rol ENUM ('admin', 'cliente') DEFAULT 'cliente' NOT NULL,
    avatar VARCHAR(255),
    external_id VARCHAR(255),
    external_auth VARCHAR(100),
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS TOKEN_USUARIO (
    id INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    fecha_creacion DATETIME NOT NULL,
    fecha_expiracion DATETIME NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(id_usuario) REFERENCES USUARIOS(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS MARCAS (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS CATEGORIAS (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS TALLAS (
    id INT NOT NULL,
    número INT UNIQUE NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS ZAPATILLAS (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripción TEXT,
    pvp DECIMAL(6,2) NOT NULL,
    precio DECIMAL(6,2) NOT NULL,
    imagen VARCHAR(256) NOT NULL,
    fecha_lanzamiento DATE NOT NULL,
    id_marca INT NOT NULL,
    id_categoria INT NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(id_marca) REFERENCES MARCAS(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(id_categoria) REFERENCES CATEGORIAS(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS TALLAS_ZAPATILLA (
    id INT NOT NULL AUTO_INCREMENT,
    id_zapatilla INT NOT NULL,
    id_talla INT NOT NULL,
    stock INT NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(id_zapatilla) REFERENCES ZAPATILLAS(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(id_talla) REFERENCES TALLAS(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS CARRITOS (
    id INT NOT NULL AUTO_INCREMENT,
    id_usuario INT UNIQUE NOT NULL,    
    PRIMARY KEY(ID),
    FOREIGN KEY(id_usuario) REFERENCES USUARIOS(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS ZAPATILLAS_CARRITO (
    id INT NOT NULL AUTO_INCREMENT,
    id_carrito INT NOT NULL,
    id_zapatilla INT NOT NULL,
    id_talla INT NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(id_carrito) REFERENCES CARRITOS(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(id_talla) REFERENCES TALLAS(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(id_zapatilla) REFERENCES ZAPATILLAS(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS PEDIDOS (
    id INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    precioFinal DECIMAL(10,2) NOT NULL,
    fecha DATETIME NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(id_usuario) REFERENCES USUARIOS(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS ZAPATILLAS_PEDIDO (
    id INT NOT NULL AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_zapatilla INT NOT NULL,
    id_talla INT NOT NULL,
    precio DECIMAL(6,2) NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(id_pedido) REFERENCES PEDIDOS(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(id_zapatilla) REFERENCES ZAPATILLAS(id) ON DELETE CASCADE ON UPDATE CASCADE
);

DELIMITER //

CREATE PROCEDURE IF NOT EXISTS insertTallasUnisex()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE id_zap INT;
    DECLARE talla INT;

    DECLARE cur CURSOR FOR 
    SELECT id FROM ZAPATILLAS WHERE id_categoria = 4;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    WHILE done = 0 DO
        FETCH cur INTO id_zap;
        
        IF NOT done THEN
            SET talla = 36;

            WHILE talla <= 48 DO
                INSERT INTO TALLAS_ZAPATILLA (id_zapatilla, id_talla, stock) 
                VALUES (id_zap, talla, 10);
                SET talla = talla + 1;
            END WHILE;
        END IF;

    END WHILE;

    CLOSE cur;
END;
//
DELIMITER ;

DELIMITER //

CREATE PROCEDURE IF NOT EXISTS insertTallasHombre()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE id_zap INT;
    DECLARE talla INT;

    DECLARE cur CURSOR FOR 
    SELECT id FROM ZAPATILLAS WHERE id_categoria = 1;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    WHILE done = 0 DO
        FETCH cur INTO id_zap;
        
        IF NOT done THEN
            SET talla = 38;

            WHILE talla <= 50 DO
                INSERT INTO TALLAS_ZAPATILLA (id_zapatilla, id_talla, stock) 
                VALUES (id_zap, talla, 10);
                SET talla = talla + 1;
            END WHILE;
        END IF;

    END WHILE;

    CLOSE cur;
END;
//
DELIMITER ;

DELIMITER //

CREATE PROCEDURE IF NOT EXISTS insertTallasMujer()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE id_zap INT;
    DECLARE talla INT;

    DECLARE cur CURSOR FOR 
    SELECT id FROM ZAPATILLAS WHERE id_categoria = 2;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    WHILE done = 0 DO
        FETCH cur INTO id_zap;
        
        IF NOT done THEN
            SET talla = 36;

            WHILE talla <= 45 DO
                INSERT INTO TALLAS_ZAPATILLA (id_zapatilla, id_talla, stock) 
                VALUES (id_zap, talla, 10);
                SET talla = talla + 1;
            END WHILE;
        END IF;

    END WHILE;

    CLOSE cur;
END;
//
DELIMITER ;

DELIMITER //

CREATE PROCEDURE IF NOT EXISTS insertTallasNiño()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE id_zap INT;
    DECLARE talla INT;

    DECLARE cur CURSOR FOR 
    SELECT id FROM ZAPATILLAS WHERE id_categoria = 3;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    WHILE done = 0 DO
        FETCH cur INTO id_zap;
        
        IF NOT done THEN
            SET talla = 30;

            WHILE talla <= 36 DO
                INSERT INTO TALLAS_ZAPATILLA (id_zapatilla, id_talla, stock) 
                VALUES (id_zap, talla, 10);
                SET talla = talla + 1;
            END WHILE;
        END IF;

    END WHILE;

    CLOSE cur;
END;
//
DELIMITER ;

DELIMITER //

CREATE TRIGGER IF NOT EXISTS tokenNuevoUsuario
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    DECLARE newToken CHAR(64);
    DECLARE newFechaCreacion DATETIME;
    DECLARE newFechaExpiracion DATETIME;

    SET newToken = SHA2(UUID(), 256);
    SET newFechaCreacion = NOW();
    SET newFechaExpiracion = DATE_ADD(NOW(), INTERVAL 1 HOUR);

    INSERT INTO token_usuario (id_usuario, token, fecha_creacion, fecha_expiracion)
    VALUES (NEW.id, newToken, newFechaCreacion, newFechaExpiracion);
END;
//

DELIMITER ;

INSERT IGNORE INTO USUARIOS(nombre, correo, contraseña, rol)
VALUES ('Administrador', 'admin@bambas.com', '$2a$10$BpvsKyYiwFLmidsv/7.Imuj2KzweYRuQFogk/mo2CU20u8UivMykm', 'admin');

INSERT IGNORE INTO USUARIOS(nombre, apellidos, correo, contraseña, género)
VALUES ('Ismael', 'Alvarado Martín', 'ismael@ejemplo.com', '$2a$10$LuIMr3aW/A4WG.XpxxshMuYzuN7V5X1hBfXzl55XRF6/bF4lIvmWS', 'Hombre');

INSERT IGNORE INTO MARCAS(nombre) 
VALUES ("Nike"), 
       ("Adidas"),
       ("Reebok"),
       ("Puma");

INSERT IGNORE INTO CATEGORIAS(nombre) 
VALUES ("Hombre"), 
       ("Mujer"),
       ("Niño/a"),
       ("Unisex");

INSERT IGNORE INTO TALLAS(id, número) 
VALUES (30, 30), (31, 31), (32, 32), (33, 33), (34, 34), (35, 35), (36, 36), (37, 37), (38, 38), (39, 39), (40, 40), 
       (41, 41), (42, 42), (43, 43), (44, 44), (45, 45), (46, 46), (46, 46), (47, 47), (48, 48), (49, 49), (50, 50);
INSERT IGNORE INTO ZAPATILLAS(nombre, pvp, precio, imagen, fecha_lanzamiento, id_marca, id_categoria, descripción) 
VALUES 
    -- NIKE
    ("SB Dunk Low Big Money Savings", 169.99, 169.99, "https://images.stockx.com/360/Nike-SB-Dunk-Low-Big-Money-Savings/Images/Nike-SB-Dunk-Low-Big-Money-Savings/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2024-04-27', 1, 1, "Diseñadas con un tema inspirado en el dinero y un estilo llamativo."),
    ("SB Dunk Low City Of Love", 139.99, 119.99, "https://images.stockx.com/360/Nike-SB-Dunk-Low-City-Of-Love-Light-Bone/Images/Nike-SB-Dunk-Low-City-Of-Love-Light-Bone/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1708086409&h=384&q=50", '2022-02-19', 1, 1, "Inspiradas en el amor y la ciudad de París."),
    ("Kobe 6 Protro Italian Camo 2024", 269.99, 269.99, "https://images.stockx.com/360/Nike-Kobe-6-Protro-Italian-Camo-2024/Images/Nike-Kobe-6-Protro-Italian-Camo-2024/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2024-04-27', 1, 1, "Un estilo camuflado con un toque italiano."),
    ("Kobe 6 Protro Reverse Grinch", 249.99, 249.99, "https://images.stockx.com/360/Nike-Kobe-6-Protro-Reverse-Grinch/Images/Nike-Kobe-6-Protro-Reverse-Grinch/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2024-04-27', 1, 1, "Un homenaje al personaje del Grinch en la parte superior."),
    ("Dunk Low (azul polar)", 89.99, 89.99, "https://images.stockx.com/360/Nike-Dunk-Low-Polar-Blue/Images/Nike-Dunk-Low-Polar-Blue/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2024-04-27', 1, 1, "Un diseño fresco con tonos azules inspirados en los polos árticos."),
    ("Jordan 1 Retro High OG Chicago Lost and Found", 349.99, 279.99, "https://images.stockx.com/360/Air-Jordan-1-Retro-High-OG-Chicago-Reimagined/Images/Air-Jordan-1-Retro-High-OG-Chicago-Reimagined/Lv2/img01.jpg?fm=avif&auto=compress&w=512&dpr=1&updated_at=1665692308&h=384&q=50", '2020-08-30', 1, 1, "Inspiradas en la ciudad de Chicago y su icónico colorway."),
    ("SB Dunk Low The Powerpuff Girls Bubbles", 299.99, 299.99, "https://images.stockx.com/360/Nike-SB-Dunk-Low-The-Powerpuff-Girls-Bubbles/Images/Nike-SB-Dunk-Low-The-Powerpuff-Girls-Bubbles/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2024-04-27', 1, 2, "Inspiradas en el personaje de Las Supernenas, Burbuja."),
    ("Dunk Low UNC (azul polar)", 79.99, 79.99, "https://images.stockx.com/360/Nike-Dunk-Low-UNC-2021-GS/Images/Nike-Dunk-Low-UNC-2021-GS/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2021-01-08', 1, 3, "Una versión de edición limitada de UNC Dunk Lows."),
    ("Hot Step 2 Drake NOCTA Total Orange", 264.99, 264.99, "https://images.stockx.com/360/Nike-Hot-Step-2-Drake-NOCTA-Total-Orange/Images/Nike-Hot-Step-2-Drake-NOCTA-Total-Orange/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1635269033&h=384&q=50", '2024-04-27', 1, 1, "Parte de la colección NOCTA de Drake, con un vibrante color naranja total."),
    ("Jordan 4 Retro Bred Reimagined", 209.99, 209.99, "https://images.stockx.com/360/Air-Jordan-4-Retro-Bred-Reimagined/Images/Air-Jordan-4-Retro-Bred-Reimagined/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1705954810&h=384&q=50", '2024-04-27', 1, 1, "Una reinterpretación moderna del clásico colorway 'Bred' de las Jordan 4 Retro."),
    ("Dunk Low (marrón barniz)", 119.99, 119.99, "https://images.stockx.com/360/Nike-Dunk-Low-Veneer-2020/Images/Nike-Dunk-Low-Veneer-2020/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1635269033&h=384&q=50", '2024-04-27', 1, 4, "Un diseño elegante en tono marrón barniz para un estilo urbano y sofisticado."),
    ("Jordan 4 Retro Metallic Gold", 199.99, 199.99, "https://images.stockx.com/360/Air-Jordan-4-Retro-Metallic-Gold-Womens/Images/Air-Jordan-4-Retro-Metallic-Gold-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2024-03-22', 1, 2, "Un toque de lujo con detalles en dorado metálico en las Jordan 4 Retro."),
    ("Jordan 1 Low Method of Make Perfect Pink" , 119.99, 119.99, "https://images.stockx.com/360/Air-Jordan-1-Low-Method-of-Make-Perfect-Pink-Womens/Images/Air-Jordan-1-Low-Method-of-Make-Perfect-Pink-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2023-08-04', 1, 2, "Un color rosa suave y perfecto para un look femenino y delicado."),
    ("P-6000 Summit White Pure Platinum", 129.99, 129.99, "https://images.stockx.com/360/Nike-P-6000-Summit-White-Pure-Platinum-Womens/Images/Nike-P-6000-Summit-White-Pure-Platinum-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2021-11-01', 1, 2, "Un diseño limpio y minimalista en blanco y plata para un estilo casual y moderno."),
    -- ADIDAS
    ("Samba OG (blanco y negro)", 99.99, 89.99, "https://images.stockx.com/360/adidas-Samba-OG-Cloud-White-Core-Black/Images/adidas-Samba-OG-Cloud-White-Core-Black/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2022-05-17', 2, 4, "Las clásicas Samba OG con su distintivo estilo en blanco y negro."),
    ("Campus 00s (gris y blanco)", 89.99, 89.99, "https://images.stockx.com/360/adidas-Campus-00s-Grey-White/Images/adidas-Campus-00s-Grey-White/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2024-04-27', 2, 1, "Un diseño retro de los años 2000 en gris y blanco."),
    ("Gazelle Indoor (rosa)", 129.99, 129.99, "https://images.stockx.com/360/adidas-Gazelle-Indoor-Bliss-Pink-Purple-Womens/Images/adidas-Gazelle-Indoor-Bliss-Pink-Purple-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2024-04-27', 2, 2, "Un toque de color con un vibrante tono rosa en las Gazelle Indoor para un look fresco y juvenil."),
    ("Gazelle Bold Green Lucid Pink", 89.99, 89.99, "https://images.stockx.com/360/adidas-Gazelle-Bold-Green-Lucid-Pink-Womens/Images/adidas-Gazelle-Bold-Green-Lucid-Pink-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1646687426&h=384&q=50", '2020-06-19', 2, 2, "Una combinación atrevida de verde y rosa para un estilo llamativo y original."),
    ("ADI2000 X Off White Savanna", 169.99, 169.99, "https://images.stockx.com/360/adidas-ADI2000-Off-White-Savanna-Womens/Images/adidas-ADI2000-Off-White-Savanna-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2022-05-17', 2, 2, "Una colaboración exclusiva entre Adidas y Off-White que presenta un diseño elegante en tonos blancos y cremas, perfecto para un look urbano y sofisticado."),
    ("NMD S1 Blue Fusion", 129.99, 129.99, "https://images.stockx.com/360/adidas-NMD-S1-Blue-Fusion-Womens/Images/adidas-NMD-S1-Blue-Fusion-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2021-03-21', 2, 2, "Estas zapatillas NMD S1 presentan un vibrante tono azul fusionado con detalles modernos para un estilo único y llamativo. Ideal para quienes buscan un equilibrio entre comodidad y estilo."),
    ("Ultra Boost Light White Black Solar Red", 89.99, 89.99, "https://images.stockx.com/360/adidas-Ultra-Boost-23-White-Black-Solar-Red-W/Images/adidas-Ultra-Boost-23-White-Black-Solar-Red-W/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2022-09-15', 2, 2, "Un diseño ligero en blanco y negro con detalles en rojo solar para un estilo deportivo y moderno."),
    -- REEBOOK
    ("Classic Leather Cardi B Rose Gold", 69.99, 69.99, "https://images.stockx.com/360/Reebok-Classic-Leather-Cardi-B-Rose-Gold-W/Images/Reebok-Classic-Leather-Cardi-B-Rose-Gold-W/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2022-07-13', 3, 2, "Un toque de glamour con detalles en oro rosa en las clásicas Classic Leather, en colaboración con Cardi B."),
    ("Club C Geo Mid Nicole McLaughlin White", 119.99, 119.99, "https://images.stockx.com/360/Reebok-Club-C-Geo-Mid-Nicole-McLaughlin/Images/Reebok-Club-C-Geo-Mid-Nicole-McLaughlin/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2019-10-03', 3, 2, "Un diseño geométrico y moderno en blanco para un look urbano y vanguardista."),
    ("Freestyle Hi Power Rangers Pink Ranger", 59.99, 59.99, "https://images.stockx.com/360/Reebok-Freestyle-Hi-Power-Rangers-Pink-Ranger-W/Images/Reebok-Freestyle-Hi-Power-Rangers-Pink-Ranger-W/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2023-06-21', 3, 2, "Inspiradas en el personaje de la Power Ranger Rosa, estas zapatillas ofrecen un estilo retro con un toque de nostalgia."),
    ("Ghost Smasher Ghostbusters (2022)", 299.99, 299.99, "https://images.stockx.com/360/Reebok-Ghost-Smasher-Ghostbusters/Images/Reebok-Ghost-Smasher-Ghostbusters/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2022-08-01', 3, 4, "Una colaboración con la franquicia Ghostbusters, estas zapatillas presentan un diseño único inspirado en el mundo de los cazafantasmas."),
    
    -- PUMA
    ("Palermo Pink Delight Green", 89.99, 89.99, "https://images.stockx.com/360/Puma-Palermo-Pink-Delight-Green-Womens/Images/Puma-Palermo-Pink-Delight-Green-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2023-04-27', 4, 2, "Un estilo fresco y colorido con tonos rosados y verdes para un look primaveral y divertido."),
    ("Creeper Phatty Rihanna Fenty Lavender Alert", 109.99, 109.99, "https://images.stockx.com/360/Puma-Creeper-Phatty-Rihanna-Fenty-Lavender-Alert-Womens/Images/Puma-Creeper-Phatty-Rihanna-Fenty-Lavender-Alert-Womens/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2022-06-28', 4, 2, "Una colaboración con Rihanna Fenty que presenta un diseño llamativo en color lavanda para un estilo único y atrevido."),
    ("Suede One Piece Blackbeard Teech", 79.99, 79.99, "https://images.stockx.com/360/Puma-Suede-One-Piece-Blackbeard/Images/Puma-Suede-One-Piece-Blackbeard/Lv2/img01.jpg?fm=avif&auto=compress&w=576&dpr=1&updated_at=1710948107&h=384&q=50", '2021-07-15', 4, 1, "Inspiradas en la estética del manga y anime One Piece, estas zapatillas presentan un diseño original con detalles temáticos de la serie.");

CALL insertTallasUnisex();
CALL insertTallasHombre();
CALL insertTallasMujer();
CALL insertTallasNiño();