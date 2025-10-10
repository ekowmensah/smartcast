<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Core/Database.php';

use SmartCast\Core\Database;

$db = Database::getInstance();
$connection = $db->getConnection();

echo "Events table columns:\n";
$stmt = $connection->query('DESCRIBE events');
while ($row = $stmt->fetch()) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\nContestants table columns:\n";
$stmt = $connection->query('DESCRIBE contestants');
while ($row = $stmt->fetch()) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

// Check if there are existing slug-like fields
echo "\nChecking for slug fields...\n";
$stmt = $connection->query("SELECT id, name, code FROM events WHERE id = 24");
$event = $stmt->fetch();
if ($event) {
    echo "Event 24: name='{$event['name']}', code='{$event['code']}'\n";
}

$stmt = $connection->query("SELECT id, name FROM contestants WHERE event_id = 24 LIMIT 3");
$contestants = $stmt->fetchAll();
foreach ($contestants as $contestant) {
    echo "Contestant: id={$contestant['id']}, name='{$contestant['name']}'\n";
}
?>
