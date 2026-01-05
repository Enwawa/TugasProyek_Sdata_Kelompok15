#ifndef TREE_H
#define TREE_H

// Struktur data: General Tree (multi-child) untuk menampilkan menu dalam bentuk hirarki.
// Implementasi: firstChild / nextSibling.
// Tidak menambah library standar baru.

struct TreeNode {
    string label;
    bool isCategory;      // true untuk kategori (Basah/Kering), false untuk item
    int harga;            // hanya dipakai jika item
    TreeNode* firstChild; // anak pertama
    TreeNode* nextSibling;// saudara berikutnya
};

inline TreeNode* createTreeNode(const string& label, bool isCategory, int harga) {
    TreeNode* node = new TreeNode;
    node->label = label;
    node->isCategory = isCategory;
    node->harga = harga;
    node->firstChild = nullptr;
    node->nextSibling = nullptr;
    return node;
}

// Membuat tree menu: Root -> Kategori -> Item
inline TreeNode* buildMenuTree(const string* kategori, const string* nama, const int* harga, int count) {
    TreeNode* root = createTreeNode("MENU BAKPIA", true, 0);

    for (int i = 0; i < count; ++i) {
        // Cari kategori di anak root
        TreeNode* catNode = nullptr;
        TreeNode* curCat = root->firstChild;
        TreeNode* prevCat = nullptr;
        while (curCat) {
            if (curCat->isCategory && curCat->label == kategori[i]) {
                catNode = curCat;
                break;
            }
            prevCat = curCat;
            curCat = curCat->nextSibling;
        }

        // Jika belum ada kategori, buat
        if (!catNode) {
            catNode = createTreeNode(kategori[i], true, 0);
            if (!root->firstChild) root->firstChild = catNode;
            else prevCat->nextSibling = catNode;
        }

        // Tambah item di bawah kategori
        TreeNode* itemNode = createTreeNode(nama[i], false, harga[i]);
        if (!catNode->firstChild) {
            catNode->firstChild = itemNode;
        } else {
            TreeNode* t = catNode->firstChild;
            while (t->nextSibling) t = t->nextSibling;
            t->nextSibling = itemNode;
        }
    }

    return root;
}

// Print tree dengan ASCII aman di console Windows
inline void printMenuTree(TreeNode* node, const string& prefix = "", bool isLast = true, bool isRoot = true) {
    if (!node) return;

    cout << prefix;
    if (!isRoot) {
        cout << (isLast ? "`-- " : "|-- ");
    }

    cout << node->label;
    if (!node->isCategory) {
        cout << " : Rp " << node->harga;
    }
    cout << "\n";

    string childPrefix = prefix;
    if (!isRoot) childPrefix += (isLast ? "    " : "|   ");

    TreeNode* child = node->firstChild;
    if (!child) return;

    // Tentukan last child
    TreeNode* last = child;
    while (last->nextSibling) last = last->nextSibling;

    while (child) {
        bool childIsLast = (child == last);
        printMenuTree(child, childPrefix, childIsLast, false);
        child = child->nextSibling;
    }
}

// Hapus tree (post-order)
inline void destroyMenuTree(TreeNode* node) {
    if (!node) return;
    TreeNode* child = node->firstChild;
    while (child) {
        TreeNode* next = child->nextSibling;
        destroyMenuTree(child);
        child = next;
    }
    delete node;
}

#endif
