import urllib.request
import requests
from bs4 import BeautifulSoup
import ssl
import re
ssl._create_default_https_context = ssl._create_unverified_context # résolution du problème [SSL: CERTIFICATE_VERIFY_FAILED]







def scrap_url_from_sitemap(): ### Récupère à partir du site map la liste entière des produits du site au moment où la page est visitée
    urls_list = []
    n = 0
    try:
        chaine = urllib.request.urlopen("https://www.electrodepot.fr/pub/sitemap-produit.xml").read().decode("utf-8")
        print("... chargement page web")
        bs_chaine = BeautifulSoup(chaine, "html.parser")
        urls = bs_chaine.findAll("loc")
        for url in urls:
            urls_list.append(url.getText())
            n += 1    
    except Exception as e:
        print(e)

    print(urls_list)
    print(n)

    for url in urls_list:
        myText = open(r'liste_jan3.txt','a')
        myText.write(url+"\n")
        myText.close()

    return urls_list
    
if __name__ == "__main__":
    scrap_url_from_sitemap()




