import numpy as np


class AppendProcessActsVector:

    def __init__(self, concatenated_vectors,  code_concatenated_vectors):
        self.concatenated_vectors = concatenated_vectors
        self.code_concatenated_vectors = code_concatenated_vectors

    def lower_case_all_acts_vector(self, vector_acts_with_amount, vector_acts_without_amount, selector):
        target_names = 'nombre'
        target_codes = 'codigo'
        self.concatenated_vectors = np.append(vector_acts_with_amount[target_names],
                                                  vector_acts_without_amount[target_names])
        self.code_concatenated_vectors = np.append(vector_acts_with_amount['0'][target_codes],
                                                   vector_acts_without_amount['0'][target_codes])
        self.concatenated_vectors = [x.lower() for x in self.concatenated_vectors]
        return self.concatenated_vectors, self.code_concatenated_vectors

    def lower_case_vector(self, *args):
        self.code_concatenated_vectors = []
        for index in args:
            self.code_concatenated_vectors.extend([x.lower() for x in index])
        return self.code_concatenated_vectors

















