$(document).ready(function() {
  $('#message-form').submit(function(event) {
    event.preventDefault();
    
    // CKEditor'den metin al
    var message = CKEDITOR.instances.editor.getData();
    
    // Form verilerini oluştur
    var formData = new FormData();
    formData.append('message', message);
    formData.append('ticket_id', $('#ticket_id').val());

    $.ajax({
      type: 'POST',
      url: '../assest/ajax/post/ticketMessage.php',
      data: formData,
      processData: false,  // FormData'nın otomatik işlenmesini devre dışı bırak
      contentType: false,  // FormData'nın içeriğinin türünü otomatik ayarlamayı devre dışı bırak
      success: function(response) {
        // Başarılı gönderim durumunda yapılacak işlemler
        Swal.fire({
          icon: 'success',
          title: 'Başarılı!',
          text: 'Mesaj başarıyla gönderildi!'
        }).then(() => {
          // Yeni mesajı ekrana eklemek için sayfayı yenile
          location.reload();
        });
      },
      error: function(xhr, status, error) {
        // Hata durumunda yapılacak işlemler
        Swal.fire({
          icon: 'error',
          title: 'Hata!',
          text: 'Mesaj gönderilirken bir hata oluştu: ' + error
        });
      }
    });
  });
});
