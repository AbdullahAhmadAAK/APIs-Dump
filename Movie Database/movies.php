
    
<?php 
$title = "Movies Data Viewing";
include '../Templates/header.php';
include '../Templates/navbar.php'; 
?>

<div class="notification-container">
    <div class="notification"></div>
</div>

<div class="container">
    <div class="card">
        <div class="card-header">
   
                    <form class="form bg-light p-3" action="javascript:void(0)" onsubmit="getMovieDetails(1); return false;" id="formMovies">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="movieName" id="movieName" placeholder="Enter movie name" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" id="searchMovieBtn">Find Movie</button>
                            </div>
                        </div>
                    </form>
           
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
            <!-- Pagination content goes here -->
        </div>
    </div>
</div>

<script src="./movies.js"></script>
<?php 
include '../Templates/footer.php'; 
?>