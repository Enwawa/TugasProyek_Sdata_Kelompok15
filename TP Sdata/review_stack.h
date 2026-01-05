#ifndef REVIEW_STACK_H
#define REVIEW_STACK_H

#include <string>
using namespace std;

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
    ReviewStack();
    void pushReview(const string& reviewLine);
    string popReview();
    void displayAll();
    bool isEmpty() const;
    int size() const;
    void loadFromFile();
};

extern ReviewStack reviewStack;

#endif
