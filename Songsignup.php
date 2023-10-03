<!-- CSCI 466 Group project 
Created by:
Ahmed Elfaki 
Matthew Gidron Noutai
Michael Ibikunle -->

<?php 
    include("UsrnamePasswrd.php"); 
?> 
<!DOCTYPE html>
<html>
<head>
    <title>Song Selection</title>
    <style>
        table {
    background-color: #fff;
    box-shadow: 0px 0px 10px #ccc;
}
th, td {
    background-color: #fff;
    color: #222;
}
th {
    background-color: #222;
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding-top: 15px;
    padding-bottom: 15px;
}
td {
    padding-top: 10px;
    padding-bottom: 10px;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
table {
  margin: 0 auto;
}
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['UserID'])) {
    //     // if user is not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }
    $user_id = $_SESSION['UserID'];
    // // check if UserID is passed in query string
    if (isset($_GET['UserID'])) {
        $user_id = $_GET['UserID'];
    }
    // // display UserID at the top of the page
    echo "Welcome, UserID: $user_id";
    ?>

<h1>Search Song Info by Title, Artist, Band, Or Contributor</h1>
<form method="post">
    <label for="song_select">Select Song Title:</label>
    <select name="song_select" id="song_select">
        <?php
            // query for all songs
            $sql_songs = "SELECT DISTINCT s.SongID, s.SongName FROM Song s GROUP BY s.SongName ORDER BY s.SongName ASC";
            $stmt_songs = $pdo->prepare($sql_songs);
            $stmt_songs->execute();
            $songs = $stmt_songs->fetchAll(PDO::FETCH_ASSOC);
            // loop through songs and generate options
            foreach ($songs as $song) {
                echo '<option value="' . $song['SongID'] . '">' . $song['SongName'] . '</option>';
            }
        ?>
    </select>
    <button type="submit" name="song_submit">Search Songs</button>
    <label for="artist_select">Select a Artist:</label>
    <select name="artist_select" id="artist_select">
    <?php
        // query for all contributors who contributed vocals
        $sql_vocal_contributors = "SELECT DISTINCT c.ContID, c.ContName FROM Contributors c WHERE c.contribution = 'Vocals' ORDER BY c.ContName ASC";
        $stmt_vocal_contributors = $pdo->prepare($sql_vocal_contributors);
        $stmt_vocal_contributors->execute();
        $vocal_contributors = $stmt_vocal_contributors->fetchAll(PDO::FETCH_ASSOC);
        // loop through vocal contributors and generate options
        foreach ($vocal_contributors as $vocal_contributor) {
            echo '<option value="' . $vocal_contributor['ContID'] . '">' . $vocal_contributor['ContName'] . '</option>';
        }
    ?>
    </select>
    <button type="submit" name="artist_select_submit">Search Artist</button>
    <label for="band_select">Select Band:</label>
    <select name="band_select" id="band_select">
    <?php
        // query for all bands
        $sql_bands = "SELECT DISTINCT b.BandID, b.BandName FROM Band b GROUP BY b.BandName ORDER BY b.BandName ASC";
        $stmt_bands = $pdo->prepare($sql_bands);
        $stmt_bands->execute();
        $bands = $stmt_bands->fetchAll(PDO::FETCH_ASSOC);
        // loop through bands and generate options
        foreach ($bands as $band) {
            echo '<option value="' . $band['BandID'] . '">' . $band['BandName'] . '</option>';
        }
    ?>
    </select>
    <button type="submit" name="band_submit">Search Bands</button>
    <label for="contributor_select">Select a Contributor:</label>
    <select name="contributor_select" id="contributor_select">
        <?php
            // query for all contributors
            $sql_contributors = "SELECT DISTINCT c.ContID, c.ContName FROM Contributors c ORDER BY c.ContName ASC";
            $stmt_contributors = $pdo->prepare($sql_contributors);
            $stmt_contributors->execute();
            $contributors = $stmt_contributors->fetchAll(PDO::FETCH_ASSOC);
            // loop through contributors and generate options
            foreach ($contributors as $contributor) {
                echo '<option value="' . $contributor['ContID'] . '">' . $contributor['ContName'] . '</option>';
            }
        ?>
    </select>
    <button type="submit" name="contributor_submit">Search Contributors</button>
    
    
    <div><label for="search_query">Search by Title, Artist, Band, Contributor, or Contribution:</label>
    <input type="text" id="search_query" name="search_query">
    <button type="submit" name="search_submit">Search</button>
    </div>
    <div class="center">
  
    
        <form method="post" action="Djpage.php">
    <label>Select a queue option:</label><br>
    <input type="radio" name="request_type" value="q" id="q"> 
    <label for="q">Normal Queue</label><br>
    <input type="radio" name="request_type" value="pq" id="pq"> 
    <label for="pq">Priority Queue</label><br>
    <div id="textbox" style="display:none;">
    <label for="number">Enter payment for priority queue:</label>
    <input type="number" name="payment" id="payment" min="0" max="100">
    </div>
    <script>
    var q = document.getElementById("q");
    var pq = document.getElementById("pq");
    var payment = document.getElementById("payment");
    var textbox = document.getElementById("textbox");
    q.addEventListener("change", function() {
        if (q.checked) {
            payment.disabled = true;
            payment.value = "0";
            textbox.style.display = "none";
        } else {
            payment.disabled = false;
        }
    });
    pq.addEventListener("change", function() {
        if (pq.checked) {
            textbox.style.display = "block";
            payment.disabled = false;
            payment.value = "";
        } else {
            textbox.style.display = "none";
            payment.disabled = true;
            payment.value = "0";
        }
    });
</script> 
 
    </form>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // add click event listener to table headers
  $('th').click(function() {
    // get column index
    var index = $(this).index();
    // get current sorting order (asc or desc)
    var currentOrder = $(this).attr('data-order');
    // toggle sorting order
    var newOrder = currentOrder == 'asc' ? 'desc' : 'asc';
    // get table rows
    var $table = $(this).closest('table');
    var $tbody = $table.find('tbody');
    var $rows = $tbody.find('tr');
    // sort rows based on selected column and sorting order
    $rows.sort(function(a, b) {
      var aVal = $(a).find('td').eq(index).text();
      var bVal = $(b).find('td').eq(index).text();
      if ($.isNumeric(aVal) && $.isNumeric(bVal)) {
        // numeric sorting
        return parseFloat(aVal) > parseFloat(bVal) ? 1 : -1;
      } else {
        // string sorting
        return aVal > bVal ? 1 : -1;
      }
    });
    if (newOrder == 'desc') {
      $rows = $rows.get().reverse();
    }
    // update sorting order attribute
    $(this).attr('data-order', newOrder);
    // re-append sorted rows to table
    $tbody.html($rows);
  });
});
</script>


