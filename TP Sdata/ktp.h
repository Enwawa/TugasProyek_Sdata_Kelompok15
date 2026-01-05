#ifndef KTP_H
#define KTP_H

#include <iostream>
#include <iomanip>
#include <limits>
#include <fstream>
#include <string>

using namespace std;

// Helper untuk membersihkan buffer dengan aman
static inline void ignoreLine() {
    cin.clear();
    cin.ignore(numeric_limits<streamsize>::max(), '\n');
}

struct Tanggal {
    int hari, bulan, tahun;
};

struct Identitas {
    long long nik;
    string namaLengkap;
    string tempatLahir;
    Tanggal tanggalLahir;
    bool lakiLaki;
    string golDarah;
    string alamatLengkap;
    int rt, rw;
    string kelurahan;
    string kecamatan;
    int agama;
    bool sudahMenikah;
    string pekerjaan;
    bool wni;
    int masaBerlaku;
};

class KTP {
private:
    Identitas info;

    string namaAgama() const {
        switch (info.agama) {
            case 1: return "Islam";
            case 2: return "Kristen";
            case 3: return "Katolik";
            case 4: return "Hindu";
            case 5: return "Budha";
            case 6: return "Konghucu";
            default: return "Tidak Dikenal";
        }
    }

    string masaBerlakuText() const {
        switch (info.masaBerlaku) {
            case 1: return "Sementara";
            case 2: return "5 Tahun";
            case 3: return "Selamanya";
            default: return "-";
        }
    }

public:
    // Getter methods untuk akses data KTP
    long long getNIK() const { return info.nik; }
    string getNama() const { return info.namaLengkap; }
    string getTempatLahir() const { return info.tempatLahir; }
    Tanggal getTanggalLahir() const { return info.tanggalLahir; }
    string getJenisKelamin() const { return info.lakiLaki ? "Laki-laki" : "Perempuan"; }
    string getGolDarah() const { return info.golDarah; }
    string getAlamat() const { return info.alamatLengkap; }
    int getRT() const { return info.rt; }
    int getRW() const { return info.rw; }
    string getKelurahan() const { return info.kelurahan; }
    string getKecamatan() const { return info.kecamatan; }
    string getAgama() const { return namaAgama(); }
    string getStatusKawin() const { return info.sudahMenikah ? "Menikah" : "Belum Menikah"; }
    string getPekerjaan() const { return info.pekerjaan; }
    string getKewarganegaraan() const { return info.wni ? "WNI" : "WNA"; }
    string getMasaBerlaku() const { return masaBerlakuText(); }
    int getAgamaCode() const { return info.agama; }
    bool getStatusMenikah() const { return info.sudahMenikah; }
    bool getWNI() const { return info.wni; }
    int getMasaBerlakuCode() const { return info.masaBerlaku; }

    void inputData() {
        cout << "\n=== Input Data Penduduk ===\n";

        while (true) {
            cout << "NIK (16 digit): ";
            if (cin >> info.nik) {
                // Cek apakah NIK 16 digit
                if (to_string(info.nik).length() != 16) {
                    cout << "NIK harus 16 digit!\n";
                    continue;
                }
                break;
            }
            cout << "Input tidak valid!\n";
            ignoreLine();
        }
        ignoreLine();

        cout << "Nama Lengkap: "; getline(cin, info.namaLengkap);
        cout << "Tempat Lahir: "; getline(cin, info.tempatLahir);

        cout << "Tanggal Lahir (dd mm yyyy): ";
        while (!(cin >> info.tanggalLahir.hari >> info.tanggalLahir.bulan >> info.tanggalLahir.tahun)) {
            cout << "Input tidak valid! Masukkan angka.\n";
            ignoreLine();
        }
        ignoreLine();

        cout << "Jenis Kelamin (1.Laki-laki, 2.Perempuan): ";
        int jk;
        while (!(cin >> jk) || (jk != 1 && jk != 2)) {
            cout << "Input salah! Pilih 1 atau 2: ";
            ignoreLine();
        }
        info.lakiLaki = (jk == 1);
        ignoreLine();

        cout << "Golongan Darah: ";
        getline(cin, info.golDarah);

        cout << "Alamat: "; getline(cin, info.alamatLengkap);

        cout << "RT: ";
        while (!(cin >> info.rt)) { cout << "Input salah!"; ignoreLine(); }
        cout << "RW: ";
        while (!(cin >> info.rw)) { cout << "Input salah!"; ignoreLine(); }
        ignoreLine();

        cout << "Kelurahan: "; getline(cin, info.kelurahan);
        cout << "Kecamatan: "; getline(cin, info.kecamatan);
		
		cout << "\nPilih Agama:\n";
		cout << "1. Islam\n";
		cout << "2. Kristen\n";
		cout << "3. Katolik\n";
		cout << "4. Hindu\n";
		cout << "5. Budha\n";
		cout << "6. Konghucu\n";
        cout << "Agama (1-6): ";
        while (!(cin >> info.agama) || info.agama < 1 || info.agama > 6) {
            cout << "Input salah! Masukkan 1-6: ";
            ignoreLine();
        }
        ignoreLine();

        cout << "Status Perkawinan (1. Menikah, 2. Belum): ";
        int st;
        while (!(cin >> st) || (st != 1 && st != 2)) {
            cout << "Input salah! Pilih 1 atau 2: ";
            ignoreLine();
        }
        info.sudahMenikah = (st == 1);
        ignoreLine();

        cout << "Pekerjaan: "; getline(cin, info.pekerjaan);

        cout << "Kewarganegaraan (1. WNI, 2. WNA): ";
        int kw;
        while (!(cin >> kw) || (kw != 1 && kw != 2)) {
            cout << "Input salah! Pilih 1 atau 2: ";
            ignoreLine();
        }
        info.wni = (kw == 1);
        ignoreLine();

        cout << "Masa Berlaku (1.Sementara, 2.5 tahun, 3.Selamanya): ";
        while (!(cin >> info.masaBerlaku) || info.masaBerlaku < 1 || info.masaBerlaku > 3) {
            cout << "Input salah! Masukkan 1–3: ";
            ignoreLine();
        }
        ignoreLine();
    }

