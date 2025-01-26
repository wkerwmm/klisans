<header style="position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand font-weight-bold" href="../dashboard"><?php echo $site_settings['site_name']; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="../dashboard">Anasayfa <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Lisans İşlemleri
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../licence/create">Lisans Oluştur</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../licenses">Lisanslar</a>
                    </div>
                </li>
                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['username'];?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../profile">Profil</a>
                        <a class="dropdown-item" href="../tickets">Destek Talebi</a>
                        <div class="dropdown-divider"></div>
                        <?php 
                            // Kullanıcının rolünü almak için kullanıcı bilgilerini veritabanından çek
                            $user_query = $db->prepare("SELECT role FROM users WHERE username = :username");
                            $user_query->bindParam(":username", $_SESSION['username']);
                            $user_query->execute();
                            $user = $user_query->fetch(PDO::FETCH_ASSOC);
                            
                            if ($user['role'] == 'admin'): 
                        ?>
                            <a class="dropdown-item" href="../admin">Yönetici Paneli</a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="/logout">Çıkış Yap</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>