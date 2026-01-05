#ifndef HISTORY_H
#define HISTORY_H

// Struktur data: History untuk Undo/Redo
// Implementasi yang dipakai program: Circular Single Linked List (CSLL)
// Tidak menambah library standar baru.

struct HistoryNode {
    PesananNode* pesananHead;  // Head dari CLL pesanan pada state ini
    HistoryNode* next;
};

#endif
