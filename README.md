# Luthfi Goldiansyah Kusumajadi - Sekawan Media Practical Test
 Pactical Test for Sekawan Media 2024

## Framework Info
- Laravel : 11.7.0
- PHP : 8.3.7
- MySQL : 10.4.17-MariaDB

## Username dan Password

### Admin
1. admin1: adminadmin
2. admin2: adminadmin

### Admin Pool
1. adminpool1: adminpool
2. adminpool2: adminpool

### Admin Manager
1. adminmanager1: adminmanager
2. adminmanager2: adminmanager

## Panduan Penggunaan
### Admin
1. Login menggunakan username dan password
2. Jika login berhasil akan dialihkan ke halaman dashboard dimana terdapat informasi mengenai jumlah data
3. Pilih menu vehicle orders pada side bar untuk informasi detail order
4. Akan terdapat beberapa menu dan fitur seperti Export Excell, Add new order, dan tombol cancel untuk data yang statusnya masih Requested
5. Jika memilih Export Excel maka akan muncul modal dimana selanjutnya Anda dapat memasukkan range periode waktu atau kosongkan input tanggal, dan tekan tombol export dan data akan otomatis terunduh
6. Jika memilih add new order maka akan muncul modal untuk input data, silahkan di isi dan pastikan mengisi semua data, setelah itu silahkan menekan tombol "Ok" dan data akan otomatis terinput
7. Jika menekan tombol "Cancel" maka akan muncul modal konfirmasi untuk meng-cancel request order. Jika mengkonfirmasi maka data akan berubah statusnya menjadi "Cancelled" dan data tidak akan diproses oleh admin

### Admin Pool
1. Login menggunakan username dan password
2. Jika login berhasil akan dialihkan ke halaman dashboard dimana terdapat informasi mengenai jumlah data dalam bentuk berbagai grafik
3. Pada side bar jika anda membuka Order Request, maka anda akan melihat data yang direquest yang ditujukan kepada anda
4. Jika menekan tombol Reject, maka akan muncul modal konfirmasi dan jika Anda mengkonfirmasi maka status order request akan berubah menjadi rejected dan data secara otomatis akan hilang dari tabel
5. Jika menekan tombol Approve, akan muncul modal konfirmasi beserta input select untuk memilih Admin Manager yang anda tuju dan harap pastikan untuk memilih. Jika anda tekan tombol approve, maka data akan terkirim ke Admin Manager yang anda pilih.
6. Selanjutnya ada menu Vehicle Orders sama seperti pada Admin, namun yang membedakan hanyalah tidak adanya tombol cancel melainkan tombol "Mark as Done"
7. Mark as Done ini merupakan salah satu tugas Admin Pool untuk mengkonfirmasi bahwa pesanan telah selesai dan kendaraan siap untuk dikembalikan ke pool
8. Pada saat menekan tombol ini maka akan muncul modal konfirmasi dan Admin Pool diharapkan untuk mendata dan memasukkan data jumlah BBM yang dikonsumsi dan jarak yang di tempuh. Lalu setelah mengkonfirmasi maka data akan terupdate dan status pesanan akan berubah menjadi Done.
10. (notes) untuk mempermudah kalkulasi BBM dapat menggunakan system "full to full", dan harap catat odometer kendaraan sebelum dan sesudah digunakan

### Admin Manager
1. adminmanager1: adminmanager
2. adminmanager2: adminmanager
