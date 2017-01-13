<?php
require_once('functions/view_helper.php');

class C_Articles extends C_Base
{
    protected function before()
    {
        parent::before();
        $this->menuActive = 'article';
    }

	// Главная страница
	public function action_index()
	{
        if($_SESSION['num'] === null) {
            $_SESSION['num'] = 5;
        }

        if(isset($_GET['num'])) {
            $valid_a = [3, 5, 10];                    // Допустимые значения при выборе сортировки статей
            if($this->validateParam($_GET['num'], $valid_a)) {
                $_SESSION['num'] = $_GET['num'];
            }
            $this->redirect($_SERVER['PHP_SELF']);
        }

		$mArticles = M_Articles::getInstance();
		$count = $mArticles->count();				// Подсчет кол-ва статей в БД

		$n = $count / $_SESSION['num'];

		// Проверка ГЕТ запроса, содержащего номер страницы
        if(isset($_GET['page'])) {
            $valid_a = range(1, ceil($n));
            if(!$this->validateParam($_GET['page'], $valid_a)) {
                $this->redirect($_SERVER['PHP_SELF']);
            }
        }

        // Выборка статей в виде превью
        $articles = $mArticles->getIntro(40, $_GET['page'], $_SESSION['num']);

        $this->title_page = 'Главная';              // Заголовок страницы
        $this->title .= '::' . $this->title_page;   // Заголовок сайта

        // Шаблон с выбором кол-ва статей на одной странице
        $sort = $this->template('view/templates/block/v_block_sort.php');

        // Шаблон постраничной навигации
        $nav = $this->template('view/templates/block/v_block_nav.php', ['n' => $n]);

        // Шаблон главной страницы
		$this->content = $this->template('view/templates/v_index.php', ['articles' => $articles, 'nav' => $nav, 'sort' => $sort]);
	}

	// Страница просмотра одной статьи
	public function action_article()
	{
        $mArticles = M_Articles::getInstance();

        if($this->isGet()) {
            // Выборка одной статьи
            $article = $mArticles->getOne($_GET['id']);
            $comments = $mArticles->getComments($_GET['id']);
        }

        if($this->isPost()) {
            if(!empty($_POST) && isset($_POST['name']) && isset($_POST['comment']) && isset($_GET['id'])) {

                // Проверка введенных данных
                if($mArticles->check($_POST['name'], $_POST['comment'], 'comment')) {

                    // Добавление данных в БД
                    $mArticles->addComment($_GET['id'], $_POST['name'], $_POST['comment']);

                    // Запись в сессию сообщеня об успешной загрузке
                    $_SESSION['notice'] = 'Комментарий успешно добавлен';
                    $this->redirect($_SERVER['REQUEST_URI']);
                } else {
                    // Если данные не прошли проверку, сохраняем их для повторного вывода в форму
                    $_SESSION['name'] = $_POST['name'];
                    $_SESSION['comment'] = $_POST['comment'];
                    $this->redirect($_SERVER['REQUEST_URI']);
                }
            }
        }

		$this->title_page = $article['title'];      // Заголовок страницы
		$this->title .= '::' . $this->title_page;   // Заголовок сайта

        // Шаблон одной статьи
		$this->content = $this->template('view/templates/v_article.php', ['article' => $article, 'comments' => $comments]);
	}
}