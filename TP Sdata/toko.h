#ifndef TOKO_H
#define TOKO_H

#include <iostream>
#include <fstream>
#include <string>
#include <iomanip>
#include <ctime>
#include <sstream>
#include "ktp.h"  // Include header KTP

using namespace std;

// ===============================
// PEMISAHAN STRUKTUR DATA
// (Tree, Stack, CSLL, History dipisah ke header terpisah)
// ===============================
#include "csll.h"
#include "history.h"
#include "stack.h"
#include "tree.h"

// ===============================
// VARIABEL GLOBAL
// ===============================
string currentUsername = "";
string currentNama = "";

// Global objek ReviewStack (Stack untuk review terbaru)
ReviewStack reviewStack;

// ===============================
// FUNGSI BANTU
// ===============================

// Fungsi bantu untuk split string
void splitString(const string& str, char delimiter, string* result, int maxParts) {
    int count = 0;
    int start = 0;
    int end = str.find(delimiter);
    
    while (end != string::npos && count < maxParts - 1) {
        result[count] = str.substr(start, end - start);
        start = end + 1;
        end = str.find(delimiter, start);
        count++;
    }
    result[count] = str.substr(start);
}

// Fungsi bantu untuk konversi string ke integer
int stringToInt(const string& str) {
    int result = 0;
    for (int i = 0; i < str.length(); i++) {
        if (str[i] >= '0' && str[i] <= '9') {
            result = result * 10 + (str[i] - '0');
        }
    }
    return result;
}

// ===============================
// FUNGSI REGISTRASI & LOGIN (DENGAN KTP)
// ===============================
void registrasi() {
    cout << "\n=== REGISTRASI DENGAN DATA KTP ===\n";
    cout << "Silakan isi data KTP Anda dengan benar:\n";
    
    // Input data KTP lengkap
    KTP ktp;
    ktp.inputData();
    
    // Tampilkan data untuk konfirmasi
    cout << "\n=== KONFIRMASI DATA KTP ===\n";
    ktp.tampilkanData();
    
    cout << "\nApakah data di atas sudah benar? (y/n): ";
    char konfirmasi;
    cin >> konfirmasi;
    cin.ignore(1000, '\n');
    
    if (konfirmasi != 'y' && konfirmasi != 'Y') {
        cout << "Registrasi dibatalkan.\n";
        system("pause");
        return;
    }
    
    // Cek apakah NIK sudah terdaftar
    ifstream cekFile("akun.txt");
    if (cekFile.is_open()) {
        string line;
        while (getline(cekFile, line)) {
            if (line.empty()) continue;
            
            size_t pos = line.find(';');
            if (pos != string::npos) {
                string nikFromFile = line.substr(0, pos);
                if (nikFromFile == to_string(ktp.getNIK())) {
                    cout << "\n NIK " << ktp.getNIK() << " sudah terdaftar!\n";
                    cekFile.close();
                    system("pause");
                    return;
                }
            }
        }
        cekFile.close();
    }
    
    // Simpan ke file akun.txt (format sederhana untuk login)
    ofstream loginFile("akun.txt", ios::app);
    if (loginFile.is_open()) {
        // Format: NIK;Nama
        loginFile << ktp.getNIK() << ";" << ktp.getNama() << "\n";
        loginFile.close();
    } else {
        cout << "Gagal membuka file akun.txt\n";
        system("pause");
        return;
    }
    
    // Simpan data KTP lengkap ke file terpisah
    ofstream ktpFile("data_ktp.txt", ios::app);
    if (ktpFile.is_open()) {
        ktpFile << ktp.toString() << "\n";
        ktpFile.close();
    }
    
    cout << "\n==========================================\n";
    cout << " REGISTRASI BERHASIL!\n";
    cout << " NIK Anda: " << ktp.getNIK() << "\n";
    cout << " Nama: " << ktp.getNama() << "\n";
    cout << " Simpan NIK Anda untuk login\n";
    cout << "==========================================\n";
    system("pause");
}

string login() {
    string input;
    
    cout << "\n=== SISTEM LOGIN ===\n";
    cout << "Masukkan:\n";
    cout << "  - 'admin' untuk login sebagai Administrator\n";
    cout << "  - Atau NIK KTP Anda untuk login sebagai User\n";
    cout << "Input: ";
    cin >> input;  // Gunakan cin >> untuk menghindari whitespace
    
    // LOGIN ADMIN
    if (input == "admin") {
        cout << "Password Admin: ";
        string password;
        cin >> password;
        
        if (password == "admin123") {
            currentUsername = "admin";
            currentNama = "Administrator";
            cout << "\n Login sebagai Administrator berhasil!\n";
            system("pause");
            system("cls");
            return "admin";
        } else {
            cout << "\n Password admin salah!\n";
            return "gagal";
        }
    }
    // LOGIN USER DENGAN NIK
    else {
        // Validasi input NIK
        if (input.length() != 16) {
            cout << "\n NIK harus 16 digit! (Input Anda: " << input.length() << " digit)\n";
            cout << "   Contoh NIK yang valid: 1234567890123456\n";
            return "gagal";
        }
        
        // Cek apakah semua karakter adalah digit
        bool isAllDigits = true;
        for (char c : input) {
            if (!isdigit(c)) {
                isAllDigits = false;
                break;
            }
        }
        
        if (!isAllDigits) {
            cout << "\n NIK harus berupa angka 0-9!\n";
            return "gagal";
        }
        
        // Cek di file akun.txt
        ifstream file("akun.txt");
        if (!file.is_open()) {
            cout << "\n Belum ada pengguna terdaftar!\n";
            cout << "   Silakan registrasi terlebih dahulu.\n";
            return "gagal";
        }
        
        string line;
        bool found = false;
        
        while (getline(file, line)) {
            if (line.empty()) continue;
            
            // Gunakan stringstream untuk parsing
            stringstream ss(line);
            string nikFromFile, namaFromFile;
            
            if (getline(ss, nikFromFile, ';') && getline(ss, namaFromFile)) {
                if (nikFromFile == input) {
                    currentUsername = input;
                    currentNama = namaFromFile;
                    found = true;
                    break;
                }
            }
        }
        
        file.close();
        
        if (!found) {
            cout << "\n NIK tidak ditemukan!\n";
            cout << "   NIK yang Anda masukkan: " << input << "\n";
            cout << "   Pastikan NIK sudah terdaftar.\n";
            return "gagal";
        }
        
        cout << "\n Login berhasil!\n";
        cout << " Selamat datang, " << currentNama << "!\n";
        system("pause");
        return "user";
    }

}

void clearScreen() {
    #ifdef _WIN32
        system("cls");
    #else
        system("clear");
    #endif
}

