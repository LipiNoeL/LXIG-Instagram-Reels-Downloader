<?php

function getVideoLink($reel_link)
{
    if (strpos($reel_link, '/reel/') !== false) {
        $reel_id = preg_match('/\/reel\/([\w]+)\//', $reel_link, $matches) ? $matches[1] : null;
    } else if (strpos($reel_link, '?utm_source') !== false) {
        $reel_id = preg_match('/\/reel\/([\w]+)\//', $reel_link, $matches) ? $matches[1] : null;
    } else {
        return array("", "");
    }

    if (!$reel_id) {
        return array("", "");
    }

    $link = "https://www.instagram.com/graphql/query/";
    $headers = array(
        'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36'
    );
    $variables = '{"child_comment_count":3,"fetch_comment_count":40,"has_threaded_comments":true,"parent_comment_count":24,"shortcode":"' . $reel_id . '"}';
    $params = array(
        'hl' => 'en',
        'query_hash' => 'b3055c01b4b222b8a47dc12b090e4e64',
        'variables' => $variables
    );
    $url = $link . '?' . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    try {
        $resp = curl_exec($ch);
        $data = json_decode($resp, true);
        if (isset($data['data']['shortcode_media']['video_url']) && isset($data['data']['shortcode_media']['display_url'])) {
            $video_link = $data['data']['shortcode_media']['video_url'];
            $image_preview = $data['data']['shortcode_media']['display_url'];
        } else {
            $video_link = "";
            $image_preview = "";
        }        

        return array($video_link, $image_preview);
    } catch (Exception $e) {
        return array("", "");
    } finally {
        curl_close($ch);
    }
}
?>

<?php
    // Initialize variables
    $iglink = null;	
    $iglink_error = null;  
    $success = null;
    $show_resultbox = false;

    // Check for incoming POST request
    if (isset($_POST['iglink'])) {
        // Store the link value from POST
        $iglink = $_POST['iglink'];

        // Check for empty values
        if (empty(trim($iglink))) {
            $iglink_error = "Input Link Instagram terlebih dahulu!";
        } else {
            // Check if there's an API error
            $response_array = getVideoLink($iglink);
            $embed_link = $response_array[0];
            $image_preview = $response_array[1];

            if (empty($image_preview)) {
                $iglink_error = "API Error, Coba lagi nanti!";
            } else {
                $show_resultbox = true;
            }
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Instagram Reels Downloader</title>
    <link rel="stylesheet" href="StyleSheet9.css">
    <link href="lxigraw.png" rel="shortcut icon">
    <style>
        .api-error {
            display: <?php echo ($iglink_error != null) ? 'block' : 'none'; ?>;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            width: 500px;
            border-radius: 5px;
            margin: auto;
            background-color: rgba(220, 20, 60, 0.7);
            color: white;
            text-align: center;
            padding: 10px;
        }
        .api-error.fade-in {
            opacity: 1;
        }
        .api-error.fade-out {
            opacity: 0;
            display: none;
        }
    </style>

    <script>
        function disableFormElements() {
            document.getElementById("iglink").disabled = true;
            document.getElementById("btndown").disabled = true;
        }

        window.onload = function () {
            document.getElementById("downloadForm").addEventListener("submit", function () {
                disableFormElements();
            });
        };
    </script>

</head>

<body>
    <header class="header">
        <a  href="#" class="gambarlogo"><img src="LXIG .png" class="logo">
        <nav class="navbar">
            <a href="#tutorial">How to Use</a>
            <a href="#tentang">About</a>
            <a href="#saran">Feedback Us!</a>
        </nav>
    </header>
        <section>
            <div class="downloadsection" id="downloadForm">
                <h1>Download Instagram Reels</h1>
                <form method="POST">
                    <input type="text" placeholder="Paste your Instagram Reels URL here" name="iglink" value="<?php echo $iglink; ?>">
                    <div class="buttonsearch-container">
                        <button type="submit" class="btndown" id="btndown">Download</button>
                    </div>
                </form>
            </div>
        </section>
        
        <div class="api-error <?php echo ($iglink_error != null) ? 'fade-in' : 'fade-out'; ?>">
            <?php echo $iglink_error; ?>
        </div>

    <script src="sectionanimation.js"></script>

    <?php
    if (isset($_POST['iglink']) && !empty($_POST['iglink'])) {
        $url = trim($_POST['iglink']);
        $response_array = getVideoLink($url);
        $embed_link = $response_array[0];
        $image_preview = $response_array[1];

        if (!empty($image_preview)) {
            $image_data = file_get_contents($image_preview);
            if ($image_data !== false) {
                file_put_contents('gambar.png', $image_data);
            } else {
                echo ' ';
            }
        } else {
            echo ' ';
        }                

    ?>
        <?php if ($show_resultbox) { ?>
            <div class="resultbox" id="result">
                <div class="videopreview">
                    <img src="gambar.png" width="250px" height="250px">
                </div>
                <div class="buttondownload-container">
                    <button onclick="window.open('<?php echo $embed_link; ?>', '_blank')" class="btn btn-success" type='button' name='download'>Download Video</button>
                </div>
            </div>
        <?php } ?>

    <?php } ?>

    <section id="tutorial">
        <div class="howtousebox">
            <h3>Cara Download Video Reels Instagram</h3>
            <li>Buka postingan Instagram, buka video Reels yang ingin kamu download di Instagram.</li>
            <li>Salin URL video Instagram Reels tersebut</li>
            <li>Pastekan URL Reels yang anda salin tersebut dan klik tombol "Download"</li>
        </div>
    <section>

    <div>
        <img src="ceritanyaiklanryan.png" class="advertisement">
    </div>

    <section id="tentang">
        <div class="aboutbox">
            <h3>Untuk Apa Aplikasi Ini?</h3>
            <label>Sosial media Bernama Instagram adalah sebuah social media yang sering orang gunakan untuk berinteraksi dengan pengguna lainnya secara online. Pengguna dapat pula mengabadikan momen hidupnya dengan cara membagikannya di platform media social ini. Pengguna dapat memposting foto-foto dan video miliknya ke platform social media ini.
            Ada fitur yang sangat berguna di Aplikasi social media ini, yaitu dapat mendownload video yang sudah terupload di aplikasi ini. Namun, yang menjadi kekurangan dalam aplikasi ini yaitu fitur download nya hanya bisa digunakan apabila uploader/pengirim video mengizinkan user lainnya untuk bisa mendownload videonya. Jadi, jika uploader/pengirim tidak mengizinkan user lainnya untuk mendownload videonya, maka user yang ingin mendownload tidak dapat mendownload video tersebut.
            Maka dari itu, saya membuat aplikasi ini agar orang-orang dapat mendownload video yang sudah terupload di platform social media ini.</label>
        </div>
    <section>

    <div>
        <img src="ceritanyaiklancarti.png" class="advertisement">
    </div>

    <section id="saran">
        <div class="feedbackbox">
            <h3>Feedback Us!</h3>
            <form action="https://formspree.io/f/xzblyver" method="POST">
                <input type="email" name="email" placeholder="Your Email">
                <textarea name="message" placeholder="Your Feedback Here..."></textarea>
                <button class="buttonsubmit" type="submit">Send</button>
            </form>
        </div>
    </section>
</body>

</html>