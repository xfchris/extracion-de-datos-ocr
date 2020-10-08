from .identifyVar import IdentifyVariables

def value_of_acts(ocr_text, acts_vector):
    value = []
    value_position = []
    type_acts_identify = []
    acts_position = []
    for acts_index in range(len(acts_vector)):
        for index_all_text in range(len(ocr_text)):
            value_acts = IdentifyVariables(ocr_text, index_all_text, acts_vector[acts_index], value, value_position,
                                           type_acts_identify, acts_position)
            value_acts.return_variables()
    
    value_of_detected_acts = value_acts.return_variables()
    return value_of_detected_acts

