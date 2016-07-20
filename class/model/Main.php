<?php

namespace model;


class Main {
    private $ip;
    private $cache;

    public function __construct() {

        $this->ip = IP;
        $this->cache = new \Memcache();
        $this->cache->connect("localhost");

    }

    //считывание массива забронированных мест из кэш
    public function getReservSeats($sector) {

        return $this->cache->get($sector);

    }

    //считывание массива отмеченных мест из кэш
    public function getSelectSeats($sector) {

        $seats = $this->cache->get($this->ip);
        return $seats[$sector];

    }

    //выбор места
    public function selectSeat($sector, $row, $seat) {
        // result = 0 отмена выбора
        // result = 1 выбрать место
        // result = 2 место забронировано
        $seats = $this->cache->get($this->ip);

        //если был повторный клик по выбранному месту - удаляем место из массива
        if (isset($seats[$sector][$row][$seat])) {

            unset($seats[$sector][$row][$seat]);

            if (count($seats[$sector][$row]) == 0) {
                unset($seats[$sector][$row]);
            }
            if (count($seats[$sector]) == 0) {
                unset($seats[$sector]);
            }
            $result = 0;

        } else {

            $arr = $this->cache->get($sector);

            // забронированно ли место
            if (isset($arr[$row][$seat])) {

                $result = 2;

            } else {

                $seats[$sector][$row][$seat] = 0;
                $result = 1;
            }

        }

        $this->cache->set($this->ip, $seats, false, 600); //хранить в кеш 10 мин

        echo $result;
        exit(0);
    }

    //бронирование
    public function reservations() {

        $selectSeats = $this->cache->get($this->ip); //массив отмеченных мест
        $str_error = "";
        if ($selectSeats) {

            /* проверка - не забронировал ли другой пользователь
               одно из выбранных мест */
            foreach ($selectSeats as $sector => $sectors) {
                $reservSeats = $this->getReservSeats($sector);
                foreach ($sectors as $i => $rows) {
                    foreach ($rows as $j => $v) {
                        if (isset($reservSeats[$i][$j])) {
                            $str_error .= "Извините, сектор:$sector ряд:$i место:$j уже забронировано.\n";
                        }
                    }
                }
            }

            /* если все ок, сохраняем выбранные места в БД, и сразу обновляем инфо в кеш */
            if (!$str_error) {
                foreach ($selectSeats as $sector => $sectors) {
                    $reservSeats = $this->getReservSeats($sector);
                    foreach ($sectors as $i => $rows) {
                        foreach ($rows as $j => $v) {
                            $reservSeats[$i][$j] = "";
                            $this->saveTicketsDB($sector, $i, $j, $this->ip);
                        }
                    }
                    $this->cache->set($sector, $reservSeats, false, 0);
                }
                $this->cache->delete($this->ip);
            }

        }
        return $str_error;
    }

    //добавление брОни в базу данных
    private function saveTicketsDB($sector, $row, $seat, $ip) {

        $db = new \core\IDB();
        $id = $db->insert('tickets',
            ['sector' => $sector, 'row' => $row, 'seat' => $seat, 'ip' => $ip]
        );

    }

    //считывание забронированных мест из бд в кэш
    public function readDB() {

        $what = "";
        $where = "";
        $orderColumn = "";
        $desc = "";
        $limit = "";
        $db = new \core\IDB();
        $tickets = $db->select("tickets", $what, $where, $orderColumn, $desc, $limit);

        $seats = [];
        foreach($tickets as $v) {
            $row = $v['row'];
            $seat = $v['seat'];
            $sector = $v['sector'];
            $seats[$sector][$row][$seat] = "";
        }

        $sectors = ["A","B","C","D"];
        foreach($sectors as $sector) {
            $buf_arr = isset($seats[$sector]) ? $seats[$sector] : [];
            $this->cache->set($sector, $buf_arr, false, 0);
        }
    }

    //проверка кэш, если пустой - считываем БД в кэш
    public function checkCache($sector) {

        if ( !is_array($this->cache->get($sector)) ) {
            $this->readDB();
            //echo "readDB";
        }

    }

    //очистить кэш
    public function clearCache() {
        $this->cache->flush();
    }

}