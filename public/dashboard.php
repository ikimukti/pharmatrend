<?php

function saveTrend($conn, $value)
{
    $id_item = $value['id_item'];
    $id_sale = $value['id_sale'];
    $month = $value['month'];
    $time_x = $value['time_x'];
    $year = $value['year'];
    $a = $value['a'];
    $b = $value['b'];
    $y = $value['y'];
    $sales_real = $value['sales_real'];
    $forecast = $value['forecast'];
    $averageSold = $value['averageSold'];
    $indexMusim = $value['indexMusim'];
    $ape = $value['ape'];
    $accuracy = $value['accuracy'];
    $mape = $value['mape'];
    // Memeriksa apakah data sudah ada dalam tabel
    $sql_check = "SELECT * FROM trends_moment WHERE id_item='{$id_item}' AND month='{$month}' AND year='{$year}'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        // UPDATE jika data sudah ada dalam tabel
        $sql_update = "UPDATE trends_moment SET 
            id_sale='{$id_sale}',
            time_x='{$time_x}',
            a='{$a}',
            b='{$b}',
            y='{$y}',
            sales_real='{$sales_real}',
            forecast='{$forecast}',
            averageSold='{$averageSold}',
            indexMusim='{$indexMusim}',
            ape='{$ape}',
            accuracy='{$accuracy}',
            mape='{$mape}' WHERE id_item='{$id_item}' AND month='{$month}' AND year='{$year}'";

        $conn->query($sql_update);
    }
    if ($result_check->num_rows == 0) {
        // INSERT jika data belum ada dalam tabel
        $sql_insert = "INSERT INTO trends_moment 
            (id_item, id_sale, month, time_x, year, a, b, y, sales_real, forecast, averageSold, indexMusim, ape, accuracy, mape)
            VALUES 
            ('{$id_item}','{$id_sale}', '{$month}', '{$time_x}', '{$year}', '{$a}', '{$b}', '{$y}', '{$sales_real}', '{$forecast}', '{$averageSold}', '{$indexMusim}', '{$ape}', '{$accuracy}', '{$mape}')";
        $conn->query($sql_insert);
    }
}

function backendManualTrend($sigma_y, $n, $sigma_x, $sigma_xy, $sigma_x2)
{
    // coba 1
    $cobaPreYATAS = $sigma_y;
    $cobaPreAATAS = $n;
    $cobaPreABAWAH = $sigma_x;
    $cobaPreYBAWAH = $sigma_xy;
    $cobaPreBAATAS = $sigma_x;
    $cobaPreBBAWAH = $sigma_x2;

    $times = $cobaPreABAWAH / $cobaPreAATAS;
    $cobaPraYATAS = $cobaPreYATAS * $times;
    $cobaPraAATAS = $cobaPreAATAS * $times;
    $cobaPraABAWAH = $cobaPreABAWAH;
    $cobaPraYBAWAH = $cobaPreYBAWAH;
    $cobaPraBATAS = $cobaPreBAATAS * $times;
    $cobaPraBBAWAH = $cobaPreBBAWAH;

    $cobaYBaru = $cobaPraYATAS - $cobaPraYBAWAH;
    $cobaABaru = $cobaPraAATAS - $cobaPraABAWAH;
    $cobaBBaru = $cobaPraBATAS - $cobaPraBBAWAH;

    $bREAL = $cobaYBaru / $cobaBBaru;

    $cccYATAS = $sigma_y;
    $cccAATAS = $n;
    $cccBATAS = $sigma_x;
    $cccBATAS = $cccBATAS * $bREAL;
    $cccXXXX = $cccYATAS - $cccBATAS;
    $aREAL = $cccXXXX / $cccAATAS;
    return array(
        'bREAL' => $bREAL,
        'aREAL' => $aREAL
    );
}
function initialiasiTrendMoment($iidi, $value, $year, $conn)
{
    $iidi = $iidi + 1;
    // echo $iidi . "<br>";
    $id_item = $value['id_item'];
    // get all data from sales with id_item 2 tahun lalu join item
    $th2 = $year + 1;
    $sql_2th = "SELECT i.id AS id_item, i.name, i.unit, s.sold, s.month, s.year, s.code, s.id_user FROM sales s JOIN items i ON s.id_item=i.id WHERE s.id_item='{$id_item}' AND s.year='{$th2}'";
    $th1 = $year;
    $sql_1th = "SELECT i.id AS id_item, i.name, i.unit, s.sold, s.month, s.year, s.code, s.id_user FROM sales s JOIN items i ON s.id_item=i.id WHERE s.id_item='{$id_item}' AND s.year='{$th1}'";
    // $sql_combine = "$sql_2th UNION $sql_1th";
    // $result_sales = $conn->query($sql_combine);
    $tahunini = date('Y');
    $sql_sales = "SELECT i.id AS id_item, i.name, i.unit, s.sold, s.month, s.year, s.code, s.id_user FROM sales s JOIN items i ON s.id_item=i.id WHERE s.id_item='{$id_item}' AND year <> '{$tahunini}'";
    $result_sales = $conn->query($sql_sales);
    // echo "<pre>";
    // print_r($result_sales);
    // echo "</pre>";
    // echo "jumlah data: " . $result_sales->num_rows . "<br>";
    // SELECT `id`, `code`, `sold`, `month`, `year`, `created_at`, `updated_at`, `id_item`, `id_user` FROM `sales` WHERE 1
    $sigma_y = 0;
    $sigma_x = 0;
    $sigma_x2 = 0;
    $sigma_xy = 0;
    $time_x = 0;
    $x2 = 0;
    $xy = 0;
    $dataItemTrend = array();
    foreach ($result_sales as $key => $value) {
        $id = $value['id_item'];
        $name = $value['name'];
        $code = $value['code'];
        $sold = $value['sold'];
        $month = $value['month'];
        $year = $value['year'];
        $id_item = $value['id_item'];
        $id_user = $value['id_user'];
        $data_actual_or_y = $sold;
        $x2 = $time_x * $time_x;
        $xy = $time_x * $data_actual_or_y;
        $dataItemTrend[] = array(
            'id' => $id,
            'name' => $name,
            'code' => $code,
            'sold' => $sold,
            'month' => $month,
            'year' => $year,
            'id_item' => $id_item,
            'id_user' => $id_user,
            'data_actual_or_y' => $data_actual_or_y,
            'time_x' => $time_x,
            'x2' => $x2,
            'xy' => $xy,
        );
        $sigma_y = $sigma_y + $sold;
        $sigma_x = $sigma_x + $time_x;
        $time_x = $time_x + 1;
        $sigma_x2 = $sigma_x2 + $x2;
        $sigma_xy = $sigma_xy + $xy;
    }
    return array(
        'sigma_y' => $sigma_y,
        'sigma_x' => $sigma_x,
        'sigma_x2' => $sigma_x2,
        'sigma_xy' => $sigma_xy,
        'dataItemTrend' => $dataItemTrend,
        'iidi' => $iidi,
        'id_item' => $id_item,
        'year' => $year
    );
}
function updateDataSalesCluster($conn, $dataSalesCluster)
{
    foreach ($dataSalesCluster as $key => $values) {
        $id_item = $values['id'];
        $name = $values['name'];
        $total_sold = $values['total_sold'];
        $total_sold_per_1000 = $values['total_sold_per_1000'];
        $price = $values['price'];
        $price_per_1000 = $values['price_per_1000'];
        $unit = $values['unit'];
        $m1 = $values['m1'];
        $m2 = $values['m2'];
        $m3 = $values['m3'];
        $mMin = $values['mMin'];
        $mMax = $values['mMax'];
        $mMinIndex = $values['mMinIndex'];
        $mMaxIndex = $values['mMaxIndex'];
        $nearest = $values['nearest_cluster'];
        $within_class_variation = $values['within_class_variation'];
        $iteration = $values['iteration'];
        // Memeriksa apakah data sudah ada dalam tabel
        $sql_check = "SELECT * FROM sales_cluster WHERE id_item='{$id_item}'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            // UPDATE jika data sudah ada dalam tabel
            $sql_update = "UPDATE sales_cluster SET 
                name='{$name}',
                total_sold='{$total_sold}',
                total_sold_per_1000='{$total_sold_per_1000}',
                price='{$price}',
                price_per_1000='{$price_per_1000}',
                unit='{$unit}',
                m1='{$m1}',
                m2='{$m2}',
                m3='{$m3}',
                mMin='{$mMin}',
                mMax='{$mMax}',
                mMinIndex='{$mMinIndex}',
                mMaxIndex='{$mMaxIndex}',
                nearest_cluster='{$nearest}',
                within_class_variation='{$within_class_variation}',
                iteration='{$iteration}' WHERE id_item='{$id_item}'";

            $conn->query($sql_update);
        } else {
            // INSERT jika data belum ada dalam tabel
            $sql_insert = "INSERT INTO sales_cluster 
                (id_item, name, total_sold, total_sold_per_1000, price, price_per_1000, unit, m1, m2, m3, mMin, mMax, mMinIndex, mMaxIndex, nearest_cluster, within_class_variation, iteration)
                VALUES 
                ('{$id_item}','{$name}', '{$total_sold}', '{$total_sold_per_1000}', '{$price}', '{$price_per_1000}', '{$unit}', '{$m1}', '{$m2}', '{$m3}', '{$mMin}', '{$mMax}', '{$mMinIndex}', '{$mMaxIndex}', '{$nearest}', '{$within_class_variation}', '{$iteration}')";
            $conn->query($sql_insert);
        }
    }
}
function calculateClusterCategories(&$clusterRun, $totalCluster1, $totalCluster2, $totalCluster3)
{
    // Calculate highest, middle, and lowest values
    $highest = max($totalCluster1, $totalCluster2, $totalCluster3);
    $lowest = min($totalCluster1, $totalCluster2, $totalCluster3);
    $middle = $totalCluster1 + $totalCluster2 + $totalCluster3 - $highest - $lowest;
    $clusterRun['lowest'] = $lowest;
    $clusterRun['middle'] = $middle;
    $clusterRun['highest'] = $highest;

    // Define cluster categories and values
    $clusterCategories = array("Tinggi", "Sedang", "Rendah");
    $clusterValues = array($totalCluster1, $totalCluster2, $totalCluster3);

    // Assign categories to clusters
    $clusterRun['cluster1_category'] = $clusterCategories[array_search($totalCluster1, $clusterValues)];
    $clusterRun['cluster2_category'] = $clusterCategories[array_search($totalCluster2, $clusterValues)];
    $clusterRun['cluster3_category'] = $clusterCategories[array_search($totalCluster3, $clusterValues)];

    // If any cluster category is empty, set it to "Sedang"
    if (empty($clusterRun['cluster1_category'])) {
        $clusterRun['cluster1_category'] = "Sedang";
    }
    if (empty($clusterRun['cluster2_category'])) {
        $clusterRun['cluster2_category'] = "Sedang";
    }
    if (empty($clusterRun['cluster3_category'])) {
        $clusterRun['cluster3_category'] = "Sedang";
    }
}

