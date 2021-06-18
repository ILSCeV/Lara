import WebAuthn from '../../js/webauthn/webauthn'

let larawebauthn = new WebAuthn();
declare let  publicKey: any;

$('#webauth-submit').on({
  'submit':function (e){
    e.preventDefault();
    $('#webauth-name').val($('#webauth-input-name').val());
    larawebauthn.register(
      publicKey,
      function (datas) {
        $('#webauth-register').val(JSON.stringify(datas)),
          $('#webauth-form').trigger('submit');
      }
    );
  }
});

