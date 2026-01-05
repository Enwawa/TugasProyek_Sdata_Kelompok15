#ifndef STACK_H
#define STACK_H

// Struktur data: Stack (LIFO) untuk Review
// Implementasi: Circular Single Linked List (node head sebagai TOP)
// Tidak menambah library standar baru.

// Deklarasi fungsi bantu (didefinisikan di toko.h)
void splitString(const string& str, char delimiter, string* result, int maxParts);

struct ReviewNode {
    string data;  // Format: username;nama;review
    ReviewNode* next;
};

class ReviewStack {
private:
    ReviewNode* head;
    ReviewNode* tail;
    int count;
    const int MAX_SIZE = 5; // Batasan 5 review terbaru

public:
    ReviewStack() : head(nullptr), tail(nullptr), count(0) {}

    // PUSH: Menambahkan review ke Stack
    void pushReview(const string& reviewLine) {
        ReviewNode* baru = new ReviewNode{reviewLine, nullptr};

        if (count == 0) {
            head = tail = baru;
            baru->next = baru; // Circular
        } else {
            baru->next = head;
            tail->next = baru;
            head = baru;
        }
        count++;

        // Jika melebihi batas, hapus yang paling lama (tail)
        if (count > MAX_SIZE) {
            // Cari node sebelum tail
            ReviewNode* temp = head;
            while (temp->next != tail) {
                temp = temp->next;
            }
            temp->next = head; // Skip tail
            delete tail;
            tail = temp;
            count--;
        }
        cout << "Review berhasil di-PUSH ke Stack.\n";
    }

    // POP: Mengambil dan menghapus review paling atas
    string popReview() {
        if (count == 0) return "";

        string data = head->data;
        if (count == 1) {
            delete head;
            head = tail = nullptr;
        } else {
            ReviewNode* temp = head;
            head = head->next;
            tail->next = head;
            delete temp;
        }
        count--;
        return data;
    }

    // DISPLAY: Menampilkan semua elemen Stack
    void displayAll() {
        if (count == 0) {
            cout << "Stack Review kosong. Tidak ada review untuk ditampilkan.\n";
            return;
        }

        cout << "\n=== DAFTAR SEMUA REVIEW DI STACK (TERBARU ke TERLAMA) ===\n";

        ReviewNode* current = head;
        int no = 1;

        for (int i = 0; i < count; i++) {
            string parts[3];
            splitString(current->data, ';', parts, 3);

            cout << "No. " << no++ << "\n";
            cout << "   Dari      : " << parts[1] << " (" << parts[0] << ")\n";
            cout << "   Isi Review: " << parts[2] << "\n";
            cout << "------------------------------------\n";

            current = current->next;
        }
    }

    bool isEmpty() const { return count == 0; }
    int size() const { return count; }

    // Fungsi untuk memuat review dari file
    void loadFromFile() {
        ifstream file("review.txt");
        if (!file.is_open()) return;

        string line;
        while (getline(file, line)) {
            if (!line.empty()) {
                pushReview(line);
            }
        }
        file.close();
    }
};

extern ReviewStack reviewStack;

#endif
