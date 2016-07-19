<?php

namespace view;


class Main {

    private function head() {

        $titlePage = "Тест Tallium";
        include "templates/head.php";
        include "templates/header.php";

    }

    private function foot() {

        include "templates/footer.php";

    }

    public function sector($sector) {

        $this->head();
        include "templates/sector.php";
        $this->foot();

    }

}