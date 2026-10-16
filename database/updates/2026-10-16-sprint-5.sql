-- Sprint 5 (2026-10-16): Armenian text for the new exchange-rate validation message.
-- Idempotent. Clear runtime/cache afterwards (translations are cached for 1h).
SET NAMES utf8mb4;

INSERT INTO `source_message` (`category`,`message`) SELECT 'app', 'Sell rate must not be lower than the buy rate.' WHERE NOT EXISTS (SELECT 1 FROM `source_message` WHERE `category`='app' AND `message`='Sell rate must not be lower than the buy rate.');
INSERT INTO `message` (`id`,`language`,`translation`) SELECT s.id,'am','Վաճառքի փոխարժեքը չի կարող ցածր լինել առքի փոխարժեքից։' FROM `source_message` s WHERE s.category='app' AND s.message='Sell rate must not be lower than the buy rate.' ON DUPLICATE KEY UPDATE `translation`=VALUES(`translation`);
