<?php
// Fungsi untuk membuat key matrix 5x5
function generateKeyMatrix($key) {
    $key = strtoupper(preg_replace('/[^A-Z]/', '', $key));
    $key = str_replace('J', 'I', $key); // Playfair menggabungkan I dan J
    $key .= "ABCDEFGHIKLMNOPQRSTUVWXYZ"; // Menghilangkan huruf 'J'
    $matrix = [];
    $used = [];

    for ($i = 0, $k = 0; $i < 5; $i++) {
        for ($j = 0; $j < 5; $j++) {
            while (isset($used[$key[$k]])) {
                $k++;
            }
            $matrix[$i][$j] = $key[$k];
            $used[$key[$k]] = true;
            $k++; // Pastikan untuk melanjutkan ke karakter berikutnya
        }
    }
    return $matrix;
}

// Fungsi untuk mempersiapkan bigram
function prepareText($text) {
    $text = preg_replace('/[^A-Za-z]/', '', $text); // Mempertahankan huruf besar dan kecil
    $text = str_replace(['j', 'J'], 'I', $text); // Menggabungkan I dan J

    $preparedText = '';
    for ($i = 0; $i < strlen($text); $i += 2) {
        $pair = $text[$i];
        if ($i + 1 < strlen($text)) {
            if (strtoupper($text[$i]) == strtoupper($text[$i + 1])) {
                $pair .= 'X'; // Sisipkan X jika ada dua huruf yang sama
            } else {
                $pair .= $text[$i + 1];
            }
        } else {
            // Jangan tambahkan 'X' jika panjang teks ganjil
            // $pair .= 'X'; // Baris ini dihapus
        }
        $preparedText .= $pair;
    }

    return $preparedText;
}

// Fungsi untuk mencari posisi huruf di dalam matriks
function findPosition($matrix, $char) {
    $char = strtoupper($char); // Pastikan pencarian posisi selalu uppercase
    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 5; $j++) {
            if ($matrix[$i][$j] === $char) {
                return [$i, $j];
            }
        }
    }
    return null;
}

// Fungsi untuk mengenkripsi plaintext menggunakan Playfair Cipher
function encrypt($plaintext, $key) {
    $matrix = generateKeyMatrix($key);
    $preparedText = prepareText($plaintext);
    $ciphertext = '';

    for ($i = 0; $i < strlen($preparedText); $i += 2) {
        $char1 = $preparedText[$i];
        $char2 = isset($preparedText[$i + 1]) ? $preparedText[$i + 1] : 'X'; // Ganti dengan 'X' jika tidak ada karakter kedua

        list($row1, $col1) = findPosition($matrix, $char1);
        list($row2, $col2) = findPosition($matrix, $char2);

        if ($row1 === $row2) {
            $ciphertext .= $matrix[$row1][($col1 + 1) % 5];
            $ciphertext .= $matrix[$row2][($col2 + 1) % 5];
        } elseif ($col1 === $col2) {
            $ciphertext .= $matrix[($row1 + 1) % 5][$col1];
            $ciphertext .= $matrix[($row2 + 1) % 5][$col2];
        } else {
            $ciphertext .= $matrix[$row1][$col2];
            $ciphertext .= $matrix[$row2][$col1];
        }
    }

    return $ciphertext; // Mengembalikan ciphertext
}

// Fungsi untuk mendekripsi ciphertext menggunakan Playfair Cipher
function decrypt($ciphertext, $key) {
    $matrix = generateKeyMatrix($key);
    $ciphertext = preg_replace('/[^A-Za-z]/', '', $ciphertext); // Pertahankan casing asli
    $plaintext = '';

    for ($i = 0; $i < strlen($ciphertext); $i += 2) {
        $char1 = $ciphertext[$i];
        $char2 = isset($ciphertext[$i + 1]) ? $ciphertext[$i + 1] : 'X'; // Ganti dengan 'X' jika tidak ada karakter kedua

        list($row1, $col1) = findPosition($matrix, $char1);
        list($row2, $col2) = findPosition($matrix, $char2);

        if ($row1 === $row2) {
            $plaintext .= $matrix[$row1][($col1 - 1 + 5) % 5];
            $plaintext .= $matrix[$row2][($col2 - 1 + 5) % 5];
        } elseif ($col1 === $col2) {
            $plaintext .= $matrix[($row1 - 1 + 5) % 5][$col1];
            $plaintext .= $matrix[($row2 - 1 + 5) % 5][$col2];
        } else {
            $plaintext .= $matrix[$row1][$col2];
            $plaintext .= $matrix[$row2][$col1];
        }
    }

    return rtrim($plaintext, 'X'); // Menghilangkan 'X' di belakang plaintext
}

// Fungsi utama untuk enkripsi dan dekripsi
function playfairCipher($text, $key, $mode) {
    if ($mode === 'encrypt') {
        return encrypt($text, $key);
    } elseif ($mode === 'decrypt') {
        return decrypt($text, $key);
    }
    return '';
}
?>
