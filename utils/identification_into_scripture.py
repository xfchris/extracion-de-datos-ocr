from .identifyVar import IdentifyVariables


def return_identification_documents(ocr_text, target):
    value = []
    value_position = []
    type_document = []
    document_position = []
    identification_document = []
    try:
        for ocr_document in range(len(ocr_text)):
            for type_identification in range(len(target)):
                number_identification = IdentifyVariables(ocr_text, ocr_document, target[type_identification], value,
                                                          value_position, type_document, document_position)
                number_identification.return_variables()
        identification_document = number_identification.return_variables()
    except NameError:
        print('the text was not extract')
    return identification_document
