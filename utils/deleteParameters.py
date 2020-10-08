
class DeleteCharacters:
    def __init__(self, ocr_text, selector, process_text):
        self.ocr_text = ocr_text
        self.selector = selector
        self.process_text = process_text

    def filter_parameter(self):
        if self.selector == 1:
            self.process_text = self.ocr_text.replace('-', '').replace(',', '').replace('.', '').replace(']', '').\
                replace('|', '').replace('}', '').replace('{', '').replace('$', '')
        elif self.selector == 2:
            self.process_text = self.ocr_text.replace(',', '').replace('.', '').replace(']', '').replace('|', '').replace('}', '')\
                .replace('{', '').replace('$', '')
        elif self.selector == 3:
            self.process_text = self.ocr_text.replace('.', '').replace(']', '').replace('|', '').replace('}', '').replace('{', '')\
                .replace('$', '').replace('', '').replace('-', '').replace('=', '')
        elif self.selector == 4:
            self.process_text = self.ocr_text.replace('=', '').replace('-', '').replace('.', '')
        return self.process_text
