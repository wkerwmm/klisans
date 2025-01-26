function editLicense(licenseId) {
    // Bootstrap modalı göster
    $('#editLicenseModal').modal('show');

    // Lisans ID'sini gizli alan içine yaz
    $('#licenseId').val(licenseId);
}

// Düzenleme formunu gönderme işlevi
function submitEditForm() {
    // AJAX isteği gönder
    $.ajax({
        type: 'POST',
        url: '../assest/ajax/post/licenseEdit.php',
        data: $('#editForm').serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Başarılı mesajı göster
                Swal.fire('Başarılı!', 'Lisans başarıyla güncellendi.', 'success').then(() => {
                    // Sayfayı yeniden yükle
                });
            } else {
                // Hata mesajı göster
                if(response.error) {
                    Swal.fire('Hata!', response.error, 'error');
                } else {
                    Swal.fire('Hata!', 'Lisans güncellenirken bir hata oluştu.', 'error');
                }
            }
        },
        error: function(xhr, status, error) {
            // AJAX hatası
            Swal.fire('Hata!', 'AJAX hatası: ' + error, 'error');
        }
    });
}

// Lisansı silme fonksiyonu
function deleteLicense(licenseId) {
    if (confirm('Lisansı silmek istediğinize emin misiniz?')) {
        $.ajax({
            type: 'POST',
            url: '../assest/ajax/post/licenseDelete.php',
            data: { id: licenseId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Lisans başarıyla silindi.');
                    location.reload(); // Sayfayı yeniden yükleme
                } else {
                    alert('Lisans silinirken bir hata oluştu.');
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX hatası: ' + error);
            }
        });
    }
}
