<?php
// Memasukkan file konfigurasi database
include 'config.php';

// Memasukkan header halaman
include '.includes/header.php';

// Mengambil ID postingan yang akan diedit dari parameter URL
// ../edit_post.php?post_id=
$postIdToEdit = $_GET['post_id']; // Pastikan parameter 'post_id' ada di URL

// Query untuk mengambil data postingan berdasarkan ID
$query = "SELECT * FROM posts WHERE id_post = $postIdToEdit";
$result = $conn->query($query);

// Memeriksa apakah data postingan ditemukan
if ($result->num_rows > 0) {
    $post = $result->fetch_assoc(); // Mengambil data postingan ke dalam array
} else {
    // Menampilkan pesan jika postingan tidak ditemukan
    echo "Post not found.";
    exit(); // Menghentikan eksekusi jika tidak ada postingan
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Judul halaman -->
    <h4 class="fw-bold py-3">Mengedit Postingan</h4>
    <div class="row">
        <!-- Form untuk mengedit postingan -->
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Formulir menggunakan metode POST untuk mengirim data -->
                    <form method="POST" action="proses_post.php" enctype="multipart/form-data">
                        <!-- Input tersembunyi untuk menyimpan ID postingan -->
                        <input type="hidden" name="post_id" value="<?= $postIdToEdit; ?>">

                        <!-- Input untuk judul postingan -->
                        <div class="mb-3">
                            <label for="post_title" class="form-label">Judul Postingan</label>
                            <input type="text" class="form-control" id="post_title" name="post_title" value="<?= $post['post_title']; ?>" required>
                        </div>

                        <!-- Input untuk unggah gambar -->
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Unggah Gambar</label>
                            <input class="form-control" type="file" id="formFile" name="image_path" accept="image/*">
                            <?php if (!empty($post['image_path'])): ?>
                                <!-- Menampilkan gambar yang sudah diunggah -->
                                <div class="mt-2">
                                    <img src="<?= $post['image_path']; ?>" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Dropdown untuk kategori -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" selected disabled>Select one</option>
                                <?php
                                // Mengambil data kategori dari database
                                $queryCategories = "SELECT * FROM categories";
                                $resultCategories = $conn->query($queryCategories);
                                // Menambahkan opsi ke dropdown
                                if ($resultCategories->num_rows > 0) {
                                while ($row = $resultCategories->fetch_assoc()) {
                                // Menandai kategori yang sudah dipilih
                                $selected = ($row['category_id'] == $post['category_id']) ? "selected" : "";
                        echo "<option value='" . $row['category_id'] . "' $selected>" . $row['category_name'] . "</option>";
                    }
                }
            ?>
        </select>
    </div>

<!-- Textarea untuk konten postingan -->
<div class="mb-3">
    <label for="content" class="form-label">Konten</label>
    <textarea class="form-control" id="content" name="content" required><?= $post['content']; ?></textarea>
</div>

<!-- Tombol untuk memperbarui postingan -->
<button type="submit" name="update" class="btn btn-primary">Update</button>
</form>
</div>
</div>
</div>
</div>
</div>

<?php
// Memasukkan footer halaman
include '.includes/footer.php';
?>