void tampilLogo(){
   	clearScreen();
	cout<<"88888888888 .d88888b.  888    d8P   .d88888b.       888888b.         d8888 888    d8P  8888888b. 8888888        d8888 "<<endl;
	cout<<"    888    d88P* *Y88b 888   d8P   d88P* *Y88b      888  *88b       d88888 888   d8P   888   Y88b  888         d88888 "<<endl;
	cout<<"    888    888     888 888  d8P    888     888      888  .88P      d88P888 888  d8P    888    888  888        d88P888 "<<endl;
	cout<<"    888    888     888 888d88K     888     888      8888888K.     d88P 888 888d88K     888   d88P  888       d88P 888 "<<endl;
	cout<<"    888    888     888 8888888b    888     888      888  *Y88b   d88P  888 8888888b    8888888P*   888      d88P  888 "<<endl;
	cout<<"    888    888     888 888  Y88b   888     888      888    888  d88P   888 888  Y88b   888         888     d88P   888 "<<endl;
	cout<<"    888    Y88b. .d88P 888   Y88b  Y88b. .d88P      888   d88P d8888888888 888   Y88b  888         888    d8888888888 "<<endl;
	cout<<"    888     *Y88888P*  888    Y88b  *Y88888P*       8888888P* d88P     888 888    Y88b 888       8888888 d88P     888 "<<endl;
	cout<<endl;
	cout<<"----------------------------------------------------------------------------------------------------------------------"<<endl;
    cout << "					Selamat Datang di Toko Bakpia Kami!\n";
    cout << "					   Tempat Bakpia Enak & Legit\n";
	cout<<"----------------------------------------------------------------------------------------------------------------------"<<endl;
}

// ===============================
// CLASS ADMIN (TIDAK BERUBAH)
// ===============================
class Admin {
public:
    void menuAdmin() {
        int pilihan;
        do {
            cout << "\n=== MENU ADMIN ===\n";
            cout << "1. Lihat Semua Akun Pengguna\n";
            cout << "2. Hapus Akun Pengguna\n";
            cout << "3. Lihat Riwayat Transaksi (Ringkasan)\n";
            cout << "4. Lihat Detail Transaksi\n";
            cout << "5. Hapus Transaksi\n";
            cout << "6. Kelola Menu Toko\n";
            cout << "7. Kelola Stack Review Terbaru\n";
            cout << "8. Logout\n";
            cout << "0. Keluar Program\n";
            cout << "Pilih: ";
            cin >> pilihan;
            cin.ignore(1000, '\n');

            switch (pilihan) {
                case 1: tampilkanSemuaAkun(); break;
                case 2: hapusAkun(); break;
                case 3: tampilkanRiwayatTransaksi(); break;
                case 4: lihatDetailTransaksi(); break;
                case 5: hapusTransaksi(); break;
                case 6: kelolaMenuToko(); break;
                case 7: kelolaStackReview(); break;
                case 8: cout << "Kembali ke menu utama...\n"; system("pause"); return; break;
                case 0: cout << "Terima kasih. Program selesai.\n"; exit(0); break;
                default: cout << "Pilihan tidak valid!\n";
            }
        } while (pilihan != 0);
    }

private:
    // Fungsi untuk membuat Circular Linked List dari file akun
    AkunNode* bacaAkunDariFile() {
        ifstream file("akun.txt");
        if (!file.is_open()) return nullptr;
        
        AkunNode* head = nullptr;
        AkunNode* tail = nullptr;
        string line;
        string parts[5];
        
        while (getline(file, line)) {
            if (line.empty()) continue;
            
            splitString(line, ';', parts, 5);
            AkunNode* baru = new AkunNode{parts[0], parts[1], "", "", "", "", nullptr};
            
            if (!head) {
                head = tail = baru;
                baru->next = baru;
            } else {
                baru->next = head;
                tail->next = baru;
                tail = baru;
            }
        }
        file.close();
        return head;
    }
    
    // Fungsi untuk menghapus Circular Linked List akun
    void hapusAkunList(AkunNode*& head) {
        if (!head) return;
        
        AkunNode* current = head;
        AkunNode* nextNode;
        
        do {
            nextNode = current->next;
            delete current;
            current = nextNode;
        } while (current != head);
        
        head = nullptr;
    }

    void tampilkanSemuaAkun() {
        AkunNode* head = bacaAkunDariFile();
        if (!head) {
            cout << "File akun.txt tidak ditemukan atau kosong.\n";
            return;
        }
        
        cout << "\n=== DAFTAR AKUN PENGGUNA ===\n";
        cout << left << setw(20) << "NIK" << setw(30) << "Nama" << "\n";
        cout << "--------------------------------------------------\n";
        
        AkunNode* current = head;
        int no = 1;
        
        do {
            cout << left << setw(4) << no++ << ". " 
                 << setw(20) << current->nik 
                 << setw(30) << current->nama << "\n";
            current = current->next;
        } while (current != head);
        
        hapusAkunList(head);
    }

    void hapusAkun() {
        string target;
        cout << "Masukkan NIK yang ingin dihapus: ";
        getline(cin, target);
        
        ifstream file("akun.txt");
        ofstream temp("temp.txt");
        if (!file.is_open() || !temp.is_open()) {
            cout << "Gagal membuka file akun.txt\n"; return;
        }

        string line; 
        bool found = false;
        string parts[5];
        
        while (getline(file, line)) {
            if (line.empty()) continue;
            splitString(line, ';', parts, 5);
            if (parts[0] != target) {
                temp << line << '\n';
            } else {
                found = true;
                cout << "Akun dengan NIK " << target << " (" << parts[1] << ") akan dihapus.\n";
            }
        }
        
        file.close(); 
        temp.close();
        
        remove("akun.txt"); 
        rename("temp.txt", "akun.txt");
        
        // Juga hapus dari data KTP jika ada
        if (found) {
            // Hapus dari data_ktp.txt
            ifstream ktpFile("data_ktp.txt");
            ofstream ktpTemp("temp_ktp.txt");
            
            if (ktpFile.is_open() && ktpTemp.is_open()) {
                while (getline(ktpFile, line)) {
                    if (line.empty()) continue;
                    splitString(line, ';', parts, 15); // Sesuaikan dengan jumlah field
                    if (parts[0] != target) {
                        ktpTemp << line << '\n';
                    }
                }
                ktpFile.close();
                ktpTemp.close();
                remove("data_ktp.txt");
                rename("temp_ktp.txt", "data_ktp.txt");
            }
            
            cout << "Akun berhasil dihapus.\n";
        } else {
            cout << "Akun tidak ditemukan.\n";
        }
    }

