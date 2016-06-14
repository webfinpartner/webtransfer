$(document).ready(function() {
    /*$(".maskPhone").mask("(999) 999-9999");*/
    $(".maskDate").mask("99 99 9999");
    $(".maskcard2").mask("9999 9999 9999 9999");
    $(".maskPhone22").mask("+999(999) 999-9999");

    $.datepicker.setDefaults($.datepicker.regional['ru']);
    $('#day').datepicker({
        yearRange: "1940:2000"
    });

    var par = {
        maxlength: 50,
        alphanumeric2: true
    };

    $("#wizard2").formwizard({
        formPluginEnabled: true,
        validationEnabled: true,
        focusFirstInput: false,
        disableUIStyles: true,

        formOptions :{
                success: function(data){
                    //$("#container").html(data);
                    console.log("DATA:", data);
                    if(data.redirect) location.replace(data.redirect);
                },
                dataType: "json",
                beforeSubmit: function(data){
console.log('start_send_form');

                    if (on_reg_form_submit() == false)
                        return false;

                    $("input[name^='wire_']").each(function(){
                        if( $(this).val !== '' || $(this).val != -1 )
                        {
                            select_country_change();
                        }
                    });

                    if( !$('#next2').hasClass('pirmited') )
                    {
                        mn.security_module
                            .init()
                            .show_window('save_security_settings')
                            .done(function(res){
                                console.log('#next',res);
                                if( res['res'] === undefined || res['res'] != 'success' ) return false;

                                console.log('#next2-code');

                                $('#sms-code').val( res['code'] );
                                $('#next2').addClass('pirmited').trigger('click');
                                console.log('#next2-code-2');

//                                window.scrollTo(0,0);
//                                $(".profile").hide();
//                                $(".profile_top").hide();
//                                var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
//                                $(".profile").after(l);
                                return false;
                            });
                        return false;
                    }

                    console.log('#next_0');
                    window.scrollTo(0,0);
                    $(".profile").hide();
                    $(".profile_top").hide();
                    var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
                    $(".profile").after(l);

                },
                resetForm: true
        },

        /*formOptions :{
			success: function(data){$("#status2").fadeTo(500,1,function(){ $(this).html("<span>Form was submitted!</span>").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#w2").html("<span>Form was submitted with ajax. Data sent to the server: " + $.param(data) + "</span>");},
			dataType: 'json',
			resetForm: true
		},*/
        validationOptions: {
            rules: {
                n_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 200
                },
                f_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 200
                },
                o_name: {
                    minlength: 0,
                    maxlength: 200
                },
                n_vklad: {
                    minlength: 3,
                    maxlength: 13
                },
                email: {
                    required: true,
                    email: true,
                    check_login: true
                },
                email_send: {
                    required: true,
                    email: true
                },
                summ: {
                    required: true,
                    credit_summ: true
                },
                time: {
                    required: true,
                    credit_time: true
                },
                //				phone: {required: true, digits:true,minlength:7, maxlength:15},
                born_date: {
                    required: true,
                    dateITA: true,
                    dateAge: true
                },

                //				bank_cc : { minlength:9 , maxlength:20},
                //				bank_qiwi: { digits:false,minlength:5 , maxlength:15},
                //				bank_paypal : { digits:false, email: true, minlength:3 , maxlength:50},

                //				bank_name : { minlength:3 , maxlength:50},
                //				bank_schet: { digits:false,minlength:5 , maxlength:45},
                //				bank_bik : { digits:false, minlength:3 , maxlength:50},

                password: {
                    required: true,
                    login: true,
                    minlength: 6,
                    maxlength: 15
                },
                password2: {
                    required: true,
                    equalTo: '#form_password'
                },

                //				p_seria: { maxlength:4},
                //				p_number: { digits:true,maxlength:8},
                p_date: {
                    dateITA: true,
                    maxlength: 20
                },
                p_kpd: {
                    maxlength: 20
                },
                p_kvn: {
                    maxlength: 150,
                    alphanumeric2: true
                },
                p_born: par,

                r_house: par,
                f_house: par,
                r_index: {
                    digits: true,
                    maxlength: 10
                },
                f_index: {
                    digits: true,
                    maxlength: 10
                },
                r_town: par,
                f_town: par,
                r_street: par,
                f_street: par,
                r_flat: par,
                f_flat: par,
                r_kc: par,
                f_kc: par,
                w_name: {
                    maxlength: 30,
                    alphanumeric2: true
                },
                w_phone: {},
                w_place: {
                    maxlength: 30,
                    alphanumeric2: true
                },
                w_who: {
                    maxlength: 20,
                    alphanumeric2: true
                },
                w_time: {
                    maxlength: 20,
                    alphanumeric2: true
                },
                w_money: {
                    maxlength: 20,
                    alphanumeric2: true
                },
                agree: {
                    required: true
                },

                wire_beneficiary_name: {
//                    required: true,
                    minlength: 1,
                    maxlength: 200
                },
                wire_beneficiary_address: {
//                    required: true,
                    minlength: 1,
                    maxlength: 200
                },
                wire_beneficiary_bank: {
//                    required: true,
                    minlength: 1,
                    maxlength: 200
                },
                wire_beneficiary_bank_country:{
                    alphanumeric2: true
                },
                wire_beneficiary_bank_address:{
                    minlength: 1,
                    maxlength: 200
                },

                wire_beneficiary_account:{
                    alphanumeric2: true
                },
                wire_beneficiary_swift:{
                    alphanumeric2: true
                },
                wire_corresponding_bank: {
                    minlength: 1,
                    maxlength: 200
                },
                wire_corresponding_bank_swift:{
                    alphanumeric2: true
                },
                wire_corresponding_account:{
                    alphanumeric2: true
                }
            }
            /*messages: {


			}*/
        }
    });

    $(document).on('change','#select_countries', select_country_change );
    select_country_change();
    remove_req_fileds( true, true );
    $("input[name^='wire_']").keydown(function(){

       if( $(this).val === '' )
           remove_req_fileds();
       else
           select_country_change(true);
    });
});
var last_country = null;

function remove_req_fileds( input, req )
{
    if( input === true ) $("input[name^='wire_']").prop('required',false);
    if( input === undefined ) $("input[name^='wire_']").parent().siblings('label').find('.req').remove();
}
function set_req_fileds( fields, input )
{
    var name = '';
    var input = null, label = null;

    for( var i in fields )
    {
        name = fields[i];
        input = null;
        label = null;

        console.log(name);

        input = $("input[name='"+name+"']");
        if( input === true )
        {
            input.prop('required',true);
        }

        label = input.parent().siblings('label');
        label.append('<span class="req">*</span>');

    }
}
function select_country_change( input )
{
    if( typeof(wire_bank_reqired_fileds) === 'undefined' || wire_bank_reqired_fileds.length == 0 )
        return false;

    var wire_form = $('#select_countries').find("option:selected").data('wire_form');


    if( wire_form === undefined )
    {
        return false;
    }

    switch( wire_form )
    {
        case 'NA':
        case 'SA':
            remove_req_fileds();
            set_req_fileds( wire_bank_reqired_fileds['ea'], input );
            break;
        case 'UK':
            remove_req_fileds();
            set_req_fileds( wire_bank_reqired_fileds['uk'], input );
            break;

        default:
            remove_req_fileds();
            set_req_fileds( wire_bank_reqired_fileds['others'], input );
            break;
    }
}