<?php
class MainController extends Controller
{
    public function actionIndex()
    {
        $this->title = 'Приложение-библиотека';
        $this->meta_desc = 'Приложение выводит список книг из БД '.
            'с пагинацией, и позволяет добавлять/редактировать/удалять '.
            'книги авторизованному пользователю.';
        $this->meta_key = 'приложение-библиотека, библиотека';

        $blog = new Blog();
        $blog->books = BookDB::getAll(
            Config::COUNT_BOOKS_ON_PAGE,
            $this->getOffset(Config::COUNT_BOOKS_ON_PAGE)
        );
        $books_count = BookDB::getCount();
        $blog->books_count =  $books_count;
        $blog->books_count_text = Module::numberOf(
            $books_count,
            ['книга', 'книги', 'книг']
        );
        $blog->is_it_an_admin = $this->is_it_an_admin;
        $blog->pagination = $this->getPagination(
            $books_count,
            Config::COUNT_BOOKS_ON_PAGE,
            '/'
        );

        $this->render($this->renderData(['blog' => $blog], 'index'));
    }

    public function actionDescription()
    {
        $this->title = 'Приложение &laquo;библиотека&raquo; - описание';
        $this->meta_desc = 'Описание работы и функционала приложения-библиотеки.';
        $this->meta_key = 'приложение-библиотека, описание '.
            'приложения-библиотеки, работа приложения-библиотеки, функционал '.
            'приложения-библиотеки';

        $text = 'Веб-приложение &laquo;Библиотека&raquo; содержит список '.
            'книг с пагинацией (по пять книг на странице). Каждая книга '.
            'включает: заголовок, аннотацию, имя автора, ссылку на страницу'.
            ' данной книги, год написания или издания книги, дату и время '.
            'добавления книги в базу данных, описание (description) '.
            'и ключевые слова. К странице книги можно перейти, кликнув '.
            'по её названию. Авторизованный администратор имеет возможность '.
            'добавлять новые, редактировать и удалять уже существующие книги. '.
            'Все вводимые данные проходят проверку на валидность. Сайт '.
            'создан с использованием паттерна MVC и фреймворка Bootstrap 4.';

        $pm = new PageMessage();
        $pm->header = 'Описание приложения-библиотеки';
        $pm->text = $text;

        $this->render($pm);
    }

    public function actionBook()
    {
        $book_db = new BookDB();
        $book_db->load($this->request->id);

        if (!$book_db->isSaved()) {
            $this->notFound();
        }

        $this->title = $book_db->title;
        $this->meta_desc = $book_db->meta_desc;
        $this->meta_key = $book_db->meta_key;

        $hornav = $this->getHornav();
        $hornav->addData($book_db->title);

        $book = new Book();
        $book->hornav = $hornav;
        $book->book = $book_db;
        $book->is_it_an_admin = $this->is_it_an_admin;

        $this->render($book);
    }

    public function actionLogout()
    {
        UserDB::logout();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