    void kelolaMenuToko() {
        // ... (kode yang sama seperti sebelumnya) ...
        MenuNode* head = nullptr;
        MenuNode* tail = nullptr;

        // Baca dari file menu.txt ke CLL
        ifstream fin("menu.txt");
        if (fin.is_open()) {
            string line;
            string parts[3];
            while (getline(fin, line)) {
                if (line.empty()) continue;
                splitString(line, ';', parts, 3);
                int harga = stringToInt(parts[2]);
                
                MenuNode* baru = new MenuNode{parts[0], parts[1], harga, nullptr};
                if (!head) {
                    head = tail = baru;
                    baru->next = baru;
                } else {
                    baru->next = head;
                    tail->next = baru;
                    tail = baru;
                }
            }
            fin.close();
        }

        int pilih;
        do {
            cout << "\n=== KELOLA MENU TOKO ===\n";
            cout << "1. Tampilkan Semua Menu\n";
            cout << "2. Tambah Menu Baru\n";
            cout << "3. Hapus Menu\n";
            cout << "4. Simpan & Kembali\n";
            cout << "0. Batal & Kembali\n";
            cout << "Pilih: ";
            cin >> pilih; 
            cin.ignore(1000, '\n');

        if (pilih == 1) {
            if (!head) { cout << "Belum ada menu.\n"; continue; }
            cout << "\n--- Daftar Menu ---\n";
            MenuNode* current = head;
            int i = 1;
            
            do {
                cout << i++ << ". [" << current->kategori << "] " 
                     << current->nama << " : Rp " << current->harga << "\n";
                current = current->next;
            } while (current != head);
        }
        else if (pilih == 2) {
            string kategori, nama; int harga;
            cout << "Kategori (Basah/Kering): "; getline(cin, kategori);
            cout << "Nama Bakpia            : "; getline(cin, nama);
            cout << "Harga (angka)          : "; cin >> harga; 
            cin.ignore(1000, '\n');
            
            MenuNode* baru = new MenuNode{kategori, nama, harga, nullptr};
            if (!head) {
                head = tail = baru;
                baru->next = baru;
            } else {
                baru->next = head;
                tail->next = baru;
                tail = baru;
            }
            cout << "Menu berhasil ditambahkan.\n";
        }
        else if (pilih == 3) {
            if (!head) { cout << "Belum ada menu.\n"; continue; }
            cout << "Masukkan nomor menu yang ingin dihapus: ";
            int no; cin >> no; 
            cin.ignore(1000, '\n');
            
            if (no < 1) { cout << "Nomor tidak valid.\n"; continue; }
            
            MenuNode* current = head;
            MenuNode* prev = tail;
            int idx = 1;
            bool found = false;
            
            do {
                if (idx == no) {
                    found = true;
                    // Hapus node
                    prev->next = current->next;
                    
                    if (current == head) head = current->next;
                    if (current == tail) tail = prev;
                    
                    // Jika hanya tersisa 1 node
                    if (current == current->next) {
                        delete current;
                        head = tail = nullptr;
                    } else {
                        delete current;
                    }
                    cout << "Menu berhasil dihapus.\n";
                    break;
                }
                prev = current;
                current = current->next;
                idx++;
            } while (current != head);
            
            if (!found) cout << "Nomor tidak ditemukan.\n";
        }
        else if (pilih == 4) {
            ofstream fout("menu.txt");
            if (!fout.is_open()) { 
                cout << "Gagal membuka menu.txt untuk menyimpan.\n"; 
                continue;
            }
            
            if (head) {
                MenuNode* current = head;
                do {
                    fout << current->kategori << ";" << current->nama << ";" << current->harga << "\n";
                    current = current->next;
                } while (current != head);
            }
            fout.close();
            cout << "Perubahan menu disimpan ke menu.txt.\n";
            
            // Hapus CLL
            if (head) {
                MenuNode* current = head;
                MenuNode* nextNode;
                
                do {
                    nextNode = current->next;
                    delete current;
                    current = nextNode;
                } while (current != head);
                
                head = tail = nullptr;
            }
            return;
        }
        else if (pilih == 0) {
            // Hapus CLL
            if (head) {
                MenuNode* current = head;
                MenuNode* nextNode;
                
                do {
                    nextNode = current->next;
                    delete current;
                    current = nextNode;
                } while (current != head);
                
                head = tail = nullptr;
            }
            cout << "Batal. Kembali ke menu admin.\n";
            return;
        }
        else cout << "Pilihan tidak valid.\n";

        } while (true);
    }

    void tampilkanRiwayatTransaksi() {
        // ... (kode yang sama seperti sebelumnya) ...
        ifstream file("transaksi.txt");
        if (!file.is_open()) {
            cout << "Belum ada data transaksi.\n";
            return;
        }

        cout << "\n=== RIWAYAT TRANSAKSI (RINGKASAN) ===\n";
        cout << left << setw(4) << "No" << setw(20) << "Tanggal & Jam"
             << setw(15) << "Username" << setw(20) << "Nama User"
             << setw(12) << "Total" << "\n";
        cout << "---------------------------------------------------------------------------\n";

        string line; int no = 1;
        string parts[5];
        while (getline(file, line)) {
            if (line.empty()) continue;
            splitString(line, ';', parts, 5);
            cout << left << setw(4) << no++ << setw(20) << parts[0]
                 << setw(15) << parts[1] << setw(20) << parts[2]
                 << "Rp " << parts[3] << "\n";
        }
        file.close();
    }

    void lihatDetailTransaksi() {
        // ... (kode yang sama seperti sebelumnya) ...
        ifstream file("transaksi.txt");
        if (!file.is_open()) {
            cout << "Tidak ada riwayat transaksi.\n";
            return;
        }

        const int MAX_TRANSAKSI = 1000;
        string lines[MAX_TRANSAKSI];
        int count = 0;
        string line;
        while (getline(file, line) && count < MAX_TRANSAKSI) {
            if (!line.empty()) lines[count++] = line;
        }
        file.close();

        if (count == 0) {
            cout << "Riwayat transaksi kosong.\n";
            return;
        }

        cout << "Total transaksi tersimpan: " << count << "\n";
        int nomor;
        cout << "Masukkan nomor transaksi yang ingin dilihat: ";
        cin >> nomor;
        cin.ignore(1000, '\n');

        if (nomor < 1 || nomor > count) {
            cout << "Nomor transaksi tidak valid.\n";
            return;
        }

        string parts[5];
        splitString(lines[nomor - 1], ';', parts, 5);

        cout << "\n=== DETAIL TRANSAKSI #" << nomor << " ===\n";
        cout << "Tanggal dan Waktu : " << parts[0] << "\n";
        cout << "Username          : " << parts[1] << "\n";
        cout << "Nama              : " << parts[2] << "\n";
        cout << "Total Belanja     : Rp " << parts[3] << "\n\n";

        cout << "Item :\n";
        cout << left << setw(4) << "No" << setw(40) << "Rasa" << setw(8) << "Qty"
             << setw(14) << "HargaSatuan" << setw(14) << "TotalItem" << "\n";
        cout << "--------------------------------------------------------------------------------\n";

        string detail = parts[4];
        string items[100];
        int itemCount = 0;
        int start = 0;
        int end = detail.find('|');
        
        while (end != -1 && itemCount < 100) {
            items[itemCount++] = detail.substr(start, end - start);
            start = end + 1;
            end = detail.find('|', start);
        }
        if (start < detail.length() && itemCount < 100) {
            items[itemCount++] = detail.substr(start);
        }

        for (int i = 0; i < itemCount; i++) {
            string itemParts[4];
            splitString(items[i], ',', itemParts, 4);
            cout << left << setw(4) << (i+1) << setw(40) << itemParts[0]
                 << setw(8) << itemParts[1] << "Rp " << setw(11) << itemParts[2]
                 << "Rp " << setw(11) << itemParts[3] << "\n";
        }
    }