function get_random_sales($conn)
{
    $year = date("Y") - 2;
    $sales2yearago = "SELECT i.id, i.name, AVG(s.sold) AS total_sold, i.price AS price, i.unit AS unit 
                     FROM items i 
                     JOIN sales s ON i.id = s.id_item 
                     WHERE year = '$year' OR year = '$year' + 1 
                     GROUP BY i.id, i.name";

    $salesResult = mysqli_query($conn, $sales2yearago);
    $salesAll = array();

    while ($salesRow = mysqli_fetch_assoc($salesResult)) {
        $salesTotalSold = $salesRow['total_sold'];
        $salesPrice = $salesRow['price'];

        $dataSales = array(
            "id" => $salesRow['id'],
            "name" => $salesRow['name'],
            "total_sold" => $salesTotalSold,
            "total_sold_per_1000" => $salesTotalSold / 1000,
            "price" => $salesPrice,
            "price_per_1000" => $salesPrice / 1000,
            "unit" => $salesRow['unit']
        );

        array_push($salesAll, $dataSales);
    }

    if (count($salesAll) >= 3) {
        shuffle($salesAll);
        $randomSales = array_slice($salesAll, 0, 3);
    } else {
        $randomSales = $salesAll;
    }

    return array(
        "randomSales" => $randomSales,
        "salesAll" => $salesAll
    );
}
function calculate_clusters($salesAll, $randomSales)
{
    $bw1 = euclideanDistance($randomSales[0]['total_sold_per_1000'], $randomSales[1]['total_sold_per_1000'], $randomSales[0]['price_per_1000'], $randomSales[1]['price_per_1000']);
    $bw2 = euclideanDistance($randomSales[0]['total_sold_per_1000'], $randomSales[2]['total_sold_per_1000'], $randomSales[0]['price_per_1000'], $randomSales[2]['price_per_1000']);
    $bw3 = euclideanDistance($randomSales[1]['total_sold_per_1000'], $randomSales[2]['total_sold_per_1000'], $randomSales[1]['price_per_1000'], $randomSales[2]['price_per_1000']);
    $beetweenClassVariation = ($bw1 + $bw2 + $bw3);
    $totalWithinClassVariation = 0;
    $rasio = 0;
    $newRasio = 0;
    $clusterRun = array(
        "cluster1" => 0,
        "cluster2" => 0,
        "cluster3" => 0,
        "cluster1_sold" => 0,
        "cluster2_sold" => 0,
        "cluster3_sold" => 0,
        "cluster1_price" => 0,
        "cluster2_price" => 0,
        "cluster3_price" => 0,
        "cluster1_sold_per_1000" => 0,
        "cluster2_sold_per_1000" => 0,
        "cluster3_sold_per_1000" => 0,
        "cluster1_price_per_1000" => 0,
        "cluster2_price_per_1000" => 0,
        "cluster3_price_per_1000" => 0
    );
    $salesCluster = array();
    $clusterRepeat = false;
    $clusterIteration = 0;
    foreach ($salesAll as $key => $value) {
        $salesId = $salesAll[$key]['id'];
        $salesName = $salesAll[$key]['name'];
        $salesTotalSold = $salesAll[$key]['total_sold'];
        $salesTotalSoldPer1000 = $salesAll[$key]['total_sold_per_1000'];
        $salesPrice = $salesAll[$key]['price'];
        $salesPricePer1000 = $salesAll[$key]['price_per_1000'];
        $salesUnit = $salesAll[$key]['unit'];
        $m1 = euclideanDistance($randomSales[0]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[0]['price_per_1000'], $salesPricePer1000);
        $m2 = euclideanDistance($randomSales[1]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[1]['price_per_1000'], $salesPricePer1000);
        $m3 = euclideanDistance($randomSales[2]['total_sold_per_1000'], $salesTotalSoldPer1000, $randomSales[2]['price_per_1000'], $salesPricePer1000);
        $m = array($m1, $m2, $m3);
        $mMin = min($m);
        $mMax = max($m);
        $mMinIndex = array_search($mMin, $m);
        if ($mMin == $m1) {
            $clusterRun['cluster1'] += 1;
            $clusterRun['cluster1_sold'] += $salesTotalSold;
            $clusterRun['cluster1_price'] += $salesPrice;
            $clusterRun['cluster1_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster1_price_per_1000'] += $salesPricePer1000;
        } else if ($mMin == $m2) {
            $clusterRun['cluster2'] += 1;
            $clusterRun['cluster2_sold'] += $salesTotalSold;
            $clusterRun['cluster2_price'] += $salesPrice;
            $clusterRun['cluster2_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster2_price_per_1000'] += $salesPricePer1000;
        } else if ($mMin == $m3) {
            $clusterRun['cluster3'] += 1;
            $clusterRun['cluster3_sold'] += $salesTotalSold;
            $clusterRun['cluster3_price'] += $salesPrice;
            $clusterRun['cluster3_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster3_price_per_1000'] += $salesPricePer1000;
        }
        $mMaxIndex = array_search($mMax, $m);
        $nearestCluster = $mMin;
        $withinClassVariation = pow($nearestCluster, 2);
        $totalWithinClassVariation += $withinClassVariation;
        $dataSales = array(
            "id" => $salesId,
            "name" => $salesName,
            "total_sold" => $salesTotalSold,
            "total_sold_per_1000" => $salesTotalSold / 1000,
            "price" => $salesPrice,
            "price_per_1000" => $salesPrice / 1000,
            "unit" => $salesUnit,
            "m1" => $m1,
            "m2" => $m2,
            "m3" => $m3,
            "mMin" => $mMin,
            "mMinIndex" => $mMinIndex,
            "mMax" => $mMax,
            "mMaxIndex" => $mMaxIndex,
            "nearest_cluster" => $nearestCluster,
            "within_class_variation" => $withinClassVariation
        );
        array_push($salesCluster, $dataSales);
    }
    $rasio = $beetweenClassVariation / $totalWithinClassVariation;
    $bw1 = euclideanDistance($clusterRun['cluster1_sold_per_1000'], $clusterRun['cluster2_sold_per_1000'], $clusterRun['cluster1_price_per_1000'], $clusterRun['cluster2_price_per_1000']);
    $bw2 = euclideanDistance($clusterRun['cluster1_sold_per_1000'], $clusterRun['cluster3_sold_per_1000'], $clusterRun['cluster1_price_per_1000'], $clusterRun['cluster3_price_per_1000']);
    $bw3 = euclideanDistance($clusterRun['cluster2_sold_per_1000'], $clusterRun['cluster3_sold_per_1000'], $clusterRun['cluster2_price_per_1000'], $clusterRun['cluster3_price_per_1000']);
    $beetweenClassVariation = ($bw1 + $bw2 + $bw3);
    $clusterRun['cluster1'] = 0;
    $clusterRun['cluster2'] = 0;
    $clusterRun['cluster3'] = 0;
    $clusterIteration += 1;
    $dataSalesCluster = array();
    foreach ($salesCluster as $key => $value) {
        $salesId = $salesAll[$key]['id'];
        $salesName = $salesAll[$key]['name'];
        $salesTotalSold = $salesAll[$key]['total_sold'];
        $salesTotalSoldPer1000 = $salesAll[$key]['total_sold_per_1000'];
        $salesPrice = $salesAll[$key]['price'];
        $salesPricePer1000 = $salesAll[$key]['price_per_1000'];
        $salesUnit = $salesAll[$key]['unit'];
        $m1 = euclideanDistance($clusterRun['cluster1_sold_per_1000'], $salesTotalSoldPer1000, $clusterRun['cluster1_price_per_1000'], $salesPricePer1000);
        $m2 = euclideanDistance($clusterRun['cluster2_sold_per_1000'], $salesTotalSoldPer1000, $clusterRun['cluster2_price_per_1000'], $salesPricePer1000);
        $m3 = euclideanDistance($clusterRun['cluster3_sold_per_1000'], $salesTotalSoldPer1000, $clusterRun['cluster3_price_per_1000'], $salesPricePer1000);
        $m = array($m1, $m2, $m3);
        $mMin = min($m);
        $mMax = max($m);
        $mMinIndex = array_search($mMin, $m);
        if ($mMin == $m1) {
            $clusterRun['cluster1'] += 1;
            $clusterRun['cluster1_sold'] += $salesTotalSold;
            $clusterRun['cluster1_price'] += $salesPrice;
            $clusterRun['cluster1_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster1_price_per_1000'] += $salesPricePer1000;
        } else if ($mMin == $m2) {
            $clusterRun['cluster2'] += 1;
            $clusterRun['cluster2_sold'] += $salesTotalSold;
            $clusterRun['cluster2_price'] += $salesPrice;
            $clusterRun['cluster2_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster2_price_per_1000'] += $salesPricePer1000;
        } else if ($mMin == $m3) {
            $clusterRun['cluster3'] += 1;
            $clusterRun['cluster3_sold'] += $salesTotalSold;
            $clusterRun['cluster3_price'] += $salesPrice;
            $clusterRun['cluster3_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster3_price_per_1000'] += $salesPricePer1000;
        }
        $nearestCluster = $mMinIndex + 1;
        $withinClassVariation = $mMin;
        $dataSales = array(
            "id" => $salesId,
            "name" => $salesName,
            "total_sold" => $salesTotalSold,
            "total_sold_per_1000" => $salesTotalSoldPer1000,
            "price" => $salesPrice,
            "price_per_1000" => $salesPrice / 1000,
            "unit" => $salesUnit,
            "m1" => $m1,
            "m2" => $m2,
            "m3" => $m3,
            "mMin" => $mMin,
            "mMinIndex" => $mMinIndex,
            "mMax" => $mMax,
            "mMaxIndex" => $mMaxIndex,
            "nearest_cluster" => $nearestCluster,
            "within_class_variation" => $withinClassVariation
        );
        array_push($dataSalesCluster, $dataSales);
    }
    $newRasio = $beetweenClassVariation / $totalWithinClassVariation;
    $clusterIteration += 1;
    $totalCluster1 = 0;
    $totalCluster2 = 0;
    $totalCluster3 = 0;
    return array(
        "clusterRun" => $clusterRun,
        "salesCluster" => $salesCluster,
        "rasio" => $rasio,
        "newRasio" => $newRasio,
        "totalCluster1" => $totalCluster1,
        "totalCluster2" => $totalCluster2,
        "totalCluster3" => $totalCluster3,
        "mMaxIndex" => $mMaxIndex,
        "clusterIteration" => $clusterIteration,
        "totalWithinClassVariation" => $totalWithinClassVariation
    );
}
function salesCluster($clusterRun, $salesCluster, $salesAll, $clusterIteration, $totalCluster1, $totalCluster2, $totalCluster3, $mMaxIndex)
{
    $bw1 = euclideanDistance($clusterRun['cluster1_sold_per_1000'], $clusterRun['cluster2_sold_per_1000'], $clusterRun['cluster1_price_per_1000'], $clusterRun['cluster2_price_per_1000']);
    $bw2 = euclideanDistance($clusterRun['cluster1_sold_per_1000'], $clusterRun['cluster3_sold_per_1000'], $clusterRun['cluster1_price_per_1000'], $clusterRun['cluster3_price_per_1000']);
    $bw3 = euclideanDistance($clusterRun['cluster2_sold_per_1000'], $clusterRun['cluster3_sold_per_1000'], $clusterRun['cluster2_price_per_1000'], $clusterRun['cluster3_price_per_1000']);
    $beetweenClassVariation = ($bw1 + $bw2 + $bw3);
    $clusterRun['cluster1'] = 0;
    $clusterRun['cluster2'] = 0;
    $clusterRun['cluster3'] = 0;
    $dataSalesCluster = array();
    foreach ($salesCluster as $key => $value) {
        $salesId = $salesAll[$key]['id'];
        $salesName = $salesAll[$key]['name'];
        $salesTotalSold = $salesAll[$key]['total_sold'];
        $salesTotalSoldPer1000 = $salesAll[$key]['total_sold_per_1000'];
        $salesPrice = $salesAll[$key]['price'];
        $salesPricePer1000 = $salesAll[$key]['price_per_1000'];
        $salesUnit = $salesAll[$key]['unit'];
        $m1 = euclideanDistance($clusterRun['cluster1_sold_per_1000'], $salesTotalSoldPer1000, $clusterRun['cluster1_price_per_1000'], $salesPricePer1000);
        $m2 = euclideanDistance($clusterRun['cluster2_sold_per_1000'], $salesTotalSoldPer1000, $clusterRun['cluster2_price_per_1000'], $salesPricePer1000);
        $m3 = euclideanDistance($clusterRun['cluster3_sold_per_1000'], $salesTotalSoldPer1000, $clusterRun['cluster3_price_per_1000'], $salesPricePer1000);
        $m = array($m1, $m2, $m3);
        $mMin = min($m);
        $mMax = max($m);
        $mMinIndex = array_search($mMin, $m);
        if ($mMin == $m1) {
            $clusterRun['cluster1'] += 1;
            $clusterRun['cluster1_sold'] += $salesTotalSold;
            $clusterRun['cluster1_price'] += $salesPrice;
            $clusterRun['cluster1_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster1_price_per_1000'] += $salesPricePer1000;
            $totalCluster1 = $totalCluster1 + ($salesTotalSoldPer1000 * $salesPricePer1000);
        } else if ($mMin == $m2) {
            $clusterRun['cluster2'] += 1;
            $clusterRun['cluster2_sold'] += $salesTotalSold;
            $clusterRun['cluster2_price'] += $salesPrice;
            $clusterRun['cluster2_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster2_price_per_1000'] += $salesPricePer1000;
            $totalCluster2 = $totalCluster2 + ($salesTotalSoldPer1000 * $salesPricePer1000);
        } else if ($mMin == $m3) {
            $clusterRun['cluster3'] += 1;
            $clusterRun['cluster3_sold'] += $salesTotalSold;
            $clusterRun['cluster3_price'] += $salesPrice;
            $clusterRun['cluster3_sold_per_1000'] += $salesTotalSoldPer1000;
            $clusterRun['cluster3_price_per_1000'] += $salesPricePer1000;
            $totalCluster3 = $totalCluster3 + ($salesTotalSoldPer1000 * $salesPricePer1000);
        }
        $nearestCluster = $mMinIndex + 1;
        $withinClassVariation = $mMin;
        $dataSales = array(
            "id" => $salesId,
            "name" => $salesName,
            "total_sold" => $salesTotalSold,
            "total_sold_per_1000" => $salesTotalSoldPer1000,
            "price" => $salesPrice,
            "price_per_1000" => $salesPrice / 1000,
            "unit" => $salesUnit,
            "m1" => $m1,
            "m2" => $m2,
            "m3" => $m3,
            "mMin" => $mMin,
            "mMinIndex" => $mMinIndex,
            "mMax" => $mMax,
            "mMaxIndex" => $mMaxIndex,
            "nearest_cluster" => $nearestCluster,
            "within_class_variation" => $withinClassVariation,
            "iteration" => $clusterIteration,
        );
        array_push($dataSalesCluster, $dataSales);
    }
    return array(
        "clusterRun" => $clusterRun,
        "salesCluster" => $dataSalesCluster,
        "totalCluster1" => $totalCluster1,
        "totalCluster2" => $totalCluster2,
        "totalCluster3" => $totalCluster3,
        "beetweenClassVariation" => $beetweenClassVariation,
        "mMaxIndex" => $mMaxIndex,
        "clusterIteration" => $clusterIteration,
        "dataSalesCluster" => $dataSalesCluster
    );
}
function updateClusters($conn, $clusterRun, $clusterIteration)
{
    $sql_check = "SELECT COUNT(*) as count FROM clustering";
    $result_check = $conn->query($sql_check);
    $row_check = $result_check->fetch_assoc();
    $count = $row_check["count"];

    $clusters = array(
        'cluster1' => array(
            'id' => 1,
            'cluster' => $clusterRun['cluster1'],
            'cluster_sold' => $clusterRun['cluster1_sold'],
            'cluster_price' => $clusterRun['cluster1_price'],
            'cluster_sold_per_1000' => $clusterRun['cluster1_sold_per_1000'],
            'cluster_price_per_1000' => $clusterRun['cluster1_price_per_1000'],
            'category' => $clusterRun['cluster1_category'],
            'lowest' => $clusterRun['lowest'],
            'middle' => $clusterRun['middle'],
            'highest' => $clusterRun['highest']
        ),
        'cluster2' => array(
            'id' => 2,
            'cluster' => $clusterRun['cluster2'],
            'cluster_sold' => $clusterRun['cluster2_sold'],
            'cluster_price' => $clusterRun['cluster2_price'],
            'cluster_sold_per_1000' => $clusterRun['cluster2_sold_per_1000'],
            'cluster_price_per_1000' => $clusterRun['cluster2_price_per_1000'],
            'category' => $clusterRun['cluster2_category'],
            'lowest' => $clusterRun['lowest'],
            'middle' => $clusterRun['middle'],
            'highest' => $clusterRun['highest']
        ),
        'cluster3' => array(
            'id' => 3,
            'cluster' => $clusterRun['cluster3'],
            'cluster_sold' => $clusterRun['cluster3_sold'],
            'cluster_price' => $clusterRun['cluster3_price'],
            'cluster_sold_per_1000' => $clusterRun['cluster3_sold_per_1000'],
            'cluster_price_per_1000' => $clusterRun['cluster3_price_per_1000'],
            'category' => $clusterRun['cluster3_category'],
            'lowest' => $clusterRun['lowest'],
            'middle' => $clusterRun['middle'],
            'highest' => $clusterRun['highest']
        )
    );

    if ($clusterIteration == 20) {
        foreach ($clusters as $cluster => $values) {
            if ($count > 0) {
                // UPDATE jika data sudah ada dalam tabel
                $sql_update = "UPDATE clustering SET 
                    cluster='{$values['cluster']}',
                    cluster_sold='{$values['cluster_sold']}',
                    cluster_price='{$values['cluster_price']}',
                    cluster_sold_per_1000='{$values['cluster_sold_per_1000']}',
                    cluster_price_per_1000='{$values['cluster_price_per_1000']}',
                    category='{$values['category']}',
                    lowest='{$values['lowest']}',
                    middle='{$values['middle']}',
                    highest='{$values['highest']}' WHERE id='{$values['id']}'";
                $conn->query($sql_update);
            } else {
                // INSERT jika data belum ada dalam tabel
                $sql_insert = "INSERT INTO clustering 
                    (id, cluster, cluster_sold, cluster_price, cluster_sold_per_1000, cluster_price_per_1000, category, lowest, middle, highest)
                    VALUES 
                    ('{$values['id']}','{$values['cluster']}', '{$values['cluster_sold']}', '{$values['cluster_price']}', '{$values['cluster_sold_per_1000']}', '{$values['cluster_price_per_1000']}', '{$values['category']}', '{$values['lowest']}', '{$values['middle']}', '{$values['highest']}')";
                $conn->query($sql_insert);
            }
        }
    }
}

