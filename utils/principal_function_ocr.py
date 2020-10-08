from PIL import Image
from .deleteParameters import DeleteCharacters
from .extrae import extrae_pal
from .conversorPdftoImage import pdf_to_image
from .text_detection_ocr import principal_function_ocr
import os
import os.path as path
import glob
import matplotlib.image as mpimg
import filetype
import sys

def label_detection_ocr(filename, pdf_size, options):
    text_process = []
    files_process = []
    text_filter_process = []
    current_directory = path.abspath(path.join(os.getcwd(), '.'))
    input_path = os.path.join(os.path.sep, current_directory, 'media', 'uploads', '', filename)
    output_path = os.path.join(os.path.sep, current_directory, 'media', 'static', 'output', '')
    file_type_doc = filetype.guess(input_path)

    if (file_type_doc != None):
        document_type = file_type_doc.extension
        extension_file = document_type.split('/')[-1]
    else:
        #tratar de adivinarlo con el puntito al final, si tampoco lo adivina asi, entonces pongale .pdf
        extension_file = os.path.splitext(input_path)[1].strip(".");
        if (extension_file == ''):
            extension_file = 'pdf' 

    path_image = input_path
    if extension_file == 'pdf':
        file = pdf_to_image(filename, pdf_size)
        filename = file[0]
        path_image = output_path
    elif extension_file == 'jpg' or extension_file == 'jpeg' or extension_file == 'png':
        path_image = input_path
    ocr_text_all = ''
    for nI in glob.glob(path_image+'*.jpg'):
        image = Image.open(nI)
        if image.format == 'PNG':
            image = mpimg.imread(nI, 0)
        else:
            image = mpimg.imread(nI)
        ocr_text, position_bounding_box = principal_function_ocr(nI)
        ocr_text_all += ocr_text
        #print(ocr_text)
        #sys.exit()
        #text_format_money = DeleteCharacters(ocr_text, 3, text_process_money)
        #text_to_return_nit = DeleteCharacters(ocr_text, 2, text_process_nit)
        text_filter = DeleteCharacters(ocr_text, 4, text_filter_process)
        #format_money = text_format_money.filter_parameter()
        #final_text_nit = text_to_return_nit.filter_parameter()
        text_filter_scripture = text_filter.filter_parameter()
        #text_return = DeleteCharacters(ocr_text, 1, text_process)
        #ocr_text = text_return.filter_parameter()
        ocr_text_process_numerical_format = extrae_pal(text_filter_scripture)
        #ocr_text_verification_digit = extrae_pal(final_text_nit)
        files_process.extend(ocr_text_process_numerical_format)

    #print(ocr_text)
    #sys.exit()

    if (options == 3):
        return ocr_text_all

    return files_process

