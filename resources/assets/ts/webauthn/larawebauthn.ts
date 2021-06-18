import WebAuthn from '../../js/webauthn/webauthn'

let larawebauthn = new WebAuthn();
declare let  publicKey: any;

$('#webauth-submit').on({
  'click':function (){
    larawebauthn.register(
      publicKey,
      function (datas) {
        $('#webauth-register').val(JSON.stringify(datas)),
          $('#webauth-form').trigger('submit');
      }
    );
  }
});

