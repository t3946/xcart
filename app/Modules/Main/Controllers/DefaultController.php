<?php
use Xcart\App\Controller\Controller;

class DefaultController extends Controller
{
    public $defaultAction= 'index';

    public function index()
    {
        echo 123;
    }
}