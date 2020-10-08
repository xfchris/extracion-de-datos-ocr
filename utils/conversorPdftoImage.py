import os
import os.path as path
from pdf2image import convert_from_path


def pdf_to_image(pdf_path, pdf_size):
    current_directory = path.abspath(path.join(os.getcwd(), "."))
    input_path = os.path.join(os.path.sep, current_directory, 'media', 'uploads', '', pdf_path)
    media_directory = os.path.join(os.path.sep, current_directory, 'media', 'static', 'output', '')
    auxiliary_increment = 0
    pdf_file = open(os.path.join(input_path), 'rb')
    #length_pdf = len(convert_from_path(pdf_file.name, 40))
    #quantity_images_processing = 10
    #min_quantity_pdf_images = min(length_pdf, quantity_images_processing)
    read_pages = convert_from_path(pdf_file.name, 100,None,None,last_page=pdf_size)[0:pdf_size]
    #if length_pdf >= pdf_size:
    #    read_pages = convert_from_path(pdf_file.name, 100,None,1,last_page=pdf_size)[0:pdf_size]
    #else:
    #    read_pages = convert_from_path(pdf_file.name, 100,None,1,last_page=min_quantity_pdf_images)[0:min_quantity_pdf_images]
    path_new_image = ''
    new_label_document = [index for index in range(len(read_pages[0:pdf_size]))]
    for page in read_pages:
        label_size_document = str(new_label_document[auxiliary_increment])
        pdf_path = os.path.basename(pdf_path)
        auxiliary_file_name = pdf_path.split('.')
        type_file = 'jpg'
        page.save(os.path.join(media_directory+auxiliary_file_name[0]+'-'+label_size_document+'.'+type_file), 'JPEG')
        path_new_image = os.path.join(auxiliary_file_name[0]+'-'+label_size_document+'.'+type_file), 'JPEG'
        auxiliary_increment += 1
    return path_new_image
