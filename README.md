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
1. Login menggunakan username dan password.
2. Jika login berhasil akan dialihkan ke halaman dashboard dimana terdapat informasi mengenai jumlah data.
3. Pilih menu vehicle orders pada side bar untuk informasi detail order.
4. Akan terdapat beberapa menu dan fitur seperti Export Excell, Add new order, dan tombol cancel untuk data yang statusnya masih Requested.
5. Jika memilih Export Excel maka akan muncul modal dimana selanjutnya Anda dapat memasukkan range periode waktu atau kosongkan input tanggal, dan tekan tombol export dan data akan otomatis terunduh, data hanya akan menampilkan data yang anda request.
6. Jika memilih add new order maka akan muncul modal untuk input data, silahkan di isi dan pastikan mengisi semua data, setelah itu silahkan menekan tombol "Ok" dan data akan otomatis terinput.
7. Jika menekan tombol "Cancel" maka akan muncul modal konfirmasi untuk meng-cancel request order. Jika mengkonfirmasi maka data akan berubah statusnya menjadi "Cancelled" dan data tidak akan diproses oleh admin.

### Admin Pool
1. Login menggunakan username dan password.
2. Jika login berhasil akan dialihkan ke halaman dashboard dimana terdapat informasi mengenai jumlah data dalam bentuk berbagai grafik.
3. Pada side bar jika anda membuka Order Request, maka anda akan melihat data yang direquest yang ditujukan kepada anda.
4. Jika menekan tombol Reject, maka akan muncul modal konfirmasi dan jika Anda mengkonfirmasi maka status order request akan berubah menjadi rejected dan data secara otomatis akan hilang dari tabel.
5. Pada fitur Export Excel, untuk Admin Pool dan Admin Manager akan menamilkan seluruh data.
6. Jika menekan tombol Approve, akan muncul modal konfirmasi beserta input select untuk memilih Admin Manager yang anda tuju dan harap pastikan untuk memilih. Jika anda tekan tombol approve, maka data akan terkirim ke Admin Manager yang anda pilih..
7. Selanjutnya ada menu Vehicle Orders sama seperti pada Admin, namun yang membedakan hanyalah tidak adanya tombol cancel melainkan tombol "Mark as Done".
8. Mark as Done ini merupakan salah satu tugas Admin Pool untuk mengkonfirmasi bahwa pesanan telah selesai dan kendaraan siap untuk dikembalikan ke pool.
9. Pada saat menekan tombol ini maka akan muncul modal konfirmasi dan Admin Pool diharapkan untuk mendata dan memasukkan data jumlah BBM yang dikonsumsi dan jarak yang di tempuh. Lalu setelah mengkonfirmasi maka data akan terupdate dan status pesanan akan berubah menjadi Done.
- (notes) untuk mempermudah kalkulasi BBM dapat menggunakan system "full to full", dan harap catat odometer kendaraan sebelum dan sesudah digunakan.

### Admin Manager
1. Login menggunakan username dan password.
2. Jika login berhasil akan dialihkan ke halaman dashboard dimana terdapat informasi mengenai jumlah data dalam bentuk berbagai grafik.
3. Pada sidebar akan terdapat tombol "Manage Data", dan akan dapat beberapa sub-menu dimana akan menampilkan beberapa menu lainnya.
4. Pada semua menu ini Anda dapat melakukan CRUD terhadap data, namun untuk halaman User Anda hanya dapat menrubah jabatan atau role suatu user dan menghapus user.
5. Secara umum akan ada tombol tambah data, edit, dan delete.
6. Jika Anda menekan tombol tambah data, maka akan muncul modal untuk menambah data dan silahkan isi dan pastikan seluruh data terisi.
7. Jika Anda menekan tombol edit, maka akan muncul modal untuk edit data, jika anda simpan maka data akan otomatis terupdate.
8. Jika Anda hapus, maka akan muncul modal konfirmasi dan jika anda konfirmasi maka data akan terhapus dan tidak akan di tampilkan di tabel.
9. Selanjutnya ada menu Activity Log, dimana jika Anda buka maka akan menampilkan seluruh log aktivitas user pada aplikasi website.
10. Selanjutnya akan ada Order Request, sama seperti Admin Poll hanya bedanya pada pilihan di modal terdapat pilihan Approve Final dan list nama admin manager lainnya. Jika anda memilih admin manager lainnya maka data akan dialihkan ke admin manager tersebut. Jika anda memilih Final Approve maka data akan berubah menjadi Approved dan data akan hilang dari tabel.
11. Yang terakhir adalah halaman Vehicle Orders dimana halaman ini memiliki tampilan dan fitur yang sama dengan Admin Pool, yang membedakan hanyalah tidak ada tombol dan fitur untuk "Mark as Done".

## Diagram
![Diagram](/public/img/diagram.png)

## Link Tambahan
- Link Activity Diagram : https://drive.google.com/drive/folders/1s7zzncNFhkqDUzAfTPOG6bcPSKWawTz2?usp=sharing
- Link Database MySQL : https://drive.google.com/file/d/1AVaWvaPw_VLxKMYg0mKU76gqqx-MtH2X/view?usp=sharing
- (info) Database name : sekawan-luthfi
