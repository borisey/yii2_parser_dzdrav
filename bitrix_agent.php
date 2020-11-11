<?php
CAgent::AddAgent(
    "Run_Parser_Function();",
    "N",
    3600,
    "",
    "Y",
    "");
?>

<?php
// Файл /bitrix/php_interface/init.php
function Run_Parser_Function()
{
    header("Location:localhost:8888/yii2_parser_dzdrav/web/?page=1");
    exit;
}
?>
