<?php

    namespace App\Scripts;

    class PostInstall
    {
        public static function welcomeMessage()
        {
            $projectFolderName = basename(getcwd());

            echo "\nWelcome to SymStartSaaS!\n\n";
            echo "> cd " . $projectFolderName . "\n";
            echo "> composer install\n";
            echo "> npm install\n\n";
            echo "You can find the documentation at https://symstartsaas.mathieulp.fr/docs\n\n";
        }
    }
