<?php

set_time_limit(0);
header('Content-Type: text/plain');

if (PHP_SAPI !== 'cli' && getenv('NHL94_ENABLE_RECOVERY') !== '1') {
    http_response_code(404);
    exit;
}

require_once("./_INCLUDES/dbconnect.php");
require_once("./_INCLUDES/addgame.php");

$conn = $GLOBALS['$conn'];
$uploadsDir = rtrim($GLOBALS['$saveFilePath'], "/\\");
$dryRun = !isset($_GET['run']) || $_GET['run'] !== '1';
$start = isset($_GET['start']) ? (int) $_GET['start'] : 1396;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 1;
$order = (isset($_GET['order']) && $_GET['order'] === 'mtime') ? 'mtime' : 'gameid';
$processed = 0;
$checked = 0;
$logPath = __DIR__ . '/db/recover_uploads.log';

echo ($dryRun ? "DRY RUN" : "LIVE RUN") . "\n";
echo "Uploads: " . $uploadsDir . "\n";
echo "Mode: direct parser, no file upload/save\n";
echo "Start: " . $start . "\n";
echo "Limit: " . $limit . "\n";
echo "Order: " . $order . "\n\n";

file_put_contents($logPath, date('c') . "|start|dry_run=" . ($dryRun ? "1" : "0") . "|start=" . $start . "|limit=" . $limit . "|order=" . $order . "\n", FILE_APPEND);

if (!$dryRun && (!isset($_GET['confirm']) || $_GET['confirm'] !== 'replay')) {
    echo "Live run requires confirm=replay.\n";
    file_put_contents($logPath, date('c') . "|error|missing_live_confirm\n", FILE_APPEND);
    exit;
}

