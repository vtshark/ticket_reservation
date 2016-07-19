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

    //считывание массива отмеченных мест из кэш
    public function getSelSeats($sector) {

        return $this->cache->get($sector);

    }

    //выбор места
    public function selectSeat($sector, $row, $seat) {
        // result = 0 отмена выбора
        // result = 1 выбрать место
        // result = 2 место выбрано другим пользователем
        // result = 3 место забронировано
        $result = 1;

        $arr = $this->cache->get($sector);

        if (isset($arr[$row][$seat])) {

            $bufArr = explode("#",$arr[$row][$seat]); //$bufArr временный массив
            $ipUser = $bufArr[1];
            $reserv = $bufArr[0];

            if ($ipUser == $this->ip && !$reserv) {

                $result = 0;
                unset($arr[$row][$seat]);

                if (count($arr[$row]) == 0) {
                    unset($arr[$row]);
                }

                $this->cache->set($sector, $arr, false, 0);

            } else {

                $result = ($reserv) ? 3 : 2;

            }

        } else {

            $arr[$row][$seat] = "0#".$this->ip;
            $this->cache->set($sector, $arr, false, 0);

        }

        echo $result;
        exit(0);

    }

    //бронирование
    public function reservations($sector) {

        $arr = $this->getSelSeats($sector);

        foreach($arr as $i => $row) {
            foreach($row as $j => $v) {

                // $bufArr временный массив
                // [0] - признак (0 - место выбрано, 1 - место забронировано)
                // [1] - ip пользовател
                $bufArr = explode("#",$v);

                if ($bufArr[1] == $this->ip && $bufArr[0] == 0) {

                    $arr[$i][$j] = "1#".$this->ip;
                    $this->saveTicketsDB($sector,$i,$j,$this->ip);

                }
            }
        }
        $this->cache->set($sector, $arr, false, 0);
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
            $ip = $v['ip'];
            $sector = $v['sector'];
            $seats[$sector][$row][$seat] = "1#".$ip;
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
            echo "read DB";
        }
    }

    //очистить кэш
    public function clearCache() {
        $this->cache->flush();
    }

}