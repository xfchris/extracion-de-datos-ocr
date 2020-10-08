import os
import os.path as path
import json
import pandas as pd
import nltk
from .digit_verification_to_extract_names import digit_verification
from .cant_digitos import number_of_digits


class IdentifyDocumentActs:

    def __init__(self, document_identification, index_identify_acts):
        self.index_identify_acts = index_identify_acts
        self.document_identification = document_identification

    def extract_acts(self, ocr_text, acts_vector):
        self.document_identification = ''
        self.index_identify_acts = []
        accumulated_ocr_word = 0
        len_of_word = 3
        len_of_unique_act = 1
        for index in range(len(ocr_text)):
            for vector in range(len(acts_vector)):
                acts_position = ocr_text[index].find(acts_vector[vector].split()[0])
                if len(acts_vector[vector].split()) == len_of_unique_act and ocr_text[index] == \
                        acts_vector[vector].split()[0]:
                    self.document_identification += (acts_vector[vector]) + ' '
                    self.index_identify_acts.append(vector)
                if len(acts_vector[vector].split()) >= len_of_word and ocr_text[index] ==\
                        acts_vector[vector].split()[0]:
                    for index_into_a_word in range(len(acts_vector[vector].split())):
                        compound_word = acts_vector[vector].split()
                        acts_position = ocr_text[index+index_into_a_word].find(compound_word[index_into_a_word])
                        accumulated_ocr_word += 1
                        if ocr_text[index+index_into_a_word] == compound_word[index_into_a_word] and \
                                accumulated_ocr_word >= len_of_word:
                            self.document_identification += (acts_vector[vector]) + ' '
                            self.index_identify_acts.append(vector)
                            break
                    accumulated_ocr_word = 0
        return self.document_identification, self.index_identify_acts

    def extract_names(self, ocr_text, target_names):
        name_accumulator = 0
        possible_name = []
        self.document_identification = []
        for index_names in range(len(ocr_text)):
            for index_target in range(len(target_names)):
                if ocr_text[index_names] == target_names[index_target] and name_accumulator <= len(ocr_text)-1:
                    if name_accumulator == len(ocr_text)-1:
                        break
                    while ocr_text[index_names + name_accumulator] != 'cc':
                        name_accumulator += 1
                        possible_name.append(ocr_text[index_names + name_accumulator])
        self.document_identification = possible_name[0: len(possible_name) - 2]
        self.document_identification = ' '.join(self.document_identification)
        return self.document_identification

    def extract_tittle(self, ocr_text):
        self.document_identification = {}
        current_directory = path.abspath(path.join(os.getcwd(), '.'))
        resources_directory = os.path.join(os.path.sep, current_directory, 'resources', '')
        with open(resources_directory + 'titles_target.json') as marker_tittle:
            target_tittle = json.loads(marker_tittle.read())
        document_type = set(ocr_text) & set(target_tittle['targets_tittle'])
        self.document_identification = [x_index for x_index in iter(document_type)]
        return self.document_identification

    def extract_identificators(self, ocr_text, identifiers):
        self.document_identification = []
        vector_identifiers = []
        for index in range(len(identifiers)):
            auxiliar_identifiers = [position for position, char in enumerate(ocr_text) if char == identifiers[index]]
            vector_identifiers.extend(auxiliar_identifiers)
        self.document_identification = vector_identifiers
        self.document_identification = pd.unique(self.document_identification).tolist()
        return self.document_identification

    def inferior_limit(self, upper_limit, ocr_text):
        self.document_identification = []
        self.index_identify_acts = []
        reject_number = 5
        offset = 2
        if len(upper_limit) == 0:
            ocr_identifier_text = ocr_text
            upper_limit = [0]
        else:
            ocr_identifier_text = ocr_text[upper_limit[0]:]
        string_ocr = ' '.join(ocr_identifier_text)
        string_ocr_process = string_ocr.replace(',', '').replace('.', '')
        actual_index_list = string_ocr_process.split(' ')
        for numerical_index in range(len(actual_index_list)):
            identification_digit = actual_index_list[numerical_index].isdigit()
            numerical_value, number_position = digit_verification(identification_digit, actual_index_list,
                                                                  numerical_index)
            quantity_digits = number_of_digits(int(numerical_value))
            variable_position = number_position + upper_limit[0] + offset
            if not quantity_digits <= reject_number:
                self.document_identification.append(numerical_value)
                self.document_identification = pd.unique(self.document_identification).tolist()
                self.index_identify_acts.append(variable_position)
        self.index_identify_acts = pd.unique(self.index_identify_acts).tolist()
        return self.document_identification, self.index_identify_acts

    def extract_names_natural_language(self, ocr_text, upper_limit, inferior_limit):
        self.document_identification = []
        self.index_identify_acts = []
        text = ocr_text[upper_limit[0]:inferior_limit[0]]
        string_ocr_text = ' '.join(text)
        natural_language = nltk.word_tokenize(string_ocr_text)
        natural_language = nltk.pos_tag(natural_language)
        text_data_frame = pd.DataFrame(natural_language)
        data_frame_name = text_data_frame.loc[text_data_frame[1].isin(['NNP'])]
        data_frame_id = text_data_frame.loc[text_data_frame[1].isin(['CD'])]
        if not data_frame_name.empty or data_frame_id.empty:
            self.document_identification = text_data_frame.loc[text_data_frame[1].isin(['NNP'])]
            self.index_identify_acts = text_data_frame.loc[text_data_frame[1].isin(['CD'])]
        return self.document_identification, self.index_identify_acts

    def extract_names_with_upper_inferior_limits(self, ocr_text, upper_limit, inferior_limit, target, target_id,
                                                 constant_target):

        self.document_identification = []
        size_upper = len(upper_limit)
        size_inferior = len(inferior_limit)
        min_value = min(size_upper, size_inferior)
        for indicator in range(min_value):
            text = ocr_text[upper_limit[indicator]:inferior_limit[indicator]]
            unique_text = pd.unique(text).tolist()
            dictionary_text = {element: index for (index, element) in enumerate(unique_text, start=0)}
            identification = set(dictionary_text).intersection(constant_target)
            index_identification = [dictionary_text[z] for z in identification]
            if not len(index_identification) == 0:
                del [text[index_identification[0]]]
            actual_dictionary_text = {new_element: x_index for (x_index, new_element) in enumerate(text, start=0)}
            intersection_identify = set(actual_dictionary_text).intersection(target_id)
            intersection_id = set(actual_dictionary_text).intersection(target)
            index_identification = [actual_dictionary_text[x] for x in intersection_identify]
            index_position = [actual_dictionary_text[y] for y in intersection_id]
            if len(index_identification) == 0 or len(index_position) == 0:
                break
            names = text[index_identification[0] + 1:index_position[0] - 1]

            self.document_identification.append(' '.join(names))
            self.document_identification = pd.unique(self.document_identification).tolist()
        return self.document_identification

    def identification_number_with_upper_inferior_limits(self, ocr_text, upper_limit, inferior_limit):
        self.document_identification = []
        people_involved = []
        size_upper = len(upper_limit)
        size_inferior = len(inferior_limit)
        min_value = min(size_upper, size_inferior)
        reject_number = 6
        for indicator in range(min_value):
            text = ocr_text[upper_limit[indicator]:inferior_limit[indicator]]
            unique_text = pd.unique(text).tolist()
            people_involved.extend(unique_text)
        for index in range(len(people_involved)):
            identification_digit = people_involved[index].isdigit()
            numerical_value, number_position = digit_verification(identification_digit, people_involved,
                                                                  index)
            quantity_digits = number_of_digits(int(numerical_value))
            if not quantity_digits <= reject_number:
                self.document_identification.append(numerical_value)
                self.document_identification = pd.unique(self.document_identification).tolist()
        return self.document_identification

    def business_type_upper_limit_inferior_limit(self, ocr_text, upper_limit, inferior_limit, target_id):
        self.document_identification = []
        people_involved = []
        size_upper = len(upper_limit)
        size_inferior = len(inferior_limit)
        min_value = min(size_upper, size_inferior)
        for indicator in range(min_value):
            text = ocr_text[upper_limit[indicator]:inferior_limit[indicator]]
            unique_text = pd.unique(text).tolist()
            dictionary_text = {element: index for (index, element) in enumerate(unique_text, start=0)}
            intersection = set(dictionary_text).intersection(target_id)
            index_identification = [dictionary_text[x] for x in intersection]
            people_involved.extend(unique_text)
            if len(index_identification) == 0:
                break
            if unique_text[index_identification[0]] == 'nit':
                self.document_identification.extend(['PO'])
            else:
                self.document_identification.extend(['PP'])
        self.document_identification = pd.unique(self.document_identification).tolist()
        return self.document_identification





















































