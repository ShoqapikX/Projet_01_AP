Visitor counter

This project adds a lightweight visitor counter endpoint at `ajax/visitor_counter.php`.

What it does
- Creates a `visitors` table automatically (if it doesn't exist).
- Records a visit per session+page with timestamps (`created_at`, `last_seen`).
- Returns JSON with `active` (active viewers in last 5 minutes) and `today` (unique sessions today).

Table schema (created automatically):

CREATE TABLE IF NOT EXISTS visitors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(128) NOT NULL,
  page VARCHAR(255) NOT NULL,
  last_seen DATETIME NOT NULL,
  created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

Notes
- The endpoint is polled by the front-end every 8 seconds on the homepage.
- To reset counts, truncate the `visitors` table.
- Adjust the `INTERVAL 5 MINUTE` in `ajax/visitor_counter.php` if you want a different active window.
