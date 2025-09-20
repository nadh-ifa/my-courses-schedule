<?php
// Function to load data from JSON file
function loadData($filename) {
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        return json_decode($data, true);
    }
    return [];
}

// Function to save data to JSON file
function saveData($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

// Function to sort schedule by day and time
function sortSchedule($schedule) {
    usort($schedule, function($a, $b) {
        $days = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
        $dayA = $days[$a['hari']] ?? 8;
        $dayB = $days[$b['hari']] ?? 8;
        if ($dayA == $dayB) {
            return strcmp($a['jam'], $b['jam']);
        }
        return $dayA - $dayB;
    });
    return $schedule;
}

// Function to sort tasks by deadline
function sortTasks($tasks) {
    usort($tasks, function($a, $b) {
        return strtotime($a['deadline']) - strtotime($b['deadline']);
    });
    return $tasks;
}

// Function to calculate total SKS
function calculateTotalSKS($schedule) {
    $total = 0;
    foreach ($schedule as $item) {
        $total += (int)($item['sks'] ?? 0);
    }
    return $total;
}

// Function to get today's schedule
function getTodaySchedule($schedule) {
    $today = date('l'); // English day
    $hariMap = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    ];
    $hari = $hariMap[$today] ?? '';
    return array_filter($schedule, function($item) use ($hari) {
        return $item['hari'] == $hari;
    });
}

// Function to get pending tasks
function getPendingTasks($tasks) {
    return array_filter($tasks, function($item) {
        return $item['status'] != 'Selesai';
    });
}

// Function to get tasks nearing deadline (within 3 days)
function getNearingDeadlineTasks($tasks) {
    $now = time();
    $threeDays = 3 * 24 * 60 * 60;
    return array_filter($tasks, function($item) use ($now, $threeDays) {
        if ($item['status'] == 'Selesai') return false;
        $deadline = strtotime($item['deadline']);
        return $deadline >= $now && ($deadline - $now) <= $threeDays;
    });
}
