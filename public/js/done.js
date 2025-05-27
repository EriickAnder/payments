$('#copy-pix-btn').on('click', function () {
    const key = $('#pix-key').text();
    navigator.clipboard.writeText(key).then(() => {
        $(this).text('Copiado!');
        setTimeout(() => $(this).text('Copiar'), 2000);
    }).catch(err => alert('Erro ao copiar: ' + err));
});


function toggleCreditCardForm() {
    const isCreditCard = $('select[name="payment_method"]').val() === 'CREDIT_CARD';

    $('#credit-card-form').toggle(isCreditCard);

    $('#credit-card-form input').each(function () {
        if (isCreditCard) {
            $(this).attr('required', true);
        } else {
            $(this).removeAttr('required');
        }
    });
}
$('select[name="payment_method"]').on('change', toggleCreditCardForm);
toggleCreditCardForm();

