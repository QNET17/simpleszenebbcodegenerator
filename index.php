<?php

if (isset($_GET['ssbg'])) {

    switch ($_GET['ssbg']) {

        case 'music':

            include './music.php';

            break;

        case 'game':

            include './game.php';
            break;

        case 'movie':

            include './movie.php';

            break;

        case 'moviexrel':

            include './moviexrel.php';

            break;

        case 'software':

            include './software.php';

            break;

        case 'porn':

            include './porn.php';

            break;

        case 'simple':

            include './simple.php';

            break;

        /* xTRa Generatoren */

        case 'password':

            include './password.php';

            break;

        /* Gaming Generatoren */

        case 'crknamechanger':

            include './crknamechanger.php';

            break;

        case 'faq':

            include './faq.php';

            break;

        case 'api':

            include './api_info.php';

            break;

        case 'uldonate':

            header("Location: http://ul.to/ref/10583228");

            break;

        case 'sodonate':

            header("Location: https://www.share-online.biz/affiliate/343834343537313B53756D616C652E6D79");

            break;

        default:

            include './uranjitsu.php';
            break;

    }

} else {
    include './uranjitsu.php';
}
