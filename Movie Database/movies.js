const getMovieDetails = (pg_num) => {
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
};

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