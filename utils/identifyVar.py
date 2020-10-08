from .digitos import digit_verification_ocr_text
from .cant_digitos import number_of_digits


class IdentifyVariables:
    def __init__(self, ocr_text, index_iteration, identification_type, numerical_value, number_position, type_acts, position_acts):  # las IdentifyVariables que deben retornarse se deben instanciar primero aqui
        self.ocr_text = ocr_text
        self.index_iteration = index_iteration
        self.identification_type = identification_type
        self.numerical_value = numerical_value
        self.number_position = number_position
        self.type_acts = type_acts
        self.position_acts = position_acts

    def return_variables(self):
        position = []
        concatenate_acts_word = self.ocr_text[self.index_iteration]
        aux = concatenate_acts_word.find(self.identification_type)
        acummulate_word = 0
        min_number = 3
        if concatenate_acts_word == self.identification_type:
            position .append(self.index_iteration)
            next_position = self.ocr_text[self.index_iteration]
            next_position = next_position.replace('-', '')
            digit_verification = next_position.isdigit()
            self.type_acts.append(self.ocr_text[self.index_iteration])
            self.position_acts.append(self.index_iteration)
            numerical_value, number_position = digit_verification_ocr_text(digit_verification, self.ocr_text, self.index_iteration)
            if numerical_value.isdigit():
                if not len(numerical_value) <= min_number:
                    value = int(numerical_value)
                    self.numerical_value.append(value)
                    self.number_position.append(number_position)
            else:
                value = 0
                number_digits = number_of_digits(value)
                self.numerical_value.append(value)
                self.number_position.append(number_position)
        else:
            self.numerical_value
            self.number_position
        return self.numerical_value, self.number_position, self.type_acts, self.position_acts
