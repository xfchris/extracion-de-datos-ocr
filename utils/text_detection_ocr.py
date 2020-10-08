import os
import io
from google.cloud import vision


os.environ['GOOGLE_APPLICATION_CREDENTIALS'] = os.path.join(os.curdir, 'gfiles-app-gevir-bb76a90c0275.json')
client = vision.ImageAnnotatorClient()


def principal_function_ocr(path):
    square_position = []
    bounds = []
    all_new_variable = []
    ocr_text_detected = []
    with io.open(path, 'rb') as imageFile:
        content = imageFile.read()
    image = vision.types.Image(content=content)
    answer = client.document_text_detection(image=image)
    all_text_return = answer.text_annotations
    if len(all_text_return) != 0:
        for page in answer.full_text_annotation.pages:
            for block in page.blocks:
                square_position.append(block.bounding_box)
                bounds.append(block.bounding_box)
                for paragraph in block.paragraphs:
                    for word in paragraph.words:
                        word_text = ''.join([
                            symbol.text for symbol in word.symbols])
                        word_text += word_text
                        all_new_variable.append(word.bounding_box)
        ocr_text_detected = all_text_return[0].description
    else:
        ocr_text_detected = 'no text_detected'
    return ocr_text_detected, all_new_variable