if (!$dryRun) {
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS recover_uploads_manifest (
        ID int(11) NOT NULL AUTO_INCREMENT,
        SourceFolder int(11) NOT NULL,
        SourceSeriesID int(11) NOT NULL,
        SourceGameID int(11) NOT NULL,
        SourceFileName varchar(255) NOT NULL,
        SourceSha1 char(40) NOT NULL,
        SourceMTime int(11) NOT NULL,
        CanonicalSeriesID int(11) NOT NULL,
        CanonicalGameID int(11) NOT NULL,
        ImportedAt datetime NOT NULL,
        PRIMARY KEY (ID),
        UNIQUE KEY SourceFile (SourceFolder, SourceFileName, SourceSha1)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
}

if (!is_dir($uploadsDir)) {
    echo "Missing uploads directory.\n";
    file_put_contents($logPath, date('c') . "|error|missing_uploads_dir|" . $uploadsDir . "\n", FILE_APPEND);
    exit;
}

$folders = [];
$scan = scandir($uploadsDir);
foreach ($scan as $name) {
    if (preg_match('/^[0-9]+$/', $name)) {
        $seriesId = (int) $name;
        if ($seriesId >= $start && is_dir($uploadsDir . '/' . $name)) {
            $folders[] = $seriesId;
        }
    } elseif ($name !== '.' && $name !== '..') {
        file_put_contents($logPath, date('c') . "|skip_folder|non_numeric|" . $name . "\n", FILE_APPEND);
    }
}

sort($folders, SORT_NUMERIC);

foreach ($folders as $seriesId) {
    if ($limit > 0 && $checked >= $limit) {
        break;
    }
    $checked++;

    $sourceFolder = $seriesId;
    $folder = $uploadsDir . '/' . $seriesId;
    $files = [];
    $folderScan = scandir($folder);

    foreach ($folderScan as $filename) {
        $path = $folder . '/' . $filename;
        if (!is_file($path)) {
            continue;
        }

        if (!preg_match('/\.gs[0-9]*$/i', $filename)) {
            file_put_contents($logPath, date('c') . "|skip_file|not_gs|" . $seriesId . "|" . $filename . "\n", FILE_APPEND);
            continue;
        }

        if (!preg_match('/^([^-]+)-(.+)_at_([^-]+)-(.+)_gameId-([0-9]+)_seriesId-([0-9]+)_bin-(.+)\.gs[0-9]*$/i', $filename, $match)) {
            file_put_contents($logPath, date('c') . "|skip_file|bad_name|" . $seriesId . "|" . $filename . "\n", FILE_APPEND);
            continue;
        }

        $files[] = [
            'path' => $path,
            'name' => $filename,
            'header' => file_get_contents($path, false, null, 0, 32),
            'mtime' => filemtime($path),
            'size' => filesize($path),
            'sha1' => sha1_file($path),
            'away_user' => $match[2],
            'home_user' => $match[4],
            'game_id' => (int) $match[5],
            'source_series_id' => (int) $match[6],
            'bin' => $match[7],
        ];
    }

    $filesByGameId = [];
    foreach ($files as $file) {
        $gameId = $file['game_id'];
        if (!isset($filesByGameId[$gameId])) {
            $filesByGameId[$gameId] = $file;
            continue;
        }

        $keepNewFile = $file['mtime'] < $filesByGameId[$gameId]['mtime'] || ($file['mtime'] === $filesByGameId[$gameId]['mtime'] && $file['name'] < $filesByGameId[$gameId]['name']);
        $skippedFile = $keepNewFile ? $filesByGameId[$gameId] : $file;
        if ($keepNewFile) {
            $filesByGameId[$gameId] = $file;
        }
        file_put_contents($logPath, date('c') . "|skip_file|duplicate_gameid|" . $seriesId . "|" . $skippedFile['name'] . "|game=" . $gameId . "\n", FILE_APPEND);
    }
    $files = array_values($filesByGameId);

    $sortNames = array_column($files, 'name');
    if ($order === 'gameid') {
        $sortGameIds = array_column($files, 'game_id');
        array_multisort($sortGameIds, SORT_ASC, SORT_NUMERIC, $sortNames, SORT_ASC, SORT_STRING, $files);
    } else {
        $sortMtimes = array_column($files, 'mtime');
        array_multisort($sortMtimes, SORT_ASC, SORT_NUMERIC, $sortNames, SORT_ASC, SORT_STRING, $files);
    }

    echo "Series " . $seriesId . ": " . count($files) . " file(s)\n";
    file_put_contents($logPath, date('c') . "|folder|" . $seriesId . "|files=" . count($files) . "\n", FILE_APPEND);

    if (count($files) === 0) {
        echo "  skipped: no .gs files\n\n";
        file_put_contents($logPath, date('c') . "|skip_folder|no_gs|" . $sourceFolder . "\n", FILE_APPEND);
        continue;
    }

    if (!$dryRun) {
        $manifestResult = mysqli_query($conn, "SELECT CanonicalSeriesID, COUNT(*) AS Imported FROM recover_uploads_manifest WHERE SourceFolder = $sourceFolder GROUP BY CanonicalSeriesID ORDER BY Imported DESC LIMIT 1");
        $manifest = mysqli_fetch_array($manifestResult, MYSQLI_ASSOC);

        if ($manifest && (int) $manifest['Imported'] >= count($files)) {
            echo "  skipped: already recovered as DB series " . $manifest['CanonicalSeriesID'] . "\n\n";
            file_put_contents($logPath, date('c') . "|skip_folder|already_recovered|" . $sourceFolder . "|series=" . $manifest['CanonicalSeriesID'] . "\n", FILE_APPEND);
            continue;
        }
    }

    if (count($files) > 7) {
        echo "  skipped: more than 7 .gs files\n\n";
        file_put_contents($logPath, date('c') . "|skip_folder|too_many_files|" . $sourceFolder . "|files=" . count($files) . "\n", FILE_APPEND);
        continue;
    }

    $numScheduleGames = count($files) === 1 ? 1 : 7;
    $firstFile = $files[0];
    $fileMtimes = array_column($files, 'mtime');
    $firstDate = date('Y-m-d H:i:s', min($fileMtimes));
    $lastDate = date('Y-m-d H:i:s', max($fileMtimes));
    $seriesName = $firstFile['home_user'] . ' vs ' . $firstFile['away_user'];
    $escapedBin = mysqli_real_escape_string($conn, $firstFile['bin']);
    $leagueResult = mysqli_query($conn, "SELECT LeagueID FROM league WHERE LOWER(REPLACE(Name, '.bin', '')) = LOWER('$escapedBin') LIMIT 1");
    $league = mysqli_fetch_array($leagueResult, MYSQLI_ASSOC);

    if (!$league) {
        echo "  skipped: missing league " . $firstFile['bin'] . "\n\n";
        file_put_contents($logPath, date('c') . "|skip_folder|missing_league|" . $seriesId . "|" . $firstFile['bin'] . "\n", FILE_APPEND);
        continue;
    }

    $leagueId = (int) $league['LeagueID'];
    $homeUser = mysqli_real_escape_string($conn, $firstFile['home_user']);
    $awayUser = mysqli_real_escape_string($conn, $firstFile['away_user']);
    $homeResult = mysqli_query($conn, "SELECT id_user FROM users WHERE LOWER(username) = LOWER('$homeUser') LIMIT 1");
    $awayResult = mysqli_query($conn, "SELECT id_user FROM users WHERE LOWER(username) = LOWER('$awayUser') LIMIT 1");
    $home = mysqli_fetch_array($homeResult, MYSQLI_ASSOC);
    $away = mysqli_fetch_array($awayResult, MYSQLI_ASSOC);

    if (!$home || !$away) {
        echo "  skipped: missing home/away user\n\n";
        file_put_contents($logPath, date('c') . "|skip_folder|missing_user|" . $seriesId . "|home=" . $firstFile['home_user'] . "|away=" . $firstFile['away_user'] . "\n", FILE_APPEND);
        continue;
    }

    $homeUserId = (int) $home['id_user'];
    $awayUserId = (int) $away['id_user'];
    $scheduleRows = [];

    foreach ($files as $file) {
        $type = GetFileType($file['name']);
        $parserOffset = "0";
        if (substr($file['header'], 0, 7) == 'RASTATE') {
            $parserOffset = "9304";
        } elseif (substr($file['header'], 0, 10) == 'GENPLUS-GX' || $type == 'ra') {
            $parserOffset = "9320";
        }

        echo "  file " . $file['name'] . " | " . date('Y-m-d H:i:s', $file['mtime']) . " | game " . $file['game_id'] . " | offset " . $parserOffset . "\n";
        file_put_contents($logPath, date('c') . "|file|" . $sourceFolder . "|" . $file['name'] . "|mtime=" . $file['mtime'] . "|size=" . $file['size'] . "|sha1=" . $file['sha1'] . "|game=" . $file['game_id'] . "\n", FILE_APPEND);
    }
    echo "  schedule rows: " . $numScheduleGames . "\n";

    if ($dryRun) {
        echo "  dry run: would create DB series, parse original files, and correct timestamps\n\n";
        $processed++;
        continue;
    }

    $escapedSeriesName = mysqli_real_escape_string($conn, $seriesName);

    $seriesWrite = mysqli_query($conn, "INSERT INTO series (HomeUserID, AwayUserID, Name, DateCreated, Active, LeagueID, TourneyID) VALUES ($homeUserId, $awayUserId, '$escapedSeriesName', '$firstDate', 1, $leagueId, 0)");

    if (!$seriesWrite) {
        echo "  failed: could not write series row\n\n";
        file_put_contents($logPath, date('c') . "|error|series_write|" . $sourceFolder . "|" . mysqli_error($conn) . "\n", FILE_APPEND);
        continue;
    }

    $seriesId = mysqli_insert_id($conn);
    echo "  DB series: " . $seriesId . "\n";
    file_put_contents($logPath, date('c') . "|series_created|" . $sourceFolder . "|db_series=" . $seriesId . "|date=" . $firstDate . "\n", FILE_APPEND);

    while (count($scheduleRows) < $numScheduleGames) {
        $slot = count($scheduleRows);
        $slotFile = isset($files[$slot]) ? $files[$slot] : $firstFile;
        $slotHome = mysqli_real_escape_string($conn, $slotFile['home_user']);
        $slotAway = mysqli_real_escape_string($conn, $slotFile['away_user']);
        $slotHomeResult = mysqli_query($conn, "SELECT id_user FROM users WHERE LOWER(username) = LOWER('$slotHome') LIMIT 1");
        $slotAwayResult = mysqli_query($conn, "SELECT id_user FROM users WHERE LOWER(username) = LOWER('$slotAway') LIMIT 1");
        $slotHomeRow = mysqli_fetch_array($slotHomeResult, MYSQLI_ASSOC);
        $slotAwayRow = mysqli_fetch_array($slotAwayResult, MYSQLI_ASSOC);

        if (!$slotHomeRow || !$slotAwayRow) {
            echo "  failed: missing schedule user for " . $slotFile['name'] . "\n\n";
            file_put_contents($logPath, date('c') . "|error|missing_schedule_user|" . $seriesId . "|" . $slotFile['name'] . "\n", FILE_APPEND);
            continue 2;
        }

        $slotHomeId = (int) $slotHomeRow['id_user'];
        $slotAwayId = (int) $slotAwayRow['id_user'];
        mysqli_query($conn, "INSERT INTO schedule (HomeUserID, AwayUserID, SeriesID, LeagueID, TourneyID) VALUES ($slotHomeId, $slotAwayId, $seriesId, $leagueId, 0)");
        $scheduleRows[] = ['ID' => mysqli_insert_id($conn), 'GameID' => null];
    }

    $scheduleRows = [];
    $scheduleResult = mysqli_query($conn, "SELECT ID, GameID, HomeUserID, AwayUserID FROM schedule WHERE SeriesID = $seriesId ORDER BY ID ASC");
    while ($row = mysqli_fetch_array($scheduleResult, MYSQLI_ASSOC)) {
        $scheduleRows[] = $row;
    }

    foreach ($files as $index => $file) {
        $scheduleId = (int) $scheduleRows[$index]['ID'];
        $scheduleHomeUserId = (int) $scheduleRows[$index]['HomeUserID'];
        $scheduleAwayUserId = (int) $scheduleRows[$index]['AwayUserID'];
        $type = GetFileType($file['name']);
        $GLOBALS['$recoveryFilePath'] = $file['path'];
        AddGame($seriesId, $scheduleId, $scheduleHomeUserId, $scheduleAwayUserId, $leagueId, 0, $type);
        unset($GLOBALS['$recoveryFilePath']);
        file_put_contents($logPath, date('c') . "|parsed_file|" . $seriesId . "|" . $file['name'] . "|schedule=" . $scheduleId . "\n", FILE_APPEND);
    }

    $imported = [];
    $afterResult = mysqli_query($conn, "SELECT ID, GameID FROM schedule WHERE SeriesID = $seriesId AND GameID IS NOT NULL ORDER BY ID ASC");
    while ($row = mysqli_fetch_array($afterResult, MYSQLI_ASSOC)) {
        $imported[] = $row;
    }

    if (count($imported) < count($files)) {
        echo "  failed: imported " . count($imported) . " of " . count($files) . " files\n\n";
        file_put_contents($logPath, date('c') . "|error|import_count|" . $seriesId . "|imported=" . count($imported) . "|expected=" . count($files) . "\n", FILE_APPEND);
        continue;
    }

    foreach ($files as $index => $file) {
        $scheduleId = (int) $imported[$index]['ID'];
        $actualGameId = (int) $imported[$index]['GameID'];
        $fileDate = date('Y-m-d H:i:s', $file['mtime']);
        mysqli_query($conn, "UPDATE schedule SET ConfirmTime = '$fileDate' WHERE ID = $scheduleId LIMIT 1");
        $escapedFileName = mysqli_real_escape_string($conn, $file['name']);
        mysqli_query($conn, "INSERT INTO recover_uploads_manifest (SourceFolder, SourceSeriesID, SourceGameID, SourceFileName, SourceSha1, SourceMTime, CanonicalSeriesID, CanonicalGameID, ImportedAt) VALUES ($sourceFolder, " . $file['source_series_id'] . ", " . $file['game_id'] . ", '$escapedFileName', '" . $file['sha1'] . "', " . $file['mtime'] . ", $seriesId, $actualGameId, NOW()) ON DUPLICATE KEY UPDATE CanonicalSeriesID = VALUES(CanonicalSeriesID), CanonicalGameID = VALUES(CanonicalGameID), ImportedAt = VALUES(ImportedAt)");
        file_put_contents($logPath, date('c') . "|mapped_game|" . $sourceFolder . "|source_game=" . $file['game_id'] . "|db_series=" . $seriesId . "|db_game=" . $actualGameId . "\n", FILE_APPEND);
    }

    $winnerResult = mysqli_query($conn, "SELECT SeriesWonBy FROM series WHERE ID = $seriesId LIMIT 1");
    $winner = mysqli_fetch_array($winnerResult, MYSQLI_ASSOC);
    if ($winner && (int) $winner['SeriesWonBy'] !== 0) {
        mysqli_query($conn, "UPDATE series SET DateCreated = '$firstDate', DateCompleted = '$lastDate' WHERE ID = $seriesId LIMIT 1");
    } else {
        mysqli_query($conn, "UPDATE series SET DateCreated = '$firstDate' WHERE ID = $seriesId LIMIT 1");
    }

    echo "  imported and timestamp-corrected\n\n";
    file_put_contents($logPath, date('c') . "|complete|" . $seriesId . "|created=" . $firstDate . "|completed=" . $lastDate . "\n", FILE_APPEND);
    $processed++;
}

echo "Done. Processed folders: " . $processed . "\n";
echo "Done. Checked folders: " . $checked . "\n";
echo "Log: " . $logPath . "\n";

?>
