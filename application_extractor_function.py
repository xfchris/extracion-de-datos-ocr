from utils.value_and_acts_identify import value_acts
import sys
import json
import os
#import pdb

ruta_cache = 'resources/cache/'


# guarda la informacion de value_acts en
def create_cache(filename, pdf_size, extract_parameters, options):
    obj_e_p = {}
    obj_e_p['path'] = filename
    obj_e_p['pdf_size'] = pdf_size
    obj_e_p['options'] = options
    obj_e_p['extract_parameters'] = json.loads(extract_parameters)
    if options == 0:
        obj_e_p['extract_parameters'].pop(
            'ocr_text', None)  # quito todo el texto del OCR

    # elimina el cache existente si existe.
    # crea archivo /resources/cache/escritura3333.json el cual contiene un json con los atributos: pdf_size y extract_parameters
    file = os.path.basename(filename)+".json"
    with open(ruta_cache+file, 'w') as outfile:
        json.dump(obj_e_p, outfile)

    # si lo crea retorna true. si no pudo crear, false
    return False


# retorna info si existe cache, si no existe, retorna false
def get_cache(filename, pdf_size, options):
    # Abre archivo /resources/cache/escritura3333.json el cual contiene los atributos: pdf_size y extract_parameters
    file = os.path.basename(filename)+".json"
    data = False

    try:
        with open(ruta_cache+file) as json_file:
            obj_e_p = json.load(json_file)

        # Si la cantidad de hojas que estan cacheadas son iguales a las que se intentan cachear, entonces retorna extract_parameters, si no, retorna false
        if (obj_e_p['pdf_size'] == pdf_size and
                obj_e_p['options'] == options):
            data = json.dumps(obj_e_p['extract_parameters'])

    except:
        # si el archivo no existe o genera algun error, retorna false
        data = False

    return data


# Eliminar datos cacheados desde hace x dias
def delete_cache_from(dias):
    # este metodo debe entrar a resources/cache y eliminar los archivos cuya creacion sean de 30 dias hacia atrás
    # Todo
    return True


def application_extractor(filename, pdf_size, options):

    # eliminar datos cacheados desde hace x dias
    delete_cache_from(30)

    # busco si ese nombre de archivo ya tiene información
    info_cacheada = get_cache(filename, pdf_size, options)
    # info_cacheada = False
    # si no tiene
    if (info_cacheada == False):
        extract_parameters = value_acts(filename, pdf_size, options)
        # creo un cache de esa información cacheada
        create_cache(filename, pdf_size, extract_parameters, options)
    else:
        # si tiene, retorno ese dato guardado
        extract_parameters = info_cacheada

    # Guardo los datos extraidos en una carpeta.
    return extract_parameters


# si no trae ocr_text, entonces es porque es cache
try:
    valor3 = int(sys.argv[3])
except IndexError:
    valor3 = 0

print(application_extractor(sys.argv[1], int(sys.argv[2]), valor3))
# manual: script, rutaDelpdf PaginasAAnalizar RetornarData retornarTexto(0=no, 1=si, 2=solo textos sin analizar)
# python3.6 application_extractor_function.py /var/www/html/gevir_plus/ocr/escritura8087718.pdf 1 2
# python3.6 application_extractor_function.py /var/www/html/gevir_plus/ocr/scriture_12.pdf 1 2