session_start();
if (!isset($_SESSION["id"])) {
    header("Location: signin.php");
    die();
}
require_once("config.php");

$year = date("Y");
$reloadcluster = false;
$reloadtrend = false;
if (isset($_GET["reloadcluster"]) && $_GET["reloadcluster"] == "true") {
    $reloadcluster = true;
}
if (isset($_GET["reloadtrend"]) && $_GET["reloadtrend"] == "true") {
    $reloadtrend = true;
}
$month = date("m");
$reload = false;
if (isset($_GET["reload"]) && $_GET["reload"] == "true") {
    $reload = true;
}
// Menghitung Month's Revenue
$monthRevenueQuery = "SELECT SUM(s.sold * i.price) AS monthRevenue
                      FROM sales s
                      JOIN items i ON s.id_item = i.id
                      WHERE s.month = $month AND s.year = $year";
$monthRevenueResult = mysqli_query($conn, $monthRevenueQuery);
$monthRevenueRow = mysqli_fetch_assoc($monthRevenueResult);
$monthRevenue = $monthRevenueRow['monthRevenue'];

// Menghitung Last Month's Revenue
$lastMonth = $month - 1;
$lastMonthYear = $year;
if ($lastMonth == 0) {
    $lastMonth = 12;
    $lastMonthYear = $year - 1;
}
$lastMonthRevenueQuery = "SELECT SUM(s.sold * i.price) AS lastMonthRevenue
                          FROM sales s
                          JOIN items i ON s.id_item = i.id
                          WHERE s.month = $lastMonth AND s.year = $lastMonthYear";
