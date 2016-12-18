<?php

return [
    'certificate_store_path'        => base_path(). env("CERTIFICATE_PATH", ''), // The path to the certificate store (a valid  PKCS#12 file)
    'certificate_store_password'    => env("CERTIFICATE_PASS", ''), // The password to unlo
    'wwdr_certificate_path'         => base_path(). env("WWDR_CERTIFICATE", ''), // Get from here https://www.apple.com/certificateauthority/ and export to PEM
];
