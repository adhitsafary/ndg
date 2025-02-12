<?php

// Path ke file artisan
$path = '/home/netdigit/public_html/dashboard2/artisan';

// Jalankan Artisan schedule:run
shell_exec("php $path schedule:run >> /home/netdigit/public_html/dashboard2/storage/logs/cron.log 2>&1");
