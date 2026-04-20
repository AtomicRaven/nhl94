<?php

set_time_limit(0);
header('Content-Type: text/plain');

require_once("./_INCLUDES/dbconnect.php");

$conn = $GLOBALS['$conn'];
$from = isset($_GET['from']) ? (int) $_GET['from'] : 1421;
$to = isset($_GET['to']) ? (int) $_GET['to'] : 1432;
$dryRun = !isset($_GET['run']) || $_GET['run'] !== '1';
$logPath = __DIR__ . '/db/delete_recovered_series.log';

if ($from > $to) {
    $tmp = $from;
    $from = $to;
    $to = $tmp;
}

echo ($dryRun ? "DRY RUN" : "LIVE RUN") . "\n";
echo "Canonical series range: " . $from . " to " . $to . "\n\n";

file_put_contents($logPath, date('c') . "|start|dry_run=" . ($dryRun ? "1" : "0") . "|from=" . $from . "|to=" . $to . "\n", FILE_APPEND);

if (!$dryRun && (!isset($_GET['confirm']) || $_GET['confirm'] !== 'delete')) {
    echo "Live run requires confirm=delete.\n";
    file_put_contents($logPath, date('c') . "|error|missing_live_confirm\n", FILE_APPEND);
    exit;
}

$manifestExistsResult = mysqli_query($conn, "SHOW TABLES LIKE 'recover_uploads_manifest'");
$manifestExists = mysqli_fetch_array($manifestExistsResult, MYSQLI_ASSOC);

if ($manifestExists) {
    $manifestResult = mysqli_query($conn, "SELECT SourceFolder, CanonicalSeriesID, COUNT(*) AS Files, MIN(SourceGameID) AS FirstSourceGame, MAX(SourceGameID) AS LastSourceGame, MIN(CanonicalGameID) AS FirstDBGame, MAX(CanonicalGameID) AS LastDBGame FROM recover_uploads_manifest WHERE CanonicalSeriesID BETWEEN $from AND $to GROUP BY SourceFolder, CanonicalSeriesID ORDER BY CanonicalSeriesID");
    echo "Manifest rows:\n";
    while ($row = mysqli_fetch_array($manifestResult, MYSQLI_ASSOC)) {
        echo "  DB series " . $row['CanonicalSeriesID'] . " <- source folder " . $row['SourceFolder'] . " | files " . $row['Files'] . " | source games " . $row['FirstSourceGame'] . "-" . $row['LastSourceGame'] . " | DB games " . $row['FirstDBGame'] . "-" . $row['LastDBGame'] . "\n";
    }
    echo "\n";
} else {
    echo "Manifest table not found; only series cleanup will run.\n\n";
}

$seriesResult = mysqli_query($conn, "SELECT ID, Name, Active FROM series WHERE ID BETWEEN $from AND $to ORDER BY ID");
$seriesIds = [];

echo "Series rows:\n";
while ($row = mysqli_fetch_array($seriesResult, MYSQLI_ASSOC)) {
    $seriesIds[] = (int) $row['ID'];
    echo "  " . $row['ID'] . " | Active=" . $row['Active'] . " | " . $row['Name'] . "\n";
}
echo "\n";

if ($dryRun) {
    echo "Dry run only. Add run=1&confirm=delete to delete these series and manifest rows.\n";
    echo "Log: " . $logPath . "\n";
    exit;
}

if ($manifestExists) {
    mysqli_query($conn, "DELETE FROM recover_uploads_manifest WHERE CanonicalSeriesID BETWEEN $from AND $to");
    echo "Deleted manifest rows: " . mysqli_affected_rows($conn) . "\n";
    file_put_contents($logPath, date('c') . "|manifest_deleted|rows=" . mysqli_affected_rows($conn) . "\n", FILE_APPEND);
}

foreach ($seriesIds as $seriesId) {
    MarkSeriesAsInactive($seriesId);
    echo "Deleted/marked inactive series " . $seriesId . "\n";
    file_put_contents($logPath, date('c') . "|series_deleted|" . $seriesId . "\n", FILE_APPEND);
}

if (count($seriesIds) === 0) {
    echo "No series found in range.\n";
}

echo "\nDone.\n";
echo "Log: " . $logPath . "\n";

?>
