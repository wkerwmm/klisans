function deleteTicket(ticketId) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Talebi kapatmak istediğinizden emin misiniz?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Evet, kapat!',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '../assest/ajax/post/closeT.php', // Talebi kapatacak PHP dosyasının yolu
                data: {ticket_id: ticketId},
                success: function(response) {
                    Swal.fire(
                        'Kapatıldı!',
                        'Talep başarıyla kapatıldı.',
                        'success'
                    ).then((result) => {
                        location.reload(); // Sayfayı yenile
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Hata!',
                        'Talep kapatılırken bir hata oluştu.',
                        'error'
                    );
                }
            });
        }
    });
}
