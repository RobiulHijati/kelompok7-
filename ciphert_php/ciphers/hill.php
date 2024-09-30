<?php

function modInverse($a, $m) {
    $a = $a % $m;
    if ($a < 0) {
        $a += $m;
    }
    for ($x = 1; $x < $m; $x++) {
        if (($a * $x) % $m == 1) {
            return $x;
        }
    }
    return -1; // Tidak ada invers
}

function matrixMultiply($matrix, $vector, $modulus) {
    $result = [];
    for ($i = 0; $i < count($matrix); $i++) {
        $sum = 0;
        for ($j = 0; $j < count($vector); $j++) {
            $sum += $matrix[$i][$j] * $vector[$j];
        }
        $result[] = ($sum % $modulus + $modulus) % $modulus; // Menangani modulus negatif
    }
    return $result;
}

function hillEncrypt($plaintext, $keyMatrix) {
    $blockSize = count($keyMatrix);
    // Mengubah semua huruf menjadi huruf besar dan hanya menyimpan huruf A-Z
    $plaintext = strtoupper(preg_replace('/[^A-Za-z]/', '', $plaintext)); // Hanya huruf A-Z
    $ciphertext = '';

    while (strlen($plaintext) % $blockSize != 0) {
        $plaintext .= 'X'; // Mengisi dengan 'X' untuk memadatkan blok
    }

    for ($i = 0; $i < strlen($plaintext); $i += $blockSize) {
        $block = [];
        for ($j = 0; $j < $blockSize; $j++) {
            $block[] = ord($plaintext[$i + $j]) - ord('A');
        }

        $encryptedBlock = matrixMultiply($keyMatrix, $block, 26);

        foreach ($encryptedBlock as $num) {
            $ciphertext .= chr($num + ord('A'));
        }
    }
    return $ciphertext;
}

function hillDecrypt($ciphertext, $inverseKeyMatrix) {
    $blockSize = count($inverseKeyMatrix);
    $ciphertext = strtoupper(preg_replace('/[^A-Z]/', '', $ciphertext)); // Hanya huruf A-Z
    $plaintext = '';

    for ($i = 0; $i < strlen($ciphertext); $i += $blockSize) {
        $block = [];
        for ($j = 0; $j < $blockSize; $j++) {
            $block[] = ord($ciphertext[$i + $j]) - ord('A');
        }

        $decryptedBlock = matrixMultiply($inverseKeyMatrix, $block, 26);

        foreach ($decryptedBlock as $num) {
            $plaintext .= chr($num + ord('A'));
        }
    }

    // Hapus padding 'X' di akhir plaintext setelah dekripsi
    return rtrim($plaintext, 'X');
}

function getHillKeyMatrix($key) {
    if (strlen($key) >= 4) {
        return [
            [ord(strtoupper($key[0])) % 26, ord(strtoupper($key[1])) % 26],
            [ord(strtoupper($key[2])) % 26, ord(strtoupper($key[3])) % 26],
        ];
    } else {
        return [
            [3, 3],
            [2, 5]
        ];
    }
}

function determinant($matrix) {
    // Hitung determinan untuk matriks 2x2
    $det = ($matrix[0][0] * $matrix[1][1]) - ($matrix[0][1] * $matrix[1][0]);
    return ($det % 26 + 26) % 26; // Pastikan determinan positif dalam modulus 26
}

function inverseMatrix($matrix, $modulus) {
    $det = determinant($matrix);
    $detInv = modInverse($det, $modulus);

    if ($det == 0 || $detInv == -1) {
        return false; // Matriks tidak bisa di-invers jika determinan adalah 0 atau tidak memiliki invers modul
    }

    // Matriks invers Hill 2x2
    $inverseMatrix = [
        [($matrix[1][1] * $detInv) % $modulus, (-$matrix[0][1] * $detInv) % $modulus],
        [(-$matrix[1][0] * $detInv) % $modulus, ($matrix[0][0] * $detInv) % $modulus]
    ];

    // Pastikan hasil positif dengan modulus
    foreach ($inverseMatrix as &$row) {
        foreach ($row as &$value) {
            $value = ($value % $modulus + $modulus) % $modulus;
        }
    }

    return $inverseMatrix;
}

function encrypt($plaintext, $key) {
    $keyMatrix = getHillKeyMatrix($key);
    return hillEncrypt($plaintext, $keyMatrix);
}

function decrypt($ciphertext, $key) {
    $keyMatrix = getHillKeyMatrix($key);
    $det = determinant($keyMatrix);
    $inverseKeyMatrix = inverseMatrix($keyMatrix, 26);

    if (!$inverseKeyMatrix) {
        return "Dekripsi gagal: Matriks kunci tidak memiliki invers. Determinan: $det.";
    }

    return hillDecrypt($ciphertext, $inverseKeyMatrix);
}
?>
