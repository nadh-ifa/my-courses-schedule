<?php
include 'functions.php';

$jadwalFile = 'data/jadwal.json';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jadwal = loadData($jadwalFile);

    if (isset($_POST['add'])) {
        $newEntry = [
            'id' => uniqid(),
            'mata_kuliah' => $_POST['mata_kuliah'],
            'hari' => $_POST['hari'],
            'jam' => $_POST['jam'],
            'ruangan' => $_POST['ruangan'],
            'dosen' => $_POST['dosen'],
            'sks' => (int)$_POST['sks']
        ];
        $jadwal[] = $newEntry;
        saveData($jadwalFile, $jadwal);
    } elseif (isset($_POST['edit'])) {
        foreach ($jadwal as &$item) {
            if ($item['id'] == $_POST['id']) {
                $item['mata_kuliah'] = $_POST['mata_kuliah'];
                $item['hari'] = $_POST['hari'];
                $item['jam'] = $_POST['jam'];
                $item['ruangan'] = $_POST['ruangan'];
                $item['dosen'] = $_POST['dosen'];
                $item['sks'] = (int)$_POST['sks'];
                break;
            }
        }
        saveData($jadwalFile, $jadwal);
    } elseif (isset($_POST['delete'])) {
        $jadwal = array_filter($jadwal, function($item) {
            return $item['id'] != $_POST['id'];
        });
        saveData($jadwalFile, $jadwal);
    }
}

$jadwal = loadData($jadwalFile);
if (!is_array($jadwal)) {
    $jadwal = [];
}
$jadwal = sortSchedule($jadwal);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal Kuliah</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Kelola Jadwal Kuliah</h1>
        <nav>
            <a href="index.php">Dashboard</a> |
            <a href="jadwal.php">Jadwal</a> |
            <a href="tugas.php">Tugas</a>
        </nav>

        <div class="form-toggle-container">
            <div class="form-toggle-header" onclick="toggleForm('jadwal')">
                <h2>Tambah/Edit Jadwal</h2>
            </div>
            <div class="form-toggle-content" id="jadwalFormContent">
                <form method="POST" id="jadwalForm">
                    <input type="hidden" name="id" id="id">
                    <label for="mata_kuliah">Mata Kuliah:</label>
                    <input type="text" name="mata_kuliah" id="mata_kuliah" required>

                    <label for="hari">Hari:</label>
                    <select name="hari" id="hari" required>
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>

                    <label for="jam">Jam:</label>
                    <input type="time" name="jam" id="jam" required>

                    <label for="ruangan">Ruangan:</label>
                    <input type="text" name="ruangan" id="ruangan" required>

                    <label for="dosen">Dosen Pengampu:</label>
                    <input type="text" name="dosen" id="dosen" required>

                    <label for="sks">SKS:</label>
                    <input type="number" name="sks" id="sks" min="1" max="6" required>

                    <button type="submit" name="add" id="addBtn">Tambah</button>
                    <button type="submit" name="edit" id="editBtn" style="display:none;">Simpan Perubahan</button>
                    <button type="button" onclick="resetForm()">Reset</button>
                </form>
            </div>
        </div>

        <h2>Daftar Jadwal Kuliah</h2>
        <table>
            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Ruangan</th>
                    <th>Dosen</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jadwal as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['mata_kuliah']); ?></td>
                    <td><?php echo htmlspecialchars($item['hari']); ?></td>
                    <td><?php echo htmlspecialchars($item['jam']); ?></td>
                    <td><?php echo htmlspecialchars($item['ruangan']); ?></td>
                    <td><?php echo htmlspecialchars($item['dosen']); ?></td>
                    <td><?php echo htmlspecialchars($item['sks']); ?></td>
                    <td>
                        <button onclick="editEntry('<?php echo $item['id']; ?>')">Edit</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer style="text-align: center; margin-top: 50px; padding: 20px; background-color: #f5f5f5; border-top: 1px solid #ddd;">
        <p style="color: #666; font-size: 12px; margin: 0 0 10px 0;">(Created as part of the Web Application Programming course © Nadhifa Fitriyah, 2025)</p>
        <img src="https://64.media.tumblr.com/0230cab50cca75276c6ea79c8e7ecba8/9d74a7dcc52b0457-6b/s250x400/73a236290319f568534c9876e35fe5b1b30bc5b0.gifv" alt="Footer GIF" style="width: 100px; height: auto;">
    </footer>

    <script>
        function toggleForm(formType) {
            const header = document.querySelector(`.form-toggle-header[onclick="toggleForm('${formType}')"]`);
            const content = document.getElementById(`${formType}FormContent`);

            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                header.classList.remove('expanded');
            } else {
                content.classList.add('expanded');
                header.classList.add('expanded');
            }
        }

        function editEntry(id) {
            <?php foreach ($jadwal as $item): ?>
                if ('<?php echo $item['id']; ?>' === id) {
                    document.getElementById('id').value = '<?php echo $item['id']; ?>';
                    document.getElementById('mata_kuliah').value = '<?php echo addslashes($item['mata_kuliah']); ?>';
                    document.getElementById('hari').value = '<?php echo $item['hari']; ?>';
                    document.getElementById('jam').value = '<?php echo $item['jam']; ?>';
                    document.getElementById('ruangan').value = '<?php echo addslashes($item['ruangan']); ?>';
                    document.getElementById('dosen').value = '<?php echo addslashes($item['dosen']); ?>';
                    document.getElementById('sks').value = '<?php echo $item['sks']; ?>';
                    document.getElementById('addBtn').style.display = 'none';
                    document.getElementById('editBtn').style.display = 'inline';

                    // Auto-expand form when editing
                    const content = document.getElementById('jadwalFormContent');
                    const header = document.querySelector('.form-toggle-header[onclick="toggleForm(\'jadwal\')"]');
                    content.classList.add('expanded');
                    header.classList.add('expanded');
                }
            <?php endforeach; ?>
        }

        function resetForm() {
            document.getElementById('jadwalForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('addBtn').style.display = 'inline';
            document.getElementById('editBtn').style.display = 'none';
        }
    </script>
</body>
</html>