<?php
if (isset($_POST['song_submit'])) {
    // get the selected song
    $song_id = $_POST['song_select'];
    // query for song name
    $sql_song_name = "SELECT SongName,SongID FROM Song WHERE SongID = :song_id";
    $stmt_song_name = $pdo->prepare($sql_song_name);
    $stmt_song_name->bindParam(':song_id', $song_id);
    $stmt_song_name->execute();
    $song_name = $stmt_song_name->fetchColumn();
    // query for contributors and their contributions of the selected song
    $sql_contributors = "SELECT DISTINCT s.SongID, c.ContID, c.ContName, c.contribution 
                        FROM Song s
                        INNER JOIN SongCont sc ON s.SongID = sc.SongID 
                        INNER JOIN Contributors c ON sc.ContID = c.ContID 
                        WHERE s.SongID = :song_id";
    $stmt_contributors = $pdo->prepare($sql_contributors);
    $stmt_contributors->bindParam(':song_id', $song_id);
    $stmt_contributors->execute();
    $contributors = $stmt_contributors->fetchAll(PDO::FETCH_ASSOC);
    // query for available versions of the selected song
 $sql_versions = "SELECT s.SongID, GROUP_CONCAT(DISTINCT kf.Version SEPARATOR ', ') AS versions 
                 FROM KaraokeFile kf 
                 INNER JOIN Song s ON kf.SongID = s.SongID
                 WHERE s.SongID = :song_id
                 GROUP BY s.SongID";
    $stmt_versions = $pdo->prepare($sql_versions);
    $stmt_versions->bindParam(':song_id', $song_id);
    $stmt_versions->execute();
    $versions = $stmt_versions->fetchColumn();
    // display song name, contributors, and their contributions
     echo '<h2> "' . $song_name . '":</h2>';
     echo '<table>';
    echo '<thead><tr><th data-sortable>Contributor</th><th data-sortable>Contribution</th><th data-sortable>Contributed Versions</th><th data-sortable>Select Version</th></tr></thead>';
    echo '<tbody>';
    foreach ($contributors as $contributor) {
        $sql_versions = "SELECT GROUP_CONCAT(DISTINCT kf.Version SEPARATOR ', ') AS versions 
                         FROM KaraokeFile kf 
                         INNER JOIN SongCont sc ON kf.SongID = sc.SongID
                         WHERE sc.ContID = :cont_id
                         GROUP BY sc.ContID";
        $stmt_versions = $pdo->prepare($sql_versions);
        $stmt_versions->bindParam(':cont_id', $contributor['ContID']);
        $stmt_versions->execute();
        $contributor_versions = $stmt_versions->fetchColumn();
    
  // convert versions string to array using comma as delimiter
  $contributor_versions_arr = explode(',', $contributor_versions);
  echo '<tr><td><strong>' . $contributor['ContName'] . '</strong></td><td><strong>' . $contributor['contribution'] . '</strong></td>'. '<td><strong>'. implode(', ', $contributor_versions_arr) . '</strong></td><td>';
  
  // display versions as radio buttons
  foreach ($contributor_versions_arr as $version) {
    echo '<label><input type="radio" class="version-radio" name="selected_version" value="song_submit">' . $version . '</label>';
  }
  echo '</td></tr>';
}
echo '</tbody>';
echo '</table>';
}

