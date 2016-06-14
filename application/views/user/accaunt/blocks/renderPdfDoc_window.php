<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    #credit_invest_details_window tr{
        display: none;
        height: 38px;
    }
</style>
<div id="credit_invest_details_window" class="popup_window" style="z-index:99999;">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
      <h2><?=_e("Документы")?></h2>
      <div class="formRight" style="width: 97% ! important;">
        <table>
             <tr class="certificate">
                <td><?=_e("Сертификат")?></td>
                <td><a class="enquiry-link" href="" target="_blank"><img style="height: 26px;" class="pdf_image" src="/images/PDF-Ripper.gif" title=""></a></td>
              </tr>
             <tr class="ofert_borrower">
                <td><?=_e("Заявка кредитора")?></td>
                <td><a class="enquiry-link" href="" target="_blank"><img style="height: 26px;" class="pdf_image" src="/images/PDF-Ripper.gif" title=""></a></td>
              </tr>
             <tr class="ofert_investor">
                <td><?=_e("Заявка заемщика")?></td>
                <td><a class="enquiry-link" href="" target="_blank"><img style="height: 26px;" class="pdf_image" src="/images/PDF-Ripper.gif" title=""></a></td>
              </tr>
              <tr class="payment_to_borrower">
                <td><?=_e("Выдача займа")?></td>
                <td><a class="enquiry-link" href="" target="_blank"><img style="height: 26px;" class="pdf_image" src="/images/PDF-Ripper.gif" title=""></a></td>
              </tr>
              <tr class="return_to_investor">
                <td><?=_e("Возврат займа")?></td>
                <td><a class="enquiry-link" href="" target="_blank"><img style="height: 26px;" class="pdf_image" src="/images/PDF-Ripper.gif" title=""></a></td>
              </tr>
              <tr class="borrower_id">
                <td><?=_e("Удостоверение личности")?></td>
                <td style="width: 49px;"><a class="enquiry-link" href="" target="_blank">
                    <img class="pdf_image" src="/img/passport.png" title="" style="margin-top: -4px; width: 26px; margin-left: 4px;">
                    </a>
                </td>
              </tr>
              <tr class="borrower_another">
                <td><?=_e("Иной документ")?></td>
                <td style="width: 49px;"><a class="enquiry-link" href="" target="_blank">
                    <img class="pdf_image" src="/images/PDF-Ripper.gif" title="" style="margin-top: -4px; width: 26px; margin-left: 4px;">
                    </a>
                </td>
              </tr>

            </table>
      </div>
    <div class="res-message"></div>
</div>
<script>
    $(document).on('click','.detailed-link',function(){
        var docs = {};
        docs.ofert_borrower = $(this).data('ofert_borrower');
        docs.ofert_investor = $(this).data('ofert_investor');
        docs.payment_to_borrower = $(this).data('payment_to_borrower');
        docs.return_to_investor = $(this).data('return_to_investor');
        docs.borrower_id = $(this).data('borrower_id');
        docs.borrower_another = $(this).data('borrower_another');
        docs.certificate = $(this).data('certificate');

        for(var i in docs){
            if(docs[i] !== undefined){
                $('#credit_invest_details_window .'+i+' a').attr('href',docs[i]);
                $('#credit_invest_details_window .'+i).show();
            }
        }

        $('#credit_invest_details_window').show();

        return false;
    });
</script>