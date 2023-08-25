<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies API</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .notification-container {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000; /* Ensure it's above other content */
        }

        .notification {
            background-color: #f44336; /* Red background */
            color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            display: none;
        }
    </style>

</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="notification-container">
        <div class="notification"></div>
    </div>

    <div class="container">

        


        <div class="card">
            <div class="card-header">
                <!-- <div class="row mt-4"> -->
                    <!-- <div class="col-lg-6 mx-auto"> -->
                        <form class="form bg-light p-3" action="javascript:void(0)" onsubmit="getMovieDetails(1); return false;" id="formMovies">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="movieName" id="movieName" placeholder="Enter movie name" required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="searchMovieBtn">Find Movie</button>
                                </div>
                            </div>
                        </form>
                    <!-- </div> -->
                <!-- </div> -->
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 6%">#</th>
                            <th style="width: 25%">Name</th>
                            <th style="width: 15%">Media Type</th>
                            <th style="width: 20%">Actors</th>
                            <th style="width: 34%">Image</th>
                        </tr>
                    </thead>
                    <tbody id="movieTableBody">
                        <!-- Table rows go here -->
                    </tbody>
                </table>
            </div>
            <div class="card-footer" id="moviePagination">
                
            </div>
        </div>


        <!-- <div class="modal" tabindex="-1" id="moviesModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-10 mx-auto">
                            <table class="table table-bordered text-center table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 6%">#</th>
                                        <th style="width: 25%">Name</th>
                                        <th style="width: 15%">Media Type</th>
                                        <th style="width: 20%">Actors</th>
                                        <th style="width: 34%">Image</th>
                                    </tr>
                                </thead>
                                <tbody id="movieTableBody">
                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer" id="moviePagination">
                        
                    </div>
                </div>
            </div>
        </div>
 -->

            
       
    </div>


    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
    <script>
        const getMovieDetails = (pg_num) => {
            
            // let formData = new FormData($("#formMovies")[0]);
            // formData.set("action", "getMovieDetails");
            let movieName = $("#movieName").val();
            $.ajax({
                type: "GET",
                url: "movies_controller.php",
                data: {
                    "action": "getMovieDetails",
                    "pg_num": pg_num, 
                    "movieName": movieName
                },
                beforeSend: () => {
                    // start button loading animation here
                },
                success: (response) => {
                    // stop button loading animation here

                    let resp = JSON.parse(response);
                    
                    if(resp.status == "success"){
                        if(resp.content.rows == ""){
                            showNotification("Could not find any movies by the name '" + movieName + "'."); // if no rows found by that name
                        }
                        else{
                            $("#movieTableBody").html(resp.content.rows); // if rows found 
                            $("#moviePagination").html(resp.content.pagination);
                        }
                    }
                    else if(resp.status == "error"){
                        showNotification('Error: ' + resp.content + '.'); // if unforeseen error comes up
                    }
                }
            });
        }

        const notification = document.querySelector('.notification');

        function showNotification(message) {
            notification.textContent = message;
            notification.style.display = 'block';

            // Hide the notification after a certain time (e.g., 5 seconds)
            setTimeout(() => {
                hideNotification();
            }, 5000);
        }

        function hideNotification() {
            notification.style.display = 'none';
        }

        
        // Get a reference to the modal element
        const modal = new bootstrap.Modal(document.getElementById('moviesModal'));

        // Show the modal immediately when the page loads
        modal.show();

    </script>
</body>
</html>