<?php

// базовый контроллека сайта
abstract class C_Base extends C_Controller
{
	protected $title;		// заголовок сайта
    protected $title_page;  // заголовок страницы
	protected $content;		// содержание страницы
    protected $menuActive;

	function __construct()
	{
		// Языковая настройка.
		setlocale(LC_ALL, 'ru_RU.UTF-8');
		mb_internal_encoding('UTF-8');

		// запуск сесси
		session_start();
	}

	protected function before()
	{
		$this->title = 'Мой сайт';	// заголовок сайта
		$this->title_page = '';		// заголовок страницы
		$this->content = '';        // содержимое страницы
	}

	protected function validateParam($value, $valid)
	{
		if(in_array((int)$value, $valid)) {
			return true;
		}
		return false;
	}

	// генерация базового шаблона
	public function render()
	{
		$vars = ['title' => $this->title, 'content' => $this->content, 'title_page' => $this->title_page, 'menuActive' => $this->menuActive];
		$page = $this->template('view/v_main.php', $vars);
		echo $page;
	}
}