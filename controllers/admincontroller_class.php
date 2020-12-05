<?php
class AdminController extends Controller
{
    public function actionAddBook()
    {
        $this->title = 'Добавление книги';
        $this->meta_desc = 'Добавление книги в библиотеку.';
        $this->meta_key = 'добавление книги, добавление книги на сайт,'.
            ' добавить книгу на сайт, добавить книгу в библиотеку';

        $message_add_book = 'add_book';
        $message_add_sef = 'add_sef';

        if ($this->request->add_book_form) {
            $sef_alias = Translit::translitStr($this->request->add_book_title);
            $book_link = '/'. $sef_alias.Config::SEF_SUFFIX;
            $checks[] = [
                BookDB::existsLink($book_link),
                false,
                'ERROR_LINK_ALREADY_EXISTS'
            ];
            $fields = [
                ['title', $this->request->add_book_title],
                ['annotation', $this->request->add_book_annotation],
                ['author', $this->request->add_book_author],
                ['link', $book_link],
                ['year', $this->request->add_book_year],
                ['meta_desc', $this->request->add_book_md],
                ['meta_key', $this->request->add_book_mk]
            ];
            $added_book = $this->fp->process(
                $message_add_book,
                new BookDB(),
                $fields,
                $checks
            );

            if ($added_book instanceof BookDB) {
                $sef_link = 'book?id='.$added_book->id;
                $added_sef = $this->fp->process(
                    $message_add_sef,
                    new SefDB(),
                    [['link', '/'.$sef_link], ['alias', $sef_alias]]
                );

                if ($added_sef instanceof SefDB) {
                    $this->redirect(URL::get($sef_link));
                } else {
                    $msg = $this->fp->getSessionMessage($message_add_sef);
                    $book_db_del = new BookDB();
                    $book_db_del->load($added_book->id);
                    $this->fp->delete('', $book_db_del);
                }
            }
        }

        $form_add_book = new Form();
        $form_add_book->name = 'add_book_form';
        $form_add_book->action = URL::current();
        $form_add_book->message = $msg ?? $this->fp->getSessionMessage(
            $message_add_book
        );
        $form_add_book->form_header = 'Добавить книгу';
        $form_add_book->text(
            'add_book_title',
            'Название книги:*',
            $this->request->add_book_title
        );
        $form_add_book->textarea(
            'add_book_annotation',
            'Аннотация:*',
            7,
            $this->request->add_book_annotation
        );
        $form_add_book->text(
            'add_book_author',
            'Автор:*',
            $this->request->add_book_author
        );
        $form_add_book->number(
            'add_book_year',
            'Год написания или издания:*',
            ValidateYear::MAX_VAL,
            ValidateYear::MIN_VAL,
            $this->request->add_book_year
        );
        $form_add_book->text(
            'add_book_md',
            'Описание (description):*',
            $this->request->add_book_md
        );
        $form_add_book->text(
            'add_book_mk',
            'Ключевые слова:*',
            $this->request->add_book_mk
        );
        $form_add_book->submit('Сохранить');

        $form_add_book->addJSV('add_book_title', $this->jsv->title(true, true));
        $form_add_book->addJSV(
            'add_book_annotation',
            $this->jsv->textInput('ValidateAnnotation', true, true, true)
        );
        $form_add_book->addJSV(
            'add_book_author',
            $this->jsv->name('ValidateAuthor')
        );
        $form_add_book->addJSV('add_book_year', $this->jsv->number());
        $form_add_book->addJSV(
            'add_book_md',
            $this->jsv->textInput('ValidateMD')
        );
        $form_add_book->addJSV(
            'add_book_mk',
            $this->jsv->textInput('ValidateMK')
        );

        $hornav = $this->getHornav();
        $hornav->addData('Добавить книгу');

        $this->render(
            $this->renderData(
                ['hornav' => $hornav, 'form_add_book' => $form_add_book],
                'addbook'
            )
        );
    }

