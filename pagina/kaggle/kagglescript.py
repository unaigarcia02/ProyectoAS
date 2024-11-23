import os
import pandas as pd
from kaggle.api.kaggle_api_extended import KaggleApi

# Inicializa la API de Kaggle
api = KaggleApi()
api.authenticate()  # No se pasan argumentos aquí

# Define el nombre del dataset y la ruta de descarga
dataset_name = 'owm4096/laptop-prices'
download_path = os.path.join(os.path.dirname(__file__), 'dataset')

# Crea el directorio de descarga si no existe
os.makedirs(download_path, exist_ok=True)

# Descarga el dataset
api.dataset_download_files(dataset_name, path=download_path, unzip=True)

# Carga el archivo CSV y guárdalo como JSON
csv_file = os.path.join(download_path, 'laptop_prices.csv')  # Cambia por el nombre del archivo CSV
df = pd.read_csv(csv_file)
json_file_path = os.path.join(download_path, 'laptop_prices.json')
df.to_json(json_file_path, orient='records', lines=True)

print(f'Dataset descargado y convertido a JSON en: {json_file_path}')

