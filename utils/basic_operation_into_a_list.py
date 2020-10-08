import pandas as pd


class BasicListOperation:

    def __init__(self, vector_of_list, processed_vector):
        self.vector_of_list = vector_of_list
        self.processed_vector = processed_vector

    def non_repeated_acts(self):
        self.processed_vector = pd.unique(self.vector_of_list).tolist()
        return self.processed_vector

    def isempty_list(self, list_value):
        self.processed_vector = []
        self.processed_vector = [x for x in list_value if x]
        return self.processed_vector

    def delete_special_characters(self, names_extract, target_names):
        self.processed_vector = []
        dictionary_text = {element: x for (x, element) in enumerate(names_extract, start=0)}
        intersection = set(dictionary_text).intersection(target_names)
        index_intersection = [dictionary_text[point] for point in intersection]
        if not len(index_intersection) == 0:
            del names_extract[index_intersection[0]]
        self.processed_vector.extend(names_extract)
        return self.processed_vector