    void hapusTransaksi() {
        // ... (kode yang sama seperti sebelumnya) ...
        ifstream file("transaksi.txt");
        if (!file.is_open()) {
            cout << "Tidak ada transaksi tersimpan.\n";
            return;
        }
        
        const int MAX_TRANSAKSI = 1000;
        string lines[MAX_TRANSAKSI];
        int count = 0;
        string line;
        while (getline(file, line) && count < MAX_TRANSAKSI) {
            if (!line.empty()) lines[count++] = line;
        }
        file.close();

        if (count == 0) {
            cout << "Tidak ada transaksi dalam riwayat.\n";
            return;
        }

        cout << "Total transaksi tersimpan: " << count << "\n";
        int nomor;
        cout << "Masukkan nomor transaksi yang ingin dihapus: ";
        cin >> nomor;
        cin.ignore(1000, '\n');

        if (nomor < 1 || nomor > count) {
            cout << "Nomor tidak valid.\n";
            return;
        }

        ofstream out("temp.txt");
        if (!out.is_open()) {
            cout << "Gagal membuka file sementara.\n";
            return;
        }
        for (int i = 0; i < count; ++i) {
            if (i != nomor - 1) out << lines[i] << "\n";
        }
        out.close();

        remove("transaksi.txt");
        rename("temp.txt", "transaksi.txt");
        cout << "Transaksi berhasil dihapus.\n";
    }

    void kelolaStackReview() {
        // ... (kode yang sama seperti sebelumnya) ...
        int pilih;
        do {
            cout << "\n=== KELOLA STACK REVIEW TERBARU ===\n";
            cout << "Status: " << (reviewStack.isEmpty() ? "KOSONG" : "ADA review") 
                 << " (" << reviewStack.size() << " slot terisi)\n";
            cout << "1. Lihat & Proses Review Paling Atas (POP)\n";
            cout << "2. Tampilkan Semua Review di Stack (DISPLAY)\n"; 
            cout << "0. Kembali\n";
            cout << "Pilih: ";
            cin >> pilih; 
            cin.ignore(1000, '\n');

            if (pilih == 1) {
                string reviewLine = reviewStack.popReview();
                if (reviewLine.empty()) {
                    cout << "Stack Review kosong. Tidak ada review untuk diproses.\n";
                } else {
                    string parts[3];
                    splitString(reviewLine, ';', parts, 3);
                    cout << "\n--- REVIEW TELAH DIPROSES (POP) ---\n";
                    cout << "Dari           : " << parts[1] << " (" << parts[0] << ")\n";
                    cout << "Isi Review     : " << parts[2] << "\n";
                    cout << "------------------------------------\n";
                    cout << "Review ini telah dikeluarkan dari Stack.\n";
                }
            }
            else if (pilih == 2) {
                reviewStack.displayAll(); 
            }
            else if (pilih != 0) {
                cout << "Pilihan tidak valid.\n";
            }
        } while (pilih != 0);
    }
};

// ===============================
// CLASS BAKPIA (USER) - TIDAK BERUBAH
// ===============================
class Bakpia {
private:
    PesananNode* pesananHead;
    PesananNode* pesananTail;
    HistoryNode* historyHead;
    HistoryNode* historyTail;
    HistoryNode* currentHistory;
    FavoritNode* favoritHead;
    FavoritNode* favoritTail;
    
    // Fungsi untuk menyalin CLL pesanan
    PesananNode* copyPesananCLL(PesananNode* original) {
        if (!original) return nullptr;
        
        PesananNode* newHead = nullptr;
        PesananNode* newTail = nullptr;
        PesananNode* current = original;
        
        do {
            PesananNode* newNode = new PesananNode;
            newNode->jenis = current->jenis;
            newNode->rasa = current->rasa;
            newNode->jumlah = current->jumlah;
            newNode->hargaSatuan = current->hargaSatuan;
            newNode->total = current->total;
            newNode->next = nullptr;
            
            if (!newHead) {
                newHead = newTail = newNode;
                newNode->next = newNode;
            } else {
                newNode->next = newHead;
                newTail->next = newNode;
                newTail = newNode;
            }
            
            current = current->next;
        } while (current != original);
        
        return newHead;
    }
    
    // Fungsi untuk menghapus CLL pesanan
    void clearPesananCLL(PesananNode*& head) {
        if (!head) return;
        
        PesananNode* current = head;
        PesananNode* nextNode;
        
        do {
            nextNode = current->next;
            delete current;
            current = nextNode;
        } while (current != head);
        
        head = nullptr;
    }
    
    // Simpan state ke history
    void saveState() {
        HistoryNode* newState = new HistoryNode;
        newState->pesananHead = copyPesananCLL(pesananHead);
        newState->next = nullptr;
        
        if (!historyHead) {
            historyHead = historyTail = currentHistory = newState;
            newState->next = newState; // Circular
        } else {
            newState->next = historyHead;
            historyTail->next = newState;
            historyTail = newState;
            currentHistory = newState;
        }
    }
    
    // Load menu ke array untuk pemrosesan
    void loadMenu(string* kategori, string* nama, int* harga, int& count) {
        count = 0;
        ifstream f("menu.txt");
        if (!f.is_open()) return;
        
        string line;
        string parts[3];
        while (getline(f, line) && count < 100) {
            if (line.empty()) continue;
            splitString(line, ';', parts, 3);
            kategori[count] = parts[0];
            nama[count] = parts[1];
            harga[count] = stringToInt(parts[2]);
            count++;
        }
        f.close();
    }
    
    // ===============================
    // FUNGSI FAVORIT - PRIVATE
    // ===============================
    
    // Cek apakah menu sudah ada di favorit
    bool isMenuInFavorit(const string& namaMenu) {
        if (!favoritHead) return false;
        
        FavoritNode* current = favoritHead;
        do {
            if (current->nama == namaMenu) return true;
            current = current->next;
        } while (current != favoritHead);
        
        return false;
    }
    
    // Tambah menu ke favorit
    void tambahKeFavorit(const string& kategori, const string& nama, int harga) {
        if (isMenuInFavorit(nama)) {
            cout << "Menu \"" << nama << "\" sudah ada di favorit.\n";
            return;
        }
        
        FavoritNode* baru = new FavoritNode{kategori, nama, harga, nullptr};
        
        if (!favoritHead) {
            favoritHead = favoritTail = baru;
            baru->next = baru;
        } else {
            baru->next = favoritHead;
            favoritTail->next = baru;
            favoritTail = baru;
        }
        
        cout << "Menu \"" << nama << "\" berhasil ditambahkan ke favorit!\n";
    }
    
