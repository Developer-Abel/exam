/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 100424
 Source Host           : localhost:3306
 Source Schema         : indeplo

 Target Server Type    : MySQL
 Target Server Version : 100424
 File Encoding         : 65001

 Date: 22/02/2023 17:26:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for articulos
-- ----------------------------
DROP TABLE IF EXISTS `articulos`;
CREATE TABLE `articulos`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nombre` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `precio` decimal(8, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of articulos
-- ----------------------------
INSERT INTO `articulos` VALUES (1, '100', 'mesa', 1000.00);
INSERT INTO `articulos` VALUES (2, '101', 'silla', 550.00);
INSERT INTO `articulos` VALUES (3, '102', 'ropero', 3500.00);
INSERT INTO `articulos` VALUES (4, '102', 'cama', 7600.00);
INSERT INTO `articulos` VALUES (5, '103', 'comedor', 13200.00);

-- ----------------------------
-- Table structure for clientes
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `razon_social` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clientes
-- ----------------------------
INSERT INTO `clientes` VALUES (1, 'Cliente 1', 'Cliente 1 SA de CV');
INSERT INTO `clientes` VALUES (2, 'Cliente 2', 'Cliente 2 SA de CV');
INSERT INTO `clientes` VALUES (3, 'Cliente 3', 'Cliente 3 SA de CV');

-- ----------------------------
-- Table structure for facturas
-- ----------------------------
DROP TABLE IF EXISTS `facturas`;
CREATE TABLE `facturas`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pedido_id` int NOT NULL,
  `fecha` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pedido_id`(`pedido_id`) USING BTREE,
  CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of facturas
-- ----------------------------
INSERT INTO `facturas` VALUES (1, '78541', 1, '2023-02-27');
INSERT INTO `facturas` VALUES (2, '78431', 2, '2023-03-02');
INSERT INTO `facturas` VALUES (3, '78432', 1, '2023-03-02');

-- ----------------------------
-- Table structure for facturas_items
-- ----------------------------
DROP TABLE IF EXISTS `facturas_items`;
CREATE TABLE `facturas_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `factura_id` int NOT NULL,
  `articulo_id` int NOT NULL,
  `cantidad` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `factura_id`(`factura_id`) USING BTREE,
  INDEX `articulo_id`(`articulo_id`) USING BTREE,
  CONSTRAINT `facturas_items_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `facturas_items_ibfk_2` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of facturas_items
-- ----------------------------
INSERT INTO `facturas_items` VALUES (1, 1, 2, 3);
INSERT INTO `facturas_items` VALUES (2, 1, 3, 2);
INSERT INTO `facturas_items` VALUES (3, 2, 3, 4);
INSERT INTO `facturas_items` VALUES (4, 2, 1, 2);
INSERT INTO `facturas_items` VALUES (5, 3, 2, 3);

-- ----------------------------
-- Table structure for imagen
-- ----------------------------
DROP TABLE IF EXISTS `imagen`;
CREATE TABLE `imagen`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `img` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of imagen
-- ----------------------------
INSERT INTO `imagen` VALUES (1, 'ok.png');
INSERT INTO `imagen` VALUES (2, 'cocodrilo.png');
INSERT INTO `imagen` VALUES (3, 'pajaro.png');

-- ----------------------------
-- Table structure for pedidos
-- ----------------------------
DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `codigo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cliente_id`(`cliente_id`) USING BTREE,
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pedidos
-- ----------------------------
INSERT INTO `pedidos` VALUES (1, 1, '2525A', '2023-02-22');
INSERT INTO `pedidos` VALUES (2, 2, '2526A', '2023-02-23');
INSERT INTO `pedidos` VALUES (3, 1, '2527A', '2023-02-25');
INSERT INTO `pedidos` VALUES (17, 2, '1004', '2023-02-11');

-- ----------------------------
-- Table structure for pedidos_items
-- ----------------------------
DROP TABLE IF EXISTS `pedidos_items`;
CREATE TABLE `pedidos_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `pedido_id` int NOT NULL,
  `articulo_id` int NOT NULL,
  `cantidad` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pedido_id`(`pedido_id`) USING BTREE,
  INDEX `articulo_id`(`articulo_id`) USING BTREE,
  CONSTRAINT `pedidos_items_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `pedidos_items_ibfk_2` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pedidos_items
-- ----------------------------
INSERT INTO `pedidos_items` VALUES (1, 1, 2, 3);
INSERT INTO `pedidos_items` VALUES (2, 2, 1, 2);
INSERT INTO `pedidos_items` VALUES (3, 2, 3, 4);
INSERT INTO `pedidos_items` VALUES (4, 3, 1, 1);
INSERT INTO `pedidos_items` VALUES (5, 1, 3, 2);
INSERT INTO `pedidos_items` VALUES (6, 17, 2, 2);

-- ----------------------------
-- Table structure for serie
-- ----------------------------
DROP TABLE IF EXISTS `serie`;
CREATE TABLE `serie`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `Codigo` int NULL DEFAULT NULL,
  `img` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 98 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of serie
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
