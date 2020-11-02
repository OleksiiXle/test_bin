<?php
$style = ($offset < 0) ? 'style= "display: none;  margin-left: ' . $offset . 'px;";' : 'style="display: none;"';

?>
<style>
    .route {
        cursor: pointer;
    }
    .menu-icon{
    }
    .menu-action{
        padding: 0!important;
        margin: 0!important;
    }
    .items{
        position: absolute;
        background: #eeeeee;
        border: 2px solid #bdbdbd; /* Параметры границы */
     /*   opacity: 1;*/
        padding: 20px;
        margin-top: -5px;
        z-index: 2;
    }



</style>
    <ul class="menu-action"
        onmouseover="$(this).find('.items').show();"
        onmouseout="$(this).find('.items').hide();"
        style="margin-left: 0; /* Отступ слева в браузере IE и Opera */
               padding-left: 0; /* Отступ слева в браузере Firefox, Safari, Chrome */"


    >
        <span class="menu-icon <?=$icon;?> " ></span>
        <li class="items" <?=$style;?>>
            <?php foreach ($items as $text => $route):?>
                <?php if (is_array($route)):?>
                    <a class="route no-pjax" href="<?=$route['route'];?>">
                        <span>
                        <span class="<?=$route['icon']?>"></span>
                        <span style="padding-left: 5px"><?=$text;?></span>
                        </span>
                    </a>
                <?php else:?>
                    <a class="route no-pjax" href="<?=$route;?>"><?=$text;?></a>
                <?php endif;?>
                <br>
            <?php endforeach;?>
        </li>

    </ul>

<script>
    function drawMenu(item) {
        if($(item).siblings('.items').css('display') == 'none'){
            $('.items').hide();
            $(item).siblings('.items').show();
        } else {
            $(item).siblings('.items').hide();

        }
      /*
             onmouseover="$(this).siblings().show();"
       onmouseout="$(this).siblings().hide();"

       */
    }
</script>
