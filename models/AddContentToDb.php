<?php


namespace app\models;

use yii\base\Model;
use \garyjl\simplehtmldom\SimpleHTMLDom;


class AddContentToDb extends Model
{
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function Add()
    {
        $html = SimpleHTMLDom::file_get_html($this->url);

        // Получаем заголовок
        $title = $html->find('h1');

        // Получаем дату
        $date = $html->find('span[class=dt]');

        // Получаем текст
        $text = $html->find('div[class=description]');

        // Получаем url картинки
        $img_url = $html->find('meta[property=og:image]', 0)->{"content"};

        // Получаем название картинки
        $path_parts = pathinfo($img_url);
        $img_title = $path_parts['filename'] . '.' . $path_parts['extension'];

        // Сохраняем картинку в папку
        $path = $_SERVER['DOCUMENT_ROOT'] . '/yii2_parser_dzdrav/web/img/' . $img_title;
        $get=file_get_contents($img_url);
        if ($get) {
            $get = file_get_contents($img_url);
            $result = file_put_contents($path, $get);
        }

        // Сохраняем контент в базу
        $post = Post::find()
            ->where(['url' => $this->url])
            ->one();
        $post->title = $title[0]->plaintext;
        $post->date = $date[0]->plaintext;
        $post->text = $text[0]->plaintext;
        $post->img = $img_title;
        if($post->save()) {
            echo 'Статья ' . $post->title . ' успешно добавлена в базу' . '<br>';
        }
    }
}