// Установки
// var time_start = 1401096912 ;	// время начала отсчета 26 мая 2014 г

var time_start = Date.parse("Jan 20, 2015"); // время начала отсчета 26 июня 2014 г.
var summa_start = 13000000 ;      // начальная сумма

var period_s = 40 ;				// период в секундах 
var period_mks = period_s *1000; 	// период в милисекундах 
var n = 20 ;						// количество тиков в цикле

var arrtt = [];						// масив прироста средств (за 20 (n) циклов + 625$)
arrtt[1] = 24 ; 		arrtt[2] = 327 ;			
arrtt[3] = 25 ;			arrtt[4] = 13 ;		
arrtt[5] = 15 ;			arrtt[6] = 18 ;
arrtt[7] = 20 ;			arrtt[8] = 12 ;
arrtt[9] = 15 ; 		arrtt[10] = 13 ;
arrtt[11] = 15 ; 		arrtt[12] = 18 ;			
arrtt[13] = 15 ;		arrtt[14] = 18 ;		
arrtt[15] = 10 ;		arrtt[16] = 15 ;
arrtt[17] = 11 ;		arrtt[18] = 10 ;
arrtt[19] = 14 ; 		arrtt[20] = 17 ;

arrtt[0] = 0 ;
 
var date = new Date();
 
var hours = date.getHours();
var min   = date.getMinutes();
var sec   = date.getSeconds();
 
var time_current = Math.floor(date) ;

var i = 1 ;  		// Переменная цикла 
var time_delta = ((time_current - time_start)/1000).toFixed(0);	// Сколько времени в секундах прошло
var desytky_ziklov = Math.floor(time_delta/(n*period_s)) ; 	// Сколько прошло полных циклов по 10 (n) отсчетов от начального момента времени time_start
var ediniz_ziklov = time_delta % n ; 

var suma_ediniz = 0;
do {
  suma_ediniz = suma_ediniz + arrtt[ediniz_ziklov] ;
  ediniz_ziklov -= 1;
} while (ediniz_ziklov >= 1);

var suma_desyatky = 0;		// Прирост финансов за полных n циклов
var z = 1; 					// Переменная цикла 
for (z=1;z<11;z++) {		// Расчет прироста суммы за 10 (n) циклов
suma_desyatky = suma_desyatky + arrtt[z];
}

summa = summa_start + desytky_ziklov * suma_desyatky + suma_ediniz;
 
 
 /**
 * Форматирование числа.
 * @param val - Значение для форматирования
 * @param thSep - Разделитель разрядов
 * @param dcSep - Десятичный разделитель
 * @returns string
 */
 function numeric_format(val, thSep, dcSep) {
	if (!thSep) thSep = ' ';	// Проверка указания разделителя разрядов
	if (!dcSep) dcSep = ',';	// Проверка указания десятичного разделителя
 
    var res = val.toString();
    var lZero = (val < 0); // Признак отрицательного числа
 
    // Определение длины форматируемой части
    var fLen = res.lastIndexOf('.'); // До десятичной точки
    fLen = (fLen > -1) ? fLen : res.length;
 
    // Выделение временного буфера
    var tmpRes = res.substring(fLen);
    var cnt = -1;
    for (var ind = fLen; ind > 0; ind--) {
        // Формируем временный буфер
        cnt++;
        if (((cnt % 3) === 0) && (ind !== fLen) && (!lZero || (ind > 1))) {
            tmpRes = thSep + tmpRes;
        }
        tmpRes = res.charAt(ind - 1) + tmpRes;
    }
 
    return tmpRes.replace('.', dcSep);
} 
 
 
 
function display() {
time_current+=1;
sec+=1;
if (sec>=60)	{	min+=1;		sec=0;  }
if (min>=60)  	{	hours+=1;	min=0;  }
if (hours>=24)  hours=0;
 
if (i <= n) {summa = summa + arrtt[i]; i+=1;} else{i=1;}

 
var pokaz = numeric_format (summa , ',', ','); 		//	Вызов подпрограммы формирования запятых

document.getElementById("tiker_tt").innerHTML = "$"+pokaz ;
setTimeout("display();", period_mks);
}
display(); 	