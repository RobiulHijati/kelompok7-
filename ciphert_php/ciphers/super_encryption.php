<?php
include_once 'vigenere.php';

function columnarTransposition($text, $key) {
    $n = strlen($key);
    $columns = array_fill(0, $n, '');

    for ($i = 0; $i < strlen($text); $i++) {
        $columns[$i % $n] .= $text[$i];
    }

    ksort($columns);

    return implode('', $columns);
}

// Ganti nama fungsi untuk menghindari konflik dengan vigenere.php
function superEncrypt($plaintext, $key) {
    $vigenere_cipher = encryptVigenere($plaintext, $key); // Panggil fungsi Vigenère dari vigenere.php
    return columnarTransposition($vigenere_cipher, $key); // Transposisi kolom
}

function superDecrypt($ciphertext, $key) {
    // Implementasi dekripsi
    $plaintext = ''; // Placeholder
    return $plaintext;
}
?>