$lastMonthRevenueResult = mysqli_query($conn, $lastMonthRevenueQuery);
$lastMonthRevenueRow = mysqli_fetch_assoc($lastMonthRevenueResult);
$lastMonthRevenue = $lastMonthRevenueRow['lastMonthRevenue'];

// Menghitung Year Revenue
$yearRevenueQuery = "SELECT SUM(s.sold * i.price) AS yearRevenue
                     FROM sales s
                     JOIN items i ON s.id_item = i.id
                     WHERE s.year = $year";
$yearRevenueResult = mysqli_query($conn, $yearRevenueQuery);
$yearRevenueRow = mysqli_fetch_assoc($yearRevenueResult);
$yearRevenue = $yearRevenueRow['yearRevenue'];

// Menghitung Last Year Revenue
$lastYear = $year - 1;
$lastYearRevenueQuery = "SELECT SUM(s.sold * i.price) AS lastYearRevenue
                         FROM sales s
                         JOIN items i ON s.id_item = i.id
                         WHERE s.year = $lastYear";
$lastYearRevenueResult = mysqli_query($conn, $lastYearRevenueQuery);
$lastYearRevenueRow = mysqli_fetch_assoc($lastYearRevenueResult);
$lastYearRevenue = $lastYearRevenueRow['lastYearRevenue'];

