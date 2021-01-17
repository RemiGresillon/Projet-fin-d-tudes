#Import de toutes les libraires utiles
import json
import time
from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.keys import Keys
from subprocess import check_output
import re
from selenium.webdriver.support.ui import Select
import mysql.connector
import logging
import requests
from bs4 import BeautifulSoup
import random

t0 = time.time()


def get_data(url): ### Récupère le code de la page une fois seulement
    s = requests.Session() 
    user = random_agent()
    s.headers.update(user)    
    try:
        r = s.get(url).text
        bs_str = BeautifulSoup(r,"html.parser")
    except Exception as error:
        print(error)
    scrap_features(bs_str,url)


def random_agent(): ### Change l'user agent à chaque appel de la fonction "get_data" pour éviter au maximum de se faire bloquer
    user_agent_dict = {
                        0:{"User-Agent":"Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/4.0.202.0 Safari/532.0"},
                        1:{"User-Agent":"Mozilla/5.0 (X11; Linux x86_64; rv:80.0) Gecko/20100101 Firefox/80.0"},
                        2:{"User-Agent":"Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.7.6) Gecko/20050512 Firefox"},
                        3:{"User-Agent":"Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7"},
                        4:{"User-Agent":"Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8) Gecko/20051111 Firefox/1.5"},
                        5:{"User-Agent":"Mozilla/5.0 (X11; U; Linux x86_64; fr; rv:1.9.0.4) Gecko/2008111217 Fedora/3.0.4-1.fc10 Firefox/3.0.4"},
                        6:{"User-Agent":"Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0"},
                        7:{"User-Agent":"Mozilla/5.0 (Android; Tablet; rv:19.0) Gecko/19.0 Firefox/19.0"},
                        8:{"User-Agent":"Mozilla/5.0 (X11; U; Linux i686; fr; rv:1.9b5) Gecko/2008041514 Firefox/3.0b5"},
                        9:{"User-Agent":"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31"},
                        10:{"User-Agent":"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36"},
                        11:{"User-Agent":"Mozilla/5.0 (Linux; Android 4.2.2; Nexus 7 Build/JDQ39) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.49 Safari/537.31"},
                        12:{"User-Agent":"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13"},
                        13:{"User-Agent":"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1"}
                    }
    return user_agent_dict[random.randint(0,13)]

def check_present_url(URL): ### Vérifie que le produit actuellement scrapé n'est pas présent dans la base données pour éviter tout scrap inutile
    mydb = mysql.connector.connect(
        host = "localhost",
        user = "test",
        password = "Test123456-+!",
        database = "Elec_db"
    )
    cursor = mydb.cursor()
    try:
        cursor.execute("""SELECT * FROM products WHERE URL = '{}'""".format(URL)) ## On vérifie par l'URL évitant tout processus de chargement de page
        result = cursor.fetchall()
        if len(result) != 0:
            fill=True
        else:
            fill=False
    except Exception as error:
        print(error)
    return fill

def check_present(p_id): ### Double Vérification de la présence du produit dans la base de données
    mydb = mysql.connector.connect(
        host = "localhost",
        user = "test",
        password = "Test123456-+!",
        database = "Elec_db"
    )
    cursor = mydb.cursor()
    try:
        cursor.execute("""SELECT * FROM products WHERE ID = {}""".format(p_id)) ## L'ID étant supposé unique on peut se fier à cette variable
        result = cursor.fetchall()
        if len(result) != 0:
            fill=True
        else:
            fill=False
    except Exception as error:
        print(error)
    return fill



def query_creator(table,dict_data): ### Création des requêtes de d'insertion des données dans la base de données, en fonction de la table à populer
    if table == "features":
        for e in dict_data:
            if e == 'features':
                for i in dict_data[e]:
                    query = """INSERT INTO features(id_products,feature_type,feature_value) values("{}","{}","{}")""".format(dict_data['ID'],i,dict_data[e][i])
                    fill_db(query,table,dict_data)
    else:
        keys = ""
        values = ""
        for e in dict_data:
            if e != "features":
                keys += e+","
                values += '"'+dict_data[e].strip().replace('"','')+'",'
            else:
                pass
        keys= keys[:-1]
        values = values[:-1]
        query = """INSERT INTO {}({}) values ({})""".format(table,keys,values)
        fill_db(query,table,dict_data)


def fill_db(query,table,dict_data): ### Récupère la query précédente et l'insère dans la base de données.
    mydb = mysql.connector.connect(
        host = "localhost",
        user = "test",
        password = "Test123456-+!",
        database = "Elec_db"
    )
    cursor = mydb.cursor()
    try :
        cursor.execute(query)
        if table != "features":
            print("Informations about product ID: ",dict_data["ID"]," were successfully inserted in ",table)
        else:
            pass
    except Exception as error: 
        print(error)
    cursor.close()
    mydb.commit()

