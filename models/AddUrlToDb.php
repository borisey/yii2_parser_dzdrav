<?php


namespace app\models;

use yii\base\Model;
use \garyjl\simplehtmldom\SimpleHTMLDom;


class AddUrlToDb extends Model
{
    public $url;
    public $page;

    public function __construct($url, $page)
    {
        $this->url = $url;
        $this->page = $page;
    }

    public function Add()
    {
        $url = "$this->url$this->page";

        // Проверяем на наличие ошибки 404
        $headers = get_headers($url);
        $headers = explode(' ', $headers [0]);
        if ($headers[1] != 404) {
            $html = SimpleHTMLDom::file_get_html("$url");

            // Получаем все ссылки с классом header
            $urls = $html->find('a[class=header]');

            foreach ($urls as $url) {
                // Проверяем есть ли URL в базе
                $check_isset_url = Post::find()
                    ->where(['url' => $url->href])
                    ->one();
                // Если URL уже есть в базе, то не сохраняем данные
                if ($check_isset_url) {
                    echo 'URL ' . $url->href . ' уже существует в базе и не может быть добавлен' . '<br>';
                // Сохраняем URL в базу
                } else {
                    $posts = new Post;
                    $posts->url = $url->href;
                    if ($posts->save()) {
                        // Добавляем контент в базу
                        $add_content_to_db = new AddContentToDb($url->href);
                        $add_content_to_db->add();
                    } else {
                        echo 'URL не добавлен' . '<br>';
                    }
                }
            }
            $page = $this->page + 1;
            header("Refresh:0; url=?page=$page");
        } else {
            echo 'Выполнение скрипта завершено';
        }
    }
}
