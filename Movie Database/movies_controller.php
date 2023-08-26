<?php

if(isset($_GET['action']) && $_GET['action']=="getMovieDetails" )  {

    $moviename = $_GET['movieName'] ?? "";
    $moviename_query_form = strtolower(str_replace(" ", "%20", $moviename));
    $page_num = $_GET['pg_num'];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://imdb-search2.p.rapidapi.com/".$moviename_query_form,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: imdb-search2.p.rapidapi.com",
            "X-RapidAPI-Key: 62b1eab6ddmsh901e2fb3d7df102p1a8884jsn1c82e8127c80"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        die(json_encode(["content" => $err, "status" => "error"]));
    } 

    $responseArray = json_decode($response, true); // Decode the JSON string into an associative array

    if (isset($responseArray['description'])) {
        ob_start();
        
        // pagination helpers 
        $limit = 10; // will show 10 results at 1 page
        $start_at = ($limit * ($page_num - 1)) + 1; // this is supposed to be the # of record which is displayed 1st
        $last_index = $start_at + $limit; // this is supposed to be the ( # of last displayed record + 1)
        $index = $start_at; // this is a running index counter
        $total_records = count($responseArray['description']); // this is the total records that are found in database

        foreach ($responseArray['description'] as $movieKey => $movieDetails) {
            if($index >= $last_index) {
                break;
            }
            ?>
                <tr>
                    <th><?= $index++ ?></th>
                    <td>
                        <?php if(isset($movieDetails['#IMDB_URL']) && $movieDetails['#IMDB_URL'] != "") { ?> 
                        <a target="_blank" href="<?= $movieDetails['#IMDB_URL'] ?>"> <?php } ?>
                            <?= isset($movieDetails['#TITLE']) && $movieDetails['#TITLE'] != "" ? $movieDetails['#TITLE'] : "Unnamed" ?>
                            <?= isset($movieDetails['#YEAR']) && $movieDetails['#YEAR'] != "" ? "(".$movieDetails['#YEAR'].")" : "" ?>
                        <?php if(isset($movieDetails['#IMDB_URL']) && $movieDetails['#IMDB_URL'] != "") { ?> 
                        </a> <?php } ?>
                    </td>
                    <td><?= isset($movieDetails['#RANK']) && $movieDetails['#RANK'] != "" ? "#".$movieDetails['#RANK'] : "Unspecified" ?></td>
                    <td><?= isset($movieDetails['#ACTORS']) && $movieDetails['#ACTORS'] != "" ? $movieDetails['#ACTORS'] : "Unspecified" ?></td>
                    <td>
                        <div class="ratio ratio-4x3">
                            <img src="<?= isset($movieDetails['#IMG_POSTER']) ? $movieDetails['#IMG_POSTER'] : './Media/movie_placeholder.jpg' ?>" 
                            alt="Picture of <?= isset($movieDetails['#TITLE']) ? $movieDetails['#TITLE'] . ", the movie" : "an unnamed movie" ?>" 
                            class="img-fluid">
                        </div>
                    </td>
                </tr>
            <?php
        }
        $html = ob_get_clean();

        // pagination helpers
        $num_links = 2; // 2 for forward, 2 for backward
        $last_page = ceil($total_records/$limit);

        ob_start();
        ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="float-left">
                Showing <?= $start_at ?> to <?= $index-1 ?> of <?= $total_records ?>  records
            </div>
            <nav aria-label="">
                <ul class="pagination">
                    <li class="page-item">
                        <button class="page-link hover-bg-info" onclick="getMovieDetails(1)">first</button>
                    </li>
                    <?php if($page_num-1 > 0) { ?>
                        <li class="page-item">
                            <button class="page-link hover-bg-info" onclick="getMovieDetails(<?= $page_num - 1 ?>)">prev</button>
                        </li>
                    <?php } ?>
                    <li class="page-item">
                        <button class="page-link active hover-bg-info" onclick="getMovieDetails(<?= $page_num ?>)"><?= $page_num ?></button>
                    </li>
                    <?php if($page_num+1 <= $last_page) { ?> 
                    <li class="page-item">
                        <button class="page-link hover-bg-info" onclick="getMovieDetails(<?= $page_num + 1 ?>)">next</button>
                    </li>
                    <?php } ?>
                    <li class="page-item">
                        <button class="page-link hover-bg-info" onclick="getMovieDetails(<?= $last_page ?>)">last</button>
                    </li>
                </ul>
            </nav>
        </div>
        <?php
        $pagination = ob_get_clean();

        die(json_encode(["content" => ["rows" => $html, "pagination" => $pagination], "status" => "success"]));
    } else {
        die(json_encode(["content" => "Error encountered.", "status" => "error"]));
    }


}   