if(isset($_POST['artist_select_submit'])) {
    // get selected artist's ID
    $artist_id = $_POST['artist_select'];
    // query for all songs made by the contributor with vocals contribution
    $artist_songs = "SELECT DISTINCT s.SongID, s.SongName, c.ContName, GROUP_CONCAT(kf.Version ORDER BY kf.Version ASC SEPARATOR ', ') AS Versions FROM Song s
                JOIN SongCont sc ON s.SongID = sc.SongID
                JOIN Contributors c ON sc.ContID = c.ContID
                LEFT JOIN KaraokeFile kf ON s.SongID = kf.SongID
                WHERE c.ContID = :artist_id AND c.contribution = 'Vocals'
                GROUP BY s.SongName";
    $stmt_artist = $pdo->prepare($artist_songs);
    $stmt_artist->bindParam(':artist_id', $artist_id);
    $stmt_artist->execute();
    $artists = $stmt_artist->fetchAll(PDO::FETCH_ASSOC);
    // display the songs in a table
    echo '<h2>Songs Made By ' . $artists[0]['ContName'] . ' </h2>';
    echo '<table class="sortable">';
    echo '<thead><tr><th data-sortable>Song Name</th><th data-sortable>Select version Available</th></tr></thead>';
    echo '<tbody>';
    foreach ($artists as $artist) {
        echo '<tr><td><h3><strong>' . $artist['SongName'] . '</strong><h3></td>';
        echo '<td>';
        // Check if any versions are available
        if ($artist['Versions']) {
            // Split versions into an array
            $versions = explode(',', $artist['Versions']);
            // Loop through versions and create a radio button for each one
            foreach ($versions as $version) {
                echo '<label><input type="radio" name="selected_version" value="artist_select_sumbit"> ' . $version . '</label><br>';            
            }
        } else {
            // No versions available
            echo '<strong>N/A</strong>';
        }
        echo '</td></tr>';
    }
}


