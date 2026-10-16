-- OPTIONAL data cleanup (2026-10-16). Review before running; this one is not a schema change.
-- Removes test rows typed into the admin panel. Every DELETE matches on the junk text itself,
-- so if a row was already renamed or reused for real content it is left alone. Idempotent.
SET NAMES utf8mb4;

-- News categories "adfasdf" / "sdfsdfg" (keyboard mashing, created 2026-05-22), only if no news
-- article uses them.
DELETE t FROM `tr_news_category` t
  JOIN `news_category` c ON c.id = t.category_id
 WHERE c.name IN ('adfasdf', 'sdfsdfg')
   AND NOT EXISTS (SELECT 1 FROM `news` n WHERE n.category_id = c.id);
DELETE c FROM `news_category` c
 WHERE c.name IN ('adfasdf', 'sdfsdfg')
   AND NOT EXISTS (SELECT 1 FROM `news` n WHERE n.category_id = c.id)
   AND c.id NOT IN (SELECT parent_id FROM (SELECT parent_id FROM `news_category` WHERE parent_id IS NOT NULL) p);

-- Blog post titled "asdfasdf" (2019). The public blog already 301s to /news.
DELETE t FROM `tr_blog` t JOIN `blog` b ON b.id = t.blog_id WHERE TRIM(b.title) = 'asdfasdf';
DELETE FROM `blog` WHERE TRIM(title) = 'asdfasdf';

-- USD exchange rate: stored as sell 382.68 / buy 390, i.e. we'd buy dollars for more than we sell
-- them. Probably the two values were swapped on entry. All rates were last updated in Sept 2025,
-- so the better fix is entering today's rates in Admin -> Exchange Rates (the form now rejects
-- sell < buy). If you just want the swap, uncomment:
-- UPDATE `exchange_rates` SET sell_rate = buy_rate, buy_rate = sell_rate WHERE sell_rate < buy_rate;
