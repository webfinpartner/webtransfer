<?php 
switch ($status)
{
//    case Currency_exchange_model::ORDER_STATUS_PROCESSING:
//        echo _e('На рассмотрении оператора');
//    break;
//
//    case Currency_exchange_model::ORDER_STATUS_SET_OUT:
//        echo _e('Активна, доступна в поиске');
//    break;
    case Currency_exchange_model::ORDER_STATUS_CANCELED:
        echo _e('status_80');
    break;
    
    case Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED:
        echo _e('Отменена оператором');
    break;
    
    case Currency_exchange_model::ORDER_STATUS_CANCELED_BY_USER_BLOCK:
        echo _e('Отмена в связи с блокировкой');
    break;
    
    case Currency_exchange_model::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR:
        echo _e('Оператор провёл <br/>заявку до конца');
    break;
    
    case Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR:
        echo _e('Оператор <br/>отменил заявку');
    break;
    
    case Currency_exchange_model::ORDER_STATUS_ANNULLED:
        echo _e('Аннулирована');
    break;
    
    case Currency_exchange_model::ORDER_STATUS_REMOVED:
        echo _e('Удалена пользователем');
    break;
}
?>