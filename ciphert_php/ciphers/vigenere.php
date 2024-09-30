<?php

function vigenereEncrypt($plaintext, $key) {
    $key = strtoupper($key); // Kunci selalu dikonversi menjadi huruf besar
    $keyLength = strlen($key);
    $keyIndex = 0;
    $ciphertext = '';

    for ($i = 0; $i < strlen($plaintext); $i++) {
        $plainChar = $plaintext[$i]; // Simpan karakter asli

        // Hanya enkripsi huruf alfabet
        if (ctype_alpha($plainChar)) {
            $plainCharUpper = strtoupper($plainChar); // Konversi karakter menjadi huruf besar untuk enkripsi
            $plainCharValue = ord($plainCharUpper) - ord('A');
            $keyCharValue = ord($key[$keyIndex % $keyLength]) - ord('A');
            $cipherCharValue = ($plainCharValue + $keyCharValue) % 26;
            $cipherChar = chr($cipherCharValue + ord('A'));
            
            // Mempertahankan casing asli
            if (ctype_lower($plainChar)) {
                $ciphertext .= strtolower($cipherChar); // Menjaga huruf tetap kecil
            } else {
                $ciphertext .= $cipherChar; // Menjaga huruf tetap besar
            }
            $keyIndex++;
        } else {
            $ciphertext .= $plainChar; // Tambahkan karakter non-alfabet tanpa modifikasi
        }
    }

    return $ciphertext; // Mengembalikan ciphertext
}

function encrypt($plaintext, $key) {
    return vigenereEncrypt($plaintext, $key); // Mengembalikan hasil enkripsi
}

function decrypt($ciphertext, $key) {
    $key = strtoupper($key); // Kunci selalu dikonversi menjadi huruf besar
    $keyLength = strlen($key);
    $keyIndex = 0;
    $plaintext = '';

    for ($i = 0; $i < strlen($ciphertext); $i++) {
        $cipherChar = $ciphertext[$i]; // Simpan karakter asli

        // Hanya dekripsi huruf alfabet
        if (ctype_alpha($cipherChar)) {
            $cipherCharUpper = strtoupper($cipherChar); // Konversi karakter menjadi huruf besar untuk dekripsi
            $cipherCharValue = ord($cipherCharUpper) - ord('A');
            $keyCharValue = ord($key[$keyIndex % $keyLength]) - ord('A');
            $plainCharValue = ($cipherCharValue - $keyCharValue + 26) % 26;
            $plainChar = chr($plainCharValue + ord('A'));
            
            // Mempertahankan casing asli
            if (ctype_lower($cipherChar)) {
                $plaintext .= strtolower($plainChar); // Mengubah hasil dekripsi menjadi huruf kecil
            } else {
                $plaintext .= $plainChar; // Mengubah hasil dekripsi menjadi huruf besar
            }
            $keyIndex++;
        } else {
            $plaintext .= $cipherChar; // Tambahkan karakter non-alfabet tanpa modifikasi
        }
    }

    return $plaintext; // Mengembalikan plaintext
}
?>
