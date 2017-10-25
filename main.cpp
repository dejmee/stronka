#include <cstdlib>
#include <iostream>
#include <string>

//using namespace std;

int main(int argc, char *argv[])
{

    std::string sNapis = "To jest napis";
    std::getline(std::cin, sNapis);
    std::cout << "Napis: " << sNapis << std::endl;
    std::cout << "Dlugosc napisu to (metoda size): " << sNapis.size() << std::endl;
    std::cout << "Dlugosc napisu to (metoda length): " << sNapis.length() << std::endl;
    
    system("PAUSE");
    return EXIT_SUCCESS;
}