    // Hapus menu dari favorit
    void hapusDariFavorit(const string& namaMenu) {
        if (!favoritHead) {
            cout << "Daftar favorit kosong.\n";
            return;
        }
        
        FavoritNode* current = favoritHead;
        FavoritNode* prev = favoritTail;
        bool found = false;
        
        do {
            if (current->nama == namaMenu) {
                found = true;
                
                if (current == current->next) { // Hanya 1 node
                    delete current;
                    favoritHead = favoritTail = nullptr;
                } else {
                    prev->next = current->next;
                    
                    if (current == favoritHead) favoritHead = current->next;
                    if (current == favoritTail) favoritTail = prev;
                    
                    delete current;
                }
                
                cout << "Menu \"" << namaMenu << "\" dihapus dari favorit.\n";
                break;
            }
            prev = current;
            current = current->next;
        } while (current != favoritHead);
        
        if (!found) cout << "Menu \"" << namaMenu << "\" tidak ditemukan di favorit.\n";
    }
    
    // Tampilkan semua favorit
    void tampilkanFavorit() {
        if (!favoritHead) {
            cout << "Daftar favorit kosong.\n";
            return;
        }
        
        cout << "\n=== DAFTAR MENU FAVORIT ===\n";
        
        // Tampilkan Basah
        cout << "\n--- Bakpia Basah ---\n";
        FavoritNode* current = favoritHead;
        int no = 1;
        bool foundBasah = false;
        
        do {
            if (current->kategori == "Basah") {
                cout << setw(2) << no++ << ". " << current->nama << " : Rp " << current->harga << "\n";
                foundBasah = true;
            }
            current = current->next;
        } while (current != favoritHead);
        
        if (!foundBasah) cout << "Tidak ada menu favorit dalam kategori Basah.\n";
        
        // Tampilkan Kering
        cout << "\n--- Bakpia Kering ---\n";
        current = favoritHead;
        no = 1;
        bool foundKering = false;
        
        do {
            if (current->kategori == "Kering") {
                cout << setw(2) << no++ << ". " << current->nama << " : Rp " << current->harga << "\n";
                foundKering = true;
            }
            current = current->next;
        } while (current != favoritHead);
        
        if (!foundKering) cout << "Tidak ada menu favorit dalam kategori Kering.\n";
    }
    
    // Simpan favorit ke file
    void simpanFavoritKeFile() {
        ofstream file(currentUsername + "_favorit.txt");
        if (!file.is_open()) {
            cout << "Gagal menyimpan favorit.\n";
            return;
        }
        
        if (favoritHead) {
            FavoritNode* current = favoritHead;
            do {
                file << current->kategori << ";" << current->nama << ";" << current->harga << "\n";
                current = current->next;
            } while (current != favoritHead);
        }
        file.close();
    }
    
    // Muat favorit dari file
    void muatFavoritDariFile() {
        ifstream file(currentUsername + "_favorit.txt");
        if (!file.is_open()) return;
        
        string line;
        string parts[3];
        while (getline(file, line)) {
            if (line.empty()) continue;
            splitString(line, ';', parts, 3);
            int harga = stringToInt(parts[2]);
            
            // Tambah ke CLL favorit
            FavoritNode* baru = new FavoritNode{parts[0], parts[1], harga, nullptr};
            
            if (!favoritHead) {
                favoritHead = favoritTail = baru;
                baru->next = baru;
            } else {
                baru->next = favoritHead;
                favoritTail->next = baru;
                favoritTail = baru;
            }
        }
        file.close();
    }
    
    // Hapus semua favorit
    void clearFavorit() {
        if (!favoritHead) return;
        
        FavoritNode* current = favoritHead;
        FavoritNode* nextNode;
        
        do {
            nextNode = current->next;
            delete current;
            current = nextNode;
        } while (current != favoritHead);
        
        favoritHead = favoritTail = nullptr;
    }
    
public:
    Bakpia() : pesananHead(nullptr), pesananTail(nullptr), 
               historyHead(nullptr), historyTail(nullptr), currentHistory(nullptr),
               favoritHead(nullptr), favoritTail(nullptr) {
        saveState();
        muatFavoritDariFile();
    }

    ~Bakpia() {
        clearPesananCLL(pesananHead);
        clearFavorit();
        
        // Hapus history
        if (historyHead) {
            HistoryNode* current = historyHead;
            HistoryNode* nextNode;
            
            do {
                nextNode = current->next;
                clearPesananCLL(current->pesananHead);
                delete current;
                current = nextNode;
            } while (current != historyHead);
        }
    }
    
    // ===============================
    // FUNGSI PUBLIC UTAMA
    // ===============================
    
    bool undo() {
        if (!currentHistory || currentHistory == historyHead) {
            cout << "Tidak bisa undo. Sudah di state paling awal.\n";
            return false;
        }
        
        // Cari history sebelumnya
        HistoryNode* prev = historyHead;
        while (prev->next != currentHistory) {
            prev = prev->next;
        }
        
        currentHistory = prev;
        clearPesananCLL(pesananHead);
        pesananHead = copyPesananCLL(currentHistory->pesananHead);
        
        // Cari tail dari pesanan baru
        if (pesananHead) {
            pesananTail = pesananHead;
            while (pesananTail->next != pesananHead) {
                pesananTail = pesananTail->next;
            }
        }
        
        cout << "Undo berhasil.\n";
        return true;
    }

    bool redo() {
        if (!currentHistory || !currentHistory->next || currentHistory->next == historyHead) {
            cout << "Tidak bisa redo. Sudah di state paling baru.\n";
            return false;
        }
        
        currentHistory = currentHistory->next;
        clearPesananCLL(pesananHead);
        pesananHead = copyPesananCLL(currentHistory->pesananHead);
        
        // Cari tail dari pesanan baru
        if (pesananHead) {
            pesananTail = pesananHead;
            while (pesananTail->next != pesananHead) {
                pesananTail = pesananTail->next;
            }
        }
        
        cout << "Redo berhasil.\n";
        return true;
    }

    void tampilkanSemuaMenu() {
        string kategori[100], nama[100];
        int harga[100];
        int count = 0;
        loadMenu(kategori, nama, harga, count);
        
        if (count == 0) { 
            cout << "File menu.txt tidak ditemukan atau kosong.\n"; 
            return; 
        }

        cout << "\n=== DAFTAR MENU BAKPIA (DARI FILE) ===\n";
        
        cout << "\n--- Bakpia Basah ---\n";
        for (int i = 0; i < count; ++i) {
            if (kategori[i] == "Basah") {
                cout << setw(2) << i+1 << ". " << nama[i] << " : Rp " << harga[i];
                if (isMenuInFavorit(nama[i])) {
                    cout << " ?";
                }
                cout << "\n";
            }
        }
        
        cout << "\n--- Bakpia Kering ---\n";
        for (int i = 0; i < count; ++i) {
            if (kategori[i] == "Kering") {
                cout << setw(2) << i+1 << ". " << nama[i] << " : Rp " << harga[i];
                if (isMenuInFavorit(nama[i])) {
                    cout << " ?";
                }
                cout << "\n";
            }
        }
    }

