<?php

    namespace App\Scripts;

    class PostInstall
    {
        public static function welcomeMessage()
        {
            $projectFolderName = basename(getcwd());

            echo "\nWelcome to SymStartSaaS!\n\n";
            echo "\t> cd " . $projectFolderName . "\n";
            echo "\t> composer install\n";
            echo "\t> npm install\n";
            echo "\t> npm run watch\n\n";
            echo "You can find the documentation at https://symstartsaas.mathieulp.fr/docs\n\n";
        }
    }
