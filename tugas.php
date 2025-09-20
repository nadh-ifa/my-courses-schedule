<?php
include 'functions.php';

$tugasFile = 'data/tugas.json';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tugas = loadData($tugasFile);

    if (isset($_POST['add'])) {
        $newTask = [
            'id' => uniqid(),
            'nama_tugas' => $_POST['nama_tugas'],
            'deadline' => $_POST['deadline'],
            'status' => $_POST['status']
        ];
        $tugas[] = $newTask;
        saveData($tugasFile, $tugas);
    } elseif (isset($_POST['edit'])) {
        foreach ($tugas as &$item) {
            if ($item['id'] == $_POST['id']) {
                $item['nama_tugas'] = $_POST['nama_tugas'];
                $item['deadline'] = $_POST['deadline'];
                $item['status'] = $_POST['status'];
                break;
            }
        }
        saveData($tugasFile, $tugas);
    } elseif (isset($_POST['delete'])) {
        $tugas = array_filter($tugas, function($item) {
            return $item['id'] != $_POST['id'];
        });
        saveData($tugasFile, $tugas);
    }
}

$tugas = loadData($tugasFile);
if (!is_array($tugas)) {
    $tugas = [];
}
$tugas = sortTasks($tugas);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Tugas Kuliah</title>
    <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
    <div class="container">
        <h1>Kelola Tugas Kuliah</h1>
        <nav>
            <a href="index.php">Dashboard</a> |
            <a href="jadwal.php">Jadwal</a> |
            <a href="tugas.php">Tugas</a>
        </nav>

        <div class="form-toggle-container">
            <div class="form-toggle-header" onclick="toggleForm('tugas')">
                <h2>Tambah/Edit Tugas</h2>
            </div>
            <div class="form-toggle-content" id="tugasFormContent">
                <form method="POST" id="tugasForm">
                    <input type="hidden" name="id" id="id" />
                    <label for="nama_tugas">Nama Tugas:</label>
                    <input type="text" name="nama_tugas" id="nama_tugas" required />

                    <label for="deadline">Deadline:</label>
                    <input type="date" name="deadline" id="deadline" required />

                    <label for="status">Status:</label>
                    <select name="status" id="status" required>
                        <option value="Belum">Belum</option>
                        <option value="Selesai">Selesai</option>
                    </select>

                    <button type="submit" name="add" id="addBtn">Tambah</button>
                    <button type="submit" name="edit" id="editBtn" style="display:none;">Simpan Perubahan</button>
                    <button type="button" onclick="resetForm()">Reset</button>
                </form>
            </div>
        </div>

        <h2>Daftar Tugas</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Tugas</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tugas as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nama_tugas']); ?></td>
                    <td><?php echo htmlspecialchars($item['deadline']); ?></td>
                    <td><?php echo htmlspecialchars($item['status']); ?></td>
                    <td>
                        <button onclick="editEntry('<?php echo $item['id']; ?>')">Edit</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
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
            <?php foreach ($tugas as $item): ?>
                if ('<?php echo $item['id']; ?>' === id) {
                    document.getElementById('id').value = '<?php echo $item['id']; ?>';
                    document.getElementById('nama_tugas').value = '<?php echo addslashes($item['nama_tugas']); ?>';
                    document.getElementById('deadline').value = '<?php echo $item['deadline']; ?>';
                    document.getElementById('status').value = '<?php echo $item['status']; ?>';
                    document.getElementById('addBtn').style.display = 'none';
                    document.getElementById('editBtn').style.display = 'inline';

                    // Auto-expand form when editing
                    const content = document.getElementById('tugasFormContent');
                    const header = document.querySelector('.form-toggle-header[onclick="toggleForm(\'tugas\')"]');
                    content.classList.add('expanded');
                    header.classList.add('expanded');
                }
            <?php endforeach; ?>
        }

        function resetForm() {
            document.getElementById('tugasForm').reset();
            document.getElementById('id').value = '';
            document.getElementById('addBtn').style.display = 'inline';
            document.getElementById('editBtn').style.display = 'none';
        }
    </script>
</body>
</html>
