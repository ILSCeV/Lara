
$('.login-forms').find('#loginType').on('change', function() {
    if (this.value === 'Lara') {
        $('.login-forms').find('[name=username]').hide();
        $('.login-forms').find('[name=email]').show();
    }
    else if (this.value === 'LDAP') {
        $('.login-forms').find('[name=username]').show();
        $('.login-forms').find('[name=email]').hide();
    }
});

$('.login-forms').find('[name=email]').hide();