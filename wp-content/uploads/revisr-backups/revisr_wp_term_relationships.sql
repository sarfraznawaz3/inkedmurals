
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `wp_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_term_relationships` WRITE;
/*!40000 ALTER TABLE `wp_term_relationships` DISABLE KEYS */;
INSERT INTO `wp_term_relationships` VALUES (0,16,0),(0,22,0),(1,1,0),(21,1,0),(26,1,0),(27,1,0),(282,16,0),(312,21,0),(362,16,0),(756,16,0),(800,23,0),(801,23,0),(802,23,0),(803,23,0),(808,21,0),(810,21,0),(811,21,0),(812,21,0),(822,24,0),(823,24,0),(963,21,0),(968,21,0),(1054,16,0),(1054,22,0),(1056,16,0),(1056,22,0),(1060,16,0),(1060,22,0),(1062,16,0),(1062,22,0),(1064,16,0),(1064,22,0),(1066,16,0),(1066,22,0),(1068,16,0),(1068,22,0),(1070,16,0),(1070,22,0),(1072,16,0),(1072,22,0),(1074,16,0),(1074,22,0),(1076,16,0),(1076,22,0),(1078,16,0),(1078,22,0),(1082,16,0),(1082,22,0),(1084,16,0),(1084,22,0),(1086,16,0),(1086,22,0),(1088,16,0),(1088,22,0),(1090,16,0),(1090,22,0),(1092,16,0),(1092,22,0),(1094,16,0),(1094,22,0),(1097,16,0),(1097,22,0),(1099,16,0),(1099,22,0),(1101,16,0),(1101,22,0),(1103,16,0),(1103,22,0),(1105,16,0),(1105,22,0),(1107,16,0),(1107,22,0),(1109,16,0),(1109,22,0),(1111,16,0),(1111,22,0),(1113,16,0),(1113,22,0),(1115,16,0),(1115,22,0),(1117,16,0),(1117,22,0),(1119,16,0),(1119,22,0),(1121,16,0),(1121,22,0),(1123,16,0),(1123,22,0),(1125,16,0),(1125,22,0),(1127,16,0),(1127,22,0),(1129,16,0),(1129,22,0),(1131,16,0),(1131,22,0),(1133,16,0),(1133,22,0),(1135,16,0),(1135,22,0),(1137,16,0),(1137,22,0),(1139,16,0),(1139,22,0),(1141,16,0),(1141,22,0),(1143,16,0),(1143,22,0),(1145,16,0),(1145,22,0),(1147,16,0),(1147,22,0),(1149,16,0),(1149,22,0),(1151,16,0),(1151,22,0),(1153,16,0),(1153,22,0),(1155,16,0),(1155,22,0),(1157,16,0),(1157,22,0),(1159,16,0),(1159,22,0),(1161,16,0),(1161,22,0),(1163,16,0),(1163,22,0),(1165,16,0),(1165,22,0),(1167,16,0),(1167,22,0),(1169,16,0),(1169,22,0),(1171,16,0),(1171,22,0),(1173,16,0),(1173,22,0),(1175,16,0),(1175,22,0),(1177,16,0),(1177,22,0),(1179,16,0),(1179,22,0),(1181,16,0),(1181,22,0),(1183,16,0),(1183,22,0),(1185,16,0),(1185,22,0),(1187,16,0),(1187,22,0),(1189,16,0),(1189,22,0),(1191,16,0),(1191,22,0),(1193,16,0),(1193,22,0),(1195,16,0),(1195,22,0),(1197,16,0),(1197,22,0),(1199,16,0),(1199,22,0),(1201,16,0),(1201,22,0),(1203,16,0),(1203,22,0),(1205,16,0),(1205,22,0),(1207,16,0),(1207,22,0),(1209,16,0),(1209,22,0),(1211,16,0),(1211,22,0),(1213,16,0),(1213,22,0),(1215,16,0),(1215,22,0),(1217,16,0),(1217,22,0),(1219,16,0),(1219,22,0),(1221,16,0),(1221,22,0),(1223,16,0),(1223,22,0),(1225,16,0),(1225,22,0),(1227,16,0),(1227,22,0),(1229,16,0),(1229,22,0),(1231,16,0),(1231,22,0),(1233,16,0),(1233,22,0),(1235,16,0),(1235,22,0),(1237,16,0),(1237,22,0),(1239,16,0),(1239,22,0),(1241,16,0),(1241,22,0),(1243,16,0),(1243,22,0),(1245,16,0),(1245,22,0),(1247,16,0),(1247,22,0),(1249,16,0),(1249,22,0),(1251,16,0),(1251,22,0),(1253,16,0),(1253,22,0),(1255,16,0),(1255,22,0),(1257,16,0),(1257,22,0),(1259,16,0),(1259,22,0),(1261,16,0),(1261,22,0),(1263,16,0),(1263,22,0),(1265,16,0),(1265,22,0),(1267,16,0),(1267,22,0),(1269,16,0),(1269,22,0),(1271,16,0),(1271,22,0),(1273,16,0),(1273,22,0),(1275,16,0),(1275,22,0),(1277,16,0),(1277,22,0),(1279,16,0),(1279,22,0),(1281,16,0),(1281,22,0),(1283,16,0),(1283,22,0),(1285,16,0),(1285,22,0),(1287,16,0),(1287,22,0),(1289,16,0),(1289,22,0),(1291,16,0),(1291,22,0),(1293,16,0),(1293,22,0),(1295,16,0),(1295,22,0),(1297,16,0),(1297,22,0),(1299,16,0),(1299,22,0),(1301,16,0),(1301,22,0),(1303,16,0),(1303,22,0),(1305,16,0),(1305,22,0),(1307,16,0),(1307,22,0),(1309,16,0),(1309,22,0),(1311,16,0),(1311,22,0),(1313,16,0),(1313,22,0),(1315,16,0),(1315,22,0),(1317,16,0),(1317,22,0),(1319,16,0),(1319,22,0),(1321,16,0),(1321,22,0),(1323,16,0),(1323,22,0),(1325,16,0),(1325,22,0),(1327,16,0),(1327,22,0),(1329,16,0),(1329,22,0),(1331,16,0),(1331,22,0),(1333,16,0),(1333,22,0),(1335,16,0),(1335,22,0),(1337,16,0),(1337,22,0),(1339,16,0),(1339,22,0),(1341,16,0),(1341,22,0),(1343,16,0),(1343,22,0),(1345,16,0),(1345,22,0),(1347,16,0),(1347,22,0),(1349,16,0),(1349,22,0),(1351,16,0),(1351,22,0),(1353,16,0),(1353,22,0),(1355,16,0),(1355,22,0),(1357,16,0),(1357,22,0),(1359,16,0),(1359,22,0),(1361,16,0),(1361,22,0),(1363,16,0),(1363,22,0),(1365,16,0),(1365,22,0),(1367,16,0),(1367,22,0),(1369,16,0),(1369,22,0),(1371,16,0),(1371,22,0),(1373,16,0),(1373,22,0),(1375,16,0),(1375,22,0),(1377,16,0),(1377,22,0),(1379,16,0),(1379,22,0),(1381,16,0),(1381,22,0),(1383,16,0),(1383,22,0),(1385,16,0),(1385,22,0),(1387,16,0),(1387,22,0),(1389,16,0),(1389,22,0),(1391,16,0),(1391,22,0),(1393,16,0),(1393,22,0),(1395,16,0),(1395,22,0),(1397,16,0),(1397,22,0),(1399,16,0),(1399,22,0),(1401,16,0),(1401,22,0),(1403,16,0),(1403,22,0),(1405,16,0),(1405,22,0),(1407,16,0),(1407,22,0),(1409,16,0),(1409,22,0),(1411,16,0),(1411,22,0),(1413,16,0),(1413,22,0),(1415,16,0),(1415,22,0),(1417,16,0),(1417,22,0),(1419,16,0),(1419,22,0),(1421,16,0),(1421,22,0),(1423,16,0),(1423,22,0),(1425,16,0),(1425,22,0),(1427,16,0),(1427,22,0),(1429,16,0),(1429,22,0),(1431,16,0),(1431,22,0),(1434,16,0),(1434,22,0),(1436,16,0),(1436,22,0),(1438,16,0),(1438,22,0),(1440,16,0),(1440,22,0),(1442,16,0),(1442,22,0),(1444,16,0),(1444,22,0),(1446,16,0),(1446,22,0),(1448,16,0),(1448,22,0),(1450,16,0),(1450,22,0),(1452,16,0),(1452,22,0),(1454,16,0),(1454,22,0),(1456,16,0),(1456,22,0),(1458,16,0),(1458,22,0),(1460,16,0),(1460,22,0),(1462,16,0),(1462,22,0),(1464,16,0),(1464,22,0),(1466,16,0),(1466,22,0),(1468,16,0),(1468,22,0),(1470,16,0),(1470,22,0),(1472,16,0),(1472,22,0),(1474,16,0),(1474,22,0),(1476,16,0),(1476,22,0),(1478,16,0),(1478,22,0),(1480,16,0),(1480,22,0),(1482,16,0),(1482,22,0),(1484,16,0),(1484,22,0),(1486,16,0),(1486,22,0),(1488,16,0),(1488,22,0),(1490,16,0),(1490,22,0),(1492,16,0),(1492,22,0),(1494,16,0),(1494,22,0),(1496,16,0),(1496,22,0),(1498,16,0),(1498,22,0),(1500,16,0),(1500,22,0),(1502,16,0),(1502,22,0),(1504,16,0),(1504,22,0),(1506,16,0),(1506,22,0),(1508,16,0),(1508,22,0),(1510,16,0),(1510,22,0),(1512,16,0),(1512,22,0),(1514,16,0),(1514,22,0),(1516,16,0),(1516,22,0),(1518,16,0),(1518,22,0),(1520,16,0),(1520,22,0),(1522,16,0),(1522,22,0),(1524,16,0),(1524,22,0),(1527,16,0),(1527,22,0),(1529,16,0),(1529,22,0),(1531,16,0),(1531,22,0),(1533,16,0),(1533,22,0),(1535,16,0),(1535,22,0),(1537,16,0),(1537,22,0),(1539,16,0),(1539,22,0),(1541,16,0),(1541,22,0),(1543,16,0),(1543,22,0),(1545,16,0),(1545,22,0),(1547,16,0),(1547,22,0),(1549,16,0),(1549,22,0),(1551,16,0),(1551,22,0),(1553,16,0),(1553,22,0),(1555,16,0),(1555,22,0),(1557,16,0),(1557,22,0),(1559,16,0),(1559,22,0),(1561,16,0),(1561,22,0),(1563,16,0),(1563,22,0),(1565,16,0),(1565,22,0),(1567,16,0),(1567,22,0),(1569,16,0),(1569,22,0),(1578,21,0),(1579,16,0),(1579,22,0),(1581,16,0),(1581,22,0),(1583,16,0),(1583,22,0),(1585,16,0),(1585,22,0),(1587,16,0),(1587,22,0),(1589,16,0),(1589,22,0),(1591,16,0),(1591,22,0),(1593,16,0),(1593,22,0),(1595,16,0),(1595,22,0),(1597,16,0),(1597,22,0),(1599,16,0),(1599,22,0),(1601,16,0),(1601,22,0),(1603,16,0),(1603,22,0),(1605,16,0),(1605,22,0),(1607,16,0),(1607,22,0),(1609,16,0),(1609,22,0),(1611,16,0),(1611,22,0),(1613,16,0),(1613,22,0),(1615,16,0),(1615,22,0),(1617,16,0),(1617,22,0),(1619,16,0),(1619,22,0),(1621,16,0),(1621,22,0),(1623,16,0),(1623,22,0),(1625,16,0),(1625,22,0),(1627,16,0),(1627,22,0),(1629,16,0),(1629,22,0),(1631,16,0),(1631,22,0),(1633,16,0),(1633,22,0),(1635,16,0),(1635,22,0),(1637,16,0),(1637,22,0),(1639,16,0),(1639,22,0),(1641,16,0),(1641,22,0),(1643,16,0),(1643,22,0),(1645,16,0),(1645,22,0),(1647,16,0),(1647,22,0),(1649,16,0),(1649,22,0),(1651,16,0),(1651,22,0),(1653,16,0),(1653,22,0),(1655,16,0),(1655,22,0),(1657,16,0),(1657,22,0),(1659,16,0),(1659,22,0),(1661,16,0),(1661,22,0),(1663,16,0),(1663,22,0),(1665,16,0),(1665,22,0),(1667,16,0),(1667,22,0),(1669,16,0),(1669,22,0),(1671,16,0),(1671,22,0),(1673,16,0),(1673,22,0),(1675,16,0),(1675,22,0),(1677,16,0),(1677,22,0),(1679,16,0),(1679,22,0),(1681,16,0),(1681,22,0),(1683,16,0),(1683,22,0),(1685,16,0),(1685,22,0),(1687,16,0),(1687,22,0),(1689,16,0),(1689,22,0),(1691,16,0),(1691,22,0),(1693,16,0),(1693,22,0),(1695,16,0),(1695,22,0),(1697,16,0),(1697,22,0),(1699,16,0),(1699,22,0),(1701,16,0),(1701,22,0),(1703,16,0),(1703,22,0),(1705,16,0),(1705,22,0),(1707,16,0),(1707,22,0),(1709,16,0),(1709,22,0),(1711,16,0),(1711,22,0),(1713,16,0),(1713,22,0),(1715,16,0),(1715,22,0),(1717,16,0),(1717,22,0),(1719,16,0),(1719,22,0),(1721,16,0),(1721,22,0),(1723,16,0),(1723,22,0),(1725,16,0),(1725,22,0),(1727,16,0),(1727,22,0),(1729,16,0),(1729,22,0),(1731,16,0),(1731,22,0),(1733,16,0),(1733,22,0),(1735,16,0),(1735,22,0),(1737,16,0),(1737,22,0),(1739,16,0),(1739,22,0),(1741,16,0),(1741,22,0),(1743,16,0),(1743,22,0),(1745,16,0),(1745,22,0),(1747,16,0),(1747,22,0),(1749,16,0),(1749,22,0),(1751,16,0),(1751,22,0),(1753,16,0),(1753,22,0),(1754,16,0),(1754,22,0),(1757,16,0),(1757,22,0),(1759,16,0),(1759,22,0),(1761,16,0),(1761,22,0),(1763,16,0),(1763,22,0),(1765,16,0),(1765,22,0),(1767,16,0),(1767,22,0),(1769,16,0),(1769,22,0),(1771,16,0),(1771,22,0),(1773,16,0),(1773,22,0),(1775,16,0),(1775,22,0),(1777,16,0),(1777,22,0),(1779,16,0),(1779,22,0),(1781,16,0),(1781,22,0),(1783,16,0),(1783,22,0),(1785,16,0),(1785,22,0),(1787,16,0),(1787,22,0),(1789,16,0),(1789,22,0),(1791,16,0),(1791,22,0),(1793,16,0),(1793,22,0),(1795,16,0),(1795,22,0),(1797,16,0),(1797,22,0),(1799,16,0),(1799,22,0),(1801,16,0),(1801,22,0),(1803,16,0),(1803,22,0),(1805,16,0),(1805,22,0),(1807,16,0),(1807,22,0),(1809,16,0),(1809,22,0),(1811,16,0),(1811,22,0),(1813,16,0),(1813,22,0),(1815,16,0),(1815,22,0),(1817,16,0),(1817,22,0),(1819,16,0),(1819,22,0),(1821,16,0),(1821,22,0),(1823,16,0),(1823,22,0),(1825,16,0),(1825,22,0),(1827,16,0),(1827,22,0),(1829,16,0),(1829,22,0),(1831,16,0),(1831,22,0),(1833,16,0),(1833,22,0),(1835,16,0),(1835,22,0),(1837,16,0),(1837,22,0),(1839,16,0),(1839,22,0),(1841,16,0),(1841,22,0),(1843,16,0),(1843,22,0),(1845,16,0),(1845,22,0),(1847,16,0),(1847,22,0),(1849,16,0),(1849,22,0),(1851,16,0),(1851,22,0),(1853,16,0),(1853,22,0),(1855,16,0),(1855,22,0),(1857,16,0),(1857,22,0),(1859,16,0),(1859,22,0),(1861,16,0),(1861,22,0),(1863,16,0),(1863,22,0),(1865,16,0),(1865,22,0),(1867,16,0),(1867,22,0),(1869,16,0),(1869,22,0),(1871,16,0),(1871,22,0),(1873,16,0),(1873,22,0),(1875,16,0),(1875,22,0),(1877,16,0),(1877,22,0),(1879,16,0),(1879,22,0),(1881,16,0),(1881,22,0),(1883,16,0),(1883,22,0),(1885,16,0),(1885,22,0),(1887,16,0),(1887,22,0),(1889,16,0),(1889,22,0),(1891,16,0),(1891,22,0),(1893,16,0),(1893,22,0),(1895,16,0),(1895,22,0),(1897,16,0),(1897,22,0),(1899,16,0),(1899,22,0),(1901,16,0),(1901,22,0),(1903,16,0),(1903,22,0),(1905,16,0),(1905,22,0),(1907,16,0),(1907,22,0),(1909,16,0),(1909,22,0),(1911,16,0),(1911,22,0),(1913,16,0),(1913,22,0),(1915,16,0),(1915,22,0),(1917,16,0),(1917,22,0),(1919,16,0),(1919,22,0),(1921,16,0),(1921,22,0),(1923,16,0),(1923,22,0),(1925,16,0),(1925,22,0),(1927,16,0),(1927,22,0),(1929,16,0),(1929,22,0),(1931,16,0),(1931,22,0),(1933,16,0),(1933,22,0),(1935,16,0),(1935,22,0),(1937,16,0),(1937,22,0),(1939,16,0),(1939,22,0),(1941,16,0),(1941,22,0),(1943,16,0),(1943,22,0),(1945,16,0),(1945,22,0),(1947,16,0),(1947,22,0),(1949,16,0),(1949,22,0),(1951,16,0),(1951,22,0),(1953,16,0),(1953,22,0),(1955,16,0),(1955,22,0),(1957,16,0),(1957,22,0),(1959,16,0),(1959,22,0),(1961,16,0),(1961,22,0),(1963,16,0),(1963,22,0),(1965,16,0),(1965,22,0),(1967,16,0),(1967,22,0),(1969,16,0),(1969,22,0),(1971,16,0),(1971,22,0),(1973,16,0),(1973,22,0),(1975,16,0),(1975,22,0),(1977,16,0),(1977,22,0),(1979,16,0),(1979,22,0),(1981,16,0),(1981,22,0),(1983,16,0),(1983,22,0),(1985,16,0),(1985,22,0),(1987,16,0),(1987,22,0),(1989,16,0),(1989,22,0),(1991,16,0),(1991,22,0),(1993,16,0),(1993,22,0),(1995,16,0),(1995,22,0),(1997,16,0),(1997,22,0),(1999,16,0),(1999,22,0),(2001,16,0),(2001,22,0),(2003,16,0),(2003,22,0),(2005,16,0),(2005,22,0),(2007,16,0),(2007,22,0),(2009,16,0),(2009,22,0),(2011,16,0),(2011,22,0),(2013,16,0),(2013,22,0),(2015,16,0),(2015,22,0),(2017,16,0),(2017,22,0),(2019,16,0),(2019,22,0),(2021,16,0),(2021,22,0),(2023,16,0),(2023,22,0),(2025,16,0),(2025,22,0),(2027,16,0),(2027,22,0),(2029,16,0),(2029,22,0),(2031,16,0),(2031,22,0),(2033,16,0),(2033,22,0),(2035,16,0),(2035,22,0),(2037,16,0),(2037,22,0),(2039,16,0),(2039,22,0),(2041,16,0),(2041,22,0),(2043,16,0),(2043,22,0),(2045,16,0),(2045,22,0),(2047,16,0),(2047,22,0),(2049,16,0),(2049,22,0),(2051,16,0),(2051,22,0),(2053,16,0),(2053,22,0),(2055,16,0),(2055,22,0),(2057,16,0),(2057,22,0),(2059,16,0),(2059,22,0),(2061,16,0),(2061,22,0),(2063,16,0),(2063,22,0),(2065,16,0),(2065,22,0),(2067,16,0),(2067,22,0),(2069,16,0),(2069,22,0),(2071,16,0),(2071,22,0),(2073,16,0),(2073,22,0),(2075,16,0),(2075,22,0),(2077,16,0),(2077,22,0),(2079,16,0),(2079,22,0),(2081,16,0),(2081,22,0),(2083,16,0),(2083,22,0),(2085,16,0),(2085,22,0),(2087,16,0),(2087,22,0),(2089,16,0),(2089,22,0),(2091,16,0),(2091,22,0),(2093,16,0),(2093,22,0),(2095,16,0),(2095,22,0),(2097,16,0),(2097,22,0),(2099,16,0),(2099,22,0),(2101,16,0),(2101,22,0),(2103,16,0),(2103,22,0),(2105,16,0),(2105,22,0),(2107,16,0),(2107,22,0),(2109,16,0),(2109,22,0),(2111,16,0),(2111,22,0),(2113,16,0),(2113,22,0),(2115,16,0),(2115,22,0),(2117,16,0),(2117,22,0),(2119,16,0),(2119,22,0),(2121,16,0),(2121,22,0),(2123,16,0),(2123,22,0),(2125,16,0),(2125,22,0),(2127,16,0),(2127,22,0),(2129,16,0),(2129,22,0),(2131,16,0),(2131,22,0),(2133,16,0),(2133,22,0),(2135,16,0),(2135,22,0),(2137,16,0),(2137,22,0),(2139,16,0),(2139,22,0),(2141,16,0),(2141,22,0),(2143,16,0),(2143,22,0),(2145,16,0),(2145,22,0),(2147,16,0),(2147,22,0),(2149,16,0),(2149,22,0),(2151,16,0),(2151,22,0),(2154,16,0),(2154,22,0),(2156,16,0),(2156,22,0),(2158,16,0),(2158,22,0),(2160,16,0),(2160,22,0),(2162,16,0),(2162,22,0),(2164,16,0),(2164,22,0),(2166,16,0),(2166,22,0),(2168,16,0),(2168,22,0),(2170,16,0),(2170,22,0),(2172,16,0),(2172,22,0),(2174,16,0),(2174,22,0),(2176,16,0),(2176,22,0),(2178,16,0),(2178,22,0),(2180,16,0),(2180,22,0),(2182,16,0),(2182,22,0),(2184,16,0),(2184,22,0),(2186,16,0),(2186,22,0),(2188,16,0),(2188,22,0),(2190,16,0),(2190,22,0),(2192,16,0),(2192,22,0),(2194,16,0),(2194,22,0),(2196,16,0),(2196,22,0),(2198,16,0),(2198,22,0),(2200,16,0),(2200,22,0),(2202,16,0),(2202,22,0),(2204,16,0),(2204,22,0),(2206,16,0),(2206,22,0),(2208,16,0),(2208,22,0),(2210,16,0),(2210,22,0),(2211,16,0),(2211,22,0),(2214,16,0),(2214,22,0),(2216,16,0),(2216,22,0),(2218,16,0),(2218,22,0),(2220,16,0),(2220,22,0),(2222,16,0),(2222,22,0),(2224,16,0),(2224,22,0),(2226,16,0),(2226,22,0),(2228,16,0),(2228,22,0),(2230,16,0),(2230,22,0),(2232,16,0),(2232,22,0),(2234,16,0),(2234,22,0),(2236,16,0),(2236,22,0),(2238,16,0),(2238,22,0),(2240,16,0),(2240,22,0),(2242,16,0),(2242,22,0),(2244,16,0),(2244,22,0),(2246,16,0),(2246,22,0),(2248,16,0),(2248,22,0),(2250,16,0),(2250,22,0),(2252,16,0),(2252,22,0),(2254,16,0),(2254,22,0),(2256,16,0),(2256,22,0),(2258,16,0),(2258,22,0),(2260,16,0),(2260,22,0),(2262,16,0),(2262,22,0),(2264,16,0),(2264,22,0),(2266,16,0),(2266,22,0),(2268,16,0),(2268,22,0),(2270,16,0),(2270,22,0),(2272,16,0),(2272,22,0),(2274,16,0),(2274,22,0),(2276,16,0),(2276,22,0),(2278,16,0),(2278,22,0),(2280,16,0),(2280,22,0),(2282,16,0),(2282,22,0),(2284,16,0),(2284,22,0),(2286,16,0),(2286,22,0),(2288,16,0),(2288,22,0),(2290,16,0),(2290,22,0),(2292,16,0),(2292,22,0),(2294,16,0),(2294,22,0),(2296,16,0),(2296,22,0),(2298,16,0),(2298,22,0),(2300,16,0),(2300,22,0),(2302,16,0),(2302,22,0),(2304,16,0),(2304,22,0),(2306,16,0),(2306,22,0),(2308,16,0),(2308,22,0),(2310,16,0),(2310,22,0),(2312,16,0),(2312,22,0),(2314,16,0),(2314,22,0),(2316,16,0),(2316,22,0),(2318,16,0),(2318,22,0),(2320,16,0),(2320,22,0),(2322,16,0),(2322,22,0),(2324,16,0),(2324,22,0),(2326,16,0),(2326,22,0),(2328,16,0),(2328,22,0),(2330,16,0),(2330,22,0),(2332,16,0),(2332,22,0),(2334,16,0),(2334,22,0),(2336,16,0),(2336,22,0),(2338,16,0),(2338,22,0),(2340,16,0),(2340,22,0),(2342,16,0),(2342,22,0),(2344,16,0),(2344,22,0),(2346,16,0),(2346,22,0),(2348,16,0),(2348,22,0),(2350,16,0),(2350,22,0),(2352,16,0),(2352,22,0),(2354,16,0),(2354,22,0),(2356,16,0),(2356,22,0),(2358,16,0),(2358,22,0),(2360,16,0),(2360,22,0),(2362,16,0),(2362,22,0),(2364,16,0),(2364,22,0),(2366,16,0),(2366,22,0),(2368,16,0),(2368,22,0),(2370,16,0),(2370,22,0),(2372,16,0),(2372,22,0),(2374,16,0),(2374,22,0),(2376,16,0),(2376,22,0),(2378,16,0),(2378,22,0),(2380,16,0),(2380,22,0),(2382,16,0),(2382,22,0),(2384,16,0),(2384,22,0),(2386,16,0),(2386,22,0),(2388,16,0),(2388,22,0),(2390,16,0),(2390,22,0),(2392,16,0),(2392,22,0),(2394,16,0),(2394,22,0),(2396,16,0),(2396,22,0),(2398,16,0),(2398,22,0),(2400,16,0),(2400,22,0),(2402,16,0),(2402,22,0),(2404,16,0),(2404,22,0),(2406,16,0),(2406,22,0),(2408,16,0),(2408,22,0),(2410,16,0),(2410,22,0),(2412,16,0),(2412,22,0),(2414,16,0),(2414,22,0),(2416,16,0),(2416,22,0),(2418,16,0),(2418,22,0),(2420,16,0),(2420,22,0),(2422,16,0),(2422,22,0),(2424,16,0),(2424,22,0),(2426,16,0),(2426,22,0),(2428,16,0),(2428,22,0),(2430,16,0),(2430,22,0),(2432,16,0),(2432,22,0),(2434,16,0),(2434,22,0),(2436,16,0),(2436,22,0),(2438,16,0),(2438,22,0),(2440,16,0),(2440,22,0),(2442,16,0),(2442,22,0),(2444,16,0),(2444,22,0),(2446,16,0),(2446,22,0),(2448,16,0),(2448,22,0),(2450,16,0),(2450,22,0),(2452,16,0),(2452,22,0),(2454,16,0),(2454,22,0),(2456,16,0),(2456,22,0),(2458,16,0),(2458,22,0),(2460,16,0),(2460,22,0),(2462,16,0),(2462,22,0),(2464,16,0),(2464,22,0),(2466,16,0),(2466,22,0),(2468,16,0),(2468,22,0),(2470,16,0),(2470,22,0),(2472,16,0),(2472,22,0),(2474,16,0),(2474,22,0),(2476,16,0),(2476,22,0),(2478,16,0),(2478,22,0),(2480,16,0),(2480,22,0),(2481,16,0),(2481,22,0),(2482,16,0),(2482,22,0),(2483,16,0),(2483,22,0),(2484,16,0),(2484,22,0),(2485,16,0),(2485,22,0),(2486,16,0),(2486,22,0),(2487,16,0),(2487,22,0),(2488,16,0),(2488,22,0),(2489,16,0),(2489,22,0),(2490,16,0),(2490,22,0),(2491,16,0),(2491,22,0),(2492,16,0),(2492,22,0),(2493,16,0),(2493,22,0),(2494,16,0),(2494,22,0),(2495,16,0),(2495,22,0),(2496,16,0),(2496,22,0),(2497,16,0),(2497,22,0),(2498,16,0),(2498,22,0),(2499,16,0),(2499,22,0),(2500,16,0),(2500,22,0),(2501,16,0),(2501,22,0),(2502,16,0),(2502,22,0),(2503,16,0),(2503,22,0),(2504,16,0),(2504,22,0),(2505,16,0),(2505,22,0),(2506,16,0),(2506,22,0),(2507,16,0),(2507,22,0),(2508,16,0),(2508,22,0),(2509,16,0),(2509,22,0),(2510,16,0),(2510,22,0),(2511,16,0),(2511,22,0),(2512,16,0),(2512,22,0),(2513,16,0),(2513,22,0),(2514,16,0),(2514,22,0),(2515,16,0),(2515,22,0),(2516,16,0),(2516,22,0),(2517,16,0),(2517,22,0),(2518,16,0),(2518,22,0),(2519,16,0),(2519,22,0),(2520,16,0),(2520,22,0),(2521,16,0),(2521,22,0),(2522,16,0),(2522,22,0),(2523,16,0),(2523,22,0),(2524,16,0),(2524,22,0),(2525,16,0),(2525,22,0),(2526,16,0),(2526,22,0),(2527,16,0),(2527,22,0),(2528,16,0),(2528,22,0),(2529,16,0),(2529,22,0),(2530,16,0),(2530,22,0),(2531,16,0),(2531,22,0),(2532,16,0),(2532,22,0),(2533,16,0),(2533,22,0),(2534,16,0),(2534,22,0),(2535,16,0),(2535,22,0),(2536,16,0),(2536,22,0),(2537,16,0),(2537,22,0),(2538,16,0),(2538,22,0),(2539,16,0),(2539,22,0),(2540,16,0),(2540,22,0),(2541,16,0),(2541,22,0),(2542,16,0),(2542,22,0),(2543,16,0),(2543,22,0),(2544,16,0),(2544,22,0),(2545,16,0),(2545,22,0),(2546,16,0),(2546,22,0),(2547,16,0),(2547,22,0),(2548,16,0),(2548,22,0),(2549,16,0),(2549,22,0),(2550,16,0),(2550,22,0),(2551,16,0),(2551,22,0),(2552,16,0),(2552,22,0),(2553,16,0),(2553,22,0),(2554,16,0),(2554,22,0),(2555,16,0),(2555,22,0),(2556,16,0),(2556,22,0),(2557,16,0),(2557,22,0),(2558,16,0),(2558,22,0),(2559,16,0),(2559,22,0),(2560,16,0),(2560,22,0),(2561,16,0),(2561,22,0),(2562,16,0),(2562,22,0),(2563,16,0),(2563,22,0),(2564,16,0),(2564,22,0),(2565,16,0),(2565,22,0),(2566,16,0),(2566,22,0),(2567,16,0),(2567,22,0),(2568,16,0),(2568,22,0),(2569,16,0),(2569,22,0),(2570,16,0),(2570,22,0),(2571,16,0),(2571,22,0),(2572,16,0),(2572,22,0),(2573,16,0),(2573,22,0),(2574,16,0),(2574,22,0),(2575,16,0),(2575,22,0),(2576,16,0),(2576,22,0),(2577,16,0),(2577,22,0),(2578,16,0),(2578,22,0),(2579,16,0),(2579,22,0),(2580,16,0),(2580,22,0),(2581,16,0),(2581,22,0),(2582,16,0),(2582,22,0),(2583,16,0),(2583,22,0),(2584,16,0),(2584,22,0),(2585,16,0),(2585,22,0),(2586,16,0),(2586,22,0),(2587,16,0),(2587,22,0),(2588,16,0),(2588,22,0),(2589,16,0),(2589,22,0),(2590,16,0),(2590,22,0),(2591,16,0),(2591,22,0),(2592,16,0),(2592,22,0),(2593,16,0),(2593,22,0),(2594,16,0),(2594,22,0),(2595,16,0),(2595,22,0),(2597,16,0),(2597,22,0),(2598,16,0),(2598,22,0),(2599,16,0),(2599,22,0),(2600,16,0),(2600,22,0),(2601,16,0),(2601,22,0),(2602,16,0),(2602,22,0),(2603,16,0),(2603,22,0),(2604,16,0),(2604,22,0),(2605,16,0),(2605,22,0),(2606,16,0),(2606,22,0),(2607,16,0),(2607,22,0),(2608,16,0),(2608,22,0),(2609,16,0),(2609,22,0),(2610,16,0),(2610,22,0),(2611,16,0),(2611,22,0),(2612,16,0),(2612,22,0),(2613,16,0),(2613,22,0),(2614,16,0),(2614,22,0),(2615,16,0),(2615,22,0),(2616,16,0),(2616,22,0),(2617,16,0),(2617,22,0),(2618,16,0),(2618,22,0),(2619,16,0),(2619,22,0),(2620,16,0),(2620,22,0),(2621,16,0),(2621,22,0),(2622,16,0),(2622,22,0),(2623,16,0),(2623,22,0),(2624,16,0),(2624,22,0),(2625,16,0),(2625,22,0),(2626,16,0),(2626,22,0),(2627,16,0),(2627,22,0),(2628,16,0),(2628,22,0),(2629,16,0),(2629,22,0),(2630,16,0),(2630,22,0),(2631,16,0),(2631,22,0),(2632,16,0),(2632,22,0),(2633,16,0),(2633,22,0),(2634,16,0),(2634,22,0),(2635,16,0),(2635,22,0),(2636,16,0),(2636,22,0),(2637,16,0),(2637,22,0),(2638,16,0),(2638,22,0),(2639,16,0),(2639,22,0),(2640,16,0),(2640,22,0),(2641,16,0),(2641,22,0),(2642,16,0),(2642,22,0),(2643,16,0),(2643,22,0),(2644,16,0),(2644,22,0),(2645,16,0),(2645,22,0),(2646,16,0),(2646,22,0),(2647,16,0),(2647,22,0),(2648,16,0),(2648,22,0),(2649,16,0),(2649,22,0),(2650,16,0),(2650,22,0),(2651,16,0),(2651,22,0),(2652,16,0),(2652,22,0),(2653,16,0),(2653,22,0),(2654,16,0),(2654,22,0),(2655,16,0),(2655,22,0),(2656,16,0),(2656,22,0),(2657,16,0),(2657,22,0),(2658,16,0),(2658,22,0),(2659,16,0),(2659,22,0),(2660,16,0),(2660,22,0),(2661,16,0),(2661,22,0),(2662,16,0),(2662,22,0),(2663,16,0),(2663,22,0),(2664,16,0),(2664,22,0),(2665,16,0),(2665,22,0),(2666,16,0),(2666,22,0),(2667,16,0),(2667,22,0),(2668,16,0),(2668,22,0),(2669,16,0),(2669,22,0),(2670,16,0),(2670,22,0),(2671,16,0),(2671,22,0),(2672,16,0),(2672,22,0),(2673,16,0),(2673,22,0),(2674,16,0),(2674,22,0),(2675,16,0),(2675,22,0),(2676,16,0),(2676,22,0),(2677,16,0),(2677,22,0),(2678,16,0),(2678,22,0),(2679,16,0),(2679,22,0),(2680,16,0),(2680,22,0),(2681,16,0),(2681,22,0),(2682,16,0),(2682,22,0),(2683,16,0),(2683,22,0),(2684,16,0),(2684,22,0),(2685,16,0),(2685,22,0),(2686,16,0),(2686,22,0),(2687,16,0),(2687,22,0),(2688,16,0),(2688,22,0),(2689,16,0),(2689,22,0),(2690,16,0),(2690,22,0),(2691,16,0),(2691,22,0),(2692,16,0),(2692,22,0),(2693,16,0),(2693,22,0),(2694,16,0),(2694,22,0),(2695,16,0),(2695,22,0),(2696,16,0),(2696,22,0),(2697,16,0),(2697,22,0),(2698,16,0),(2698,22,0),(2699,16,0),(2699,22,0),(2700,16,0),(2700,22,0),(2701,16,0),(2701,22,0),(2702,16,0),(2702,22,0),(2703,16,0),(2703,22,0),(2704,16,0),(2704,22,0),(2705,16,0),(2705,22,0),(2706,16,0),(2706,22,0),(2707,16,0),(2707,22,0),(2708,16,0),(2708,22,0),(2709,16,0),(2709,22,0),(2710,16,0),(2710,22,0),(2711,16,0),(2711,22,0),(2712,16,0),(2712,22,0),(2713,16,0),(2713,22,0),(2714,16,0),(2714,22,0),(2715,16,0),(2715,22,0),(2716,16,0),(2716,22,0),(2717,16,0),(2717,22,0),(2718,16,0),(2718,22,0),(2719,16,0),(2719,22,0),(2720,16,0),(2720,22,0),(2721,16,0),(2721,22,0),(2722,16,0),(2722,22,0),(2723,16,0),(2723,22,0),(2724,16,0),(2724,22,0),(2725,16,0),(2725,22,0),(2726,16,0),(2726,22,0),(2727,16,0),(2727,22,0),(2728,16,0),(2728,22,0),(2729,16,0),(2729,22,0),(2730,16,0),(2730,22,0),(2731,16,0),(2731,22,0),(2732,16,0),(2732,22,0),(2733,16,0),(2733,22,0),(2795,16,0),(2795,22,0),(2796,16,0),(2796,22,0),(2797,16,0),(2797,22,0),(2798,16,0),(2798,22,0),(2799,16,0),(2799,22,0),(2800,16,0),(2800,22,0),(2801,16,0),(2801,22,0),(2802,16,0),(2802,22,0),(2803,16,0),(2803,22,0),(2804,16,0),(2804,22,0),(2805,16,0),(2805,22,0),(2806,16,0),(2806,22,0),(2807,16,0),(2807,22,0),(2808,16,0),(2808,22,0),(2809,16,0),(2809,22,0),(2810,16,0),(2810,22,0),(2811,16,0),(2811,22,0),(2812,16,0),(2812,22,0),(2813,16,0),(2813,22,0),(2814,16,0),(2814,22,0),(2815,16,0),(2815,22,0),(2816,16,0),(2816,22,0),(2817,16,0),(2817,22,0),(2818,16,0),(2818,22,0),(2819,16,0),(2819,22,0),(2820,16,0),(2820,22,0),(2821,16,0),(2821,22,0),(2822,16,0),(2822,22,0),(2823,16,0),(2823,22,0),(2824,16,0),(2824,22,0),(2825,16,0),(2825,22,0),(2826,16,0),(2826,22,0),(2828,16,0),(2828,22,0),(2830,16,0),(2830,22,0),(2831,16,0),(2831,22,0),(2832,16,0),(2832,22,0),(2833,16,0),(2833,22,0),(2834,16,0),(2834,22,0),(2835,16,0),(2835,22,0),(2836,16,0),(2836,22,0),(2837,16,0),(2837,22,0),(2838,16,0),(2838,22,0),(2839,16,0),(2839,22,0),(2840,16,0),(2840,22,0),(2841,16,0),(2841,22,0),(2842,16,0),(2842,22,0),(2843,16,0),(2843,22,0),(2844,16,0),(2844,22,0),(2845,16,0),(2845,22,0),(2846,16,0),(2846,22,0),(2847,16,0),(2847,22,0),(2848,16,0),(2848,22,0),(2849,16,0),(2849,22,0),(2850,16,0),(2850,22,0),(2851,16,0),(2851,22,0),(2852,16,0),(2852,22,0),(2853,16,0),(2853,22,0),(2854,16,0),(2854,22,0),(2855,16,0),(2855,22,0),(2856,16,0),(2856,22,0),(2857,16,0),(2857,22,0),(2858,16,0),(2858,22,0),(2859,16,0),(2859,22,0),(2860,16,0),(2860,22,0),(2861,16,0),(2861,22,0),(2862,16,0),(2862,22,0),(2863,16,0),(2863,22,0),(2864,16,0),(2864,22,0),(2865,16,0),(2865,22,0),(2866,16,0),(2866,22,0),(2867,16,0),(2867,22,0),(2868,16,0),(2868,22,0),(2869,16,0),(2869,22,0),(2870,16,0),(2870,22,0),(2871,16,0),(2871,22,0),(2872,16,0),(2872,22,0),(2873,16,0),(2873,22,0),(2874,16,0),(2874,22,0),(2875,16,0),(2875,22,0),(2876,16,0),(2876,22,0),(2877,16,0),(2877,22,0),(2878,16,0),(2878,22,0),(2879,16,0),(2879,22,0),(2880,16,0),(2880,22,0),(2881,16,0),(2881,22,0),(2882,16,0),(2882,22,0),(2883,16,0),(2883,22,0),(2884,16,0),(2884,22,0),(2885,16,0),(2885,22,0),(2886,16,0),(2886,22,0),(2887,16,0),(2887,22,0),(2888,16,0),(2888,22,0),(2889,16,0),(2889,22,0),(2891,16,0),(2891,22,0),(2892,16,0),(2892,22,0),(2893,16,0),(2893,22,0),(2894,16,0),(2894,22,0),(2895,16,0),(2895,22,0),(2896,16,0),(2896,22,0),(2897,16,0),(2897,22,0),(2898,16,0),(2898,22,0),(2899,16,0),(2899,22,0),(2900,16,0),(2900,22,0),(2901,16,0),(2901,22,0),(2902,16,0),(2902,22,0),(2903,16,0),(2903,22,0),(2904,16,0),(2904,22,0),(2905,16,0),(2905,22,0),(2906,16,0),(2906,22,0),(2907,16,0),(2907,22,0),(2908,16,0),(2908,22,0),(2909,16,0),(2909,22,0),(2910,16,0),(2910,22,0),(2911,16,0),(2911,22,0),(2912,16,0),(2912,22,0),(2913,16,0),(2913,22,0),(2914,16,0),(2914,22,0),(2915,16,0),(2915,22,0),(2916,16,0),(2916,22,0),(2917,16,0),(2917,22,0),(2918,16,0),(2918,22,0),(2919,16,0),(2919,22,0),(2920,16,0),(2920,22,0),(2922,16,0),(2922,22,0),(2923,16,0),(2923,22,0),(2924,16,0),(2924,22,0),(2925,16,0),(2925,22,0),(2926,16,0),(2926,22,0),(2927,16,0),(2927,22,0),(2928,16,0),(2928,22,0),(2929,16,0),(2929,22,0),(2930,16,0),(2930,22,0),(2931,16,0),(2931,22,0),(2932,16,0),(2932,22,0),(2933,16,0),(2933,22,0),(2934,16,0),(2934,22,0),(2935,16,0),(2935,22,0),(2936,16,0),(2936,22,0),(2937,16,0),(2937,22,0),(2938,16,0),(2938,22,0),(2939,16,0),(2939,22,0),(2940,16,0),(2940,22,0),(2941,16,0),(2941,22,0),(2942,16,0),(2942,22,0),(2943,16,0),(2943,22,0),(2944,16,0),(2944,22,0),(2945,16,0),(2945,22,0),(2946,16,0),(2946,22,0),(2947,16,0),(2947,22,0),(2948,16,0),(2948,22,0),(2949,16,0),(2949,22,0),(2950,16,0),(2950,22,0),(2951,16,0),(2951,22,0),(2952,16,0),(2952,22,0),(2953,16,0),(2953,22,0),(2954,16,0),(2954,22,0),(2955,16,0),(2955,22,0),(2956,16,0),(2956,22,0),(2957,16,0),(2957,22,0),(2958,16,0),(2958,22,0),(2959,16,0),(2959,22,0),(2960,16,0),(2960,22,0),(2961,16,0),(2961,22,0),(2962,16,0),(2962,22,0),(2963,16,0),(2963,22,0),(2964,16,0),(2964,22,0),(2965,16,0),(2965,22,0),(2966,16,0),(2966,22,0),(2967,16,0),(2967,22,0),(2968,16,0),(2968,22,0),(2969,16,0),(2969,22,0),(2970,16,0),(2970,22,0),(2971,16,0),(2971,22,0),(2972,16,0),(2972,22,0),(2973,16,0),(2973,22,0),(2974,16,0),(2974,22,0),(2975,16,0),(2975,22,0),(2976,16,0),(2976,22,0),(2977,16,0),(2977,22,0),(2978,16,0),(2978,22,0),(2979,16,0),(2979,22,0),(2980,16,0),(2980,22,0),(2981,16,0),(2981,22,0),(2982,16,0),(2982,22,0),(2983,16,0),(2983,22,0),(2984,16,0),(2984,22,0),(2985,16,0),(2985,22,0),(2986,16,0),(2986,22,0),(2987,16,0),(2987,22,0),(2988,16,0),(2988,22,0),(2989,16,0),(2989,22,0),(2990,16,0),(2990,22,0),(2991,16,0),(2991,22,0),(2992,16,0),(2992,22,0),(2993,16,0),(2993,22,0),(2994,16,0),(2994,22,0),(2995,16,0),(2995,22,0),(2996,16,0),(2996,22,0),(2997,16,0),(2997,22,0),(2998,16,0),(2998,22,0),(2999,16,0),(2999,22,0),(3000,16,0),(3000,22,0),(3001,16,0),(3001,22,0),(3002,16,0),(3002,22,0),(3003,16,0),(3003,22,0),(3004,16,0),(3004,22,0),(3005,16,0),(3005,22,0),(3006,16,0),(3006,22,0),(3007,16,0),(3007,22,0),(3008,16,0),(3008,22,0),(3009,16,0),(3009,22,0),(3010,16,0),(3010,22,0),(3011,16,0),(3011,22,0),(3013,16,0),(3013,22,0),(3014,16,0),(3014,22,0),(3015,16,0),(3015,22,0),(3016,16,0),(3016,22,0),(3017,16,0),(3017,22,0),(3018,16,0),(3018,22,0),(3019,16,0),(3019,22,0),(3020,16,0),(3020,22,0),(3021,16,0),(3021,22,0),(3022,16,0),(3022,22,0),(3023,16,0),(3023,22,0),(3024,16,0),(3024,22,0),(3025,16,0),(3025,22,0),(3026,16,0),(3026,22,0),(3027,16,0),(3027,22,0),(3028,16,0),(3028,22,0),(3029,16,0),(3029,22,0),(3030,16,0),(3030,22,0),(3032,16,0),(3032,22,0),(3033,16,0),(3033,22,0),(3034,16,0),(3034,22,0),(3035,16,0),(3035,22,0),(3037,22,0),(3038,16,0),(3038,22,0),(3039,16,0),(3039,22,0),(3040,16,0),(3040,22,0),(3041,16,0),(3041,22,0),(3042,16,0),(3042,22,0),(3043,16,0),(3043,22,0),(3044,16,0),(3044,22,0),(3045,16,0),(3045,22,0),(3046,16,0),(3046,22,0),(3047,16,0),(3047,22,0),(3048,16,0),(3048,22,0),(3049,16,0),(3049,22,0),(3050,16,0),(3050,22,0),(3052,16,0),(3052,22,0);
/*!40000 ALTER TABLE `wp_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