    void tampilkanData(int index = -1) const {
        cout << "\n===========================================\n";
        cout << "              DATA KTP\n";
        cout << "===========================================\n";
        if (index != -1) cout << "Data ke-" << index + 1 << "\n";

        cout << "NIK              : " << info.nik << endl;
        cout << "Nama             : " << info.namaLengkap << endl;
        cout << "Tempat/Tgl Lahir : " << info.tempatLahir << ", ";
        cout << info.tanggalLahir.hari << "-" << info.tanggalLahir.bulan << "-" << info.tanggalLahir.tahun << endl;
        cout << "Jenis Kelamin    : " << (info.lakiLaki ? "Laki-laki" : "Perempuan") << endl;
        cout << "Gol. Darah       : " << info.golDarah << endl;
        cout << "Alamat           : " << info.alamatLengkap << endl;
		cout << "   RT/RW            : " << info.rt << "/" << info.rw << endl;
        cout << "   Kelurahan        : " << info.kelurahan << endl;
        cout << "   Kecamatan        : " << info.kecamatan << endl;
        cout << "Agama            : " << namaAgama() << endl;
        cout << "Status Kawin     : " << (info.sudahMenikah ? "Menikah" : "Belum") << endl;
        cout << "Pekerjaan        : " << info.pekerjaan << endl;
        cout << "Kewarganegaraan  : " << (info.wni ? "WNI" : "WNA") << endl;
        cout << "Masa Berlaku     : " << masaBerlakuText() << endl;
        cout << "===========================================\n";
    }

    void ubahData() {
        int pilih;

        do {
            cout << "\n--- Edit Data ---\n";
            cout << "1. Nama\n2. Tempat Lahir\n3. Tanggal Lahir\n";
            cout << "4. Alamat\n5. Agama\n6. Pekerjaan\n7. Status Kawin\n";
            cout << "0. Selesai\nPilih: ";
            cin >> pilih;
            ignoreLine();

            switch (pilih) {
                case 1: cout << "Nama baru: "; getline(cin, info.namaLengkap); break;
                case 2: cout << "Tempat lahir baru: "; getline(cin, info.tempatLahir); break;
                case 3:
                    cout << "Tanggal lahir (dd mm yyyy): ";
                    cin >> info.tanggalLahir.hari >> info.tanggalLahir.bulan >> info.tanggalLahir.tahun;
                    ignoreLine(); break;
                case 4:
                    cout << "Alamat baru: "; getline(cin, info.alamatLengkap);
                    cout << "RT: "; cin >> info.rt;
                    cout << "RW: "; cin >> info.rw;
                    ignoreLine();
                    break;
                case 5:
			        cout << "1. Islam" << endl;
					cout << "2. Kristen" << endl;
					cout << "3. Katolik" << endl;
					cout << "4. Hindu" << endl;
					cout << "5. Budha" << endl;
					cout << "6. Konghucu" << endl;
                    cout << "Agama (1-6): ";
                    cin >> info.agama;
                    ignoreLine();
                    break;
                case 6:
                    cout << "Pekerjaan baru: ";
                    getline(cin, info.pekerjaan);
                    break;
                case 7:
                    int st; 
                    cout << "1.Menikah, 2.Belum: ";
                    cin >> st;
                    info.sudahMenikah = (st == 1);
                    ignoreLine();
                    break;
                case 0: cout << "Selesai mengedit.\n"; break;
                default: cout << "Pilihan tidak valid!\n";
            }
        } while (pilih != 0);
    }

    void tulisKeFile(ofstream &out, int idx) const {
        out << "Data ke-" << idx + 1 << "\n";
        out << "NIK: " << info.nik << "\n";
        out << "Nama: " << info.namaLengkap << "\n";
        out << "Alamat: " << info.alamatLengkap << "\n";
        out << "------------------------------------\n";
    }
    
    // Method untuk menyimpan data KTP ke string format
    string toString() const {
        string tanggalLahir = to_string(info.tanggalLahir.hari) + "-" +
                             to_string(info.tanggalLahir.bulan) + "-" +
                             to_string(info.tanggalLahir.tahun);
        
        return to_string(info.nik) + ";" +
               info.namaLengkap + ";" +
               info.tempatLahir + ";" +
               tanggalLahir + ";" +
               (info.lakiLaki ? "Laki-laki" : "Perempuan") + ";" +
               info.golDarah + ";" +
               info.alamatLengkap + ";" +
               to_string(info.rt) + ";" +
               to_string(info.rw) + ";" +
               info.kelurahan + ";" +
               info.kecamatan + ";" +
               namaAgama() + ";" +
               (info.sudahMenikah ? "Menikah" : "Belum Menikah") + ";" +
               info.pekerjaan + ";" +
               (info.wni ? "WNI" : "WNA") + ";" +
               masaBerlakuText();
    }
};

#endif
