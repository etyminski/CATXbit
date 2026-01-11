<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>CATXbit</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
    <link rel="icon" type="image/svg" href="https://res.cloudinary.com/dlb75krri/image/upload/v1768013624/cat-icon_j6rgmo.svg"/>
  </head>

  <body>
    <!-- Barra de navegação -->
    <nav class="navbar">
      <div class="navbar_container">
        <a href="#home" id="navbar_logo">CATXbit</a>
        <ul class="navbar_menu">
          <li class="navbar_item">
            <a href="http://catxbit.wuaze.com/" class="navbar_links" id="home-page">Home</a>
          </li>
          <li class="navbar_item">
            <a href="#gallery" class="navbar_links" id="gallery-page"
              >Gallery</a
            >
          </li>
          <li class="navbar_item">
            <a href="facts.html" class="navbar_links" id="facts-page">Facts</a>
          </li>
          <li class="navbar_item">
            <a href="about.html" class="navbar_links" id="home-page">Sobre</a>
          </li>
          <li class="navbar_btn">
            <!-- button -->
            <a href="form.php" class="button" id="comments">Login</a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Hero Section  -->
    <div class="hero" id="home">
      <div class="hero_container">
        <h1 class="hero_heading">The <span>Cat</span> Exhibit</h1>
        <p class="hero_description">
          Imagens muito incríveis para ver rapidamente.
        </p>
      </div>
    </div>

    <!-- gallery -->
    <div class="gallery" id="gallery">
      <h1>Gallery</h1>
      <div class="gallery_wrapper">
        <div class="gallery_card">
          <img
            src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013621/big-eyes_jb1dr0.jpg"
            alt="Gato 1"
            data-gif="https://res.cloudinary.com/dlb75krri/image/upload/v1768013645/black-cat_jobw36.gif"
          />
        </div>
        <div class="gallery_card">
          <img
            src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013625/cat-mustache_xrwjgq.jpg"
            alt="Gato 2"
            data-gif="https://res.cloudinary.com/dlb75krri/image/upload/v1768013627/drinking-cat_siigwa.gif"
          />
        </div>
        <div class="gallery_card">
          <img
            src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013624/cat-ghost_yhbgzq.jpg"
            alt="Gato 3"
            data-gif="https://res.cloudinary.com/dlb75krri/image/upload/v1768013625/cat-leaf_kdu5zv.gif"
          />
        </div>
        <div class="gallery_card">
          <img
            src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013623/cat-food_f83ch9.jpg"
            alt="Gato 4"
            data-gif="https://res.cloudinary.com/dlb75krri/image/upload/v1768013652/bread-cat_pyrlx4.gif"
          />
        </div>
        <div class="gallery_card">
          <img
            src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013626/dev-cat_vrsk1m.jpg"
            alt="Gato 5"
            data-gif="https://res.cloudinary.com/dlb75krri/image/upload/v1768016789/pc-cat_vegslc.gif"
          />
        </div>
        <div class="gallery_card">
          <img
            src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013622/bunny-cat_niqttc.jpg"
            alt="Gato 6"
            data-gif="https://res.cloudinary.com/dlb75krri/image/upload/v1768013622/bunny-cat_bmkt9r.gif"
          />
        </div>
        <div class="gallery_card">
          <img
            src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013629/siamese-cat_szerxt.jpg"
            alt="Gato 7"
            data-gif="https://res.cloudinary.com/dlb75krri/image/upload/v1768013629/siamese-cat_takgin.gif"
          />
        </div>
        <div class="gallery_card">
          <img
            src="https://res.cloudinary.com/dlb75krri/image/upload/v1768013627/noodles-cat_hdopvp.jpg"
            alt="Gato 8"
            data-gif="https://res.cloudinary.com/dlb75krri/image/upload/v1768016740/noodles-cat_eoppgf.gif"
          />
        </div>
      </div>
      <h3>Providenciando mais imagens...</h3>
    </div>

    <!-- Modal -->
    <div id="gallery_modal" class="modal">
      <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-body">
          <img id="modalGif" src="" alt="GIF" />
          <div class="modal-loading">Carregando GIF...</div>
        </div>
      </div>
    </div>

    <script src="assets/js/script.js"></script>
  </body>
</html>


<?php
$host = 'localhost';
$user = 'root';
$password = '';

$mysqli = new mysqli($host, $user, $password);

if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

$sqlScript = file_get_contents('catxbit.sql');

if ($mysqli->multi_query($sqlScript)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->next_result());
} else {
    echo "Erro na execução do SQL: " . $mysqli->error;
}

$mysqli->close();
?>