$(document).ready(function(){
    $('#forgotPasswordForm').submit(function(event){
        event.preventDefault(); // Formun normal submit işlemini engelle

        var formData = $(this).serialize(); // Form verilerini al

        // AJAX isteği gönder
        $.ajax({
            type: 'POST',
            url: '../assest/ajax/post/resetPass.php', // Şifre sıfırlama işlemi yapacak olan PHP dosyasının yolu
            data: formData,
            success: function(response){
                // PHP'den gelen yanıtı al ve SweetAlert2 ile göster
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: response
                });
            },
            error: function(xhr, status, error) {
                // Hata durumunda SweetAlert2 ile hata mesajını göster
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: xhr.responseText
                });
            }
        });
    });
});