    public function actionEditBook()
    {
        $book_db = new BookDB();
        $book_db->load($this->request->id);

        if (!$book_db->isSaved()) {
            $this->notFound();
        }

        $message_edit_sef = 'edit_sef';
        $message_edit_text = 'edit_text_data';
        $message_edit_cover = 'edit_cover';

        if ($this->request->change_cover) {
            $img = $this->fp->uploadIMG(
                $message_edit_cover,
                $_FILES['edit_book_cover'],
                Config::MAX_SIZE_IMG,
                Config::DIR_COVERS
            );

            if ($img) {
                $tmp = $book_db->getCover();
                $obj = $this->fp->process(
                    $message_edit_cover,
                    $book_db,
                    [['cover', $img]],
                    [],
                    'SUCCESS_COVER_CHANGE'
                );

                if ($obj instanceof BookDB) {
                    if ($tmp) {
                        File::delete(Config::DIR_COVERS.$tmp);
                    }

                    $this->redirect(URL::current());
                }
            }
        } elseif ($this->request->edit_book_form) {
            $old_sef_alias = Translit::translitStr($book_db->title);
            $new_sef_alias = Translit::translitStr($this->request->edit_book_title);
            $new_book_link = '/'.$new_sef_alias.Config::SEF_SUFFIX;
            $is_ex_same_link = BookDb::existsOtherSameLink(
                $new_book_link,
                $book_db->id
            );

            $checks = [[$is_ex_same_link, false, 'ERROR_ALIAS_ALREADY_EXISTS']];
            $fields = [
                ['title', $this->request->edit_book_title],
                ['annotation', $this->request->edit_book_annotation],
                ['author', $this->request->edit_book_author],
                ['link', $new_book_link],
                ['year', $this->request->edit_book_year],
                ['date', $book_db->getDate()],
                ['meta_desc', $this->request->edit_book_md],
                ['meta_key', $this->request->edit_book_mk]
            ];
            $edited_book = $this->fp->process(
                $message_edit_text,
                $book_db,
                $fields,
                $checks
            );

            if ($edited_book instanceof BookDB) {
                $sef_db = new SefDB();
                $sef_db->loadOnAlias($old_sef_alias);

                $edited_sef = $this->fp->process(
                    $message_edit_sef,
                    $sef_db,
                    [['alias', $new_sef_alias]]
                );

                if ($edited_sef instanceof SefDB) {
                    $this->redirect(URL::get(substr($sef_db->link, 1)));
                } else {
                    $msg = $this->fp->getSessionMessage($message_edit_sef);
                }
            }
        }

        $this->title = 'Редактирование книги '.$book_db->title;;
        $this->meta_desc = 'Редактирование книги '.$book_db->title;
        $this->meta_key = 'редактирование книги, '.mb_strtolower($book_db->title);

        $form_text = new Form();
        $form_text->name = 'edit_book_form';
        $form_text->action = URL::current();
        $form_text->message = $msg ?? $this->fp->getSessionMessage(
            $message_edit_text
        );
        $form_text->text('edit_book_title', 'Название книги:*', $book_db->title);
        $form_text->textarea('edit_book_annotation', 'Аннотация:*', 7, $book_db->annotation);
        $form_text->text('edit_book_author', 'Автор:*', $book_db->author);
        $form_text->number(
            'edit_book_year',
            'Год написания или издания:*',
            ValidateYear::MAX_VAL,
            ValidateYear::MIN_VAL,
            $book_db->year
        );
        $form_text->text(
            'edit_book_md',
            'Описание (description):*',
            $book_db->meta_desc
        );
        $form_text->text('edit_book_mk', 'Ключевые слова:*',  $book_db->meta_key);
        $form_text->submit('Сохранить');

        $form_text->addJSV(
            'edit_book_title',
            $this->jsv->title(true, true)
        );
        $form_text->addJSV(
            'edit_book_annotation',
            $this->jsv->textInput('ValidateAnnotation', true, true, true)
        );
        $form_text->addJSV(
            'edit_book_author',
            $this->jsv->name('ValidateAuthor')
        );
        $form_text->addJSV('edit_book_year', $this->jsv->number());
        $form_text->addJSV(
            'edit_book_md',
            $this->jsv->textInput('ValidateMD')
        );
        $form_text->addJSV(
            'edit_book_mk',
            $this->jsv->textInput('ValidateMK')
        );

        $form_cover = new Form();
        $form_cover->name = 'change_cover';
        $form_cover->action = URL::current();
        $form_cover->enctype = 'multipart/form-data';
        $form_cover->message = $this->fp->getSessionMessage($message_edit_cover);
        $form_cover->fileImage('edit_book_cover', 'Изображение обложки: ');
        $form_cover->submit('Сохранить');

        $form_cover->addJSV('edit_book_cover', $this->jsv->image());

        $hornav = $this->getHornav();
        $hornav->addData('Редактирование');
        $hornav->addData($book_db->title, $book_db->link);

        $this->render(
            $this->renderData(
                [
                    'hornav' => $hornav,
                    'form_text' => $form_text,
                    'form_cover' => $form_cover
                ],
                'editbook',
                [
                    'cover' => '..'.$book_db->cover,
                    'max_size' => (Config::MAX_SIZE_IMG / KB_B)
                ]
            )
        );
    }

    public function actionDelBook()
    {
        $del_book = new BookDB();
        $del_book->load($this->request->id);

        if (!$del_book->isSaved()) {
            $this->notFound();
        }

        $link = '/book?id='.$del_book->id;
        $del_sef = new SefDB();
        $del_sef->loadOnLink($link);
        $tmp = $del_book->getCover();
        $success_del_book = $this->fp->delete('', $del_book);
        $success_del_sef = $this->fp->delete('', $del_sef);

        if ($success_del_book && $success_del_sef) {
            if ($tmp) {
                File::delete(Config::DIR_COVERS.$tmp);
            }

            $this->redirect(URL::get('sdelbook', 'admin'));
        } else {
            $this->redirect(URL::get('errdelbook', 'admin'));
        }
    }

    public function actionSDelBook()
    {
        $this->title = 'Книга удалена';
        $this->meta_desc = 'Книга удалена.';
        $this->meta_key = 'удаление книги, книга удалена';

        $hornav = $this->getHornav();
        $hornav->addData('Книга удалена');

        $pm = new PageMessage();
        $pm->hornav = $hornav;
        $pm->header = 'Книга удалена';
        $pm->text = 'Книга успешно удалена из базы данных.';

        $this->render($pm);
    }

    public function actionErrDelBook()
    {
        $this->title = 'Ошибка удаления книги';
        $this->meta_desc = 'Ошибка удаления книги.';
        $this->meta_key = 'удаление книги, ошибка удаления книги';

        $hornav = $this->getHornav();
        $hornav->addData('Книга не удалена');

        $pm = new PageMessage();
        $pm->hornav = $hornav;
        $pm->header = 'Ошибка удаления книги';
        $pm->text = 'Книга не была удалена из базы данных или была удалена '.
            'некорректно. Это может привести к некорректному отображению '.
            'книг. Для исправления ошибки обратитесь к администрации сайта.';

        $this->render($pm);
    }

    protected function access()
    {
        if ($this->is_it_an_admin) {
            return true;
        }

        return false;
    }
}
