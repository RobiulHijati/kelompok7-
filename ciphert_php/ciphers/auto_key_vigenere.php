<?php
function autoKeyVigenereEncrypt($plaintext, $key) {
    $key = strtoupper($key); // Konversi kunci menjadi huruf besar
    
    // Buat ciphertext dengan memperhitungkan huruf besar-kecil dan non-alfabet
    $ciphertext = '';
    $keyIndex = 0;
    
    for ($i = 0; $i < strlen($plaintext); $i++) {
        $plainChar = $plaintext[$i];
        
        // Hanya enkripsi huruf alfabet, biarkan karakter non-alfabet tetap utuh
        if (ctype_alpha($plainChar)) {
            $plainCharUpper = strtoupper($plainChar); // Konversi ke huruf besar untuk diproses
            $plainValue = ord($plainCharUpper) - ord('A');
            $keyChar = ord($key[$keyIndex % strlen($key)]) - ord('A');
            $cipherValue = ($plainValue + $keyChar) % 26;
            $cipherChar = chr($cipherValue + ord('A'));

            // Kembalikan huruf ke case (besar/kecil) aslinya
            if (ctype_lower($plainChar)) {
                $ciphertext .= strtolower($cipherChar);
            } else {
                $ciphertext .= $cipherChar;
            }

            $key .= $plainCharUpper; // Tambahkan huruf ke kunci
            $keyIndex++;
        } else {
            $ciphertext .= $plainChar; // Tambahkan karakter non-alfabet
        }
    }

    return $ciphertext;
}

function encrypt($plaintext, $key) {
    return autoKeyVigenereEncrypt($plaintext, $key);
}

function decrypt($ciphertext, $key) {
    $key = strtoupper($key); // Konversi kunci menjadi huruf besar
    $plaintext = '';
    $keyIndex = 0;

    for ($i = 0; $i < strlen($ciphertext); $i++) {
        $cipherChar = $ciphertext[$i];

        // Hanya dekripsi huruf alfabet, biarkan karakter non-alfabet tetap utuh
        if (ctype_alpha($cipherChar)) {
            $cipherCharUpper = strtoupper($cipherChar); // Konversi ke huruf besar untuk diproses
            $cipherValue = ord($cipherCharUpper) - ord('A');
            $keyChar = ord($key[$keyIndex % strlen($key)]) - ord('A');
            $plainValue = ($cipherValue - $keyChar + 26) % 26;
            $plainChar = chr($plainValue + ord('A'));

            // Kembalikan huruf ke case (besar/kecil) aslinya
            if (ctype_lower($cipherChar)) {
                $plaintext .= strtolower($plainChar);
            } else {
                $plaintext .= $plainChar;
            }

            $key .= $plainChar; // Tambahkan huruf ke kunci
            $keyIndex++;
        } else {
            $plaintext .= $cipherChar; // Tambahkan karakter non-alfabet
        }
    }

    return $plaintext;
}
?>
