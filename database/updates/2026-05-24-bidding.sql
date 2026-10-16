-- Bidding + auction close (feature built 2026-05-22..24; applied by hand on
-- production and local Docker at the time, recorded here on 2026-10-16 so a
-- fresh database can be brought up to date). Idempotent.
-- After running: clear backend/runtime/cache/* and frontend/runtime/cache/*.

CREATE TABLE IF NOT EXISTS `bid` (
  `id`         INT(11)       NOT NULL AUTO_INCREMENT,
  `auction_id` INT(11)       NOT NULL,
  `user_id`    INT(11)       NOT NULL,
  `amount`     DECIMAL(12,2) NOT NULL,
  `created_at` DATETIME      NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_bid_auction` (`auction_id`, `id` DESC),
  KEY `idx_bid_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `auction`
    ADD COLUMN IF NOT EXISTS `bid_increment`  DECIMAL(12,2) NOT NULL DEFAULT 50.00 AFTER `start_price`,
    ADD COLUMN IF NOT EXISTS `current_price`  DECIMAL(12,2) NULL AFTER `bid_increment`,
    ADD COLUMN IF NOT EXISTS `winner_user_id` INT(11)       NULL,
    ADD COLUMN IF NOT EXISTS `winning_bid_id` INT(11)       NULL,
    ADD COLUMN IF NOT EXISTS `winning_amount` DECIMAL(12,2) NULL,
    ADD COLUMN IF NOT EXISTS `closed_at`      DATETIME      NULL;

-- Lot numbers are auto-generated (GM-YYYY-NNN) and must be unique.
CREATE UNIQUE INDEX IF NOT EXISTS `uk_auction_lot_number` ON `auction` (`lot_number`);
