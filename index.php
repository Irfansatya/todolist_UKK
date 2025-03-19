<?php
// Koneksi ke database MySQL
$koneksi = mysqli_connect("localhost", "root", "", "ukk2025_todolist");

// Menambahkan task baru
if (isset($_POST['add_task'])) {
    $task = $_POST['task']; // Mengambil input nama task
    $priority = $_POST['priority']; // Mengambil input prioritas task
    $due_date = $_POST['due_date']; // Mengambil input tanggal task

    // Cek apakah semua field terisi
    if (!empty($task) && !empty($priority) && !empty($due_date)) {
        // Menyimpan data task ke dalam tabel "task" dengan status awal 0 (Belum Selesai)
        mysqli_query($koneksi, "INSERT INTO task VALUES ('', '$task', '$priority', '$due_date', '0')");
        echo "<script>alert('Task berhasil ditambahkan')</script>";
    } else {
        echo "<script>alert('Task gagal ditambahkan')</script>";
        header("location: index.php"); // Refresh halaman
    }
}

// Menandai task sebagai selesai
if (isset($_GET['complete'])) {
    $id = $_GET['complete'];
    mysqli_query($koneksi, "UPDATE task SET status = '1' WHERE id = '$id'");
    echo "<script>alert('Task berhasil diselesaikan')</script>";
    header("location: index.php"); // Refresh halaman
}

// Menghapus task dari database
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM task WHERE id = '$id'");
    echo "<script>alert('Task berhasil dihapus')</script>";
    header("location: index.php"); // Refresh halaman
}

// Mengambil semua task dari database dan mengurutkan berdasarkan status, prioritas, dan tanggal
$result = mysqli_query($koneksi, "SELECT * FROM task ORDER BY status ASC, priority DESC, due_date ASC");
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Todo List | UKK RPL 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  </head>
  <body>
    <div class="container mt-2">
        <h2 class="text-center">Aplikasi To Do List</h2>
        
        <!-- Form untuk menambah task baru -->
        <form action="" method="post" class="border rounded bg-light p-2">
            <label class="form-label">Nama Task</label>
            <input type="text" name="task" class="form-control" placeholder="Masukan Task Baru" autocomplete="off" autofocus required>
            
            <label class="form-label">Prioritas</label>
            <select name="priority" class="form-control" required>
                <option value="">--Pilih Prioritas--</option>
                <option value="1">Low</option>
                <option value="2">Medium</option>
                <option value="3">High</option>
            </select>
            
            <label class="form-label">Tanggal</label>
            <input type="date" name="due_date" class="form-control" value="<?php echo date('Y-m-d') ?>" required>
            
            <button class="btn btn-primary w-100 mt-2" name="add_task">Tambah</button>
        </form>
        <br>
        <hr>
        
        <!-- Tabel daftar task -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Task</th>
                    <th>Priority</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) { 
                ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $row['task'] ?></td>
                    <td>
                        <?php
                        if ($row['priority'] == 1) {
                            echo "Low";
                        } elseif ($row['priority'] == 2) {
                            echo "Medium";
                        } else {
                            echo "High";
                        }
                        ?>
                    </td>
                    <td><?php echo $row['due_date']?></td>
                    <td>
                        <?php
                        if ($row['status'] == 0) {
                            echo "<span style='color: red;'>Belum Selesai</span>";
                        } else {
                            echo "<span style='color: green;'>Selesai</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 0) { ?>
                            <a href="?complete=<?php echo $row['id'] ?>" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Selesai</a>
                        <?php } ?>
                        <a href="?delete=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
                <?php 
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>