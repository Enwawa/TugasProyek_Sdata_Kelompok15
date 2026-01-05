#include "toko.h"

int main() {
    // Muat review dari file ke Stack
    reviewStack.loadFromFile(); 

    int pilih;
    string status;

menuLogin:
    while (true) {
        tampilLogo();
        
        cout << "\n=== MENU UTAMA ===\n";
        cout << "1. Registrasi (Dengan Data KTP)\n";
        cout << "2. Login\n";
        cout << "0. Keluar\n";
        cout << "Pilih: ";
        cin >> pilih;
        
        if (pilih == 1) {
            registrasi();
            tampilLogo();
        }
        else if (pilih == 2) {
            status = login();
            
            if (status == "admin") {
                Admin admin;
				tampilLogo();
                admin.menuAdmin();
            } 
            else if (status == "user") {
				tampilLogo();
                Bakpia b;
                int pilihanMenu;
                
                do {
                    cout << "\n=== MENU USER (" << currentNama << ") ===\n";
                    cout << "1. Tampilkan Semua Menu\n";
                    cout << "2. Filter & Cari Menu\n";
                    cout << "3. Kelola Pesanan\n";
                    cout << "4. Kelola Menu Favorit\n";
                    cout << "5. Tambah Review\n";
                    cout << "6. Logout\n";
                    cout << "7. Tampilkan Menu dalam Bentuk Tree\n";
                    cout << "0. Keluar Program\n";

                    cout << "Pilih: ";
                    cin >> pilihanMenu; 
                    cin.ignore(1000, '\n');

                    if (pilihanMenu == 1) {
                        b.tampilkanSemuaMenu();
                    }
                    else if (pilihanMenu == 2) {
                        b.menuFilter();
                    }
                    else if (pilihanMenu == 7) {
                        b.tampilkanMenuTree();
                    }
                    else if (pilihanMenu == 3) {
                        int sub;
                        do {
                            cout << "\n=== KELOLA PESANAN ===\n";
                            cout << "1. Tambah Pesanan\n";
                            cout << "2. Tampilkan Pesanan\n";
                            cout << "3. Edit Jumlah Pesanan\n";
                            cout << "4. Hapus Pesanan\n";
                            cout << "5. Undo Pesanan\n";
                            cout << "6. Redo Pesanan\n";
                            cout << "7. Pembayaran\n";
                            cout << "8. Kembali\n";
                            cout << "Pilih: ";
                            cin >> sub; 
                            cin.ignore(1000, '\n');

                            if (sub == 1) b.tambahPesanan();
                            else if (sub == 2) b.tampilkanPesanan();
                            else if (sub == 3) b.editJumlahPesanan();
                            else if (sub == 4) b.hapusPesananByNo();
                            else if (sub == 5) b.undo();
                            else if (sub == 6) b.redo();
                            else if (sub == 7) b.pembayaran();
                            else if (sub == 8) break;
                            else cout << "Pilihan tidak valid.\n";
                        } while (sub != 8);
                    }
                    else if (pilihanMenu == 4) {
                        b.menuKelolaFavorit();
                    }
                    else if (pilihanMenu == 5) {
                        b.tambahReview();
                    }
                    else if (pilihanMenu == 6) {
                        cout << "Kembali ke menu utama...\n";
                        currentUsername = ""; 
                        currentNama = "";
                        system("pause");
                        goto menuLogin;
                    }
                    else if (pilihanMenu == 0) {
                        cout << "Keluar program. Sampai jumpa.\n";
                        return 0;
                    }
                    else {
                        cout << "Pilihan tidak valid.\n";
                        system("pause");
                    }
                } while (true);
            } else {
            	system("pause");
			}
        }
        else if (pilih == 0) { 
            cout << "Terima kasih. Program selesai.\n"; 
            return 0; 
        }
        else {
            cout << "Pilihan tidak valid.\n";
            system("pause");
        }
    }

    return 0;
}
