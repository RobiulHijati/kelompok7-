<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cipher Program Kelompok 7</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label {
            width: 150px; /* Lebar label untuk menyamakan lebar */
            margin-right: 10px;
            text-align: right;
        }
        .form-group select, .form-group input, .form-group textarea {
            flex-grow: 1;
        }
        .btn-download {
            display: inline-block;
            padding: 10px 15px;
            font-size: 14px;
            color: #fff;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-download:hover {
            background-color: #218838;
        }
        textarea {
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cipher Program Kelompok 7</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="cipher">Pilih Cipher:</label>
                <select name="cipher" id="cipher" required>
                    <option value="vigenere" <?php if(isset($_POST['cipher']) && $_POST['cipher'] == 'vigenere') echo 'selected'; ?>>Vigenère Cipher</option>
                    <option value="auto_key_vigenere" <?php if(isset($_POST['cipher']) && $_POST['cipher'] == 'auto_key_vigenere') echo 'selected'; ?>>Auto-Key Vigenère Cipher</option>
                    <option value="playfair" <?php if(isset($_POST['cipher']) && $_POST['cipher'] == 'playfair') echo 'selected'; ?>>Playfair Cipher</option>
                    <option value="hill" <?php if(isset($_POST['cipher']) && $_POST['cipher'] == 'hill') echo 'selected'; ?>>Hill Cipher</option>
                    <option value="super_encryption" <?php if(isset($_POST['cipher']) && $_POST['cipher'] == 'super_encryption') echo 'selected'; ?>>Super Encryption</option>
                </select>
            </div>

            <div class="form-group">
                <label for="key">Kunci:</label>
                <input type="text" name="key" id="key" value="<?php echo isset($_POST['key']) ? htmlspecialchars($_POST['key']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="operation">Pilih Operasi:</label>
                <select name="operation" id="operation" required>
                    <option value="encrypt" <?php if(isset($_POST['operation']) && $_POST['operation'] == 'encrypt') echo 'selected'; ?>>Enkripsi</option>
                    <option value="decrypt" <?php if(isset($_POST['operation']) && $_POST['operation'] == 'decrypt') echo 'selected'; ?>>Dekripsi</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Pesan atau File:</label>
                <textarea name="message" id="message" placeholder="Masukkan pesan di sini..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="file">Unggah File:</label>
                <input type="file" name="file" id="file">
            </div>

            <div class="form-group">
                <label></label>
                <input type="submit" value="Proses">
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cipher = $_POST['cipher'];
            $key = $_POST['key'];
            $message = isset($_POST['message']) ? $_POST['message'] : '';
            $file = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '';
            $operation = isset($_POST['operation']) ? $_POST['operation'] : '';

            include_once "ciphers/$cipher.php";

            if ($file && is_uploaded_file($file)) {
                $file_contents = file_get_contents($file);

                echo "<h3>Input File</h3>";
                echo "<pre>" . htmlspecialchars($file_contents) . "</pre>";  // Tampilkan konten file

                if ($operation === 'encrypt') {
                    $encrypted = encrypt($file_contents, $key);
                    if ($encrypted !== false) {
                        $encoded = base64_encode($encrypted);
                        file_put_contents('ciphered_file.txt', $encoded); // Simpan sebagai file teks

                        echo "<h3>Hasil Enkripsi File</h3>";
                        echo "<pre>" . htmlspecialchars($encoded) . "</pre>";  // Tampilkan hasil enkripsi
                        echo '<a class="btn-download" href="ciphered_file.txt" download="ciphered_file.txt">Download Hasil Enkripsi</a>'; // Link download
                    } else {
                        echo "<p>Enkripsi file gagal.</p>";
                    }
                } elseif ($operation === 'decrypt') {
                    $decodedFile = base64_decode($file_contents); // Decode hasil base64
                    $decrypted = decrypt($decodedFile, $key); // Proses dekripsi
                    if ($decrypted !== false) {
                        file_put_contents('deciphered_file.txt', $decrypted); // Simpan hasil dekripsi sebagai teks
                        
                        echo "<h3>Hasil Dekripsi File</h3>";
                        echo "<pre>" . htmlspecialchars($decrypted) . "</pre>";  // Tampilkan hasil dekripsi
                        echo '<a class="btn-download" href="deciphered_file.txt" download="deciphered_file.txt">Download Hasil Dekripsi</a>'; // Link download
                    } else {
                        echo "<p>Dekripsi file gagal.</p>";
                    }
                }
            } elseif ($message) {
                if ($operation === 'encrypt') {
                    $encrypted = encrypt($message, $key);
                    if ($encrypted !== false) {
                        $encoded = base64_encode($encrypted);
                        file_put_contents('ciphered_message.txt', $encoded); // Simpan sebagai file teks

                        echo "<h3>Hasil Enkripsi Pesan</h3>";
                        echo "<pre>" . htmlspecialchars($encoded) . "</pre>";  // Tampilkan hasil enkripsi
                        echo '<a class="btn-download" href="ciphered_message.txt" download="ciphered_message.txt">Download Hasil Enkripsi</a>'; // Link download
                    } else {
                        echo "<p>Enkripsi pesan gagal.</p>";
                    }
                } elseif ($operation === 'decrypt') {
                    $decodedMessage = base64_decode($message);
                    $decrypted = decrypt($decodedMessage, $key);
                    if ($decrypted !== false) {
                        file_put_contents('deciphered_message.txt', $decrypted); // Simpan hasil dekripsi sebagai teks
                        
                        echo "<h3>Hasil Dekripsi Pesan</h3>";
                        echo "<pre>" . htmlspecialchars($decrypted) . "</pre>";  // Tampilkan hasil dekripsi
                        echo '<a class="btn-download" href="deciphered_message.txt" download="deciphered_message.txt">Download Hasil Dekripsi</a>'; // Link download
                    } else {
                        echo "<p>Dekripsi pesan gagal.</p>";
                    }
                }
            } else {
                echo "<p>Silakan masukkan pesan atau unggah file untuk diproses.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