    void tampilkanMenuTree() {
        string kategori[100], nama[100];
        int harga[100];
        int count = 0;
        loadMenu(kategori, nama, harga, count);

        if (count == 0) {
            cout << "File menu.txt tidak ditemukan atau kosong.\n";
            return;
        }

        TreeNode* root = buildMenuTree(kategori, nama, harga, count);

        cout << "\n=== MENU DALAM BENTUK TREE ===\n";
        printMenuTree(root);
        destroyMenuTree(root);
    }

    void tambahPesanan() {
        string kategori[100], nama[100];
        int harga[100];
        int count = 0;
        loadMenu(kategori, nama, harga, count);
        
        if (count == 0) { 
            cout << "File menu.txt tidak ditemukan atau kosong.\n"; 
            return; 
        }

        char ulangJenis = 'n';
        do {
            cout << "\nPilih kategori:\n1. Basah\n2. Kering\nPilih: ";
            int jenis; cin >> jenis; 
            cin.ignore(1000, '\n');
            
            string target = (jenis == 1) ? "Basah" : (jenis == 2) ? "Kering" : "";
            if (target.empty()) { 
                cout << "Pilihan kategori tidak valid.\n"; 
                return; 
            }

            int idx[100];
            int idxCount = 0;
            cout << "\n--- " << (target=="Basah"? "Bakpia Basah":"Bakpia Kering") << " ---\n";
            
            for (int i = 0; i < count; ++i) {
                if (kategori[i] == target) {
                    idx[idxCount] = i;
                    cout << setw(2) << (idxCount+1) << ". " << nama[i] << " : Rp " << harga[i];
                    if (isMenuInFavorit(nama[i])) {
                        cout << " ?";
                    }
                    cout << "\n";
                    idxCount++;
                }
            }
            
        if (idxCount == 0) { 
            cout << "Belum ada menu di kategori ini.\n"; 
            return; 
        }

        char ulangRasa = 'n';
        do {
            cout << "\nMasukkan nomor rasa yang ingin dipilih: ";
            int no; cin >> no; 
            cin.ignore(1000, '\n');
            
            if (no < 1 || no > idxCount) { 
                cout << "Nomor tidak valid.\n"; 
                continue; 
            }

            int i = idx[no-1];
            PesananNode* baru = new PesananNode;
            baru->jenis = kategori[i];
            baru->rasa = nama[i];
            baru->hargaSatuan = harga[i];
            baru->next = nullptr;
            
            cout << "Masukkan jumlah pesanan: "; 
            cin >> baru->jumlah; 
            cin.ignore(1000, '\n');
            
            if (baru->jumlah <= 0) { 
                cout << "Jumlah harus > 0. Pesanan dibatalkan.\n"; 
                delete baru; 
                continue; 
            }
            
            baru->total = baru->hargaSatuan * baru->jumlah;

            // Tambah ke CLL pesanan
            if (!pesananHead) {
                pesananHead = pesananTail = baru;
                baru->next = baru;
            } else {
                baru->next = pesananHead;
                pesananTail->next = baru;
                pesananTail = baru;
            }

            cout << "Pesanan ditambahkan: " << baru->rasa << " x" << baru->jumlah 
                 << " (Rp " << baru->total << ")\n";
            
            cout << "Tambah rasa lagi di kategori yang sama? (y/n): "; 
            cin >> ulangRasa; 
            cin.ignore(1000, '\n');
        } while (ulangRasa=='y' || ulangRasa=='Y');

        cout << "Ingin menambah pesanan dari kategori lain? (y/n): "; 
            cin >> ulangJenis; 
            cin.ignore(1000, '\n');
        } while (ulangJenis=='y' || ulangJenis=='Y');
        
        saveState();
    }

    void tampilkanPesanan() {
        if (!pesananHead) { 
            cout << "Belum ada pesanan.\n"; 
            return; 
        }
        
        cout << "\n=== DAFTAR PESANAN ===\n";
        cout << left << setw(4) << "No" << setw(40) << "Rasa" << setw(12) << "Jenis" 
             << setw(6) << "Jml" << setw(12) << "Harga" << setw(12) << "Total" << "\n";
        cout << "------------------------------------------------------------------------------------------\n";
        
        PesananNode* current = pesananHead;
        int no = 1, grand = 0;
        
        do {
            cout << left << setw(4) << no++ << setw(40) << current->rasa 
                 << setw(12) << current->jenis << setw(6) << current->jumlah 
                 << "Rp " << setw(9) << current->hargaSatuan << "Rp " 
                 << setw(9) << current->total << "\n";
            
            grand += current->total;
            current = current->next;
        } while (current != pesananHead);
        
        cout << "------------------------------------------------------------------------------------------\n";
        cout << right << setw(70) << "GRAND TOTAL: Rp " << grand << "\n";
    }

    void hapusPesananByNo() {
        if (!pesananHead) { 
            cout << "Belum ada pesanan.\n"; 
            return; 
        }
        
        tampilkanPesanan();
        cout << "Masukkan nomor pesanan yang ingin dihapus: ";
        int no; cin >> no; 
        cin.ignore(1000, '\n');
        
        if (no <= 0) { 
            cout << "Nomor tidak valid.\n"; 
            return; 
        }

        PesananNode* current = pesananHead;
        PesananNode* prev = pesananTail;
        int idx = 1;
        
        do {
            if (idx == no) {
                // Hapus node
                prev->next = current->next;
                
                if (current == pesananHead) pesananHead = current->next;
                if (current == pesananTail) pesananTail = prev;
                
                // Jika hanya tersisa 1 node
                if (current == current->next) {
                    delete current;
                    pesananHead = pesananTail = nullptr;
                } else {
                    delete current;
                }
                
                cout << "Pesanan berhasil dihapus.\n";
                saveState();
                return;
            }
            prev = current;
            current = current->next;
            idx++;
        } while (current != pesananHead);
        
        cout << "Nomor tidak ditemukan.\n";
    }

    void editJumlahPesanan() {
        if (!pesananHead) { 
            cout << "Belum ada pesanan.\n"; 
            return; 
        }
        
        tampilkanPesanan();
        cout << "Masukkan nomor pesanan yang ingin di-edit jumlahnya: ";
        int no; cin >> no; 
        cin.ignore(1000, '\n');
        
        if (no <= 0) { 
            cout << "Nomor tidak valid.\n"; 
            return; 
        }

        PesananNode* current = pesananHead;
        int idx = 1;
        
        do {
            if (idx == no) {
                cout << "Jumlah saat ini: " << current->jumlah 
                     << ". Masukkan jumlah baru: ";
                int baru; cin >> baru; 
                cin.ignore(1000, '\n');
                
                if (baru <= 0) { 
                    cout << "Jumlah harus > 0.\n"; 
                    return; 
                }
                
                current->jumlah = baru;
                current->total = current->jumlah * current->hargaSatuan;
                cout << "Jumlah pesanan diperbarui.\n";
                saveState();
                return;
            }
            current = current->next; 
            idx++;
        } while (current != pesananHead);
        
        cout << "Nomor tidak ditemukan.\n";
    }

