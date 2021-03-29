<?php

//include main sistem untuk mengambil function
include("mainSystem.php");

if(!isset($_POST['username'], $_POST['email'], $_POST['phonenumber'], $_POST['password'], $_POST['repassword'])){

    sendErrorMessage("Proses tidak dapat di lanjutkan !", "notificationErrorField", "form-control");
    exit;
    return false;

}else{

    if(strlen($_POST['username']) <= 0 & strlen($_POST['email']) <= 0 & strlen($_POST['phonenumber']) <= 0 & strlen($_POST['password']) <= 0 & strlen($_POST['repassword']) <= 0){

        sendErrorMessage("silahka isi bidang data terlebih dahulu !".$_POST['password'] , "notificationErrorField", "form-control");
        exit;
        return false;

    }else{

        if(strlen($_POST['username']) > 50){
            
            sendErrorMessage("Panjang digit nama melebihi batas yang di tentukan !", "notificationErrorField", "username");
            exit;
            return false;

        }else{

            if(preg_match('/^\s*$/', $_POST["username"])){
            
                sendErrorMessage("Hai..Nama Pengguna Seperti nya masih Kosong", "notificationErrorField", "username");
                exit;
                return false;
            
            }else{

                if(!preg_match('/^[^\s][a-zA-Z\s]{1,48}[a-zA-Z]$/', $_POST["username"])){
            
                    sendErrorMessage("Oppss..sorry Username hanya dapat di masukan Huruf. dan tidak dapat di awali dan di akhri dengan spasi !", "notificationErrorField", "username");
                    exit;
                    return false;
                
                }else{
                    
                    if(preg_match('/^\s*$/', $_POST["email"])){
            
                        sendErrorMessage("Hai..email Seperti nya masih Kosong", "notificationErrorField", "email");
                        exit;
                        return false;
                    
                    }else{
        
                        if(strlen($_POST["email"]) > 225){
            
                            sendErrorMessage("email teralu panjang !", "notificationErrorField", "email");
                            exit;
                            return false;
                        
                        }else{
            
                            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            
                                sendErrorMessage("Penulisan email Salah !", "notificationErrorField", "email");
                                exit;
                                return false;
                            
                            }else{
                
                                if(domainemailCheck($_POST["email"])){
            
                                    sendErrorMessage("Proses tidak dapat di lanjutkan, penyedia layanan email tidak dapat di temukan !", "notificationErrorField", "email");
                                    exit;
                                    return false;
                                
                                }else{

                                    if(preg_match('/^[\s]*$/', $_POST["phonenumber"])){
            
                                        sendErrorMessage("Hai..Maaf, Seperti nya Nomor Hp kamu masih kosong ", "notificationErrorField", "phonenumber");
                                        exit;
                                        return false;
                                    
                                    }else{
                            
                                        if(strlen($_POST['phonenumber']) < 11 || strlen($_POST['phonenumber']) > 13){

                                            if(strlen($_POST['phonenumber']) < 11){
                                                
                                                $panjangPendek = "Pendek";

                                            }else if(strlen($_POST['phonenumber']) > 13){

                                                $panjangPendek = "Panjang";

                                            }

                                            sendErrorMessage("Hai..Maaf, Nomor hp kamu terlalu ". $panjangPendek ." coba cek lagi ya.", "notificationErrorField", "phonenumber");
                                            exit;
                                            return false;
                                        
                                        }else{
                                    
                                            if(!preg_match('/^[0][8][0-9]{9,11}$/', $_POST['phonenumber'])){

                                                sendErrorMessage("Hai..Maaf, hanya dapat di masukan angka dan hanya dapat menggunakan 08 pada 2 digit pertama", "notificationErrorField", "phonenumber");
                                                exit;
                                                return false;
                                            
                                            }else{

                                                if(preg_match('/^[\s]*$/', $_POST["password"])){
                
                                                    sendErrorMessage('Hai..Maaf, Seperti nya kata sandi kamu masih kosong ', "notificationErrorField", "password");
                                                    exit;
                                                    return false;
                                                    
                                                }else{
                                                    
                                                    if(strlen($_POST["password"]) < 8 || strlen($_POST["password"]) > 100){
                                                        
                                                        sendErrorMessage('Hai..Maaf, Masukan paling sedikit 8 digit, dan maksimal 100 digit', "notificationErrorField", "password");
                                                        exit;
                                                        return false;
                                                    
                                                    }else{
                                                
                                                        if(preg_match('/^[\s]*$/', $_POST["repassword"])){

                                                            sendErrorMessage('Hai..Maaf, Seperti nya Konfirmasi kata sandi kamu masih kosong ', "notificationErrorField", "repassword");
                                                            exit;
                                                            return false;
                                                            
                                                        }else{
                                                            
                                                            if(strlen($_POST["repassword"]) < 8 || strlen($_POST["repassword"]) > 100){
                                                                
                                                                sendErrorMessage('Hai..Maaf, Masukan paling sedikit 8 digit, dan maksimal 100 digit', "notificationErrorField", "repassword");
                                                                exit;
                                                                return false;
                                                            
                                                            }else{
                                                         
                                                                if($_POST["password"] !== $_POST["repassword"]){
                                                                
                                                                    sendErrorMessage('Hai..Maaf, kata sandi dan konfirmasi kata sandi tidak sesuai', "notificationErrorField", "repassword");
                                                                    exit;
                                                                    return false;
                                                                
                                                                }else{

                                                                    include "../ConfigDB/index.php";

                                                                    function hitungJumlahPengguna($koneksi){

                                                                        $queryJumlahPengguna = mysqli_query($koneksi, "SELECT * FROM userdata COUNT ");

                                                                        if(!$queryJumlahPengguna){
                                                                            
                                                                            sendErrorMessage(mysqli_error($koneksi), 'notificationErrorField', null);
                                                                            exit;

                                                                        }else{

                                                                            return (mysqli_num_rows($queryJumlahPengguna) === 0) ? '' : mysqli_num_rows($queryJumlahPengguna);

                                                                        }
                                                                    
                                                                    }

                                                                    function cekIDRandom($koneksi, $idRandom){
                                                                        $queryIdRandom = mysqli_query($koneksi, "SELECT * FROM userdata WHERE IdRandom = '".$idRandom."' ");

                                                                        if(!$queryIdRandom){
                                                                            
                                                                            return  false;
                                                                        
                                                                        }else{

                                                                            if(mysqli_num_rows($queryIdRandom) > 0){
                                                                                
                                                                                return $idRandom.hitungJumlahPengguna($koneksi);
                                                                            
                                                                            }else{

                                                                                return $idRandom;

                                                                            }

                                                                        }
                                                                    }

                                                                    function cekDataUdahAdaAtauBelum($koneksi, $data, $valData){

                                                                        $cekuserdata = mysqli_query($koneksi, "SELECT * FROM userdata WHERE ".$data." = '".$valData."' ");

                                                                        if(!$cekuserdata){
                                                                            
                                                                           sendErrorMessage(mysqli_error($koneksi), 'notificationErrorField', null);
                                                                           exit;
                                                                           
                                                                        
                                                                        }else{

                                                                            if(mysqli_num_rows($cekuserdata) > 0){
                                                                                
                                                                                return false;
                                                                            
                                                                            }else{

                                                                                return true;

                                                                            }

                                                                        }

                                                                    }

                                                                    if(explode('@', strtolower($_POST["email"]))[1]  === 'gmail.com'){
                                                                        
                                                                        $cariTitik          = str_replace('.', '', explode('@', strtolower($_POST["email"]))[0]);
                                                                        $filterDomainemail  = $cariTitik.'@'.explode('@', strtolower($_POST["email"]))[1];
                                                                    
                                                                    }else{

                                                                        $filterDomainemail = strtolower($_POST["email"]);
                                                                    
                                                                    }
                                                                    
                                                                    $namaPengguna       = explode(' ', $_POST["username"])[0].hitungJumlahPengguna($koneksi);
                                                                    $username           = base64_encode(htmlentities($_POST["username"], ENT_QUOTES));
                                                                    $nomorNikKtp        = base64_encode(str_shuffle("1234567890"));
                                                                    $emailPengguna      = base64_encode($filterDomainemail);
                                                                    $nomorHp            = base64_encode($_POST["phonenumber"]);
                                                                    $Garem              = "*|_*_|* Semoga Semua Yang Di Kerja Kan Menghasilkan Kesuksesan...Aamiin *|_*_|*";
                                                                    $password           = md5($_POST["password"]." ".$Garem);
                                                                    $tanggalBergabung   = date("d-m-Y H:i:s", strtotime("today ".date("H:i:s")));
                                                                    $idRandomGabung     = md5($namaPengguna." ".$tanggalBergabung." ".hitungJumlahPengguna($koneksi));
                                                                    $idRandom           = strtoupper(substr($idRandomGabung, 22, strlen($idRandomGabung)));
                                                                    $finalIdRandom      = cekIDRandom($koneksi, $idRandom);
                                                                    $kodeverifikasi      = md5($finalIdRandom." ".$Garem);
                                                                    $satatusAkun        = "Terbuka";
                                                                    $typePengguna       = "user";

                                                                    if(cekDataUdahAdaAtauBelum($koneksi, 'nikktp', $nomorNikKtp) !== true){

                                                                       sendErrorMessage("Haii..Nomor Ktp Kamu Sudah terdaftar, Maaf kamu tidak dapat melanjutkan Pembuatan Akun", 'notificationErrorField', 'NKT');
                                                                        exit;

                                                                    }else{
                                                                        
                                                                        if(cekDataUdahAdaAtauBelum($koneksi, 'email', $emailPengguna) !== true){
                                                                            
                                                                           sendErrorMessage("Haii..email Kamu Sudah terdaftar, Maaf kamu tidak dapat melanjutkan Pembuatan Akun", 'notificationErrorField', 'email');
                                                                            exit;
                                                                        
                                                                        }else{

                                                                            if(cekDataUdahAdaAtauBelum($koneksi, 'phonenumber', $nomorHp) !== true){
                                                                            
                                                                               sendErrorMessage("Haii..No Hape Kamu Sudah terdaftar, Maaf kamu tidak dapat melanjutkan Pembuatan Akun", 'notificationErrorField', 'phonenumber');
                                                                                exit;
                                                                            
                                                                            }else{

                                                                                $queryInputPengguna = mysqli_query($koneksi, "INSERT INTO userdata (username, name, nikktp, email, idrandom, statusakun, durasiterkunci , typeakun, password, phonenumber, tanggalbergabung, typepengguna, kodeverifikasi) VALUES ('$username', '$namaPengguna', '$nomorNikKtp', '$emailPengguna', '$finalIdRandom', '$satatusAkun', '0', 'Normal', '$password', '$nomorHp', '$tanggalBergabung', '$typePengguna', '$kodeverifikasi')");

                                                                                if(!$queryInputPengguna){

                                                                                   sendErrorMessage("Ooops..maaf kami gagal mendaftar kan kamu, coba di ulang yah.. ".mysqli_error($koneksi), 'notificationErrorField', null);
                                                                                   exit;

                                                                                }else{

                                                                                    $mimePesan      = "MIME-Version: 1.0"."\r\n";
                                                                                    $typePesan      = "Content-type:text/html; charset=UTF-8"."\r\n";
                                                                                    $pengirimPesan  = "From: ".$_SERVER['HTTP_HOST'];
                                                                                    $ccPesan        = "";
                                                                                    $bccPesan       = "";
                                                                                    $isiPesan       = '
                                                                                    <body style="background-color: #01090f;text-align: center;font-family: verdana;border-radius:10px;">
                                                                                        
                                                                                        <div class="nav" style="background-color: #031421;width:auto;height:auto;padding:10px;">
                                                                                            <img src="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/Pengaturan/Gambar/LogoUtama/Logo.png" />
                                                                                        </div>
                                                                                        
                                                                                        <h1 style="width: auto;text-align: center;color: #eee;line-height: 31px;margin: auto;padding: 5px;border-bottom: solid 2px #042035;text-shadow: #fbdf03 1px 1px 0px;font-weight: none;">Terima Kasih '.$namaPengguna.' Pendaftaran Kamu Berhasil</h1>
                                                                                        
                                                                                        <p style="text-indent:20px;text-align:left;padding:10px;color:#ececec;background-color: #01090f;">
                                                                                            
                                                                                            Terimakasih yah <strong>'.$namaPengguna.'</strong>. Kamu Sudah mendaftar di <strong style="color:#56cdd2;">'.strtoupper($_SERVER['HTTP_HOST']).'</strong>, Sebelum kamu menggunkan layanan dari kami,
                                                                                            kami meminta maaf atas ketidak nyamanan nanti yang kamu temukan, karna website kami masih berstatus beta. kamu juga dapat memeberi masukan memelalui menu saran ya, terimakasih. <strong>HAVE A NICE DAY</strong>
                                                                                            <br/>
                                                                                            <div class="verfikasi" style="color:#ececec;text-align:center;">Ini Kode Verfikasi Kamu ya : <strong style="color:#56cdd2;">'.$finalIdRandom.'</strong></div>
                                                                                            <div class="salam" style="width:auto; font-weight: bold; padding:7px; margin:7px;text-align:left;color: #fbdf00;">
                                                                                                Salam Hangat CEO & OWNER,
                                                                                                <br/>
                                                                                                <br/>
                                                                                                <br/>
                                                                                                Septian & Ahmad Arrafi
                                                                                            </div>
                                                                                            <div class="bawah" style="text-align:center;font-size:10px;width:auto;padding:10px;color:white;background-color: #03121e;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;border-top: solid 2px #3a4957;">
                                                                                            jika kamu tidak merasa melakukan pendaftaran / melakukan aktivitas pada '.$_SERVER['HTTP_HOST'].', Silahkan abaikan atau Hapus Pesan ini, atas pengertian nya
                                                                                            <br/>
                                                                                            team '.$_SERVER['HTTP_HOST'].' mengucapkan terimakasih.
                                                                                        </div>
                                                                                        </p>
                                                                                    </body>
                                                                                    ';

                                                                                    if(!mail(base64_decode($emailPengguna), 'Sukses Mendaftar Di '.$_SERVER['HTTP_HOST'], $isiPesan, $mimePesan.$typePesan.$pengirimPesan)){

                                                                                        $AaBb = mysqli_query($koneksi, "DELETE FROM userdata WHERE NikKtp = '".$nomorNikKtp."' and email = '".$emailPengguna."' and IdRandom = '".$finalIdRandom."'");
                                                                                        sendErrorMessage("Ooops..maaf kami gagal mengirimkan kan kamu email, coba di ulang yah.. ".mysqli_error($koneksi), 'notificationErrorField', null);
                                                                                        exit;

                                                                                    }else{
                                                                                        
                                                                                       sendErrorMessage("Terimakasih Ya..Akun kamu sudah terdaftar silahkan Login dan masukan kode verfikasi yang dikirm melalui email ya", 'notificationErrorField', null);

                                                                                    }
                                                                                }

                                                                            }

                                                                        }

                                                                    }

                                                                }

                                                            }

                                                        }

                                                    }

                                                }
                                            
                                            }     
                                        
                                        }
                                    
                                    }                 
                                
                                }
                            
                            }
            
                        }

                    }
    
                }

            }

        }

    }

}
?>