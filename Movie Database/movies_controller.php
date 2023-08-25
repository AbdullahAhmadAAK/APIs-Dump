<?php

if(isset($_GET['action']) && $_GET['action']=="getMovieDetails" )  {

    $moviename = $_GET['movieName'] ?? "";
    $moviename_query_form = strtolower(str_replace(" ", "%20", $moviename));
    $page_num = $_GET['pg_num'];

    
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/auto-complete?q=".$moviename_query_form,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: online-movie-database.p.rapidapi.com",
            "X-RapidAPI-Key: 62b1eab6ddmsh901e2fb3d7df102p1a8884jsn1c82e8127c80"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // if ($err) {
    //     echo "cURL Error #:" . $err;
    // } else {
    //     // echo $response;
    //     die(json_encode(["content" => $response['d']]));
    // }

    $responseArray = json_decode($response, true); // Decode the JSON string into an associative array

    if (isset($responseArray['d'])) {
        ob_start();
        
        // pagination
        $limit = 10; // will show 10 results at 1 page
        $start_at = ($limit * ($page_num - 1)) + 1;
        $last_index = $start_at + $limit;
        $index = $start_at;
        $total_records = count($responseArray['d']);

        foreach ($responseArray['d'] as $movieKey => $movieDetails) {
            if($index >= $last_index) {
                break;
            }
            ?>
                <tr>
                    <th><?= $index++ ?></th>
                    <td><?= isset($movieDetails['l']) && $movieDetails['l'] != "" ? $movieDetails['l'] : "Unnamed" ?></td>
                    <td><?= isset($movieDetails['q']) && $movieDetails['q'] != "" ? $movieDetails['q'] : "Unspecified" ?></td>
                    <td><?= isset($movieDetails['s']) && $movieDetails['s'] != "" ? $movieDetails['s'] : "Unspecified" ?></td>
                    <td>
                        <img src="<?= isset($movieDetails['i']['imageUrl']) ? $movieDetails['i']['imageUrl'] : 'movie_placeholder.jpg' ?>" alt="Picture of <?= isset($movieDetails['l']) ? $movieDetails['l'] . ", the movie" : "an unnamed movie" ?>" width="400px" height="400px">
                    </td>
                </tr>
            <?php
        }
        $html = ob_get_clean();

        ob_start();
        ?>
        <div class="float-left">Showing <?= $start_at ?> to <?= $index-1 ?> of <?= $total_records ?> </div>
        <nav aria-label="...">
            <ul class="pagination">
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
                <li class="page-item">
                    <!-- <a class="page-link" href="#">1</a> -->
                    <button class="page-link" onclick="getMovieDetails(1)">1</button>
                </li>
                <li class="page-item active">
                <span class="page-link">
                    2
                    <span class="sr-only">(current)</span>
                </span>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
        <?php
        $pagination = ob_get_clean();

        die(json_encode(["content" => ["rows" => $html, "pagination" => $pagination], "status" => "success"]));
    } else {
        die(json_encode(["content" => "Error encountered.", "status" => "error"]));
    }


}   

