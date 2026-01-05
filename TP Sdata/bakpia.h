#ifndef BAKPIA_H
#define BAKPIA_H

#include <string>
using namespace std;

class Bakpia {
private:
    PesananNode* pesananHead;
    PesananNode* pesananTail;
    HistoryNode* historyHead;
    HistoryNode* historyTail;
    HistoryNode* currentHistory;
    FavoritNode* favoritHead;
    FavoritNode* favoritTail;
    
    PesananNode* copyPesananCLL(PesananNode* original);
    void clearPesananCLL(PesananNode*& head);
    void saveState();
    void loadMenu(string* kategori, string* nama, int* harga, int& count);
    
    // Fungsi favorit
    bool isMenuInFavorit(const string& namaMenu);
    void tambahKeFavorit(const string& kategori, const string& nama, int harga);
    void hapusDariFavorit(const string& namaMenu);
    void tampilkanFavorit();
    void simpanFavoritKeFile();
    void muatFavoritDariFile();
    void clearFavorit();
    
public:
    Bakpia();
    ~Bakpia();
    
    // Fungsi utama
    bool undo();
    bool redo();
    void tampilkanSemuaMenu();
    void tambahPesanan();
    void tampilkanPesanan();
    void hapusPesananByNo();
    void editJumlahPesanan();
    void pembayaran();
    void tambahReview();
    
    // Fungsi favorit - public
    void menuKelolaFavorit();
    
    // Fungsi filter - public
    void menuFilter();
};

#endif