def get_country(url): ### Vérifié dans l'URL que le site soit toujours le site français (utilisé pour la variable siteCountry)
    return url.split('/')[2].split('.')[-1]

def scrap_features(bs_str,url): ### On scrap d'abord les caractéristiques du produit, car sur chaque page est présent l'ID dans cette table, on peut alors vérifier que le produit est déjà présent dans la base de données. 
    try:
        features_str = bs_str.find("div",class_="prodItem__additional")
        features = features_str.findAll("tr")
        for e in features:
            testing = e.findAll('td')
            if testing[0].getText().strip() == 'Code article':    ### Même si cette table n'est pas réellement utilisée actuellement, il sert au moins à la double vérification via le code article (ID)
                p_id = testing[1].getText().strip()   
        if check_present(p_id) == False:
            scrap_page_product("products",bs_str,features,url)
            get_datalayer(url)
        else:
            print("This product is already in the database")
            scrap_page_product("duplicate_products",bs_str,features,url)
            pass
    except Exception as error:
        print(error)


def scrap_page_product(table,bs_str,features,url): ### Scrap des pages produits
    product_details_view_dict = {}  
    try:
        p_name = bs_str.find("h1",class_="page-title").getText().strip().lower()
        product_details_view_dict['name'] = re.sub(' +',' ',p_name)                                                             ## Noms des produits 
        product_details_view_dict['price'] = bs_str.find("span",class_="price-wrapper").getText().strip().replace("€",".")      ## Prix
        p_categories = bs_str.findAll("li",itemprop="itemListElement")                                                          ## Ensemble des categories et sous-catégories
        p_category = ""
        for e in p_categories:
            if len(p_categories) == 5 or len(p_categories) == 3:
                try:                                                                                                            ## Adaptation en fonction du nombre de sous catégorie
                    if p_categories[-1] == e or p_categories[0] == e:                                                           ## de 2 à 4 
                        if p_categories[-1] == e and product_details_view_dict['pageSubCategory_3'] != 'NULL':
                            product_details_view_dict['pageSubCategory_4'] = e.getText().strip().replace('"','')
                        if p_categories[-1] == e and product_details_view_dict['pageSubCategory_3'] == 'NULL':
                            product_details_view_dict['pageSubCategory_2'] = e.getText().strip().replace('"','')
                    else:
                        p_category += e.getText().strip().lower()+"/"
                        if p_categories[1] == e:
                            product_details_view_dict['pageSubCategory_1'] = e.getText().strip().replace('"','')
                        if p_categories[2] == e:
                            product_details_view_dict['pageSubCategory_2'] = e.getText().strip().replace('"','')
                        if p_categories[3] == e:
                            product_details_view_dict['pageSubCategory_3'] = e.getText().strip().replace('"','')
                except IndexError:
                    if p_categories[1] == e:
                        product_details_view_dict['pageSubCategory_1'] = e.getText().strip().replace('"','')
                    product_details_view_dict['pageSubCategory_2'] = 'NULL'
                    product_details_view_dict['pageSubCategory_3'] = 'NULL'
                    product_details_view_dict['pageSubCategory_4'] = 'NULL'
            if len(p_categories) == 4:
                if p_categories[1] == e:
                    product_details_view_dict['pageSubCategory_1'] = e.getText().strip().replace('"','')
                if p_categories[2] == e:
                    product_details_view_dict['pageSubCategory_2'] = e.getText().strip().replace('"','')
                if p_categories[3] == e:
                    product_details_view_dict['pageSubCategory_3'] = e.getText().strip().replace('"','')


        product_details_view_dict['category'] = p_category[:-1].replace('"','')                                                 ## Categorie générale
        try:
            product_details_view_dict['variant'] = bs_str.find('div',class_="product-stickers").getText().strip()               ## Variant
        except Exception as e:
            product_details_view_dict['variant'] = ""
        features_dict = {}
        for e in features: 
            testing = e.findAll('td')
            if testing[0].getText().strip() == 'Code article':                                                                  ## ID 
                p_id = testing[1].getText().strip()
                product_details_view_dict['ID'] = p_id
            elif testing[0].getText().strip() == 'Marque':                                                                      ## Marque
                product_details_view_dict['brand'] = testing[1].getText().strip().replace('"','').lower()
            else:
                features_dict[testing[0].getText().strip()] = testing[1].getText().strip().replace('"','')   
        try:
            p_reduction = bs_str.find('span',class_="odr-label").getText().strip()      
            product_details_view_dict['reduction'] = "oui"                                                                      ## Réduction
        except Exception as e:
            try: 
                p_reduction = bs_str.find('div',class_="prix_barres").getText().strip()
                product_details_view_dict['reduction'] = "oui"
            except Exception as e:
                product_details_view_dict['reduction'] = "non"
        product_details_view_dict['pageCategory'] = "Page Produit"                                                              ## Page produit - Dans le cas Electrodepot cette variable n'est pas représentaté sur le site, je la laisse en brut car présent dans le sitemap il se peut toujours que dans le datalayer le type de page soit mal renseigné 
        product_details_view_dict['overallRating'] = bs_str.find('span',itemprop="ratingValue").getText().strip()               ## Moyenne de note globale
        product_details_view_dict['overallRatingCount'] = bs_str.find('span',itemprop="reviewCount").getText().strip()          ## Nombre de votes total
        product_details_view_dict['siteCountry'] = get_country(url)                                                             ## Version du site (langue)
        product_details_view_dict['url'] = url                                                                                  ## URL
        product_details_view_dict['features'] = features_dict                                                                   ## Dictionnaire de toutes les caractéristiques produits
    except Exception as error:
        print(product_details_view_dict)
        print(error)
    query_creator(table,product_details_view_dict)                                                                              ## Creation des query d'insertion..
    query_creator("features",product_details_view_dict)                                                                         ## .. dans la base de données


