<?php

namespace Modules\Order\Validation;

use Modules\Translate\TranslateModule;
use Xcart\App\Translate\Translate;
use Xcart\App\Validation\Validator;

class CanadaCODSValidator extends Validator
{
    private $message = "Can't be empty";

    public function __construct( $message = null )
    {
        $this->message = empty( $message ) ? TranslateModule::t( $this->message ) : $message;
    }

    public function validate( $value )
    {
        $value = trim( $value );

        if ( empty( $value ) ) {
            return false;
        }

        if ( $value !== '1' ) {
            $this->addError( Translate::getInstance()->t( 'validation', $this->message, [] ) );
        }

        return $this->hasErrors() === false;
    }

    public function jsValidateParams()
    {
        return [
            'format' => [
                'pattern' => "^1$",
                'flags' => "im",
                'message' => Translate::getInstance()->t( 'validation', '^' . $this->message, [] )
            ]
        ];
    }
}