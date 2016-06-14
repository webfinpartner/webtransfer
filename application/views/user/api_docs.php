<h3>Loan API</h3>
<pre>
TEST URL: http://wtest2.cannedstyle.com/en/loan_api/<method>
RELEASE URL: https://webtransfer.com/en/loan_api/<method>

Все запросы вызываются с помощью метода POST. В данных обязательно должно присутстовать поле hash, которое
считается так: md5("<post_field_val1>:<post_field_val2>:<post_field_valN>:secret_key")

Все методы возвращают данные в JSON формате.
Кроме данных, в каждом вызове возврщается статус(status) выполнения операции и ошибка(error), если она 

присутствует.
Например:
{"error":"bad hash","status":"error"} - ошибка хэша
{"status":"success","message": "Вы получили займ"} - успешная операция получения займа



Описание методов:
1. get_finance_data - получение финансовых данных о пользователе
POST-данные:
 - user_id // ID пользователя
Возвращает:
 - bonus2_amount - доступная сумма на бонусе 2
 - bonus5_amount - доступная сумма на бонусе 5
 - cards - массив со списком карт в формате, где каждый элемент имеет вид:
     {"card_id":"<число>",
      "name_on_card": "<строка>",
      "card_type": <число>,  // 0 - plastic, 1 - virtual
      "card_name":"<строка>"
      "last_balance":<число> // баланс карты
 }
 - max_loan_available_by_bonus_2' - макимально доступный кредит по бонусу 2
 - security_type - значения: email, code. code - авторизация через Webtransfer App. В дальнейшем типы 

могут добавляться.

2. request_credit - заявка на получение кредита
POST-данные:
        - user_id - ID полльзователя который подает заявку на взятие кредита
        - percent - процент
        - time    - время
        - summ   - сумма
        - type    - 0 - стандарт, 1- гарант
        - account_type  - card or payment_account
        - account_id  - счет на который получить кредит: 2,5 или card_id  
Возвращает:
 - id - ID новой созданной заявки на кредит
 - message - сообщение, которое можно показать пользователю

wtest2.cannedstyle.com/ru/loan_api/request_credit?

user_id=93517463&percent=0.5&time=3&summa=50&type=1&account_type=payment_account&account_id=2


3. request_invest - заявка на выдачу кредита(инвестирование)
POST-данные:
        - user_id - - ID полльзователя который подает заявку на выдачу кредита
        - percent - процент
        - count - - количество создаваемых заявок
        - time    - время
        - summ   - сумма
        - type    - 0 - стандарт, 1- гарант
        - account_type  - card or payment_account
        - account_id  - счет с которого выдать кредит: 2,5 или card_id  
Возвращает:
 - id - ID новой созданной заявки на выдачу кредита
 - message - сообщение, которое можно показать пользователю



4. take_credit - дать кредит
POST-данные:
        - id - ID заявки на получение кредита
        - user_id - пользователь который дает займ
        - account_type  - card or payment_account
        - account_id  - счет с которого выдать кредит: 2,5 или card_id  
Возвращает:
  message - сообщение которое можно показать пользователю

5. take_invest - взять кредит
POST-данные:
        - id - id заявки на выдачу кредита(инвестирования)
        - user_id - пользователь который берет займ
        - account_type  - card or payment_account
        - account_id  - счет на который перечислить кредит: 2,5 или card_id  
Возвращает:
  message - сообщение которое можно показать пользователю

6. import_card - импортировать карту
POST-данные:
        - user_id - пользователь который импортирует карту
        - card_num  - card_proxy+card_usr_id

Возвращает:
  message - сообщение, которое можно показать пользователю


7. transfer_to_card - отправить денги на карту напрямую между пользователями
POST-данные:
        - from_user_id - пользователь с которого списываем деньги
        - from_card_id - ID карты списания
        - to_user_id - пользователь которому пополняем карту
        - to_card_id - ID карты пополнения
        - summ - сумма
 - note - Описание
        - code - код, который ввел пользователь
Возвращает:
  message - сообщение, которое можно показать пользователю

8. send_security_code - отправить код безопасности
Перенесено в Secure_api

9. get_invest_info - информация о вкладе
POST-данные:
        - user_id - пользователь 
        - id - ID вклада - если не передать, то возвращается последняя выставленная заявка
