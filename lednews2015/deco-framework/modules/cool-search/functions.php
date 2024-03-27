<?php

function dc_form_search_ajax()
{
    ?>
    <div class="header-search-container">
        <h1>
            <span>Поиск</span>
            <button type="button" onClick="dc_close_search()" class="pull-right dc_close">&times;</button>
            <a class="pull-right header-search-all btn-cool-search btn-sm btn-bordered btn-bordered-light hidden-xs hide"
               href="#">Все результаты</a>
        </h1>
        <input type="text" class="header-search-input">

        <div class="header-search-results"></div>
        <?php /*
        <a class="header-search-all btn btn-sm btn-block btn-bordered btn-bordered-light visible-xs hide"
           href="#">Все результаты</a>*/
        ?>
    </div>
<?php

}