// Menghitung All Time Revenue
$allTimeRevenueQuery = "SELECT SUM(s.sold * i.price) AS allTimeRevenue
                        FROM sales s
                        JOIN items i ON s.id_item = i.id";
$allTimeRevenueResult = mysqli_query($conn, $allTimeRevenueQuery);
$allTimeRevenueRow = mysqli_fetch_assoc($allTimeRevenueResult);
$allTimeRevenue = $allTimeRevenueRow['allTimeRevenue'];

// Menghitung jumlah item terjual pada bulan ini
$itemSoldQuery = "SELECT SUM(s.sold) AS itemSold
                  FROM sales s
                  WHERE s.month = $month AND s.year = $year";
$itemSoldResult = mysqli_query($conn, $itemSoldQuery);
$itemSoldRow = mysqli_fetch_assoc($itemSoldResult);
$itemSold = $itemSoldRow['itemSold'];

// Menghitung jumlah item terjual pada tahun ini
$itemSoldYearQuery = "SELECT SUM(s.sold) AS itemSoldYear
                      FROM sales s
                      WHERE s.year = $year";
$itemSoldYearResult = mysqli_query($conn, $itemSoldYearQuery);
$itemSoldYearRow = mysqli_fetch_assoc($itemSoldYearResult);
$itemSoldYear = $itemSoldYearRow['itemSoldYear'];

// Menghitung jumlah item terjual sepanjang waktu
$itemSoldAllTimeQuery = "SELECT SUM(s.sold) AS itemSoldAllTime
                         FROM sales s";
$itemSoldAllTimeResult = mysqli_query($conn, $itemSoldAllTimeQuery);
$itemSoldAllTimeRow = mysqli_fetch_assoc($itemSoldAllTimeResult);
$itemSoldAllTime = $itemSoldAllTimeRow['itemSoldAllTime'];

