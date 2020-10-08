
def act_filter_without_amount(value_acts, vector_acts, vector_acts_without_amount):
    value_acts_without_quantity = []
    accummulate_word = 0
    len_of_acts = 3
    unique_act = 1
    try:
        for index_acts_search in range(len(vector_acts_without_amount)):
            for index_acts in range(len(vector_acts)):
                vector_acts_detected = vector_acts[index_acts].split()
                all_vector_without = vector_acts_without_amount[index_acts_search].split()
                if len(vector_acts_detected) == unique_act and len(all_vector_without) == unique_act and \
                        vector_acts_detected[0] == all_vector_without[0]:
                    if len(value_acts[0]) < len(vector_acts):
                        value_acts[0].append('nan')
                        value_acts_without_quantity.extend(value_acts[0])
                    break
                if len(vector_acts_detected) >= len_of_acts and len(all_vector_without) >= len_of_acts and \
                        vector_acts_detected[0] == all_vector_without[0]:
                    for list_index in range(len(vector_acts_detected)):
                        compound_word = vector_acts[index_acts].split()[list_index]
                        acts_without_compound = vector_acts_without_amount[index_acts].split()[list_index]
                        accummulate_word += 1
                        if compound_word == acts_without_compound and accummulate_word >= len_of_acts:
                            if len(value_acts[0]) < len(vector_acts):
                                value_acts[0].append('nan')
                                value_acts_without_quantity.extend(value_acts[0])
                            break
                    accummulate_word = 0
    except NameError:
        print('vector size not same or vector_acts_without_amount are different')
    return value_acts



