-- Sprint 2 (2026-10-16): schema fixes for broken admin pages.
-- Idempotent: safe to run more than once. Run in phpMyAdmin on production,
-- then clear backend/runtime/cache/* and frontend/runtime/cache/* (Yii schema cache).

-- Admin -> Media (gallery/videos) and gallery upload read/write files.title and
-- files.link, which never existed in this schema → 500 on every media page.
ALTER TABLE `files`
    ADD COLUMN IF NOT EXISTS `title` VARCHAR(255) NULL AFTER `path`,
    ADD COLUMN IF NOT EXISTS `link`  VARCHAR(255) NULL AFTER `title`;

-- Footer "about" text: the template used the key 'Footer About Text', whose
-- translations were Lorem ipsum, and English pages (source language) printed the
-- key itself. The footer now uses the English sentence as source string; this adds
-- its Armenian translation.
INSERT INTO `source_message` (`category`, `message`)
SELECT 'app', 'Goldmember is Armenia''s trusted platform for gold and precious metals — buy, sell and join live auctions with confidence.'
 WHERE NOT EXISTS (SELECT 1 FROM `source_message` WHERE `category` = 'app'
       AND `message` = 'Goldmember is Armenia''s trusted platform for gold and precious metals — buy, sell and join live auctions with confidence.');
INSERT INTO `message` (`id`, `language`, `translation`)
SELECT s.id, 'am', 'Goldmember-ը Հայաստանում ոսկու և թանկարժեք մետաղների վստահելի հարթակ է․ գնեք, վաճառեք և մասնակցեք ուղիղ աճուրդների վստահությամբ։'
  FROM `source_message` s
 WHERE s.category = 'app'
   AND s.message = 'Goldmember is Armenia''s trusted platform for gold and precious metals — buy, sell and join live auctions with confidence.'
ON DUPLICATE KEY UPDATE `translation` = VALUES(`translation`);
