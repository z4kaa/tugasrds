<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crud_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $nomor_absen = $_POST['nomor_absen'];
    $kelas = $_POST['kelas'];
    
    // Upload file
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = "uploads/" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }
    
    // Insert data
    $sql = "INSERT INTO siswa (nama, nomor_absen, kelas, foto) VALUES ('$nama', '$nomor_absen', '$kelas', '$foto')";
    $conn->query($sql);
}

// Delete data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM siswa WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
}

// Fetch all data
$result = $conn->query("SELECT * FROM siswa");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Data Siswa</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nama" placeholder="Nama" required class="form-control mb-2">
        <input type="number" name="nomor_absen" placeholder="Nomor Absen" required class="form-control mb-2">
        <input type="text" name="kelas" placeholder="Kelas" required class="form-control mb-2">
        <input type="file" name="foto" class="form-control mb-2">
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
    
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Nomor Absen</th>
                <th>Kelas</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['nomor_absen'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><img src="<?= $row['foto'] ?>" width="50"></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
