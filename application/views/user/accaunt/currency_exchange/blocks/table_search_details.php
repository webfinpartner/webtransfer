                    <div class="table_row">
                        
                        <div class="table_cell col2" ><?=_e('currency_exchange/table_search/fee')?></div>
                        <div class="table_cell col2" style="padding-left: -34px;">
                            <?php if(isset($val->get_fee)): ?>
                                <?=$val->get_fee?>
                                <?//=_e('currency_id_'.$curency_id)?>
                                <?=Currency_exchange_model::show_payment_system_code(['currency_id' => $curency_id]);?>
                            <?php else: ?>
                                <?=$val->get_percent?> %
                            <?php endif; ?>
                        </div>
                        
                        <div class="clear"></div>
                        
                        <?php $this->load->view('user/accaunt/currency_exchange/blocks/for_search_table_dop.php', array('order' => $val)); ?>
                        
                        <?php if($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM && $val->status < Currency_exchange_model::ORDER_STATUS_SUCCESS): ?>
                            <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="width: 136px; background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
                                    <?= _e('Написать контрагенту')?>
                            </a>
                                
                            <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" ><?=_e('currency_exchange/table_search/problem')?></a>
                        <?php endif; ?>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <div class="table_row">
                    </div>                    
                    <?php if($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM && $val->status < Currency_exchange_model::ORDER_STATUS_SUCCESS): ?>
                        <!-- начало коментариев -->   
                        <?php $this->load->view('user/accaunt/currency_exchange/blocks/user_comment_chat.php', array('order' => $val, 'responce' => true)); ?>
                            
                    <?php endif; ?>
