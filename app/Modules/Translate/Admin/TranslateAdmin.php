<?php

namespace Modules\Translate\Admin;

use Gettext\Generators\Po;
use Gettext\Merge;
use Gettext\Translations;
use Modules\Admin\Contrib\Admin;
use Modules\Translate\Forms\TranslateCreateForm;
use Modules\Translate\Forms\TranslatesFilterForm;
use Modules\Translate\Forms\TranslateForm;
use Modules\Translate\Models\LanguageModel;
use Modules\Translate\Models\TranslateModel;
use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Translator;
use Throwable;
use Xcart\App\Main\Xcart;

/**
 * CRUD
 */
class TranslateAdmin extends Admin
{
    public string $allList = '_list.tpl';
    public $listRowTemplate = '_tr.tpl';

    /**
     * describe list of fields that need print in table
     */
    public function getListColumns()
    {
        return [
            'lang_code',
            'msgctxt',
            'msgstr',
        ];
    }

    /**
     * Edit form
     */
    public function getForm()
    {
        return new TranslateForm;
    }

    /**
     * CRUD row model
     */
    public function getModel()
    {
        return new TranslateModel;
    }

    public static function getName()
    {
        return 'Translate Manager';
    }

    private function readPO( string $lang_code ): Translations
    {
        $path = Xcart::app()->getModule( 'Translate' )->getPath();
        return Translations::fromPoFile( "$path/lang/$lang_code.po" );

    }

    private function writePO( Translations $translations, string $lang_code ): void
    {
        $path = Xcart::app()->getModule( 'Translate' )->getPath();
        Po::toFile( $translations, "$path/lang/$lang_code.po" );
    }

    private function getCreateForm()
    {
        return new TranslateCreateForm;
    }

    /**
     * Write new translation to file
     * @param TranslateModel $model new translate object
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    private function updateTranslation( TranslateModel $model ): void
    {
        //get new translation
        $translation = $model->asTranslation();
        $new_translations = new Translations;
        $new_translations->append( $translation );

        //get list translations
        $lang_code = $model->getAttribute( 'lang_code' );
        $translations = $this->readPO( $lang_code );

        //update translations
        $translations->mergeWith( $new_translations, Merge::DEFAULTS | Merge::TRANSLATION_OVERRIDE );
        $this->writePO( $translations, $lang_code );
    }

    /**
     * find translate by primary key in po file
     * @param string $pk
     * @return TranslateModel
     * @throws Throwable
     */
    private function fetchTranslateByKey( string $pk ): TranslateModel
    {
        $lang_code = substr( $pk, 0, 2 );
        $msg_id = urldecode( substr( $pk, 3 ) );

        foreach ( $this->readPO( $lang_code ) as $translate ) {
            if ( $translate->getOriginal() === $msg_id ) {
                break;
            }
        }

        return new TranslateModel( [
            'id' => $pk,
            'lang_code' => $lang_code,
            'msgctxt' => $msg_id,
            'msgid' => $msg_id,
            'msgstr' => isset( $translate ) ? $translate->getTranslation() : '',
        ] );
    }

    public function remove( $pk = null )
    {
        //get new translation
        $model = $this->fetchTranslateByKey( $pk );
        $remove_translations = new Translations;
        $remove_translations->append( $model->asTranslation() );

        //get list translations
        $lang_code = $model->getAttribute( 'lang_code' );
        $translations = $this->readPO( $lang_code );

        //update translations
        $new_translations = new Translations;
        foreach ( $translations as $item ) {
            if ( $remove_translations->find( $item ) ) {
                continue;
            }

            $new_translations->append( $item );
        }

        $this->writePO( $new_translations, $lang_code );
        $this->jsonResponse( [ 'success' => true ] );
    }

