<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="sm" >
    <div class="modal fade in" id="confirmDialog" role="dialog" aria-hidden="false" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>-->
                    <span class="pull-right" style="margin-top: -5px;"><img src="/images/security_module/universal_window/<?= $logo_type_image ?>" alt=""></span>
                    <h4 class="modal-title"><?= $title ?><br/><?= $title_type ?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-style-modal">
                        <? if(!empty($top_text)): ?>
                        <div class="form-group form-group-lg"><?= $top_text ?></div>
                        <? endif; ?>


                        <div class="form-group form-group-lg">
                            <label><?= _e('Выберите способ доставки кода') ?>:</label>
                            <?php if (!empty($show_types)) { ?>
                            <div class="btn-group bootstrap-select" style="width: 100%;">
                                <input type='hidden' name='method' value="<?php echo $show_types[0]['type']; ?>"/>
                                <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="Russian" aria-expanded="false">
                                    <span class="filter-option pull-left"><?php echo $show_types[0]['name']; ?></span>&nbsp;<span class="caret"></span></button>
                                <div class="dropdown-menu">
                                    <ul class="dropdown-menu inner" role="menu">
                                        <?php foreach ($show_types as $type) { ?>
                                        <li data-original-index="<?php echo $type['type']; ?>" class="">
                                            <a tabindex="0" class="" data-normalized-text="" data-tokens="null">
                                                <span class="text"><?php echo $type['name']; ?></span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                        </div>

                    </form>
                </div>                
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary pull-left" data-dismiss="modal" onclick="select_method();
                            return false;"><?= _e('ВЫБРАТЬ') ?></button>
                    <button type="button" class="btn pull-right" data-dismiss="modal" onclick="mn.security_module.close();
                            return false;"><?= _e('ОТМЕНИТЬ') ?></button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal-backdrop fade in" style="display: none;"></div>
</div>

<script>
    function select_method() {
        var method = $("input[name='method']").val();

        mn.security_module.change_security_type('<?= $purpose ?>', method);
    }
    ;

</script>