    void pembayaran() {
        if (!pesananHead) { 
            cout << "Tidak ada pesanan untuk dibayar.\n"; 
            return; 
        }
        
        int total = 0;
        PesananNode* current = pesananHead;
        
        do {
            total += current->total;
            current = current->next;
        } while (current != pesananHead);

        cout << "\nTotal belanja: Rp " << total << "\n";
        cout << "Masukkan uang bayar: Rp ";
        int bayar; cin >> bayar; 
        cin.ignore(1000, '\n');
        
        if (bayar < total) { 
            cout << "Uang tidak cukup!\n"; 
            return; 
        }

        cout << "\n========= STRUK PEMBAYARAN =========\n";
        time_t now = time(0);
        tm* waktu = localtime(&now);
        char buffer[30];
        strftime(buffer, sizeof(buffer), "%Y-%m-%d %H:%M:%S", waktu);
        string datetime = buffer;
        
        cout << "Tanggal & Waktu : " << datetime << "\n";
        cout << "Pembeli         : " << currentNama << " (" << currentUsername << ")\n\n";

        cout << left << setw(30) << "Item" << setw(6) << "Qty" 
             << setw(14) << "Harga" << setw(12) << "Total" << "\n";
        cout << "----------------------------------------------------------------------\n";
        
        current = pesananHead;
        do {
            cout << left << setw(30) << current->rasa << setw(6) << current->jumlah
                 << "Rp " << setw(11) << current->hargaSatuan << "Rp " 
                 << setw(11) << current->total << "\n";
            current = current->next;
        } while (current != pesananHead);
        
        cout << "----------------------------------------------------------------------\n";
        cout << right << setw(50) << "TOTAL: Rp " << total << "\n";
        cout << "Bayar  : Rp " << bayar << "\n";
        cout << "Kembali: Rp " << (bayar - total) << "\n";
        cout << "===================================\n";

        // Simpan ke file transaksi
        ofstream fout("transaksi.txt", ios::app);
        if (fout.is_open()) {
            fout << datetime << ";" << currentUsername << ";" << currentNama 
                 << ";" << total << ";";
            
            current = pesananHead;
            bool first = true;
            
            do {
                if (!first) fout << "|";
                fout << current->rasa << "," << current->jumlah << "," 
                     << current->hargaSatuan << "," << current->total;
                first = false;
                current = current->next;
            } while (current != pesananHead);
            
            fout << "\n";
            fout.close();
            cout << "Riwayat transaksi disimpan di transaksi.txt\n";
        } else {
            cout << "Gagal menyimpan riwayat transaksi.\n";
        }

        // Reset semua data
        clearPesananCLL(pesananHead);
        pesananTail = nullptr;
        
        // Reset history
        if (historyHead) {
            HistoryNode* current = historyHead;
            HistoryNode* nextNode;
            
            do {
                nextNode = current->next;
                clearPesananCLL(current->pesananHead);
                delete current;
                current = nextNode;
            } while (current != historyHead);
            
            historyHead = historyTail = currentHistory = nullptr;
        }
        
        saveState();
    }

    void tambahReview() {
        cout << "Masukkan review Anda: ";
        string review; 
        getline(cin, review);
        
        if (review.empty()) { 
            cout << "Review kosong, dibatalkan.\n"; 
            return; 
        }
        
        string reviewLine = currentUsername + ";" + currentNama + ";" + review;
        
        ofstream fout("review.txt", ios::app);
        if (fout.is_open()) {
            fout << reviewLine << "\n";
            fout.close();
            cout << "Terima kasih, review tersimpan di file.\n";
            
            reviewStack.pushReview(reviewLine);
        } else {
            cout << "Gagal menyimpan review.\n";
        }
    }
    
    // ===============================
    // FUNGSI FAVORIT - PUBLIC
    // ===============================
    
    void menuKelolaFavorit() {
        int pilihan;
        do {
            cout << "\n=== KELOLA MENU FAVORIT ===\n";
            cout << "1. Tampilkan Menu Favorit\n";
            cout << "2. Tambah Menu ke Favorit dari Daftar Menu\n";
            cout << "3. Hapus Menu dari Favorit\n";
            cout << "4. Pesan Langsung dari Favorit\n";
            cout << "0. Kembali\n";
            cout << "Pilih: ";
            cin >> pilihan;
            cin.ignore(1000, '\n');

            if (pilihan == 1) {
                tampilkanFavorit();
            }
            else if (pilihan == 2) {
                // Tambah menu ke favorit dari daftar
                string kategori[100], nama[100];
                int harga[100];
                int count = 0;
                loadMenu(kategori, nama, harga, count);
                
                if (count == 0) {
                    cout << "File menu.txt tidak ditemukan atau kosong.\n";
                    continue;
                }

                cout << "\n=== DAFTAR MENU BAKPIA ===\n";
                for (int i = 0; i < count; ++i) {
                    cout << setw(2) << i+1 << ". [" << kategori[i] << "] " 
                         << nama[i] << " : Rp " << harga[i];
                    if (isMenuInFavorit(nama[i])) {
                        cout << " ?";
                    }
                    cout << "\n";
                }

                cout << "\nMasukkan nomor menu yang ingin ditambahkan ke favorit: ";
                int no;
                cin >> no;
                cin.ignore(1000, '\n');
                
                if (no < 1 || no > count) {
                    cout << "Nomor menu tidak valid.\n";
                    continue;
                }
                
                int index = no - 1;
                tambahKeFavorit(kategori[index], nama[index], harga[index]);
            }
            else if (pilihan == 3) {
                if (!favoritHead) {
                    cout << "Daftar favorit kosong.\n";
                    continue;
                }
                
                tampilkanFavorit();
                cout << "\nMasukkan nama menu yang ingin dihapus dari favorit: ";
                string namaMenu;
                getline(cin, namaMenu);
                
                hapusDariFavorit(namaMenu);
            }
            else if (pilihan == 4) {
                if (!favoritHead) {
                    cout << "Daftar favorit kosong.\n";
                    continue;
                }
                
                tampilkanFavorit();
                cout << "\nMasukkan nama menu dari favorit yang ingin dipesan: ";
                string namaMenu;
                getline(cin, namaMenu);
                
                // Cari menu di favorit
                FavoritNode* current = favoritHead;
                bool found = false;
                
                do {
                    if (current->nama == namaMenu) {
                        found = true;
                        // Tambah ke pesanan
                        PesananNode* baru = new PesananNode;
                        baru->jenis = current->kategori;
                        baru->rasa = current->nama;
                        baru->hargaSatuan = current->harga;
                        
                        cout << "Masukkan jumlah pesanan: ";
                        cin >> baru->jumlah;
                        cin.ignore(1000, '\n');
                        
                        if (baru->jumlah <= 0) {
                            cout << "Jumlah harus > 0. Pesanan dibatalkan.\n";
                            delete baru;
                            break;
                        }
                        
                        baru->total = baru->hargaSatuan * baru->jumlah;
                        baru->next = nullptr;

                        // Tambah ke CLL pesanan
                        if (!pesananHead) {
                            pesananHead = pesananTail = baru;
                            baru->next = baru;
                        } else {
                            baru->next = pesananHead;
                            pesananTail->next = baru;
                            pesananTail = baru;
                        }

                        cout << "Pesanan dari favorit ditambahkan: " << baru->rasa 
                             << " x" << baru->jumlah << " (Rp " << baru->total << ")\n";
                        saveState();
                        break;
                    }
                    current = current->next;
                } while (current != favoritHead);
                
                if (!found) {
                    cout << "Menu \"" << namaMenu << "\" tidak ditemukan di favorit.\n";
                }
            }
            else if (pilihan == 0) {
                simpanFavoritKeFile();
                cout << "Kembali ke menu utama...\n";
            }
            else {
                cout << "Pilihan tidak valid!\n";
            }
        } while (pilihan != 0);
    }
    
