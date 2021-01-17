import mysql.connector
import logging
import sqlite3

mydb = mysql.connector.connect(
    host = "localhost",
    user = "test",
    password = "Test123456-+!"
)


mycursor = mydb.cursor()


def create_tables():
    mydb = mysql.connector.connect(
        host = "localhost",
        user = "test",
        password = "Test123456-+!",
        database = "Electrodepot_db"
    )


    mycursor = mydb.cursor()

    try:
        mycursor.execute('''DROP TABLE IF EXISTS datalayers''')
        mycursor.execute('''CREATE TABLE datalayers(
            ID VARCHAR(255) PRIMARY KEY,
            name VARCHAR(255),
            price INT,
            brand TEXT,
            category TEXT,
            reduction TEXT,
            variant TEXT,
            pageCategory TEXT,
            pageSubCategory_1 TEXT,
            pageSubCategory_2 TEXT,
            pageSubCategory_3 TEXT,
            pageSubCategory_4 TEXT,
            siteCountry TEXT
            )''')
    except Exception as e:
        print(e)
    
    print("..... datalayer table is successfully created")

    try:
        mycursor.execute('''DROP TABLE IF EXISTS products''')
        mycursor.execute('''CREATE TABLE products(
            ID VARCHAR(255) PRIMARY KEY,
            name VARCHAR(255),
            price INT,
            brand TEXT,
            category TEXT,
            reduction TEXT,
            variant TEXT,
            pageCategory TEXT,
            pageSubCategory_1 TEXT,
            pageSubCategory_2 TEXT,
            pageSubCategory_3 TEXT,
            pageSubCategory_4 TEXT,
            siteCountry TEXT,
            overallRating INT,
            overallRatingCount INT,
            URL TEXT
            )''')
    except Exception as e:
        print(e)

    print("..... products table is successfully created")

    try:
        mycursor.execute('''DROP TABLE IF EXISTS features''')
        mycursor.execute('''CREATE TABLE features(
            id_products TEXT,
            feature_type TEXT,
            feature_value TEXT
            )''')
    except Exception as e:
        print(e)

    print("..... feature table is successfully created")



def create_db():
    try:
        mycursor.execute('''DROP DATABASE IF EXISTS Electrodepot_dbbbb''')
        mycursor.execute('''CREATE DATABASE Electrodepot_dbbbb''')
        create_tables()
    except Exception as e:
        print(e)
    

if __name__ == "__main__":
    create_db()