    /**
     * @param null $pk
     * @param null $parent_id
     * @throws Throwable
     */
    public function update( $pk = null, $parent_id = null ): void
    {
        $this->setBreadcrumbs();
        $req_method = Xcart::app()->request->getMethod();
        $form = $pk ? $this->getUpdateForm() : $this->getCreateForm();

        if ( $req_method === 'GET' ) {
            $form->populate( $_GET, $_FILES );
            $translate_model = $pk ? $this->fetchTranslateByKey( $pk ) : new TranslateModel;
        }
        elseif (
            $req_method === 'POST'
            && $form->populate( $_POST, $_FILES )
            && isset( $_POST[ 'save' ] )
        ) {
            if ( $form->isValid() ) {
                $translate_model = $form->getInstance();
                $this->updateTranslation( $translate_model );
                Xcart::app()->flash->success( 'Changes have been successfully applied.' );
            }
            else {
                Xcart::app()->flash->error( 'Please, fix errors' );
                dd( $form->getErrors() );
            }
        }

        if ( !isset( $translate_model ) ) {
            return;
        }

        $form->setInstance( $translate_model );

        //создание или редактирование
        $is_new = is_null( $pk );
        $template = $is_new ? $this->createTemplate : $this->updateTemplate;

        // вывод
        $this->renderInternal( $template, [
            'form' => $form,
            'model' => $translate_model,
            'new' => $is_new
        ] );
    }

    public function getFilterForm()
    {
        return new TranslatesFilterForm;
    }

    public function getAvailableListColumns()
    {
        return [
            'lang_code' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Language',
            ],
            'msgctxt' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Text',
            ],
            'msgstr' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Translate',
            ],
        ];
    }

    public function all( $pk = null )
    {
        $request = Xcart::app()->request;

        $this->setBreadcrumbs();
        $qs = $this->getQuerySet();

        if ( $request->getIsGet() && $filter_form = $this->getFilterForm() ) {
            $filter_form->populate( $_GET, $_FILES );
            $this->handleFilter( $qs, $filter_form );
        }

        $lang_codes = $filter_form->name->getValue()
            ?? LanguageModel::objects()->valuesList( 'lang_code', true );

        $file_loader = new PoFileLoader();
        $translates = [];
        $text_filter = (string)$filter_form->text->getValue();
        $is_case_sense = $filter_form->case_sensitivity->getValue();
        $is_no_translate = $filter_form->not_translated->getValue();


        $text_filter_func = $is_case_sense ? 'strpos' : 'stripos';
        foreach ( $lang_codes as $lang_code ) {
            $translator = new Translator( $lang_code );
            $translator->addLoader( 'po', $file_loader );
            $resource_path = Xcart::app()->getModule( 'Translate' )->getPath() . "/lang/{$lang_code}.po";
            $translator->addResource( 'po', $resource_path, $lang_code, 'messages' );
            $catalogue = $translator->getCatalogue();

            foreach ( $catalogue->all()[ 'messages' ] as $msgid => $translate ) {
                //already translated
                if ( $is_no_translate && htmlentities( $translate ) !== '' ) {
                    continue;
                }

                //no match by text filter
                if (
                    $text_filter
                    && $text_filter_func( htmlentities( $msgid ), $text_filter ) === false
                    && $text_filter_func( htmlentities( $translate ), $text_filter ) === false
                ) {
                    continue;
                }

                $translates[] = new TranslateModel( [
                    'lang_code' => $lang_code,
                    'msgctxt' => htmlentities( $msgid ),
                    'msgid' => htmlentities( $msgid ),
                    'msgstr' => htmlentities( $translate ),
                    'id' => htmlentities( "$lang_code-$msgid" ),
                ] );
            }
        }

        $this->renderInternal( $this->allTemplate, [
            'objects' => $translates,
            'order' => $this->getOrder(),
            'search' => $this->getSearchColumns(),
            'columns' => $this->buildListColumns(),
            'canSort' => false,
            'filter_form' => $filter_form ?? null,
        ] );
    }
}