<style>
	#modal_visa_prefund {
		display:none;
		/*width: inherit! important;*/
		left: 21%;
		right: 21%;
	}

	#modal_visa_prefund .content_container {
		text-align: justify; 
		padding: 15px;
	}

	#modal_visa_prefund .selector_visa_card_to_prefund { 
		width: 270px;
		margin: 0 auto;
		text-align: center;
	}

	#modal_visa_prefund .price p{ 
		text-align: center;
		margin-top: 20px;
	}
	
	#modal_visa_prefund .button { 
		margin: 5px auto;
	}

	#modal_visa_prefund .selected_visa_card_to_prefund p{ 
		text-align: center;
    	font-weight: bold;
	}
</style>
<div id="modal_visa_prefund" class="popup_window_exchange valid_sendconfirmation"> 
    <div class="close" onclick="$(this).parent().hide();"></div>
    	<h2 ><?=_e('Гарантия сделки'); ?></h2>
    <div class="content_container">
    	<p>
    <?= _e('Ваши средства гарантируют Вашему контрагенту успешное завершение сделки. В случае, если вы удалите заявку или Оператор отклонит ее, деньги незамедлительно будут возвращены на эту же карту. В случае завершения сделки - средства будут перечислены контрагенту.')?>
    </p>
    	<!-- <div class="selector_visa_card_to_prefund">	
    	</div> -->
    	<p style="text-align:center; margin-bottom:0px"><?=_e('Оплата будет произведена с карты')?>:</p>
    	<div class="selected_visa_card_to_prefund"><p style="margin-top:0px"></p></div>
    	<div class="price">
    		<p >
    		<?=_e('Сумма к перечислению')?>:
    		<span></span>
    		USD
    		</p>
    	</div> 

    	<button class="button" id="start_prefund" type="button" onclick="prefund_start_transaction()"><?=_e('Перевести')?></button>
    	<p style="text-align:center">
        	<img class='loading-gif' style="display: none" src="/images/loading.gif"/>
        </p>

    </div>
</div>