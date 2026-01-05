#ifndef CSLL_H
#define CSLL_H

// Struktur data: Circular Single Linked List (CSLL)
// Header ini hanya memisahkan definisi node-node CSLL.
// Tidak menambah library standar baru.

// Node akun (CSLL)
struct AkunNode {
    string nik;      // NIK sebagai identifier
    string nama;
    string telepon;
    string email;
    string username;
    string password;
    AkunNode* next;
};

// Node menu (CSLL)
struct MenuNode {
    string kategori;
    string nama;
    int harga;
    MenuNode* next;
};

// Node pesanan (CSLL)
struct PesananNode {
    string jenis;
    string rasa;
    int jumlah;
    int hargaSatuan;
    int total;
    PesananNode* next;
};

// Node favorit (CSLL)
struct FavoritNode {
    string kategori;
    string nama;
    int harga;
    FavoritNode* next;
};

#endif