//band sumbit php
if (isset($_POST['band_submit'])) {
    $selected_band = $_POST['band_select'];
    $sql_songs = "SELECT s.SongID, b.BandName, s.SongName, GROUP_CONCAT(DISTINCT c.ContName SEPARATOR ' - ') AS contributors, GROUP_CONCAT(DISTINCT c.contribution SEPARATOR ' - ') AS contributions, GROUP_CONCAT(DISTINCT k.Version SEPARATOR ', ') AS Versions
    FROM Band b
    JOIN BandSong bs ON b.BandID = bs.BandID
    JOIN Song s ON bs.SongID = s.SongID
    LEFT JOIN SongCont sc ON s.SongID = sc.SongID
    LEFT JOIN Contributors c ON sc.ContID = c.ContID
    LEFT JOIN KaraokeFile k ON s.SongID = k.SongID
    WHERE b.BandID = :band_id
    GROUP BY b.BandID, s.SongName
    ORDER BY s.SongName ASC";
    $stmt_bands = $pdo->prepare($sql_songs);
    $stmt_bands->bindParam(':band_id', $selected_band, PDO::PARAM_INT);
    $stmt_bands->execute();
    $bands = $stmt_bands->fetchAll(PDO::FETCH_ASSOC);
    if (count($bands) > 0) {
        // loop through results and display them
        echo '<h2>Songs by ' . $bands[0]['BandName'] . ':</h2>';
        echo '<table class="sortable">';
        echo '<thead><tr><th data-sortable>Song Name</th><th data-sortable>Contributors</th><th data-sortable>Contribution</th><th data-sortable>Select version</th></tr></thead>';
        echo '<tbody>';
        foreach ($bands as $band) {
            $band_name = $band['BandName'];
            $song_name = $band['SongName'];
            $versions = explode(',', $band['Versions']); // split versions into an array
        
            echo '<tr>';
            echo '<td><strong>' . $song_name . '</strong></td>';
            echo '<td><strong>' . $band['contributors'] . '</strong></td>';
            echo '<td><strong>' . $band['contributions'] . '</strong></td>';
            echo '<td>';
        
            // create a radio button for each version
            foreach ($versions as $version) {
                echo '<label>';
                echo '<input type="radio" name="selected_version" value="band_submit">' . $version;
                echo '</label>';
            }
        
            echo '</td>';
            echo '</tr>';
        }
    }
}
//contributor submit php 
elseif (isset($_POST['contributor_submit'])) {
    // get the selected contributor
    $contributor_id = $_POST['contributor_select'];
    // query for the name of the selected contributor
    $sql_contributor = "SELECT ContName FROM Contributors WHERE ContID = :contributor_id";
    $stmt_contributor = $pdo->prepare($sql_contributor);
    $stmt_contributor->bindParam(':contributor_id', $contributor_id);
    $stmt_contributor->execute();
    $contributor = $stmt_contributor->fetch(PDO::FETCH_ASSOC);
    // query for songs made by the selected contributor and their contributions
    $sql_songs = "SELECT s.SongID, s.SongName, c.contribution, GROUP_CONCAT(kf.Version ORDER BY FIELD(kf.Version, 'Duet', 'Solo') ASC SEPARATOR ', ') AS Versions
              FROM Song s
              INNER JOIN SongCont sc ON s.SongID = sc.SongID
              INNER JOIN Contributors c ON c.ContID = sc.ContID
              LEFT JOIN (
              SELECT SongID, Version FROM KaraokeFile WHERE Version IN ('Solo', 'Duet')
              ) kf ON kf.SongID = s.SongID
              WHERE c.ContID = :contributor_id
              GROUP BY c.ContID,s.SongName";
    $stmt_songs = $pdo->prepare($sql_songs);
    $stmt_songs->bindParam(':contributor_id', $contributor_id);
    $stmt_songs->execute();
    $songs = $stmt_songs->fetchAll(PDO::FETCH_ASSOC);
    // display the name of the selected contributor
    echo '<h2>Songs ' . $contributor['ContName'] . ' Contributed to:</h2>';
    // create table header
    echo '<table class="sortable">';
    echo '<tr><th data-sortable>Song Name</th><th data-sortable>Contribution</th><th data-sortable>Select Version</th></tr>';
    // loop through songs and display their names and contributions in table rows
    foreach ($songs as $song) {
        echo '<tr><td><strong>' . $song['SongName'] . '</strong></td><td><strong>' . $song['contribution'] . '</strong></td>';
        echo '<td>';
    
        // Check if there are any versions available for the song
        if ($song['Versions']) {
            // Split the versions into an array
            $versions = explode(", ", $song['Versions']);
    
            // Loop through the versions and create a radio button for each
            foreach ($versions as $version) {
                echo '<label><input type="radio" name="selected_version" value="contributor_submit"> ' . $version . '</label>';
            }
        } else {
            echo 'N/A';
        }
    
        echo '</td></tr>';
    }
}
if (isset($_POST['search_submit'])) {
    $search_query = $_POST['search_query'] . '%'; // add a wildcard to the end of the search term
    $sql_search = "SELECT s.SongID, s.SongName, b.BandName, 
    (SELECT GROUP_CONCAT(c1.ContName SEPARATOR ' - ') 
    FROM SongCont sc1 
    JOIN Contributors c1 ON sc1.ContID = c1.ContID 
    WHERE sc1.SongID = s.SongID) AS contributors,
    (SELECT GROUP_CONCAT(c2.contribution SEPARATOR ' - ') 
    FROM SongCont sc2 
    JOIN Contributors c2 ON sc2.ContID = c2.ContID 
    WHERE sc2.SongID = s.SongID) AS contributions,
    GROUP_CONCAT(DISTINCT kf.Version SEPARATOR ', ') AS versions
    FROM Song s 
    LEFT JOIN KaraokeFile kf ON s.SongID = kf.SongID
    LEFT JOIN BandSong bs ON s.SongID = bs.SongID
    LEFT JOIN Band b ON bs.BandID = b.BandID
    WHERE s.SongName LIKE :search_query 
    OR EXISTS (SELECT 1 FROM SongCont sc3 JOIN Contributors c3 ON sc3.ContID = c3.ContID WHERE sc3.SongID = s.SongID AND c3.ContName LIKE :search_query)
    OR EXISTS (SELECT 1 FROM SongCont sc4 JOIN Contributors c4 ON sc4.ContID = c4.ContID WHERE sc4.SongID = s.SongID AND c4.contribution LIKE :search_query)
    OR kf.Version LIKE :search_query
    OR b.BandName LIKE :search_query
    GROUP BY s.SongName, b.BandName
    ORDER BY LOWER(s.SongName) COLLATE 'utf8mb4_general_ci' ASC, LOWER(b.BandName) COLLATE 'utf8mb4_general_ci' ASC";
    $stmt_search = $pdo->prepare($sql_search);
    $stmt_search->bindParam(':search_query', $search_query, PDO::PARAM_STR);
    $stmt_search->execute();
    $search_results = $stmt_search->fetchAll(PDO::FETCH_ASSOC);
    $songs = array(); // initialize an array to keep track of songs
    foreach ($search_results as $result) {
        $song_id = $result['SongID'];
        if (!isset($songs[$song_id])) {
            $songs[$song_id] = array(
                'SongName' => $result['SongName'],
                'BandName' => $result['BandName'], // add band name to the array
                'contributors' => array(),
                'contributions' => array(),
                'versions' => array()
            );
        }
        $songs[$song_id]['contributors'][] = $result['contributors'];
        $songs[$song_id]['contributions'][] = $result['contributions'];
        $songs[$song_id]['versions'][] = $result['versions'];
    }
    
    // loop through songs and display them
    echo '<h2>Search Results for ' . $_POST['search_query'] . ':</h2>';
    echo '<table class="sortable">';
    echo '<thead><<tr><th data-sortable>Song Name</th><th data-sortable></th><th data-sortable>Band Name</th><th data-sortable>Contributors</th><th data-sortable>Contributions</th><th data-sortable>Versions</th><th></th></tr></thead>';
    echo '<tbody>';
    foreach ($songs as $song) {
        echo '<tr>';
        echo '<td><strong>' . $song['SongName'] . '</strong></td>';
        echo '<td></td>';
        echo '<td><strong>' . $song['BandName'] . '</strong></td>';
        echo '<td><strong>' . implode(", ", $song['contributors']) . '</strong></td>';
        echo '<td><strong>' . implode(", ", $song['contributions']) . '</strong></td>';
        echo '<td>';
        // add radio buttons for each version
        foreach ($song['versions'] as $versions_str) {
            $versions_arr = explode(", ", $versions_str);
            echo '<td>';
            foreach ($versions_arr as $version) {
                echo '<input type="radio" name="selected_version" value="search_submit"> ' . $version . '<br>';
            }
            echo '</td>';
        }
        echo '</td>';
        echo '</tr>';
    }
}
?>
    <?php 
    ob_end_flush();
    ?>
    <!-- <?php 
    
    function submit_user_request($user_id) {
        if (isset($_POST['submit_request'])) {
            switch($_POST['submit_request']) {
                case 'song_submit':
                    // execute SQL statement for option 1
                    break;
                case 'artist_select_submit':
                    $selected_version = $_POST['selected_version'];
                    $selected_version_id = $_POST['selected_version_id'];
                    // prepare SQL statement
                    $stmt_select = $pdo->prepare("
                        SELECT kf.FileID, kf.Version
                        FROM KaraokeFile kf
                        INNER JOIN Song s ON kf.SongID = s.SongID
                        WHERE s.SongID = :song_id");
                    // bind parameters and execute SQL statement
                    $stmt_select->bindParam(':song_id', $selected_version_id);
                    $stmt_select->execute();
                    // fetch result
                    while ($result = $stmt_select->fetch()) {
                        $file_id = $result['FileID'];
                        $version = $result['Version'];
                        // perform some action with $file_id and $version
    
                        // insert payment amount into Payment table
                        $payment_amount = isset($_POST['payment']) && !empty($_POST['payment']) ? $_POST['payment'] : 0;
                        $stmt_payment = $pdo->prepare("
                        INSERT INTO `Payment` (payment) 
                        VALUES (:user_id, :payment);");
                        $stmt_payment->bindParam(':user_id', $user_id);
                        $stmt_payment->bindParam(':payment', $payment_amount);
                        $stmt_payment->execute();
    
                        // prepare insert SQL statement for OrderInfo table
                        $stmt_insert = $pdo->prepare("
                        INSERT INTO `OrderInfo` (UserID, QID, FileID, payment) 
                        VALUES (:user_id, :qid, :file_id, :payment);");
                    
                    // determine QID based on request type
                    $request_type = $_POST['request_type'];
                    $qid = ($request_type === 'pq' ? 1 : 0);
                    
                    // bind the params 
                    $stmt_insert->bindParam(':user_id', $user_id);
                    $stmt_insert->bindParam(':qid', $qid);
                    $stmt_insert->bindParam(":file_id", $file_id);
                    $stmt_insert->bindParam(":payment", $payment_amount);
                    // execute the statement
                    $stmt_insert->execute();;
                        var_dump($pdo->errorInfo());
                    }
                    break;
                case 'band_submit':
                    // execute SQL statement for option 3
                    break;
                case 'contributor_submit';
                //
                    break;
                case 'search_submit';
                //
                    break;
                default:
                    // handle invalid option
                    break;
            }
        }
    }
    ?> -->
</body>
</html>

</div>

<form action="https://students.cs.niu.edu/~z1877540/PHP_FILES/Djpage.php" method="post">
  <input type="submit" name="submit_request" value="submit_request">
</form> 
  </html>