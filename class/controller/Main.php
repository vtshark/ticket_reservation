<?php

namespace controller;


use core\Controller;

class Main extends Controller{

    //по-умолчанию вывод сектора
    public function index() {
        $this->sector();
    }

    //выбор места
    public function selectSeat() {

        if (isset($_POST['sector'])) {

            $row = $_POST['row'];
            $sector = $_POST['sector'];
            $seat = $_POST['seat'];
            $this->model = new \model\Main();
            $this->model->selectSeat($sector, $row, $seat);

        }

    }

    //вывод на экран сектора
    public function sector($param = null) {

        $sector = isset($param[0]) ? $param[0] : "A";

        $this->model = new \model\Main();
        $this->model->checkCache($sector);

        $this->view = new \view\Main();
        $this->view->sector($sector);

    }

    //считывание массива отмеченных мест
    public function getSelSeats() {

        $sector = isset($_POST['sector']) ? $_POST['sector'] : "A";
        $this->model = new \model\Main();
        $result = $this->model->getSelSeats($sector);
        echo $result ? json_encode($result) : "";

    }

    //бронирование
    public function reservation() {

        $sector = isset($_POST['sector']) ? $_POST['sector'] : "A";
        $this->model = new \model\Main();
        $this->model->reservations($sector);

    }

    //очистить кэш
    public function clearCache() {
        $this->model = new \model\Main();
        $this->model->clearCache();
        header("location:".ROOT);
    }

}