<form id="sel_wt_form" class="contentzone" method="POST">
    <input type="hidden" name="submited" value="1"/>
    <div class="homeobmentable">
        <div class="lefthomecol div_sell_payment_systems">
            <div class="lefthomecolvn">
                <div class="widgethometitle">
<!--                    <div class="widt">Отдаете</div>-->
                </div>
                <input type="hidden" name="payment_system_machine_name" value="" data-currency-id="840" />
                <? foreach( $payment_systems_groups as $group )
                   {
//                    $i = 0;
//                    if( $payment_system->present_out == 1 )
//                    { 
                        $i++;
                    ?>
                        <!--<div class="onehline leftgo <?= ($i == 1?'first':'') ?>" id="napobmen-25">-->
                        <a class="onehrine <?= ($i == 1?'first active_group_tab':'') ?>" data-group-id="<?=$group->id?>">
                            <div class="onehlug"></div>
                            <div class="onehldopug"></div>
                            <?php if($group->public_path_to_icon): ?>     
                                <div class="onehlineico" style="background: url('<?= $payment_system->public_path_to_icon ?>') no-repeat center center"></div>
                            <?php else :?>
                                <div class="onehlineico" ></div>
                            <?php endif; ?>
                            <div class="onehlinetext">                                
                                <? // echo _e( 'currency_name_'.$payment_system->machine_name) .' '. _e( 'currency_id_'.$payment_system->currency_id); ?>
                                <?php  echo $group->human_name ?>
                            </div>
                            <div class="clear"></div>
                        </a>
                    <? // } ?>
                <? } ?>
            </div>
        </div>
        <div class="righthomecol div_buy_payment_systems">
            <div class="righthomecolvn">
                <div class="widgethometitle2">
                    <!--<div class="widt1">Получаете</div>-->

                    <div class="clear"></div>
                </div>
                <? foreach( $payment_systems_groups as $group ): ?>
                <div class="tabhome act" id="napobmento-25" style="display:none;" data-group-id="<?=$group->id?>">
                        <? foreach( $payment_systems as $payment_system )
                       {    
                            if($group->id != $payment_system->group_id)
                            {
                                continue;
                            }
                            
                            $i = 0;
                            if( $payment_system->present_out == 2 )
                            { 
                                $i++;
//                                $name = 'currency_name_'.$payment_system->machine_name;
                                $name = Currency_exchange_model::get_ps($payment_system->machine_names)->humen_name;
                            ?>
                                <a class="onehrine <?= ($i == 1?'first':'') ?>">
                                    <div class="onehrineico" style="background: url('<?= $payment_system->public_path_to_icon ?>') no-repeat center center"></div>
                                    <div class="onehrinetext">
                                        <?// echo _e( $name ) .' '. _e( 'currency_id_'.$payment_system->currency_id); ?>
                                        <?= $name.' '.Currency_exchange_model::show_payment_system_code(['currency_id' => $payment_system->currency_id]); ?>
                                    </div>
                                    <input type="checkbox" name="buy_payment_systems[<?= $payment_system->machine_name ?>]" data-currency-id="<?=$payment_system->currency_id;?>" <?= ( $buy_payment_systems[ $payment_system->machine_name ] == 1 ? 'checked=""':''); ?> value="1" style="margin-top: 12px;"/>
                                    <div class="clear"></div>
                                </a>
                            <? } ?>
                        <? } ?>                    
                    </div>
                <?php endforeach; ?>
            </div>
        </div>        
        <div class="clear"></div>
    </div>
    <div class="homeobmentable" style="border-top: 1px solid #ebebeb;">
        <div class="lefthomecol">
            <div class="lefthomecolvn">                                
                <div class="onehline leftgo " id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <div class="onehline_lable" >
                        Сумма
                    </div>
                    <input class="form_input" id="sell_amount" value="<?= $sell_sum_amount ?>" type="text"/>
                    <input name="sell_sum_amount" value="<?= $sell_sum_amount ?>" type="hidden" tabindex="1"/>
                    
                    <div class="clear"></div>
                </div>
                <?php if(!isset($search)): ?>
                <div class="onehline leftgo " id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <div class="onehline_lable" >
                        Комиссия
                    </div>
                    <input class="form_input" id="sell_amount_fee" value="<?= $sell_sum_fee ?>" type="text" readonly=""  tabindex="-1"/>
                    <div class="clear"></div>
                </div>
                <div class="onehline leftgo last" id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <div class="onehline_lable" >
                        Всего
                    </div>
                    <input class="form_input" id="sell_amount_total" value="<?= $sell_sum_total ?>" type="text"  readonly="" tabindex="-1"/>
                    <div class="clear"></div>
                </div>
                <?php endif; ?>

            </div>
        </div>
        <div class="righthomecol">
            <div class="righthomecolvn">                
                <div class="onehline leftgo last" id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <div class="onehline_lable" >
                        Сумма От
                    </div>
                    <input class="form_input" id="buy_amount_down" value="<?= $buy_amount_down ?>" type="text"/>
                    <input name="buy_amount_down" value="<?= $buy_amount_down ?>" type="hidden" tabindex="2"/>
                    <div class="clear"></div>
                </div>                
            </div>
            <div class="onehrine">                
                <div class="onehline leftgo last" id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <div class="onehline_lable" >
                        Сумма До
                    </div>
                    <input class="form_input" id="buy_amount_up" name="buy_amount_up" value="<?= $buy_amount_up ?>" type="text" tabindex="3"/>
                    <input name="buy_amount_up" value="<?= $buy_amount_up ?>" type="hidden"/>
                    <div class="clear"></div>
                </div>                
            </div>            
        </div>        
        <div class="clear"></div>        
    </div>
        
</form>
