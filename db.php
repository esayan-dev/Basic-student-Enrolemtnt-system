
<?php
$db = new PDO('sqlite:d1a526666a87b5e50ee2f26368dcdb2d.sqlite');
$db->exec("CREATE TABLE IF NOT EXISTS students (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT,
  mobile TEXT,
  email TEXT,
  gender TEXT,
  enrolled_at TEXT NOT NULL
)");
?>
