import xml.etree.ElementTree as ET

# parse the XML data
root = ET.parse("Table_ARTICLE.xml").getroot()

# open a new CSV file for writing
with open("woo-import.csv", "w") as f:
    # write the header row to the CSV file
    f.write("reference,dimension,stock_quantity,sale_price\n")

    # loop through each Table_ARTICLE element in the XML data
    for article in root.findall("Table_ARTICLE"):
        # extract the relevant information
        reference = article.find("REFERENCE").text
        dimension = article.find("DIMENSION").text
        stock_quantity = article.find("Qté_Stock").text
        sale_price = article.find("PRIX_VENTE").text

        # write the information to the CSV file
        f.write(f"{reference},{dimension},{stock_quantity},{sale_price}\n")
