<?php
include 'functions.php';

$jadwalFile = 'data/jadwal.json';
$tugasFile = 'data/tugas.json';

$jadwal = loadData($jadwalFile);
$tugas = loadData($tugasFile);

$todaySchedule = getTodaySchedule($jadwal ?? []);
$pendingTasks = getPendingTasks($tugas ?? []);
$totalSKS = calculateTotalSKS($jadwal ?? []);
$nearingDeadlineTasks = getNearingDeadlineTasks($tugas ?? []);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Jadwal Kuliah</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard Jadwal Kuliah</h1>
        <nav>
            <a href="index.php">Dashboard</a> |
            <a href="jadwal.php">Jadwal</a> |
            <a href="tugas.php">Tugas</a>
        </nav>

        <div class="dashboard">
            <div class="card">
                <h2>Jadwal Hari Ini (<?php echo date('l, d M Y'); ?>)</h2>
                <?php if (empty($todaySchedule)): ?>
                    <p>Tidak ada jadwal kuliah hari ini.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Mata Kuliah</th>
                                <th>Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($todaySchedule as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['jam']); ?></td>
                                <td><?php echo htmlspecialchars($item['mata_kuliah']); ?></td>
                                <td><?php echo htmlspecialchars($item['ruangan']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="card">
                <h2>Tugas Belum Selesai</h2>
                <?php if (empty($pendingTasks)): ?>
                    <p>Semua tugas sudah selesai!</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Tugas</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingTasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['nama_tugas']); ?></td>
                                <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="card alert">
                <h2>Alert: Tugas Mendekati Deadline</h2>
                <?php if (empty($nearingDeadlineTasks)): ?>
                    <p>Tidak ada tugas yang mendekati deadline.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Tugas</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nearingDeadlineTasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['nama_tugas']); ?></td>
                                <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="card total-sks">
                <h2>Total SKS</h2>
                <p><?php echo $totalSKS; ?> SKS</p>
            </div>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 50px; padding: 20px; background-color: #f5f5f5; border-top: 1px solid #ddd;">
        <p style="color: #666; font-size: 12px; margin: 0 0 10px 0;">(Created as part of the Web Application Programming course © Nadhifa Fitriyah, 2025)</p>
        <img src="https://64.media.tumblr.com/0230cab50cca75276c6ea79c8e7ecba8/9d74a7dcc52b0457-6b/s250x400/73a236290319f568534c9876e35fe5b1b30bc5b0.gifv" alt="Footer GIF" style="width: 100px; height: auto;">
    </footer>
</body>
</html>
