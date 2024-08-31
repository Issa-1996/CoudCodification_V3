<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
require('../../traitement/fonction.php');
require('../../traitement/requete.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .section {
            max-height: 400px;
            overflow-y: auto;
        }

        label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Politique de confidentialité</h1>
        <p>Voici notre politique de confidentialité :</p>
        <form action="traitementPolitique.php" method="get">
            <div id="sections">
                <div class="section">
                    <p>Description de la collecte des données...</p>
                    <p>
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Repellat repellendus laborum sit assumenda nisi necessitatibus unde, nulla exercitationem cupiditate aliquid incidunt eum adipisci amet accusantium ratione illo quasi! Totam, quasi.
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur, natus dolorum. Voluptatum blanditiis voluptas doloremque minima, veritatis quas quos quaerat repudiandae, officiis doloribus consequatur quis omnis non vel laboriosam quasi.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio numquam possimus mollitia dolore accusantium accusamus earum rerum beatae, in temporibus. Dolorum unde similique ipsa totam fuga provident perferendis officiis magnam?
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Praesentium voluptas consequatur molestias exercitationem repellendus inventore officiis obcaecati quibusdam dolor nulla harum velit error adipisci, vero illum porro magni! Eius, possimus.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni libero fuga sed maiores ipsa commodi expedita, voluptatem fugit suscipit omnis soluta iusto, illum excepturi nobis. Sunt laudantium quis voluptate veniam.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae quod earum saepe pariatur et nihil quasi aut autem dolorem magni, commodi, velit repellat molestias ea. Exercitationem et cum accusantium voluptatum!
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime soluta eius ipsa, sint libero fugiat nobis architecto distinctio minima dicta, odit voluptate quisquam illum modi, cum error. Porro, fugit quae?
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Illo officia facilis, tempora earum veniam atque ipsum hic, optio quia aliquam placeat libero vitae doloribus? Eaque mollitia accusamus culpa nam voluptate?
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Officia accusantium voluptas ex excepturi ea hic rerum cum possimus doloremque unde, id nihil eos minus ratione, consectetur eius nesciunt sequi ipsum.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto voluptatibus corporis nulla officia fugit cumque harum. Unde dolorem ut vitae et ducimus fugiat, tenetur optio quibusdam incidunt dolores iusto reiciendis.
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptate, quasi? Quod ullam temporibus esse minus voluptatibus iure, nihil officia quisquam consequuntur velit animi molestiae, harum aspernatur architecto fuga. Quis, dolorem.
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Rerum et eos accusantium, ipsum nobis optio deserunt nam impedit sint doloribus iusto praesentium officiis, animi cum incidunt recusandae esse, velit nihil?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo facere quasi porro repudiandae laborum labore dolorem! Odit sequi repellat accusantium placeat, illum ea in voluptate dolorem, tempore accusamus minima unde.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur accusantium quidem quia? Itaque, eum iure? Possimus minima, quidem quisquam excepturi error laborum, nulla sequi neque animi reprehenderit, cupiditate ratione quia?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore alias, consequatur qui necessitatibus inventore doloremque aliquam at debitis laudantium animi? Cum animi repellat vel, a at exercitationem quod tempore laborum.
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Expedita architecto rerum commodi, sapiente nemo dolores modi autem quidem laborum non sit facilis perspiciatis, eum nobis itaque accusantium facere? Neque, nihil!
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia aliquam cum recusandae temporibus, nobis saepe quas obcaecati harum perferendis incidunt adipisci facilis, laudantium dolor aperiam. Laudantium amet sapiente maxime deserunt.
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloribus similique natus, nobis eligendi, placeat veritatis obcaecati deleniti culpa explicabo hic asperiores minima id vitae fugit laboriosam. Veniam voluptates assumenda provident.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores, autem eos eius voluptatem dolorum amet accusantium iure ad atque quis, sint voluptates dolore explicabo officiis minima sequi laborum itaque architecto?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus est non expedita nihil dolorem eos, aut cupiditate recusandae nemo fuga iure id distinctio praesentium consequatur ipsa a illo eveniet maxime.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis incidunt ipsa facilis molestias animi minima quasi excepturi tempora expedita cumque id officiis consequatur dignissimos quos pariatur, praesentium nam autem culpa!
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Praesentium architecto quia inventore id modi, non, voluptatum distinctio dolore rem molestias laudantium, in autem repudiandae culpa odio blanditiis. Quaerat, eos molestias.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dignissimos magni consectetur ratione fuga blanditiis ipsa voluptatem culpa aspernatur sed mollitia, voluptas, facilis corrupti, ullam ex? Quaerat facere facilis beatae voluptates?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus, delectus non. Hic quam quo eaque unde maiores laboriosam, vel temporibus totam exercitationem repudiandae ullam nemo nam illum ratione, magni pariatur?
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Veniam nulla tenetur amet! Maiores quisquam excepturi fugit libero enim saepe, ea consequuntur labore? Minus hic doloremque earum dolorum ipsum mollitia itaque.
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Repudiandae rerum voluptatum deleniti ducimus. Consectetur recusandae quibusdam molestias molestiae quisquam aliquid nisi debitis ducimus, impedit harum dolores ipsum facere at laboriosam!
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Laudantium, ipsum nesciunt praesentium quo repellat unde quod pariatur, hic suscipit explicabo, voluptatem possimus ullam culpa dolores. Nemo suscipit autem modi sint!
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Omnis, sint maiores itaque hic laudantium recusandae quisquam at deleniti sequi ipsum eveniet excepturi, culpa ipsa aut dicta doloremque! Ut, hic beatae.
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Repellat repellendus laborum sit assumenda nisi necessitatibus unde, nulla exercitationem cupiditate aliquid incidunt eum adipisci amet accusantium ratione illo quasi! Totam, quasi.
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur, natus dolorum. Voluptatum blanditiis voluptas doloremque minima, veritatis quas quos quaerat repudiandae, officiis doloribus consequatur quis omnis non vel laboriosam quasi.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio numquam possimus mollitia dolore accusantium accusamus earum rerum beatae, in temporibus. Dolorum unde similique ipsa totam fuga provident perferendis officiis magnam?
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Praesentium voluptas consequatur molestias exercitationem repellendus inventore officiis obcaecati quibusdam dolor nulla harum velit error adipisci, vero illum porro magni! Eius, possimus.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni libero fuga sed maiores ipsa commodi expedita, voluptatem fugit suscipit omnis soluta iusto, illum excepturi nobis. Sunt laudantium quis voluptate veniam.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae quod earum saepe pariatur et nihil quasi aut autem dolorem magni, commodi, velit repellat molestias ea. Exercitationem et cum accusantium voluptatum!
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime soluta eius ipsa, sint libero fugiat nobis architecto distinctio minima dicta, odit voluptate quisquam illum modi, cum error. Porro, fugit quae?
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Illo officia facilis, tempora earum veniam atque ipsum hic, optio quia aliquam placeat libero vitae doloribus? Eaque mollitia accusamus culpa nam voluptate?
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Officia accusantium voluptas ex excepturi ea hic rerum cum possimus doloremque unde, id nihil eos minus ratione, consectetur eius nesciunt sequi ipsum.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto voluptatibus corporis nulla officia fugit cumque harum. Unde dolorem ut vitae et ducimus fugiat, tenetur optio quibusdam incidunt dolores iusto reiciendis.
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptate, quasi? Quod ullam temporibus esse minus voluptatibus iure, nihil officia quisquam consequuntur velit animi molestiae, harum aspernatur architecto fuga. Quis, dolorem.
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Rerum et eos accusantium, ipsum nobis optio deserunt nam impedit sint doloribus iusto praesentium officiis, animi cum incidunt recusandae esse, velit nihil?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo facere quasi porro repudiandae laborum labore dolorem! Odit sequi repellat accusantium placeat, illum ea in voluptate dolorem, tempore accusamus minima unde.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur accusantium quidem quia? Itaque, eum iure? Possimus minima, quidem quisquam excepturi error laborum, nulla sequi neque animi reprehenderit, cupiditate ratione quia?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore alias, consequatur qui necessitatibus inventore doloremque aliquam at debitis laudantium animi? Cum animi repellat vel, a at exercitationem quod tempore laborum.
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Expedita architecto rerum commodi, sapiente nemo dolores modi autem quidem laborum non sit facilis perspiciatis, eum nobis itaque accusantium facere? Neque, nihil!
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia aliquam cum recusandae temporibus, nobis saepe quas obcaecati harum perferendis incidunt adipisci facilis, laudantium dolor aperiam. Laudantium amet sapiente maxime deserunt.
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloribus similique natus, nobis eligendi, placeat veritatis obcaecati deleniti culpa explicabo hic asperiores minima id vitae fugit laboriosam. Veniam voluptates assumenda provident.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores, autem eos eius voluptatem dolorum amet accusantium iure ad atque quis, sint voluptates dolore explicabo officiis minima sequi laborum itaque architecto?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus est non expedita nihil dolorem eos, aut cupiditate recusandae nemo fuga iure id distinctio praesentium consequatur ipsa a illo eveniet maxime.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis incidunt ipsa facilis molestias animi minima quasi excepturi tempora expedita cumque id officiis consequatur dignissimos quos pariatur, praesentium nam autem culpa!
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Praesentium architecto quia inventore id modi, non, voluptatum distinctio dolore rem molestias laudantium, in autem repudiandae culpa odio blanditiis. Quaerat, eos molestias.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dignissimos magni consectetur ratione fuga blanditiis ipsa voluptatem culpa aspernatur sed mollitia, voluptas, facilis corrupti, ullam ex? Quaerat facere facilis beatae voluptates?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus, delectus non. Hic quam quo eaque unde maiores laboriosam, vel temporibus totam exercitationem repudiandae ullam nemo nam illum ratione, magni pariatur?
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Expedita architecto rerum commodi, sapiente nemo dolores modi autem quidem laborum non sit facilis perspiciatis, eum nobis itaque accusantium facere? Neque, nihil!
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur accusantium quidem quia? Itaque, eum iure? Possimus minima, quidem quisquam excepturi error laborum, nulla sequi neque animi reprehenderit, cupiditate ratione quia?
                    </p>
                    <input type="checkbox" id="section1" name="section1">
                    <label for="section1">Section 1 : Collecte des données</label>
                    <div class="section">
                    </div>
                </div>
            </div>
            <button type="submit" id="acceptButton" disabled>J'accepte</button>
        </form>
    </div>
    <script>
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const acceptButton = document.getElementById('acceptButton');

        function checkAllCheckboxes() {
            let allChecked = true;
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });
            acceptButton.disabled = !allChecked;
        }
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', checkAllCheckboxes);
        });
    </script>
</body>

</html>