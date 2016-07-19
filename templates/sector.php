<?php

$sectors = ["A","B","C","D"];   //массив секторов
$numberOfRows = 15;             //количество рядов
$numberOfColumns = 15;          //количество мест
$ip = IP;
$countSeats = $numberOfRows*$numberOfColumns;

?>

<?php foreach($sectors as $v):?>
    <a href="<?= ROOT."main/sector/".$v ?>">
        Сектор <?= $v ?>
    </a>
<?php endforeach;?>

<br/>
<br/>

Сектор:
    <input id = "sector" value = "<?= $sector ?>" readonly>
    <input id = "ip" value = "<?= $ip ?>" readonly hidden>
 Кол-во мест в секторе
    <input id = "countSeats" value = "<?= $countSeats ?>" readonly>
 Свободно  <input id = "freeSeats" value = "<?= $countSeats ?>" readonly> мест

<button id="reserv-btn">Забронировать</button>
<br/>

<br/>

<?php for ( $i = 1; $i <= $numberOfRows; $i++ ): ?>

    <div class = "row">
        Ряд <?= $i ?>
    </div>

    <?php for ( $j = 1; $j <= $numberOfColumns; $j++ ): ?>

        <div id = "<?=$i?>-<?=$j?>" class = "row seat">
            <?= $j ?>
        </div>

    <?php endfor; ?>

    <br/>

<?php endfor; ?>


<!--временная ссылка для отладки-->
<br/><br/>
<a href="<?= ROOT."main/clearCache/" ?>">Очистить кэш</a>