Возвращает:
         - id
         - user_id
         - account_type - card или payment_account
         - account_id - ID счета 
         - summa - сумма
         - time - время в днях
         - type - 1 - гарант, 0 - стандарт
         - percent - проценты по вкладу
         - income - прибыль по вкладу
         - out_summ - сумма с прибылью
         - fee - комиссия
         - application_id - встречная заявка
         - contragent - контрагент
         - is_expired - просрочна ли заявка

        - cert_num - номер кредитного сертификата
        - cert_date - дата сертификата
        - cert_date_exp - дата окончания сертификата
        - cert_income - доход сертификата
        - cert_cost - стоимость сертификата

        - offert_doc - договор-оферта вкладчика
        - ofert_doc_borrower - договор-оферта заемщика
        - passport_doc
        - payment_doc_borrower - ссылка на платежное полрученение заемщика
        - payment_doc_investor - ссылка на платежное полрученение вкладчика



10. get_credit_info - информация о займе
POST-данные:
        - user_id - пользователь 
        - id - ID займа - если не передать, то возвращается последняя выставленная заявка
Возвращает:
         - id
         - user_id
         - account_type - card или payment_account
         - account_id - ID счета 
         - summa - сумма
         - time - время в днях
         - type - 1 - гарант, 0 - стандарт
         - percent - проценты по вкладу
         - income - прибыль 
         - out_summ - сумма с прибылью
         - fee - комиссия   
         
         - application_id - встречная заявка
         - contragent - контрагент
         - is_expired - просрочна ли заявка
                  
         - credit_doc - кредитный договор
         - offert_doc - договор-оферта
         - payment_doc_borrower - ссылка на платежное полрученение заемщика
         - payment_doc_investor - ссылка на платежное полрученение вкладчика
 
11. requests_list - информация о займах или кредитах
POST-данные:
        - type - тип: 1- займ, 2 - вклад
        - length - количество записей для показа
        - start - позиция с которой начать вывод записей
        - order - сортировка: <поле>:asc|desc
        - filter - фильтр: <поле>:<значение>[,<поле>:<значение>]   - фильтрует записи по полю, например id=262 , id_user=500150 выведет where id=262 and id_user=500150
        - state - 1 - несведенные заявки, 2 - сведенные(активные), 5 - погашенные. если state не указано - то по умолчанию = 1
        - friends - список друзей, заявки которых нужно вывести (через запятую). Например: friends=500150,500160
        - extraInfo=true(необязательно), если равно true, то  выводит дополнительную инфурмацию о займе или кредите (данные из get_invest_info и get_credit_info)
Возвращает:
{
"recordsTotal":"1", - всего записей
"data":[
    {"id":"263",   - id заявки
    "bonus":"6",   - ID внутренного счета
    "id_user":"93517463", - ID пользователя
    "garant":"1",    - 1 - гарант, 0 - стандарт
    "account_type":"bonus", - тип счета
    "account_id":null,      - ID счета 
    "direct":"0",           - прямой перевод(1) или нет(0)
    "summa":"50.00",        - сумма заявки
    "time":"3",             - время заявки
    "percent":"0.5",        - процент
    "out_summ":"50.75",     - сумма с процентами
    "card_id":null,         - id карты, которую указали в заявке
    "type":"2",             - тип 1- займ, 2 - вклад
    "debit":"2",            - заявка контрагента
    "debit_id_user":"2",    - контрагент
    "state":"2",            - 1 - несведенные заявки, 2 - сведенные(активные), 5 - погашенные
    "extraInfo":{"id":"91417363","user_id":"10492708","account_type":"payment_account","account_id":"2","summa":50,"time":"3","type":"0","percent":"0.5","kontract":null,"date_kontract":null,"out_time":null,"income":"0.75","out_summ":0.075000000000003,"fee":50.675,"offert_doc":"https:\/\/webtransfer.com\/en\/account\/ofert-91417363.pdf"}
    }],          
"status":"success"}
Примеры:
http://wtest2.cannedstyle.com/en/loan_api/requests_list?type=2&start=0&length=199 - первые 200 вкладов на бирже(несведенные заявки)
http://wtest2.cannedstyle.com/en/loan_api/requests_list?type=2&start=0&length=199&order=summa:desc&filter=id_user:93517463&state=2 - активные вклады для пользователя 93517463 с сортировкой по сумме

12. return_loan - вернуть займ
POST-данные:
       - loan_id - ID займа
       - user_id - ID пользователя
       - card_id - ID карты если гасим займ с бонусом 7
Возвращает:
     message - сообщение, которое можно показать пользователю
     
     
13. exchangeCertificate - продать сертификат на биржу     
POST-данные:
       - invest_id - ID вклада
       - user_id - ID пользователя
       - summ - сумма
Возвращает:
     message - сообщение, которое можно показать пользователю     


14. sellCertificate - продать сертификат Гарантийному Фонду
POST-данные:
       - invest_id - ID вклада
       - user_id - ID пользователя
Возвращает:
     message - сообщение, которое можно показать пользователю     

</pre>