$sales = mysqli_query($conn, "SELECT i.id, i.code, i.name, i.price, i.stock, i.unit, s.id_item,
       SUM(s.sold) AS total_sales, 
       COUNT(DISTINCT CONCAT(s.month, s.year)) AS total_month, 
       COALESCE(t.total_month_last_year, 0) AS total_month_last_year
FROM items i 
LEFT JOIN sales s ON i.id = s.id_item 
LEFT JOIN (
   SELECT s1.id_item, COUNT(DISTINCT CONCAT(s1.month, s1.year)) AS total_month_last_year
   FROM sales s1
   WHERE (s1.year >= YEAR(CURDATE()) - 3 AND s1.year <= YEAR(CURDATE()) - 1) -- Tahun ke-3, ke-2, dan ke-1 sejak saat ini
      OR (s1.year = YEAR(CURDATE()) AND s1.month <= MONTH(CURDATE())) -- Bulan-bulan di tahun ini hingga bulan saat ini
   GROUP BY s1.id_item
   HAVING COUNT(DISTINCT CONCAT(s1.month, s1.year)) >= 12
) AS t ON i.id = t.id_item
GROUP BY i.id 
ORDER BY total_sales DESC 
");
function euclideanDistance($clusterPrice, $clusterSold, $price, $sold)
{
    $deltaX = $clusterPrice - $price;
    $deltaY = $clusterSold - $sold;
    $distance = sqrt(pow($deltaX, 2) + pow($deltaY, 2));
    return $distance;
}
function calculateMAPE($actual, $forecast)
{
    $n = count($actual);
    $errorSum = 0;
    $countNonZero = 0;

    for ($i = 0; $i < $n; $i++) {
        if ($actual[$i] != 0) {
            $errorSum += abs(($actual[$i] - $forecast[$i]) / $actual[$i]);
            $countNonZero++;
        }
    }

    if ($countNonZero == 0) {
        $mape = 0;
    } else {
        $mape = ($errorSum / $countNonZero) * 100;
    }

    return $mape;
}
// jumlah item
$itemQuery = "SELECT COUNT(*) AS total_item FROM items";
$itemResult = mysqli_query($conn, $itemQuery);
$itemRow = mysqli_fetch_assoc($itemResult);
$totalItem = $itemRow['total_item'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PharmaTrend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="font-inter">
    <header class="bg-white w-full border-b-2 border-gray-200">
        <?php
        include("components/navbar.php");
        ?>
    </header>
    <div class="container-fluid mx-auto h-auto">
        <!-- sidebar flex and container -->
        <div class="flex">
            <?php
            include("components/sidebar.php");
            ?>
            <div class="w-10/12 h-[calc(100vh-3.5rem)] p-2">
                <!-- container with breadcrumb -->
                <div class="w-full h-auto border-2 border-gray-200 rounded-md py-4 px-6">
                    <!-- breadcrumb -->
                    <div class="flex items-center gap-2 mb-3 justify-between">
                        <div>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950"><i class="fas fa-home"></i></a>
                            <span class="text-gray-700">/</span>
                            <a href="dashboard.php" class="text-gray-700 hover:text-gray-950">Dashboard</a>
                        </div>
                        <button class="flex flex-row justify-center items-center bg-gray-200 hover:bg-gray-300 rounded-md px-4 py-2 text-gray-700 space-x-2" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                    </div>
                    <hr>
                    <!-- content -->
                    <div class="flex flex-col gap-4 mt-4">
                        <div class="flex flex-row justify-between items-center">
                            <div class="flex flex-col gap-2">
                                <h1 class="text-2xl font-bold">Dashboard</h1>
                                <p class="text-gray-700">Welcome back, <?php echo $_SESSION['fullname']; ?> !, here's what's
                                    happening with your store today.</p>
                            </div>
                            <!-- btn reload cluster -->
                            <!-- btn reload trend -->
                            <div class="flex flex-row gap-2 mt-4">
                                <a class="flex flex-row justify-center items-center bg-gray-200 hover:bg-gray-300 rounded-md px-4 py-2 text-gray-700 space-x-2" href="dashboard.php?reload=true&reloadcluster=true">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Reload Cluster</span>
                                </a>
                                <a class="flex flex-row justify-center items-center bg-gray-200 hover:bg-gray-300 rounded-md px-4 py-2 text-gray-700 space-x-2" href="dashboard.php?reload=true&reloadtrend=true">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Reload Trend</span>
                                </a>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4">
                            <?php
                            $toogleBtnAnalytics = false;
                            $toogleBtnAnalyticsCounter = 0;
                            while ($salesRow = mysqli_fetch_assoc($sales)) {
                                $salesId = $salesRow['id'];
                                $salesName = $salesRow['name'];
                                $salesTotalMonth = $salesRow['total_month'];
                                $monthBtn = date("m") + 24;
                                if ($salesTotalMonth < $monthBtn) {
                            ?>
                                    <!-- item link to sales per item -->
                                    <div href="sales_per_item.php?id=<?php echo $salesId; ?>" class="w-full h-auto bg-white rounded-md shadow-md p-4 flex flex-row gap-4 items-center">
                                        <p>
                                            This <span class="font-bold"><?php echo $salesName; ?></span> item has been sold for <?php echo $salesTotalMonth; ?> months, click here to see the analytics.
                                        </p>
                                        <a href="add_sales_per_item.php?id=<?php echo $salesId; ?>&year=<?php echo $year; ?>" class="flex flex-row justify-center items-center bg-yellow-400 hover:bg-yellow-500 rounded-md px-4 py-2 text-white space-x-2">
                                            <i class="fas fa-arrow-right"></i>
                                            <span>See Item Detail</span>
                                        </a>
                                    </div>
                            <?php
                                    $toogleBtnAnalyticsCounter++;
                                }
                            }
                            if ($toogleBtnAnalyticsCounter == 0) {
                                $toogleBtnAnalytics = true;
                                if ($reload) {
                                    if ($reloadcluster) {
                                        $result = get_random_sales($conn);
                                        $randomSales = $result['randomSales'];
                                        $salesAll = $result['salesAll'];
                                        $result = calculate_clusters($salesAll, $randomSales);
                                        $clusterRun = $result['clusterRun'];
                                        $salesCluster = $result['salesCluster'];
                                        $rasio = $result['rasio'];
                                        $newRasio = $result['newRasio'];
                                        $totalCluster1 = $result['totalCluster1'];
                                        $totalCluster2 = $result['totalCluster2'];
                                        $totalCluster3 = $result['totalCluster3'];
                                        $mMaxIndex = $result['mMaxIndex'];
                                        $clusterIteration = $result['clusterIteration'];
                                        $totalWithinClassVariation = $result['totalWithinClassVariation'];
                                        while ($newRasio > $rasio or $clusterIteration < 20) {
                                            $clusterSales = salesCluster($clusterRun, $salesCluster, $salesAll, $clusterIteration, $totalCluster1, $totalCluster2, $totalCluster3, $mMaxIndex);
                                            $clusterRun = $clusterSales['clusterRun'];
                                            $salesCluster = $clusterSales['salesCluster'];
                                            $totalCluster1 = $clusterSales['totalCluster1'];
                                            $totalCluster2 = $clusterSales['totalCluster2'];
                                            $totalCluster3 = $clusterSales['totalCluster3'];
                                            $mMaxIndex = $clusterSales['mMaxIndex'];
                                            $clusterIteration = $clusterSales['clusterIteration'];
                                            $beetweenClassVariation = $clusterSales['beetweenClassVariation'];
                                            $dataSalesCluster = $clusterSales['dataSalesCluster'];
                                            $newRasio = $beetweenClassVariation / $totalWithinClassVariation;
                                            $rasio = $newRasio;
                                            $clusterIteration += 1;
                                            calculateClusterCategories($clusterRun, $totalCluster1, $totalCluster2, $totalCluster3);
                                            updateClusters($conn, $clusterRun, $clusterIteration);
                                            $totalCluster1 = 0;
                                            $totalCluster2 = 0;
                                            $totalCluster3 = 0;
                                        }
                                    }
                                    if ($reloadtrend == 'true') {
                                        // get from sales_cluster all data
                                        $sql_sales_cluster = "SELECT * FROM sales_cluster";
                                        $sql_items = "SELECT * FROM items";
                                        // if sales_cluster empty
                                        $result_sales_cluster = $conn->query($sql_sales_cluster);
                                        $count_sales_cluster = $result_sales_cluster->num_rows;
                                        $count_items = $conn->query($sql_items)->num_rows;
                                        if ($count_sales_cluster == 0 || $count_sales_cluster < $count_items) {
                                            // reloadcluster = true
                                            header("Location: dashboard.php?reload=true&reloadcluster=true&reloadtrend=true");
                                        } else {
                                            // ulang sales_cluster untuk mendapatkan trend $id_item
                                            $sql_sales_cluster = "SELECT * FROM sales_cluster";
                                            $result_sales_cluster = $conn->query($sql_sales_cluster);
                                            $data_sales_cluster = array();
                                            $iidi = 0;
                                            $push_trend_moment = array();
                                            $trendMoments = array();
                                            foreach ($result_sales_cluster as $key => $value) {
                                                $init = initialiasiTrendMoment($iidi, $value, $year, $conn);
                                                $iidi = $init['iidi'];
                                                $dataItemTrend = $init['dataItemTrend'];
                                                $sigma_y = $init['sigma_y'];
                                                $sigma_x = $init['sigma_x'];
                                                $sigma_xy = $init['sigma_xy'];
                                                $sigma_x2 = $init['sigma_x2'];
                                                $id_item = $init['id_item'];
                                                // nilai n (jumlah data) nilai n (jumlah data)
                                                $n = count($dataItemTrend);
                                                // average data_actual_or_y rata rata y
                                                $average_y = $sigma_y / $n;

                                                // average time_x rata rata x
                                                $average_x = $sigma_x / $n;

                                                $abfind = backendManualTrend($sigma_y, $n, $sigma_x, $sigma_xy, $sigma_x2);

                                                $aREAL = $abfind['aREAL'];
                                                $bREAL = $abfind['bREAL'];

                                                $dateY = date("Y");
                                                $ape = 0;
                                                $accuracy = 0;
                                                $realaccuracy = 0;
                                                $realape = 0;
                                                $mape = 0;
                                                $actualData = [];
                                                $forecastData = [];
                                                for ($i = 1; $i < 13; $i++) {
                                                    // cari di sales data yang month = $i dan id_item = $id_item kecuali tahun ini
                                                    $sql_sales = "SELECT * FROM sales WHERE month='{$i}' AND id_item='{$id_item}' EXCEPT SELECT * FROM sales WHERE month='{$i}' AND id_item='{$id_item}' AND year='{$dateY}'";
                                                    // count data
                                                    $result_sales = $conn->query($sql_sales);
                                                    $count_sales = $result_sales->num_rows;
                                                    // average sold from sales
                                                    $sigma_sold = 0;
                                                    while ($row = $result_sales->fetch_assoc()) {
                                                        $sold = $row['sold'];
                                                        $sigma_sold = $sigma_sold + $sold;
                                                    }
                                                    $averageSold = $sigma_sold / $count_sales;
                                                    $indexMusim = $averageSold / $average_y;
                                                    $xxxx = ($n - 1) + $i;
                                                    $aPerBln = $aREAL;
                                                    $bPerBln = $bREAL * $xxxx;
                                                    $yPerBln = $aPerBln + $bPerBln;
                                                    $forecast = $yPerBln * $indexMusim;
                                                    $sql_salesss = "SELECT * FROM sales WHERE month='{$i}' AND id_item='{$id_item}' AND year='{$dateY}'";
                                                    // count data
                                                    $result_salesss = $conn->query($sql_salesss);
                                                    $count_salesss = $result_sales->num_rows;
                                                    $sales_real = 0;
                                                    $id_sale = null;
                                                    if ($count_salesss > 0) {
                                                        while ($row = $result_salesss->fetch_assoc()) {
                                                            $sales_real = $row['sold'];
                                                            $id_sale = $row['id'];
                                                            if ($sales_real <= 0) {
                                                                $ape = 0;
                                                                $accuracy = 0;
                                                            } else {
                                                                $dump = $sales_real - $forecast;
                                                                $dump2 = $dump / $sales_real;
                                                                $actualData[] = $sales_real;
                                                                $forecastData[] = $forecast;
                                                                if ($sales_real < $forecast) {
                                                                    $ape = $dump2 * 100;
                                                                    $accuracy = 100 - $ape;
                                                                    $accuracy = abs($accuracy - (($ape * 2) * -1));
                                                                    $ape = abs($ape * -1);
                                                                } else {
                                                                    $ape = abs($dump2 * 100);
                                                                    $accuracy = abs(100 - $ape);
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $sales_real = 0;
                                                        $id_sale = null;
                                                        $ape = 0;
                                                        $accuracy = 0;
                                                    }
                                                    if ($sales_real == 0) {
                                                        $id_sale = null;
                                                        $ape = 0;
                                                        $accuracy = 0;
                                                    }

                                                    $trendMoments[] = array(
                                                        'id_item' => $id_item,
                                                        'id_sale' => $id_sale,
                                                        'month' => $i,
                                                        'time_x' => $xxxx,
                                                        'year' => $dateY,
                                                        'a' => $aPerBln,
                                                        'b' => $bPerBln,
                                                        'y' => $yPerBln,
                                                        'sales_real' => $sales_real,
                                                        'forecast' => $forecast,
                                                        'averageSold' => $averageSold,
                                                        'indexMusim' => $indexMusim,
                                                        'ape' => $ape,
                                                        'accuracy' => $accuracy,
                                                        'mape' => $mape
                                                    );
                                                }
                                                $mapess = calculateMAPE($actualData, $forecastData);
                                                // update data $mape with $mapess in $trendMoments array if $id_item same
                                                foreach ($trendMoments as $key => $value) {
                                                    if ($value['id_item'] == $id_item) {
                                                        $trendMoments[$key]['mape'] = $mapess;
                                                    }
                                                }
                                            }
                                            // hitung data $push_trend_moment array
                                            $jumlahData = count($trendMoments);
                                            // print_r($jumlahData);
                                            foreach ($trendMoments as $key => $value) {
                                                saveTrend($conn, $value);
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                        <!-- massage if reload button and times -->
                        <?php
                        if (isset($_GET['reload'])) {
                            $reload = $_GET['reload'];
                            if ($reload == 'true') { ?>
                                <div class='flex flex-row gap-2 justify-between items-center bg-green-500 text-white p-2 rounded-md shadow-md'>
                                    <div class='flex flex-row gap-2 items-center'>
                                        <class='font-bold'>Data berhasil diupdate
                                            <?php if ($reloadcluster == 'true') {
                                                echo "dan clustering berhasil diupdate";
                                            } elseif ($reloadtrend == 'true') {
                                                echo "dan trend berhasil diupdate";
                                            } ?>.</span>
                                            <span class='font-bold text-sm'><?php echo date('H:i:s'); ?></span>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="flex flex-wrap gap-4 items-center">
                            <div class="flex-1 flex-row gap-2 items-center">
                                <div class="flex flex-col gap-2 items-center shadow-md rounded-md bg-white p-4">
                                    <h1 class="text-3xl font-bold">Items</h1>
                                    <span class="text-gray-700 text-md"><?php echo number_format($totalItem, 0, ',', '.'); ?> items</span>
                                </div>
                            </div>
                            <!-- sales -->
                            <div class="flex-1 flex-row gap-2 items-center">
                                <div class="flex flex-col gap-2 items-center shadow-md rounded-md bg-white p-4">
                                    <h1 class="text-3xl font-bold">Sales</h1>
                                    <span class="text-gray-700 text-md"><?php echo number_format($itemSoldAllTime, 0, ',', '.'); ?> items</span>
                                </div>
                            </div>
                        </div>
                        <!-- item auto row 3 wrap -->
                        <div class="flex flex-wrap gap-4 items-center">
                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-row gap-2 justify-between">
                                    <div class="flex-1 flex-col gap-2">
                                        <h1 class="text-xl font-bold">
                                            <i class="fas fa-chart-line"></i>
                                            <?php echo "Rp. " . number_format($monthRevenue, 2, ',', '.'); ?>
                                            <!-- span percentage pertumbuhan revenue daru bulan sebelumnya -->
                                            <?php
                                            if ($monthRevenue > $lastMonthRevenue) {
                                                $percentage = ($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100; ?>
                                                <span class="text-green-500 text-sm">
                                                    <i class="fas fa-arrow-up"></i>
                                                    <?php echo "+" . number_format($percentage, 2, ',', '.') . "%"; ?>
                                                </span>
                                            <?php
                                            } else if ($monthRevenue < $lastMonthRevenue) {
                                                $percentage = ($lastMonthRevenue - $monthRevenue) / $lastMonthRevenue * 100; ?>
                                                <span class="text-red-500 text-sm">
                                                    <i class="fas fa-arrow-down"></i>
                                                    <?php echo "-" . number_format($percentage, 2, ',', '.') . "%"; ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="text-gray-500 text-sm">
                                                    <i class="fas fa-minus"></i>
                                                    0%
                                                </span>
                                            <?php } ?>
                                        </h1>
                                        <h2 class="text-gray-700 text-sm">
                                            <i class="fas fa-calendar"></i>
                                            Rp. <?php echo number_format($lastMonthRevenue, 2, ',', '.'); ?>
                                            last month
                                        </h2>
                                        <h2 class="text-gray-700">
                                            <i class="fas fa-shopping-cart"></i>
                                            <?php echo number_format($itemSold, 0, ',', '.'); ?> items sold this month
                                        </h2>
                                        <p class="text-gray-700">Month's Revenue</p>
                                    </div>
                                    <div class="flex flex-row gap-2 justify-end items-end">
                                        <a href="sales.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-shopping-cart"></i>
                                            View Sales
                                        </a>
                                        <?php if ($toogleBtnAnalytics) { ?>
                                            <a href="analytics_per_item.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                                <i class="fas fa-chart-line"></i>
                                                View Analytics
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-row gap-2">
                                    <div class="flex-1 flex-col gap-2">
                                        <h1 class="text-xl font-bold">
                                            <i class="fas fa-chart-line"></i>
                                            <?php echo "Rp. " . number_format($yearRevenue, 2, ',', '.'); ?>
                                            <!-- span percentage pertumbuhan revenue daru tahun sebelumnya -->
                                            <?php
                                            if ($yearRevenue > $lastYearRevenue) {
                                                $percentage = ($yearRevenue - $lastYearRevenue) / $lastYearRevenue * 100; ?>
                                                <span class="text-green-500 text-sm">
                                                    <i class="fas fa-arrow-up"></i>
                                                    <?php echo "+" . number_format($percentage, 2, ',', '.') . "%"; ?>
                                                </span>
                                            <?php
                                            } else if ($yearRevenue < $lastYearRevenue) {
                                                $percentage = ($yearRevenue - $lastYearRevenue) / $lastYearRevenue * 100; ?>
                                                <span class="text-red-500 text-sm">
                                                    <i class="fas fa-arrow-down"></i>
                                                    <?php echo "" . number_format($percentage, 2, ',', '.') . "%"; ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="text-gray-500 text-sm">
                                                    <i class="fas fa-minus"></i>
                                                    0%
                                                </span>
                                            <?php } ?>
                                        </h1>
                                        <h2 class="text-gray-700 text-sm">
                                            <i class="fas fa-calendar"></i>
                                            Rp. <?php echo number_format($lastYearRevenue, 2, ',', '.'); ?>
                                            last year
                                        </h2>
                                        <h2 class="text-gray-700">
                                            <i class="fas fa-shopping-cart"></i>
                                            <?php echo number_format($itemSoldYear, 0, ',', '.'); ?> items sold this year
                                        </h2>
                                        <p class="text-gray-700">Year's Revenue</p>
                                    </div>
                                    <div class="flex flex-row gap-2 justify-end items-end">
                                        <a href="sales.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-shopping-cart"></i>
                                            View Sales
                                        </a>
                                        <?php if ($toogleBtnAnalytics) { ?>
                                            <a href="sales.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                                <i class="fas fa-chart-line"></i>
                                                View Analytics
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- card info flex forecasting -->
                            <div class="w-full h-auto bg-white rounded-md shadow-md p-4">
                                <div class="flex flex-row gap-2 justify-between">
                                    <div class="flex flex-col gap-2">
                                        <h1 class="text-xl font-bold">
                                            <i class="fas fa-chart-line"></i>
                                            <?php echo "Rp. " . number_format($allTimeRevenue, 2, ',', '.'); ?>
                                        </h1>
                                        <h2 class="text-gray-700">
                                            <i class="fas fa-shopping-cart"></i>
                                            <?php echo number_format($itemSoldAllTime, 0, ',', '.'); ?> items sold all time
                                        </h2>
                                        <p class="text-gray-700">All Time Revenue</p>
                                    </div>
                                    <div class="flex flex-row gap-2 justify-end items-end">
                                        <a href="sales.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                            <i class="fas fa-shopping-cart"></i>
                                            View Sales
                                        </a>
                                        <?php if ($toogleBtnAnalytics) { ?>
                                            <a href="sales.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md py-2 px-4 text-center text-sm">
                                                <i class="fas fa-chart-line"></i>
                                                View Analytics
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- fotter -->
        <?php
        include("components/footer.php");
        ?>
</body>

</html>