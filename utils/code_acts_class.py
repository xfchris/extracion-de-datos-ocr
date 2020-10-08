
class CodeActs:

    def __init__(self, code_acts_detected, vector_without_amount_code, code_return, value_without_amount_return):
        self.code_acts_detected = code_acts_detected
        self.vector_without_amount_code = vector_without_amount_code
        self.code_return = code_return
        self.value_without_amount_return = value_without_amount_return

    def extract_codes(self):
        try:
            for index_code in range(len(self.code_acts_detected)):
                self.code_return.append(self.vector_without_amount_code[self.code_acts_detected[index_code]])
        except ValueError:
            print('values vector code acts its not consistent')
        return self.code_return

    def extract_value_without_quantity(self):
        for index_code in range(len(self.code_acts_detected)):
            for index_without_code in range(len(self.vector_without_amount_code)):
                if self.code_acts_detected[index_code] == self.vector_without_amount_code[index_without_code]:
                    self.value_without_amount_return.append('nan')
        return self.value_without_amount_return














