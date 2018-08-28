<?php

$dsn = 'mysql:host=mysqldb;dbname=test;charset=utf8;port=3306';
$pdo = new PDO($dsn, 'root', 'root');
$kinds = ['yes', 'no', 'else'];

for ($i = 0; $i < 10; $i++) {
    $kind = $kinds[array_rand($kinds)];
    $value = "a_" . $i . "_" . mt_rand(1, 100);

    $stmt = $pdo->prepare('INSERT INTO tbl_1 (kind, value) VALUES (:kind, :value)');
    $stmt->execute([
        ':kind' => $kind,
        ':value' => $value
    ]);

    $stmt = $pdo->prepare('INSERT INTO tbl_2 (kind, value) VALUES (:kind, :value)');
    $stmt->execute([
        ':kind' => $kind,
        ':value' => $value
    ]);

    echo "inserted {$kind}, {$value}, {$s1}\n";
}
