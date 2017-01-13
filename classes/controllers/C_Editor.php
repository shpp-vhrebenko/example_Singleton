<?php
require_once('functions/view_helper.php');

class C_Editor extends C_Base
{
    protected function before()
    {
        parent::before();
        $this->menuActive = 'editor';
    }

    // Консоль редактора
    public function action_index()
    {
        $mArticles = M_Articles::getInstance();

        // Проверка существования ГЕТ запроса
        if(isset($_GET['delete'])) {
            // Удаление статьи
            if($mArticles->delete($_GET['delete']) > 0) {

                // Запись сообщения об успешном удалении и редирекет
                $_SESSION['notice'] = 'Статья успешно удаленна';
                $this->redirect('index.php?c=editor&act=index');
            } else {
                $_SESSION['notice'] = 'Ошибка';     // Запись сообщения в случаи ошибки
            }


        }

        // Выборка всех статей в виде списка
        $articles = $mArticles->getList();

        $this->title_page = 'Консоль редактора';    // Заголовок страницы
        $this->title .= '::' . $this->title_page;   // Заголовок сайта

        // Шаблон консоли редактора
        $this->content = $this->template('view/templates/v_editor.php', ['articles' => $articles]);
    }

    // Страница создания новой статьи
    public function action_new()
    {
        $this->title_page = 'Новая статья';         // Заголовок страницы
        $this->title .= '::' . $this->title_page;   // Заголовок сайта

        if($this->isPost()) {
            // Проверка отправки формы
            if(!empty($_POST) && isset($_POST['title']) && isset($_POST['content'])) {

                $mArticles = M_Articles::getInstance();

                // Проверка введенных данных
                if($mArticles->check($_POST['title'], $_POST['content'], 'article')) {

                    // Добавление данных в БД
                    $mArticles->add($_POST['title'], $_POST['content']);

                    // Запись в сессию сообщеня об успешной загрузке
                    $_SESSION['notice'] = 'Статья успешно загружена';
                    $this->redirect('index.php?c=editor&act=index');
                } else {

                    // Если данные не прошли проверку, сохраняем их для повторного вывода в форму
                    $_SESSION['title'] = $_POST['title'];
                    $_SESSION['content'] = $_POST['content'];
                    $this->redirect('index.php?c=editor&act=new');
                }
            }
        }

        // Шаблон добавления новой статьи
        $this->content = $this->template('view/templates/v_new.php');
    }

    // Страница редактирования статьи
    public function action_edit()
    {
        // Редирект, если id не передан
        if(empty($_GET['id'])) {
            $this->redirect('index.php?c=editor&act=index');
        }

        $mArticles = M_Articles::getInstance();

        // Выборка одной статьки, по id
        $article = $mArticles->getOne($_GET['id']);
        $id = $_GET['id'];

        // Проверка отправки формы
        if(!empty($_POST) && isset($_POST['title']) && isset($_POST['content'])) {

            // Сохрание введенных данных в переменную
            $title_new = $_POST['title'];
            $content_new = $_POST['content'];

            // Проверка введенных данных
            if($mArticles->check($title_new, $content_new, 'article')) {

                // Обновление введенных данных в БД
                $mArticles->update($id, $title_new, $content_new);

                // Запись в сессию сообщеня об успешном редактировании
                $_SESSION['notice'] = 'Статья успешно отредактирована';
                $this->redirect('index.php?c=editor&act=index');
            } else {
                $this->redirect("index.php?c=editor&act=edit&id=$id");
            }
        }

        $this->title_page = 'Редактирование статьи';    // Заголовок страницы
        $this->title .= '::' . $this->title_page;       // Заголовок сайта

        // Шаблон редактирования статьи
        $this->content = $this->template('view/templates/v_edit.php', ['article' => $article]);
    }
}