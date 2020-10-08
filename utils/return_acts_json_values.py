
def return_acts_target(all_target_vectors, code_detected):
    acts_target_identify = []
    acts_label_identify = []
    for index_target_acts in range(len(code_detected)):
        acts_target_identify.append(all_target_vectors[code_detected[index_target_acts]])
        acts_label_identify.append(all_target_vectors[code_detected[index_target_acts]])
    return acts_target_identify

