<div id="<?=$selectId?>" class="multi-selct-block">
    <!--*************************************************************************** выбранные наимеования -->
    <div id="selected_<?=$selectId?>" >
        <b>Selected</b><br>
        <span id="no-selected-message-<?=$selectId?>" class="selection-row">
            No choised
        </span>
        <?php foreach ($itemsArray as $key => $value) :?>
            <div class="selection-row selection-row-<?=$selectId?> no-active"
                data-key="<?=$key?>"
                data-selected="0"
            >
                <?=$value?>
            </div>

        <?php endforeach;?>
    </div>
    <hr style="margin: 1px">
    <!--*************************************************************************** наименования для выбора -->
    <div id="choise_<?=$selectId?>" >
        <?php foreach ($itemsArray as $key => $value) :?>
            <div class="choise-row choise-row-<?=$selectId?> active"
                 data-key="<?=$key?>"
                 data-choised="0"
            >
                <?=$value?>
            </div>
        <?php endforeach;?>
    </div>
    <!--***************************************************************************-->
</div>
