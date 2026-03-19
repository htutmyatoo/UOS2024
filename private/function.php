<?php
function generateKey() {
  $key = openssl_random_pseudo_bytes(32);
  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
  return array($key, $iv);
}
function generateOtp() {
  list($key, $iv) = generateKey(); // Generate random key and IV
  $otp = bin2hex(random_bytes(3)); // Generate OTP
  $encryptedOtp = openssl_encrypt($otp, 'AES-256-CBC', $key, 0, $iv); // Encrypt with new key and IV
  $timestamp = time();

  // Store encrypted OTP, timestamp, key, and IV in a file
  $dataToStore = $encryptedOtp . '|' . $timestamp . '|' . $key . '|' . $iv;
  if (!file_put_contents('otp_data.txt', $dataToStore)) {
      throw new Exception('Failed to create or write to file');
  }

  return $otp;
}
function verifyOtp($userOtp) {
  $otpData = file_get_contents('otp_data.txt');
  list($storedEncryptedOtp, $storedTimestamp, $storedKey, $storedIv) = explode('|', $otpData);

  // Decrypt and compare
  $decryptedOtp = openssl_decrypt($storedEncryptedOtp, 'AES-256-CBC', $storedKey, 0, $storedIv);
  if ($decryptedOtp === $userOtp && time() - $storedTimestamp <= 300) {
      unlink('otp_data.txt');
      return true;
  } else {
      return false;
  }
}
