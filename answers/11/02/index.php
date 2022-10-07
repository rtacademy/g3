<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>#11.2</title>
</head>
<body>
<pre>
<?php

$boolean_1 = true;
$boolean_2 = false;

var_dump( $boolean_1 );
var_dump( $boolean_2 );

print_r( $boolean_1 );
print_r( $boolean_2 );

echo( $boolean_1 );
echo( $boolean_2 );

////////////////////////////////////////////////////////////////////////////////

$a = 1234;
$b = 0123;
$c = 0x1A;
$d = 0b11111111;

var_dump( $a );
var_dump( $b );
var_dump( $c );
var_dump( $d );

print_r( $a );
print_r( $b );
print_r( $c );
print_r( $d );

echo( $a );
echo( $b );
echo( $c );
echo( $d );

////////////////////////////////////////////////////////////////////////////////

$a = 1.234;
$b = 1.2e3;
$c = 7E-10;

var_dump( $a );
var_dump( $b );
var_dump( $c );

print_r( $a );
print_r( $b );
print_r( $c );

echo( $a );
echo( $b );
echo( $c );

////////////////////////////////////////////////////////////////////////////////

$string_1 = 'Це просто рядок. Ви вже такий приклад бачили';
$string_2 = 'Одного разу Арнольд сказав: "I\'ll be back"';

var_dump( $string_1 );
var_dump( $string_2 );

print_r( $string_1 );
print_r( $string_2 );

echo( $string_1 );
echo( $string_2 );

$juice    = "apple";
$string_3 = "He drank some $juice juice.";

var_dump( $string_3 );

print_r( $string_3 );

echo( $string_3 );

$name     = "Rachel";
$string_4 = <<<EOD
    Привіт, $name. Це приклад рядка,
    що охоплює декілька рядків,
    з використанням heredoc-синтаксису.
EOD;

var_dump( $string_4 );

print_r( $string_4 );

echo( $string_4 );

$string_5 = <<<'EOD'
     Приклад тексту, який займає декілька рядків, 
     за допомогою синтаксису nowdoc.
     Всі змінні (наприклад $name) не будуть оброблені, а будуть виведені як є.
EOD;

var_dump( $string_5 );

print_r( $string_5 );

echo( $string_5 );

////////////////////////////////////////////////////////////////////////////////

$array_1 = [ 'Привіт', 'Світ', ];
$array_2 =
[
    'hello' => 'Привіт',
    'world' => 'Світ',
];

var_dump( $array_1 );
var_dump( $array_2 );

print_r( $array_1 );
print_r( $array_2 );

echo( $array_1 );
echo( $array_2 );

////////////////////////////////////////////////////////////////////////////////

$null_example = null;

var_dump( $null_example );

print_r( $null_example );

echo( $null_example );

?>
</pre>
</body>
</html>