def get_datalayer(url):  ### Récupération des données du datalayer à l'aide de Selenium
    datalayer_product_details_view_dict = {}  
    print("... Chargement URL :",url)
    options = Options()
    options.add_argument('--headless')                                                                          
    driver = webdriver.Firefox(options=options,executable_path="./drivers/geckodriver")                                         ## Driver utilisé Firefox
    driver.get(url)                                                                                                             ## Visite de l'URL de manière automatisé 
    time.sleep(1)
    dataLayer = driver.execute_script("return dataLayer ;")                                                                     ## Renvoit de l'objet javascript dans une variable
    print('Title: "{}"'.format(driver.title))
    for e in dataLayer:                                                                                                         ## Creation du dictionnaire des données à analyser pour la page ciblé
        try: 
            datalayer_product_details_view_dict['siteCountry'] = dataLayer[dataLayer.index(e)]['siteCountry']
            datalayer_product_details_view_dict['ID'] = dataLayer[dataLayer.index(e)]['ecommerce']['detail']['products'][0]['id']
            datalayer_product_details_view_dict['name'] = dataLayer[dataLayer.index(e)]['ecommerce']['detail']['products'][0]['name']
            datalayer_product_details_view_dict['price'] = dataLayer[dataLayer.index(e)]['ecommerce']['detail']['products'][0]['price']
            datalayer_product_details_view_dict['brand'] = dataLayer[dataLayer.index(e)]['ecommerce']['detail']['products'][0]['brand']
            datalayer_product_details_view_dict['category'] = dataLayer[dataLayer.index(e)]['ecommerce']['detail']['products'][0]['category']
            datalayer_product_details_view_dict['reduction'] = dataLayer[dataLayer.index(e)]['ecommerce']['detail']['products'][0]['dimension3']
            datalayer_product_details_view_dict['variant'] = dataLayer[dataLayer.index(e)]['ecommerce']['detail']['products'][0]['variant']
            datalayer_product_details_view_dict['pageCategory'] = dataLayer[dataLayer.index(e)]['pageCategory']
            datalayer_product_details_view_dict['pageSubCategory_1'] = dataLayer[dataLayer.index(e)]['pageSubCategory1']
            datalayer_product_details_view_dict['pageSubCategory_2'] = dataLayer[dataLayer.index(e)]['pageSubCategory2']
            datalayer_product_details_view_dict['pageSubCategory_3'] = dataLayer[dataLayer.index(e)]['pageSubCategory3']
            datalayer_product_details_view_dict['pageSubCategory_4'] = dataLayer[dataLayer.index(e)]['pageSubCategory4']
        except Exception as err:
            pass
    print(datalayer_product_details_view_dict)
    query_creator("datalayers",datalayer_product_details_view_dict)                                                             ## Création de la query associé au datalayer de la page
    driver.quit()


def read_url(fichier):  ## Lit le fichier créé par la "get_url.py" et permet de lancer tout le scraping et l'insertion après vérification de l'existence du produit dans la base de données.
    n = 1
    url_list = open(fichier)                                                                                                    ## Ouverte du fichier de la liste des URL à scrap
    urls = url_list.readlines()
    for url in urls:
        try:
            print("\n")
            print(url)
            if check_present_url(url.strip()) == False:                                                                         ## Vérification de la présence de l'URL dans la base de données.
                get_data(url.strip())                                                                                           ## On lance la récupération du code source de la page
            else:
                pass  
            t1 = time.time()
            print("produit N°:",n,round(t1-t0,2)," secondes")
        except Exception as e:
            print(e)
        n+=1
    url_list.close()    


if __name__ == "__main__":
    read_url("liste_jan3.txt")