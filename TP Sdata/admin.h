#ifndef ADMIN_H
#define ADMIN_H

#include <string>
using namespace std;

class Admin {
public:
    void menuAdmin();

private:
    AkunNode* bacaAkunDariFile();
    void hapusAkunList(AkunNode*& head);
    void tampilkanSemuaAkun();
    void hapusAkun();
    void kelolaMenuToko();
    void tampilkanRiwayatTransaksi();
    void lihatDetailTransaksi();
    void hapusTransaksi();
    void kelolaStackReview();
};

#endif
