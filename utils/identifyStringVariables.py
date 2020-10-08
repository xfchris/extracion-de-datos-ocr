
class VariableStringReturn:
    def __init__(self, ocr_text, index_terms, interest_word):
        self.ocr_text = ocr_text
        self.index_terms = index_terms
        self.interest_word = interest_word

    def interest_variable_returns(self):
        position = []
        auxiliary_variable = self.ocr_text[self.index_terms].find(self.interest_word)
        if auxiliary_variable != -1:
            position.append(self.index_terms)
            actual_ocr_word = self.ocr_text[self.index_terms]
            actual_position = actual_ocr_word.find()




