<?php

namespace core;


abstract class Controller {
    abstract function index();
    public $model;
    public $view;
}