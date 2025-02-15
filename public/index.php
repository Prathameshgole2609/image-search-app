<?php

require __DIR__ . '/../src/bootstrap.php';
require_login();
?>

<?php view('header', ['title' => 'Dashboard', 'href' => '../src/inc/style.css']) ?>


<div class="cnt">
   <div class="cnt1">
    <!-- <p class="search-head">🔍 Search Images by Hashtag</p> -->
    <div class="cnt2">
        <input type="text" id="hashtag" placeholder="Enter hashtag" class="search-input">
        <button id="search-btn" class="search-btn">Search</button>
    </div>
    <?php
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = db()->prepare("SELECT * FROM search_history WHERE user_id = ? ORDER BY search_date DESC LIMIT 5");
        $stmt->execute([$user_id]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($history) {
            echo "<h3>Your Recent Searches:</h3><ul>";
            foreach ($history as $search) {
                echo "<li>{$search['hashtag']} <a href='../src/delete_history.php?id={$search['id']}'>Delete</a></li>";
            }
            echo "</ul>";
        }
    }
    ?>
   </div>
     
    <div id="results" class="results"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#search-btn").click(function() {
        let hashtag = $("#hashtag").val().trim();
        if (hashtag === "") {
            alert("Please enter a hashtag.");
            return;
        }

        $.ajax({
            url: "../src/search.php",
            type: "POST",
            data: { hashtag: hashtag },
            beforeSend: function() {
                $("#results").html("<p class='loading'>⏳Fetching images...</p>");
            },
            success: function(response) {
                let images = JSON.parse(response);
                if (images.length > 0) {
                    let output = "";
                    images.forEach(img => {
                        output += `<div>
                            <img class="lazy-load abc" data-src="${img.media_url}" height="250px" width="auto">
                            <p>${img.source}</p>
                        </div>`;
                    });
                    $("#results").html(output);
                    applyLazyLoading();
                } else {
                    $("#results").html("<p>No images found.</p>");
                }
            },
            error: function() {
                $("#results").html("<p>Error fetching images.</p>");
            }
        });
    });

    function applyLazyLoading() {
        let lazyImages = document.querySelectorAll("img.lazy-load");
        let observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove("lazy-load");
                    observer.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => observer.observe(img));
    }
});
</script>


<?php view('footer') ?>