    // ===============================
    // FUNGSI FILTER - PUBLIC
    // ===============================
    
    void menuFilter() {
        int pilihan;
        do {
            cout << "\n=== FILTER & PENCARIAN MENU ===\n";
            cout << "1. Filter by Kategori (Basah/Kering)\n";
            cout << "2. Filter by Harga (Termurah)\n";
            cout << "3. Filter by Harga (Termahal)\n";
            cout << "4. Filter by Rentang Harga\n";
            cout << "5. Cari by Nama Menu\n";
            cout << "0. Kembali\n";
            cout << "Pilih: ";
            cin >> pilihan;
            cin.ignore(1000, '\n');

            if (pilihan == 1) {
                cout << "Pilih kategori (1. Basah, 2. Kering): ";
                int kat; cin >> kat; cin.ignore(1000, '\n');
                
                string kategori[100], nama[100];
                int harga[100];
                int count = 0;
                loadMenu(kategori, nama, harga, count);
                
                if (count == 0) {
                    cout << "File menu.txt tidak ditemukan atau kosong.\n";
                    continue;
                }

                string target = (kat == 1) ? "Basah" : (kat == 2) ? "Kering" : "";
                if (target.empty()) {
                    cout << "Pilihan tidak valid.\n";
                    continue;
                }

                cout << "\n=== FILTER MENU: " << target << " ===\n";
                bool found = false;
                for (int i = 0; i < count; ++i) {
                    if (kategori[i] == target) {
                        cout << "No. " << (i+1) << ". " << nama[i] << " : Rp " << harga[i];
                        if (isMenuInFavorit(nama[i])) {
                            cout << " ?";
                        }
                        cout << "\n";
                        found = true;
                    }
                }
                
                if (!found) {
                    cout << "Tidak ada menu dalam kategori " << target << ".\n";
                }
            }
            else if (pilihan == 2 || pilihan == 3) {
                // Filter harga termurah/termahal
                string kategori[100], nama[100];
                int harga[100];
                int count = 0;
                loadMenu(kategori, nama, harga, count);
                
                if (count == 0) {
                    cout << "File menu.txt tidak ditemukan atau kosong.\n";
                    continue;
                }

                // Bubble sort
                bool termurah = (pilihan == 2);
                for (int i = 0; i < count - 1; ++i) {
                    for (int j = 0; j < count - i - 1; ++j) {
                        bool shouldSwap = termurah ? (harga[j] > harga[j + 1]) : (harga[j] < harga[j + 1]);
                        if (shouldSwap) {
                            // Swap semua data
                            swap(harga[j], harga[j + 1]);
                            swap(kategori[j], kategori[j + 1]);
                            swap(nama[j], nama[j + 1]);
                        }
                    }
                }

                cout << "\n=== FILTER HARGA: " << (termurah ? "TERMURAH" : "TERMAHAL") << " ===\n";
                for (int i = 0; i < count; ++i) {
                    cout << "No. " << (i+1) << ". [" << kategori[i] << "] " 
                         << nama[i] << " : Rp " << harga[i];
                    if (isMenuInFavorit(nama[i])) {
                        cout << " ?";
                    }
                    cout << "\n";
                }
            }
            else if (pilihan == 4) {
                int minHarga, maxHarga;
                cout << "Masukkan harga minimum: Rp ";
                cin >> minHarga;
                cout << "Masukkan harga maksimum: Rp ";
                cin >> maxHarga;
                cin.ignore(1000, '\n');
                
                if (minHarga < 0 || maxHarga < 0 || minHarga > maxHarga) {
                    cout << "Input harga tidak valid.\n";
                    continue;
                }
                
                string kategori[100], nama[100];
                int harga[100];
                int count = 0;
                loadMenu(kategori, nama, harga, count);
                
                if (count == 0) {
                    cout << "File menu.txt tidak ditemukan atau kosong.\n";
                    continue;
                }

                cout << "\n=== FILTER RENTANG HARGA: Rp " << minHarga << " - Rp " << maxHarga << " ===\n";
                bool found = false;
                
                for (int i = 0; i < count; ++i) {
                    if (harga[i] >= minHarga && harga[i] <= maxHarga) {
                        cout << "No. " << (i+1) << ". [" << kategori[i] << "] " 
                             << nama[i] << " : Rp " << harga[i];
                        if (isMenuInFavorit(nama[i])) {
                            cout << " ?";
                        }
                        cout << "\n";
                        found = true;
                    }
                }
                
                if (!found) {
                    cout << "Tidak ada menu dalam rentang harga Rp " << minHarga 
                         << " - Rp " << maxHarga << ".\n";
                }
            }
            else if (pilihan == 5) {
                string keyword;
                cout << "Masukkan kata kunci pencarian: ";
                getline(cin, keyword);
                
                if (keyword.empty()) {
                    cout << "Kata kunci tidak boleh kosong.\n";
                    continue;
                }
                
                string kategori[100], nama[100];
                int harga[100];
                int count = 0;
                loadMenu(kategori, nama, harga, count);
                
                if (count == 0) {
                    cout << "File menu.txt tidak ditemukan atau kosong.\n";
                    continue;
                }

                cout << "\n=== HASIL PENCARIAN: '" << keyword << "' ===\n";
                bool found = false;
                
                for (int i = 0; i < count; ++i) {
                    // Cek apakah keyword ada dalam nama menu
                    string namaLower = nama[i];
                    string keywordLower = keyword;
                    
                    // Konversi ke lowercase
                    for (char &c : namaLower) c = tolower(c);
                    for (char &c : keywordLower) c = tolower(c);
                    
                    if (namaLower.find(keywordLower) != string::npos) {
                        cout << "No. " << (i+1) << ". [" << kategori[i] << "] " 
                             << nama[i] << " : Rp " << harga[i];
                        if (isMenuInFavorit(nama[i])) {
                            cout << " ?";
                        }
                        cout << "\n";
                        found = true;
                    }
                }
                
                if (!found) {
                    cout << "Tidak ditemukan menu dengan kata kunci '" << keyword << "'.\n";
                }
            }
            else if (pilihan == 0) {
                cout << "Kembali ke menu utama...\n";
            }
            else {
                cout << "Pilihan tidak valid!\n";
            }
        } while (pilihan != 0);
    }
};

#endif // TOKO_H
